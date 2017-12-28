<div class="wrap">
    <h1>SparkPost Message Events</h1>
    <div class="update-nag" id="events-error">
        <b>Please fill in the fields to filter Message Events.</b>
    </div>
    <form method="post" id="spr-message-events-filter-form" action="">
        <table class="form-table">
            <tbody>

            <tr class="events-recipients">
                <th scope="row"><label for="EventsRecipients">Recipients(s):</label></th>
                <td><input id="events-recipients" class="full-width spr-events-recipients" type="text"
                           name="EventsRecipients"
                           placeholder="Enter comma separated recipient ID(s)">
                </td>
            </tr>

            <tr class="events-from-date">
                <th scope="row"><label for="EventsFromDate">From:</label></th>
                <td><input id="events-from-date" type="text" name="EventsFromDate"
                           class="spr-events-datepicker-from full-width">
                    <p class="errors"><i>Please select the begin date.</i></p></td>
            </tr>

            <tr class="events-to-date">
                <th scope="row"><label for="EventsToDate">To:</label></th>
                <td><input id="events-to-date" type="text" name="EventsToDate"
                           class="spr-events-datepicker-to full-width">
                    <p class="errors"><i>Please select the end date.</i></p></td>
            </tr>

            <tr class="events-event-filter">
                <th scope="row"><label for="EventsEventFilter">Filter By Events:</label></th>
                <td>
                    <input type="checkbox" name="selectedEvents[]" value="bounce"/>Bounce
                    <input type="checkbox" name="selectedEvents[]" value="delivery"/>Delivery
                    <input type="checkbox" name="selectedEvents[]" value="open"/>Open
                    <input type="checkbox" name="selectedEvents[]" value="click"/>Click
                </td>
            </tr>

            <!--<tr class="events-template-ids">
                <th scope="rowgroup"><label for="EventsTemplateIds">Template ID(s):</label></th>
                <td><input id="events-template-ids" type="text" class="full-width" name="EventsTemplateIds" placeholder="Enter comma separated Tempalate ID(s)"></td>
            </tr>-->

            <tr class="events-campaigns">
                <th scope="rowgroup"><label for="EventsCampaigns">Campaign(s):</label></th>
                <td><input id="events-campaigns" type="text" class="full-width" name="EventsCampaigns"
                           placeholder="Enter comma separated Campaign ID(s)"></td>
            </tr>

            <!--            <tr class="events-message-ids">-->
            <!--                <th scope="rowgroup"><label for="EventsMessageIds">Message ID(s):</label></th>-->
            <!--                <td><input id="events-message-ids" type="text" class="full-width" name="EventsMessageIds" placeholder="Enter comma separated Message ID(s)"></td>-->
            <!--            </tr>-->

            <tr>
                <th class="blank">
                </th>
                <th class="events-submit-btn">
                    <input id="events-submit" type="submit" name="EventsSubmit" value="Filter"
                           class="button button-primary">
                    <input id="events-reset" type="reset" name="EventsReset" value="Reset"
                           class="button button-primary">
                    <input id="csv-download" type="button" name="CSVDownload" value="Download CSV"
                           class="button button-primary">
                    <span class="spinner"></span>
                </th>

            </tr>


            </tbody>
        </table>
    </form>

    <div class="option-page-wrap">
        <pre>
        <?php //print_r($result); ?>
            </pre>
    </div>

    <div id="spr-events-results">

        <table id="events-date-notice">
            <thead>
            <tr>
                <th class="manage-column column-title"><i>By default events reports are shown for past 10 days.
                        Sparkpost holds reports maximum upto past 10 Days.</i>
                </th>
            </tr>
            </thead>
        </table>

        <div id="spr-pager-wrap" class="mag-form-group-inline">
            <div class="alignleft">

                <!-- pager -->
                <div id="spr-events-pager" class="pager">
                    <label class="spr-form-label">Show</label>
                    <select class="pagesize" title="Select page size">
                        <option selected="selected" value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    Entries &emsp;
                    <label class="mag-form-label">Page</label>
                    <select class="gotoPage" title="Select page number"></select>
                    <label class="mag-form-label"><span class="pagedisplay"></span></label>
                </div>
            </div>
            <p class="search-box">
                <label class="mag-form-label" for="spr-events-all-search">Search</label>
                <input id="spr-events-all-search" class="search" type="search" data-column="all"> (Match
                any column)
            </p>
        </div>

        <table id="spr-events-table" class="wp-list-table widefat fixed striped posts">
            <thead>
            <tr>
                <th class="manage-column column-title">Subject</th>
                <th class="manage-column column-title">Event</th>
                <th class="manage-column column-title">Campaign</th>
                <th class="manage-column column-title">Friendly From</th>
                <th class="manage-column column-title">Recipient</th>
                <th class="manage-column column-title">Timestamp</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($result['results'])): ?>
                <?php foreach ($result['results'] as $event):
                    $date_object = new DateTime($event['timestamp']);
                    $date = $date_object->format('M d, Y - g:i A');
                    ?>
                    <tr>
                        <th id="events-timestamp" class="manage-column column-title">
                            <?php echo $event['subject']; ?>
                        </th>
                        <th id="events-event" class="manage-column column-title">
                            <?php echo $event['type']; ?>
                        </th>
                        <th id="events-campaign" class="manage-column column-title">
                            <?php echo $event['campaign_id']; ?>
                        </th>

                        <th id="events-from" class="manage-column column-title">
                            <?php echo $event['friendly_from']; ?>
                        </th>
                        <th id="events-recipient" class="manage-column column-title">
                            <?php echo $event['raw_rcpt_to']; ?>
                        </th>
                        <th id="events-timestamp" class="manage-column column-title">
                            <?php echo $date; ?>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

