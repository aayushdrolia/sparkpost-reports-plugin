<?php

namespace SparkPost\Plugins\Reports;
/**
 * Class Admin
 * @package SparkPost\Plugins\Reports
 */
class Dashboard
{

    use LoadsView;

    private $db = [];
    private $param = [];

    public function __construct()
    {
        $this->db = get_option('sparkpost_options');
    }

    public function load_page()
    {
        $client = (new Http())->getClient();

        $metrics = new Metrics($client);

        $this->param = $this->getArguments();
        $this->param['sub_account_id'] = $this->db['sub_account_id'];

        $result = $metrics->get($this->param);

        $this->loadView('dashboard', compact('result'));
    }


    public function getArguments()
    {

        $args = array();

        if (!empty($_POST['fromDate'])) {
            $args['fromDate'] = $_POST['fromDate'];
        } else {
            $args['fromDate'] = date('Y-m-d', strtotime('-9 days'));
        }

        if (!empty($_POST['toDate'])) {
            $args['toDate'] = $_POST['toDate'];
        } else {
            $args['toDate'] = date("Y-m-d");
        }
        if (!empty($_POST['campaigns'])) {
            $args['campaigns'] = $_POST['campaigns'];
        }

        return $args;
    }

}
