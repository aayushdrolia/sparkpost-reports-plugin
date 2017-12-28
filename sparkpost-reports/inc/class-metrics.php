<?php

namespace SparkPost\Plugins\Reports;

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

/**
 * Class Metrics
 * @package SparkPost\Plugins\Reports
 */
Class Metrics
{

    private $client;
    private $param = [];

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param $param
     * @return mixed
     * function to fetch reports on page load
     */
    public function get($param)
    {
        $this->param = $this->validateData($param);

        $promise = $this->client->request('GET', 'metrics/deliverability', /*[
            'subaccounts' => $this->param['sub_account_id'],
            //'per-page' => 10,
            //'recipients' => 'andy.aayush@ithands.biz',
            'from' => $this->param['from'] . 'T00:00',
            'to' => $this->param['to'] . 'T00:00',
            'metrics' => 'count_injected,count_bounce,count_rejected,count_delivered,count_targeted',
        ]*/
            $this->param);

        try {
            return $promise->wait()->getBody();
        } catch (\Exception $e) {
            //echo $e->getCode() . "\n";
            //echo $e->getMessage() . "\n";
            echo '<script>alert("Unable to fetch reports \nPlease check your connection and try again. ")</script>';
        }
    }

    /**
     * @param $param
     * @return array
     * function to validate form fields
     */
    public function validateData($param)
    {
        $args = [];

        if (isset($param['campaigns'])) {
            $args['campaigns'] = $param['campaigns'];
        }
        if (isset($param['sub_account_id'])) {
            $args['subaccounts'] = $param['sub_account_id'];
        }
        if (isset($param['fromDate'])) {
            $args['from'] = $param['fromDate'] . 'T00:00';
        }
        if (isset($param['toDate'])) {
            $args['to'] = $param['toDate'] . 'T23:59';
        }

        $args['metrics'] = 'count_targeted,count_injected,count_accepted,count_delivered,count_rendered,count_clicked';
        $args['timezone'] = 'America/New_York';
        return $args;
    }
}
