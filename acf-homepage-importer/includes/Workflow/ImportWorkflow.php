<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Workflow_ImportWorkflow {
    protected ACF_Homepage_Importer_Crawler_HomepageDetector $detector;
    protected ACF_Homepage_Importer_Crawler_HtmlCrawler $crawler;
    protected ACF_Homepage_Importer_Parser_SectionParser $sectionParser;
    protected ACF_Homepage_Importer_Data_DataSeeder $seeder;
    protected ACF_Homepage_Importer_Support_ArtifactStore $artifactStore;
    protected ACF_Homepage_Importer_Support_Logger $logger;
    protected ACF_Homepage_Importer_Data_ImportJobRepository $jobRepository;
    protected ACF_Homepage_Importer_Verification_ContentVerifier $verifier;
    protected ACF_Homepage_Importer_Verification_VerificationReport $reportBuilder;

    public function __construct(
        ?ACF_Homepage_Importer_Crawler_HomepageDetector $detector = null,
        ?ACF_Homepage_Importer_Crawler_HtmlCrawler $crawler = null,
        ?ACF_Homepage_Importer_Parser_SectionParser $sectionParser = null,
        ?ACF_Homepage_Importer_Data_DataSeeder $seeder = null,
        ?ACF_Homepage_Importer_Support_ArtifactStore $artifactStore = null,
        ?ACF_Homepage_Importer_Support_Logger $logger = null,
        ?ACF_Homepage_Importer_Data_ImportJobRepository $jobRepository = null,
        ?ACF_Homepage_Importer_Verification_ContentVerifier $verifier = null,
        ?ACF_Homepage_Importer_Verification_VerificationReport $reportBuilder = null
    ) {
        $this->detector = $detector ?: new ACF_Homepage_Importer_Crawler_HomepageDetector();
        $this->crawler = $crawler ?: new ACF_Homepage_Importer_Crawler_HtmlCrawler();
        $this->sectionParser = $sectionParser ?: new ACF_Homepage_Importer_Parser_SectionParser();
        $this->seeder = $seeder ?: new ACF_Homepage_Importer_Data_DataSeeder();
        $this->artifactStore = $artifactStore ?: new ACF_Homepage_Importer_Support_ArtifactStore();
        $this->logger = $logger ?: new ACF_Homepage_Importer_Support_Logger();
        $this->jobRepository = $jobRepository ?: new ACF_Homepage_Importer_Data_ImportJobRepository();
        $this->verifier = $verifier ?: new ACF_Homepage_Importer_Verification_ContentVerifier();
        $this->reportBuilder = $reportBuilder ?: new ACF_Homepage_Importer_Verification_VerificationReport();
    }

    public function run(string $sourceUrl, int $pageId = 0, bool $dryRun = true): array {
        $normalizedUrl = $this->detector->normalize($sourceUrl);
        if ($normalizedUrl === '') {
            return $this->failure('Source URL is required.');
        }

        $runId = gmdate('Ymd-His');
        $this->jobRepository->save(array(
            'run_id' => $runId,
            'source_url' => $normalizedUrl,
            'page_id' => $pageId,
            'dry_run' => $dryRun,
            'status' => 'running',
            'created_at' => gmdate('c'),
        ));
        $this->logger->info('Import run started.', array('run_id' => $runId, 'source_url' => $normalizedUrl));

        $crawl = $this->crawler->crawl($normalizedUrl);
        if (empty($crawl['success'])) {
            $this->jobRepository->update($runId, array('status' => 'failed', 'error' => $crawl['error'] ?? 'Crawl failed.'));
            $this->logger->error('Crawl failed.', array('run_id' => $runId, 'error' => $crawl['error'] ?? 'Crawl failed.'));
            return $this->failure($crawl['error'] ?? 'Crawl failed.', array('run_id' => $runId));
        }

        $html = $crawl['html'] ?? '';
        $sections = $this->sectionParser->parse($html);
        $seed = $this->seeder->seed($sections, $pageId, $dryRun);

        $verification = $this->verifier->verify($sections, $seed['payload'] ?? array(), $seed);
        $report = $this->reportBuilder->build(array(
            'run_id' => $runId,
            'source_url' => $normalizedUrl,
            'page_id' => $seed['page_id'] ?? $pageId,
            'dry_run' => $dryRun,
            'status' => $verification['status'] ?? 'warn',
            'warnings' => $verification['warnings'] ?? array(),
            'diff' => $verification['diff'] ?? array(),
        ));

        $artifacts = $this->artifactStore->storeRun(array(
            'crawl-result' => $crawl,
            'parsed-sections' => $sections,
            'seed-payload' => $seed['payload'] ?? array(),
            'verification-report' => $report,
        ), $runId);

        $this->jobRepository->update($runId, array(
            'status' => !empty($seed['success']) ? 'completed' : 'failed',
            'verification_status' => $report['status'],
            'artifacts' => $artifacts['paths'],
            'updated_at' => gmdate('c'),
        ));
        $this->logger->info('Import run finished.', array('run_id' => $runId, 'status' => $report['status']));

        return array(
            'success' => !empty($seed['success']),
            'run_id' => $runId,
            'source_url' => $normalizedUrl,
            'page_id' => $seed['page_id'] ?? $pageId,
            'dry_run' => $dryRun,
            'crawl' => $crawl,
            'sections' => $sections,
            'seed' => $seed,
            'verification' => $verification,
            'report' => $report,
            'artifacts' => $artifacts,
        );
    }

    protected function failure(string $message, array $extra = array()): array {
        return array_merge(array(
            'success' => false,
            'error' => $message,
        ), $extra);
    }

    public function crawl(string $sourceUrl): array {
        $normalizedUrl = $this->detector->normalize($sourceUrl);
        if ($normalizedUrl === '') {
            return $this->failure('Source URL is required.');
        }

        $crawl = $this->crawler->crawl($normalizedUrl);
        if (empty($crawl['success'])) {
            return $this->failure($crawl['error'] ?? 'Crawl failed.');
        }

        return array(
            'success' => true,
            'source_url' => $normalizedUrl,
            'crawl' => $crawl,
        );
    }

    public function parse(string $sourceUrl): array {
        $crawlResult = $this->crawl($sourceUrl);
        if (empty($crawlResult['success'])) {
            return $crawlResult;
        }

        $html = $crawlResult['crawl']['html'] ?? '';
        $sections = $this->sectionParser->parse($html);

        return array(
            'success' => true,
            'source_url' => $crawlResult['source_url'],
            'crawl' => $crawlResult['crawl'],
            'sections' => $sections,
        );
    }

    public function import(string $sourceUrl, int $pageId = 0, bool $dryRun = true): array {
        return $this->run($sourceUrl, $pageId, $dryRun);
    }

    public function verify(string $sourceUrl, int $pageId = 0): array {
        $result = $this->run($sourceUrl, $pageId, true);

        if (empty($result['success'])) {
            return $result;
        }

        return array(
            'success' => true,
            'source_url' => $result['source_url'],
            'page_id' => $result['page_id'],
            'report' => $result['report'],
            'verification' => $result['verification'],
            'artifacts' => $result['artifacts'],
        );
    }
}
