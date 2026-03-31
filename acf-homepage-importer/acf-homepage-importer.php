<?php
/**
 * Plugin Name: ACF Homepage Importer
 * Description: Imports homepage sections into ACF Flexible Content.
 * Version: 0.1.0
 * Author: OrangeToolz
 */

if (!defined('ABSPATH')) {
    exit;
}

define('ACF_HOMEPAGE_IMPORTER_VERSION', '0.1.0');
define('ACF_HOMEPAGE_IMPORTER_FILE', __FILE__);
define('ACF_HOMEPAGE_IMPORTER_PATH', plugin_dir_path(__FILE__));
define('ACF_HOMEPAGE_IMPORTER_URL', plugin_dir_url(__FILE__));

require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Core/Plugin.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Admin/AdminMenu.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Admin/ImportPage.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/CLI/TestCommand.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Crawler/HttpClient.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Crawler/HomepageDetector.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Crawler/HtmlCrawler.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Parser/DomParser.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Parser/SectionParser.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Parser/ContentNormalizer.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Parser/SectionItemMapper.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Schema/SectionSchemaRegistry.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Schema/AcfSchemaBuilder.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Schema/AcfRegistrar.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Data/PageRepository.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Data/DataSeeder.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Data/ImportJobRepository.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Support/ArtifactStore.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Support/Logger.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Workflow/ImportWorkflow.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Verification/DiffBuilder.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Verification/ContentVerifier.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Verification/VerificationReport.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Template/HomepageTemplateRenderer.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/Admin/HistoryPage.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/CLI/CrawlCommand.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/CLI/ParseCommand.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/CLI/ImportCommand.php';
require_once ACF_HOMEPAGE_IMPORTER_PATH . 'includes/CLI/VerifyCommand.php';

register_activation_hook(__FILE__, array('ACF_Homepage_Importer_Core_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('ACF_Homepage_Importer_Core_Plugin', 'deactivate'));

add_action('plugins_loaded', function () {
    $plugin = new ACF_Homepage_Importer_Core_Plugin();
    $plugin->init();
});
