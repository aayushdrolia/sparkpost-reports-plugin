<?php

namespace SparkPost\Plugins\Reports;
/**
 * Class Admin
 * @package SparkPost\Plugins\Reports
 */
class Admin
{

    /**
     * Unique plugin option page slug
     */
    const PARENT_SLUG = 'sparkpost_reports';

    /**
     * Settings class instance
     * @var Settings
     */
    private $settings;

    /**
     * Settings class instance
     * @var Settings
     */
    private $dashboard;
    private $events;


    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_links_to_sidebar'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_date_picker'));

        $this->settings = new Settings();
        $this->dashboard = new Dashboard();
        $this->events = new MessageEvents();
        new Metrics_Ajax_Handler();
        new Events_Ajax_Handler();
    }

    public function enqueue_date_picker()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    }

    /**
     * Register a page to display plugin options
     */
    public function add_links_to_sidebar()
    {
        $parent_hook_suffix = add_menu_page(
            'Sparkpost', // page title
            'Sparkpost',// menu title
            'manage_options',
            self::PARENT_SLUG,
            array($this->dashboard, 'load_page'),
            'dashicons-chart-area',
            80
        );

        $message_events_hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            'Message Events',
            'Message Events',
            'manage_options',
            'sparkpost_message_events',
            array($this->events, 'load_page')
        );

        $child_hook_suffix = add_submenu_page(
            self::PARENT_SLUG,
            'Settings', // page title
            'Settings', // menu text
            'manage_options',
            'sparkpost_options',
            array($this->settings, 'load_page')
        );

        add_action('admin_print_scripts-' . $child_hook_suffix, array($this, 'add_admin_assets'));
        add_action('admin_print_scripts-' . $parent_hook_suffix, array($this, 'add_admin_assets'));
        add_action('admin_print_scripts-' . $message_events_hook_suffix, array($this, 'add_admin_assets'));
    }

    /**
     *
     * Add javascript and css to plugin option page
     */
    public function add_admin_assets()
    {
        wp_enqueue_style('spr-admin-css', plugins_url('/assets/css/admin.css', SPR_BASE_FILE), array(), SPR_PLUGIN_VERSION, 'all');

        //wp_enqueue_style('mag-tablesorter-default-css', MAG_PLUGIN_URL . 'src/assets/plugin/tablesorter/css/theme.default.min.css');
        wp_enqueue_style('spr-tablesorter-bootstrap-css', plugins_url('/assets/plugin/tablesorter/css/theme.bootstrap.css', SPR_BASE_FILE));

        wp_enqueue_style('spr-tablesorter-pager-css', plugins_url('/assets/plugin/tablesorter/addons/pager/jquery.tablesorter.pager.css', SPR_BASE_FILE));

        //Load Scripts
        wp_enqueue_script('spr-tablesorter-js', plugins_url('/assets/plugin/tablesorter/js/jquery.tablesorter.combined.min.js', SPR_BASE_FILE), array('jquery'));
        wp_enqueue_script('spr-pager-js', plugins_url('/assets/plugin/tablesorter/addons/pager/jquery.tablesorter.pager.js', SPR_BASE_FILE), array('jquery'));

        wp_enqueue_script('spr-admin-js', plugins_url("/assets/js/admin.js", SPR_BASE_FILE), array('jquery'), SPR_PLUGIN_VERSION, true);

        // WP inbuilt hack to print js options object just before this script
        wp_localize_script('spr-admin-js', '_sprOpt', $this->get_js_options());
    }

    /*
    * Returns dynamic javascript options to be used by admin js
    * @return array
    */
    private function get_js_options()
    {
        return array(
            'ajaxUrl' => admin_url('admin-ajax.php')
        );
    }

}
