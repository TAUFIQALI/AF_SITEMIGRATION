<?php

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Homepage_Importer_Data_ImportJobRepository {
    protected string $optionName = 'acf_homepage_importer_jobs';

    public function all(): array {
        $jobs = get_option($this->optionName, array());
        return is_array($jobs) ? $jobs : array();
    }

    public function save(array $job): array {
        $jobs = $this->all();
        $jobs[] = $job;
        update_option($this->optionName, $jobs, false);

        return $job;
    }

    public function update(string $runId, array $data): array {
        $jobs = $this->all();
        foreach ($jobs as $index => $job) {
            if (($job['run_id'] ?? '') === $runId) {
                $jobs[$index] = array_merge($job, $data);
                update_option($this->optionName, $jobs, false);
                return $jobs[$index];
            }
        }

        return $this->save(array_merge(array('run_id' => $runId), $data));
    }
}
