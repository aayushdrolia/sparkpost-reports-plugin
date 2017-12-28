<?php
/**
 * Created by PhpStorm.
 * User: andyaayush
 * Date: 16/11/17
 * Time: 12:03 PM
 */

namespace SparkPost\Plugins\Reports;

use DateTime;

class Events_Ajax_Handler
{
    private $db = [];
    private $eventsParam = [];

    public function __construct()
    {
        $this->db = get_option('sparkpost_options');
        add_action('wp_ajax_spr_get_message_events', array($this, 'get_message_events'));
        add_action('wp_ajax_spr_download_csv', array($this, 'download_csv'));
        add_action('wp_ajax_spr_delete_csv', array($this, 'delete_csv'));
    }

    /**
     * function to fetch message events using ajax call
     */
    public function get_message_events()
    {
        $event = new MessageEvents();

        $this->eventsParam = $this->getEventsArguments($_GET);
        $this->eventsParam['sub_account_id'] = $this->db['sub_account_id'];

        $response = $event->get($this->eventsParam)['results'];

        if (!empty($response)) {

            $results = [];
            foreach ($response as $event) {
                $date_object = new DateTime($event['timestamp']);
                $date = $date_object->format('M d, Y - g:i A');
                $results[] = [
                    'subject' => $event['subject'],
                    'type' => $event['type'],
                    'campaign' => $event['campaign_id'],
                    'friendly_from' => $event['friendly_from'],
                    'raw_rcpt_to' => $event['raw_rcpt_to'],
                    'timestamp' => $date,
                ];
                //$event['timestamp'] = $date;
            }
            wp_send_json_success($results);
        }

        //wp_send_json_success(['status' => false, 'message' => 'Response not found.']);

    }

    /**
     * function to download the reports in csv format using ajax call.
     */
    public function download_csv()
    {
        $event = new MessageEvents();

        $this->eventsParam = $this->getEventsArguments($_GET);
        $this->eventsParam['sub_account_id'] = $this->db['sub_account_id'];

        $response = $event->get($this->eventsParam)['results'];

        $this->create_report($response);

    }

    /**
     * @param $result
     * function to delete existing csv file and a create new csv file for each of the new requests.
     */
    public function create_report($response)
    {
        if (!empty($response)) {
            if (!is_dir(plugin_dir_path(__DIR__) . 'downloads')) {
                mkdir(plugin_dir_path(__DIR__) . 'downloads');
            }

            $fileName = 'spr-reports-' . mt_rand(100, 99999) . '.csv';
            $file_path = plugin_dir_path(__DIR__) . 'downloads/' . $fileName;

            $file = fopen($file_path, 'w');

            foreach ($response as $event) {
                $date_object = new DateTime($event['timestamp']);
                $date = $date_object->format('M d, Y - g:i A');
                fputcsv($file, [$event['subject'], $event['type'], $event['campaign_id'], $event['friendly_from'], $event['raw_rcpt_to'], $date], ',');
            }

            fclose($file);


            $results = [];
            foreach ($response as $event) {
                $date_object = new DateTime($event['timestamp']);
                $date = $date_object->format('M d, Y - g:i A');
                $results[] = [
                    'subject' => $event['subject'],
                    'type' => $event['type'],
                    'campaign' => $event['campaign_id'],
                    'friendly_from' => $event['friendly_from'],
                    'raw_rcpt_to' => $event['raw_rcpt_to'],
                    'timestamp' => $date,
                ];
            }


            $file_url = plugin_dir_url(SPR_BASE_FILE) . 'downloads/' . $fileName;
            wp_send_json_success(['events' => $results, 'file_url' => $file_url]);
        }

        //wp_send_json_success(['status' => false, 'message' => 'Error']);
    }

    /**
     * @param $fields
     * @return array
     * function to validate the form fields
     */
    public function getEventsArguments($fields)
    {
        $args = array();

        if (!empty($fields['EventsRecipients'])) {
            $args['EventsRecipients'] = $fields['EventsRecipients'];
        }
        if (!empty($fields['EventsFromDate'])) {
            $args['EventsFromDate'] = $fields['EventsFromDate'];
        } else {
            $args['EventsFromDate'] = date('Y-m-d', strtotime('-9 days'));
        }
        if (!empty($fields['EventsToDate'])) {
            $args['EventsToDate'] = $fields['EventsToDate'];
        } else {
            $args['EventsToDate'] = date("Y-m-d");
        }

        if (!empty($fields['selectedEvents'])) {
            $args['selectedEvents'] = implode(',', $fields['selectedEvents']);
        } else {
            $args['selectedEvents'] = 'bounce,delivery,open,click';
        }

        if (!empty($fields['EventsCampaigns'])) {
            $args['EventsCampaigns'] = $fields['EventsCampaigns'];
        }


        return $args;
    }

    /**
     * function to delele the csv file after download using ajax call.
     */
    public function delete_csv()
    {
        $file_urls = $_GET['url'];
        $file_urls = glob(plugin_dir_path(__DIR__) . 'downloads/' . $file_urls);
        foreach ($file_urls as $file_url) {
            if (is_file($file_url)) {
                unlink($file_url);
            }
        }
    }


}
