<?php

namespace SparkPost\Plugins\Reports;

class Metrics_Ajax_Handler
{

    private $db = [];
    private $metricsParam = [];
    private $eventsParam = [];

    public function __construct()
    {
        $this->db = get_option('sparkpost_options');

        add_action('wp_ajax_spr_get_metrics', array($this, 'get_metrics'));

    }

    /**
     * function to fetch metrics reports using Ajax call
     */
    public function get_metrics()
    {
        //print_r($_GET);

        $client = (new Http())->getClient();

        $metrics = new Metrics($client);

        $this->metricsParam = $this->getMetricsArguments($_GET);
        $this->metricsParam['sub_account_id'] = $this->db['sub_account_id'];

        $result = $metrics->get($this->metricsParam);

        wp_send_json_success($result);
    }

    /**
     * @param $fields
     * @return array
     * function to validate form fields
     */
    public function getMetricsArguments($fields)
    {
        $args = array();

        if (!empty($fields['fromDate'])) {
            $args['fromDate'] = $fields['fromDate'];
        } else {
            $args['fromDate'] = date('Y-m-d', strtotime('-9 days'));
        }

        if (!empty($fields['toDate'])) {
            $args['toDate'] = $fields['toDate'];
        } else {
            $args['toDate'] = date("Y-m-d");
        }

        if (!empty($fields['campaigns'])) {
            $args['campaigns'] = $fields['campaigns'];
        }

        return $args;
    }

}
