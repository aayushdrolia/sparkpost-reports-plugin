<?php

namespace SparkPost\Plugins\Reports;

/**
 * Plugin Name: Sparkpost report
 * Plugin URI: https://www.sparkpost.com/
 * Description: Sparkpost reports plugin
 * Version: 1.0.0
 * Author: Sparkpost
 * Author URI: https://www.sparkpost.com/
 * License: Proprietary
 */


// No direct access
if (!defined('ABSPATH')) die;

define('SPR_PLUGIN_VERSION', '1.0.0');
define('SPR_BASE_FILE', __FILE__);

if (is_admin()) {
    require __DIR__ . '/inc/trait-loads-view.php';
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/inc/class-settings.php';
    require __DIR__ . '/inc/class-dashboard.php';
    require __DIR__ . '/inc/class-metrics-ajax-handler.php';
    require __DIR__ . '/inc/class-events-ajax-handler.php';
    require __DIR__ . '/inc/class-admin.php';
    require __DIR__ . '/inc/class-http.php';
    require __DIR__ . '/inc/class-metrics.php';
    require __DIR__ . '/inc/class-events.php';
    new Admin();
}
