<?php

namespace SparkPost\Plugins\Reports;
/**
 * Class Admin
 * @package SparkPost\Plugins\Reports
 */
class Settings
{

    use LoadsView;

    /**
     * Constants
     */
    const PLUGIN_OPTION_GROUP = 'sparkpost_options';


    public function __construct()
    {
        // Register setting
        add_action('admin_init', array($this, 'register_plugin_settings'));

        // To save default options upon activation
        register_activation_hook(plugin_basename(SPR_BASE_FILE), array($this, 'do_upon_plugin_activation'));

    }

    /**
     * Returns default plugin db options
     * @return array
     */
    public function get_default_options()
    {
        return array(
            'plugin_ver' => SPR_PLUGIN_VERSION,
            'api_key' => '',
            'sub_account_id' => '',
        );
    }

    /**
     * Save default settings upon plugin activation
     */
    public function do_upon_plugin_activation()
    {

        if (false == get_option('sparkpost_options')) {
            add_option('sparkpost_options', $this->get_default_options());
        }
    }

    /**
     * Register plugin settings, using WP settings API
     */
    public function register_plugin_settings()
    {
        register_setting(self::PLUGIN_OPTION_GROUP, 'sparkpost_options', array($this, 'validate_form_post'));
    }


    /**
     * Load plugin option page view
     */
    public function load_page()
    {
        $this->loadView('settings', [
            'db' => get_option('sparkpost_options'),
            'option_group' => self::PLUGIN_OPTION_GROUP,
        ]);
    }

    /**
     * Validate form $_POST data
     * @param $in array
     * @return array Validated array
     */
    public function validate_form_post($in)
    {
        $out = array();
        $errors = array();
        //always store plugin version to db
        $out['plugin_ver'] = esc_attr(SPR_PLUGIN_VERSION);;

        if (!empty($in['api_key'])) {
            $out['api_key'] = sanitize_text_field($in['api_key']);
        } else {
            $errors[] = 'API Key is required.';
            $out['api_key'] = '';
        }

        if (!empty($in['sub_account_id'])) {
            $out['sub_account_id'] = sanitize_text_field($in['sub_account_id']);
        } else {
            $errors[] = 'Sub account id is required.';
            $out['sub_account_id'] = '';
        }


        // Show all form errors in a single notice
        if (!empty($errors)) {
            add_settings_error('sparkpost_options', 'sparkpost_error', implode('<br>', $errors));
        } else {
            add_settings_error('sparkpost_options', 'sparkpost_updated', 'Settings saved.', 'updated');
        }

        return $out;
    }

}
