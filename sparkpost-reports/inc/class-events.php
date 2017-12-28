<?php
/**
 * Created by PhpStorm.
 * User: andyaayush
 * Date: 14/11/17
 * Time: 10:28 AM
 */

namespace SparkPost\Plugins\Reports;

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;


class MessageEvents
{
    use LoadsView;

    private $param = [];
    private $formData = [];
    private $db = [];

    public function __construct()
    {
        $this->db = get_option('sparkpost_options');
    }

    public function load_page()
    {
        $this->formData = $this->getArguments();
        $this->formData['sub_account_id'] = $this->db['sub_account_id'];

        $result = $this->get($this->formData);

        $this->loadView('events', compact('result'));

    }

    public function getArguments()
    {

        $args = array();

        if (!empty($_POST['EventsRecipients'])) {
            $args['EventsRecipients'] = $_POST['EventsRecipients'];
        }
        if (!empty($_POST['EventsFromDate'])) {
            $args['EventsFromDate'] = $_POST['EventsFromDate'];
        } else {
            $args['EventsFromDate'] = date('Y-m-d', strtotime('-9 days'));
        }
        if (!empty($_POST['EventsToDate'])) {
            $args['EventsToDate'] = $_POST['EventsToDate'];
        } else {
            $args['EventsToDate'] = date("Y-m-d");
        }

        if (!empty($_POST['selectedEvents'])) {
            $args['selectedEvents'] = implode(',', $_POST['selectedEvents']);
        } else {
            $args['selectedEvents'] = 'bounce,delivery,open,click';
        }

        if (!empty($_POST['EventsCampaigns'])) {
            $args['EventsCampaigns'] = $_POST['EventsCampaigns'];
        }


        return $args;
    }

    public function get($param)
    {
        $this->param = $this->validateData($param);
        $client = (new Http())->getClient();
        $promise = $client->request('GET', 'message-events', $this->param);

        try {
            return $promise->wait()->getBody();
        } catch (\Exception $e) {
            //echo $e->getCode() . "\n";
            //echo $e->getMessage() . "\n";
            echo '<script>alert("Unable to fetch reports \nPlease check your connection and try again. ")</script>';
        }
    }

    public function validateData($param)
    {
        $args = [];

        if (isset($param['EventsRecipients'])) {
            $args['recipients'] = $param['EventsRecipients'];
        }
        if (isset($param['sub_account_id'])) {
            $args['subaccounts'] = $param['sub_account_id'];
        }
        if (isset($param['EventsFromDate'])) {
            $args['from'] = $param['EventsFromDate'] . 'T00:00';
        }
        if (isset($param['EventsToDate'])) {
            $args['to'] = $param['EventsToDate'] . 'T23:59';
        }
        if (isset($param['selectedEvents'])) {
            $args['events'] = $param['selectedEvents'];
        } else {
            $args['events'] = 'bounce,delivery,open,click';
        }
        if (isset($param['EventsCampaigns'])) {
            $args['campaign_ids'] = $param['EventsCampaigns'];
        }
        $args['timezone'] = 'America/New_York';
        //$args['per_page'] = '9999';
        return $args;
    }
}
