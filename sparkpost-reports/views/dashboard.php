<div class="wrap">
    <h1>SparkPost Metrics</h1>
    <div class="update-nag" id="metrics-error">
        <b>Please fill in the fields to filter metrics reports.</b>
    </div>
    <form method="post" id="spr-metrics-filter-form" action="">
        <table class="form-table">
            <tbody>
            <tr class="from-date">
                <th scope="row"><label for="fromDate">From</label></th>
                <td><input id="fromDate" type="text" name="fromDate" class="spr-datepicker-from full-width" placeholder="Enter begin date">
                    <p class="errors"><i>Please select the begin date.</i></p></td>
            </tr>
            <tr class="to-date">
                <th scope="row"><label for="toDate">To</label></th>
                <td><input id="toDate" type="text" name="toDate" class="spr-datepicker-to full-width" placeholder="Enter end date">
                    <p class="errors"><i>Please select the end date.</i></p></td>
            </tr>
            <tr class="campaign-list">
                <th scope="rowgroup"><label for="campaigns">Campaigns</label></th>
                <td><input id="campaigns" type="text" class="full-width" name="campaigns"
                           placeholder="Enter comma separated Campaigns"></td>
            </tr>
            <tr>
                <th class="submit-btn">
                    <input id="submit" type="submit" name="submit" value="Filter" class="button button-primary">
                    <span class="spinner"></span>
                </th>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="option-page-wrap">
        <pre>
        <?php //print_r($result['results']) ?>
            </pre>
    </div>

    <div id="spr-metrics-results">

        <table id="metrics-date-notice">
            <thead>
            <tr>
                <th class="manage-column column-title"><i>By default metrics reports are shown for past 10 days.</i></th>
            </tr>
            </thead>
        </table>

        <table id="spr-metrics-table" class="wp-list-table widefat fixed striped posts">
            <thead>
            <tr>
                <th class="manage-column column-title">Total Targeted</th>
                <th class="manage-column column-title">Total Injected</th>
                <th class="manage-column column-title">Total Accepted</th>
                <th class="manage-column column-title">Total Delivered</th>
                <th class="manage-column column-title">Total Rendered</th>
                <th class="manage-column column-title">Total Clicked</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th id="count_targeted" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_targeted']; ?>
                </th>
                <th id="count_injected" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_injected']; ?>
                </th>
                <th id="count_accepted" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_accepted']; ?>
                </th>
                <th id="count_delivered" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_delivered']; ?>
                </th>
                <th id="count_rendered" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_rendered']; ?>
                </th>
                <th id="count_clicked" class="manage-column column-title">
                    <?php echo $result['results'][0]['count_clicked']; ?>
                </th>
            </tr>
            </tbody>
        </table>
    </div>

</div>
