<?php

namespace SparkPost\Plugins\Reports;

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

/**
 * Class Http
 * @package SparkPost\Plugins\Reports
 */
Class Http
{
    private $db;

    use LoadsView;

    public function __construct()
    {
        $this->db = get_option('sparkpost_options');
    }

    public function getClient()
    {
        if ($this->hasKeys()) {
            return new SparkPost($this->getGuzzleClient(), ['key' => $this->db['api_key']]);
        } else {
            $this->showError(['<div class="update-nag" id="api-key-error"><b>No API key found, please add your API key from plugin settings.</b></div>']);
        }

    }

    protected function hasKeys()
    {
        return !empty($this->db)
            && !empty($this->db['api_key']);
    }

    protected function showError($errors = [])
    {
        wp_die(implode('<br>', $errors));
    }

    protected function getGuzzleClient()
    {
        return new GuzzleAdapter(new Client([
            'connect_timeout' => 60.0, // seconds,
            'timeout' => 60.0, // seconds
            'http_errors' => true, // throw exception on non 20x response codes
            'verify' => true, // SSL verification
            'allow_redirects' => false,
            'debug' => false, // don't leave this to true in production
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],

        ]));
    }
}

