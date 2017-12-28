jQuery(function ($) {
    'use strict';

    $('.errors').hide();
    $('#metrics-error').hide();
    $('#events-error').hide();
    // getting on the ajax options ( ajaxurl, action, form data).
    var sprOptions = window._sprOpt;


    // adding options for 'from date' datepicker.
    $('.spr-datepicker-from').datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: new Date(),
        onSelect: function (date) {
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;
            var endDate = new Date(selectedDate.getTime() + msecsInADay);

            $('.spr-datepicker-to').datepicker('option', 'minDate', selectedDate);
        }
    });


    // adding options for 'to date' datepicker.
    $('.spr-datepicker-to').datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: new Date()
    });


    // adding options for 'events from date' datepicker.
    $('.spr-events-datepicker-from').datepicker(
        {
            dateFormat: 'yy-mm-dd',
            minDate: '-9d',
            maxDate: new Date(),
            onSelect: function (date) {
                var selectedDate = new Date(date);
                var msecsInADay = 86400000;
                var endDate = new Date(selectedDate.getTime() + msecsInADay);

                $('.spr-events-datepicker-to').datepicker('option', 'minDate', selectedDate);
            }
        }
    );

    // adding options for 'events to date' datepicker.
    $('.spr-events-datepicker-to').datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: new Date()
    });

    /*------------------------Metrics Ajax Handling Start----------------------------------------*/

    var $form = $('#spr-metrics-filter-form');
    //Ajax for filtered reports.
    $form.on('submit', function (e) {

        $('.errors').hide();
        $('#metrics-error').hide();

        /*----------------------------------------- validation start -----------------------------------------------------*/
        var $valid = false;
        $('#spr-metrics-filter-form :input[type=text]').each(function () {
            if ($(this).val()) {
                $valid = true;
                return false;
            }
        });

        if ($valid === false) {
            $('#metrics-error').show();
            return false;
        }


        if ($('#spr-metrics-filter-form #toDate').val() && !$('#spr-metrics-filter-form #fromDate').val()) {
            $('.from-date').find('.errors').show();
            return false;
        }

        if (!$('#spr-metrics-filter-form #toDate').val() && $('#spr-metrics-filter-form #fromDate').val()) {
            $('.to-date').find('.errors').show();
            return false;
        }
        /*----------------------------------------- validation end -----------------------------------------------------*/

        e.preventDefault();

        var $submit = $form.find(':submit');

        $submit.prop('disabled', true);
        $form.find('.spinner').css('visibility', 'visible');

        $.get(sprOptions.ajaxUrl + '?action=spr_get_metrics', $(this).serialize())

            .done(function (response) {
                var html = '';
                var datenotice = '';
                if (response.data && response.data.results) {
                    response.data.results.forEach(function (item) {
                        html += '<tr>';
                        html += '<td>' + item.count_targeted + '</td>';
                        html += '<td>' + item.count_injected + '</td>';
                        html += '<td>' + item.count_accepted + '</td>';
                        html += '<td>' + item.count_delivered + '</td>';
                        html += '<td>' + item.count_rendered + '</td>';
                        html += '<td>' + item.count_clicked + '</td>';
                        html += '</tr>';
                    });
                }

                $('#spr-metrics-table').find('tbody').empty().html(html);
                $('#metrics-date-notice').find('thead').empty().html(datenotice);
            })

            .fail(function (jqXHR) {
                alert('unable to fetch results');
            })

            .always(function () {
                $submit.prop('disabled', false);
                $form.find('.spinner').css('visibility', 'hidden');
            });

    });

    /*------------------------Metrics Ajax Handling End----------------------------------------*/


    /*-------------------Message Events Ajax Handling Start----------------------*/

    var $eventsForm = $('#spr-message-events-filter-form');
    //Ajax for filtered reports.
    $eventsForm.on('submit', function (e) {
        $('.errors').hide();
        $('#metrics-error').hide();
        $('#events-error').hide();

        /*----------------------------------------- validation start -----------------------------------------------------*/

        var $valid = false;
        $('#spr-message-events-filter-form :input[type=text]').each(function () {
            if ($(this).val()) {
                $valid = true;
                return false;
            }
        });

        $('#spr-message-events-filter-form :input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                $valid = true;
                return false;
            }
        });

        if ($valid === false) {
            $('#events-error').show();
            return false;
        }

        if ($('#spr-message-events-filter-form #events-to-date').val() && !$('#spr-message-events-filter-form #events-from-date').val()) {
            $('.events-from-date').find('.errors').show();
            return false;
        }

        if (!$('#spr-message-events-filter-form #events-to-date').val() && $('#spr-message-events-filter-form #events-from-date').val()) {
            $('.events-to-date').find('.errors').show();
            return false;
        }

        /*-------------------------------------------------------- validation end-----------------------------------------*/

        e.preventDefault();

        var $submit = $eventsForm.find(':submit');
        $submit.prop('disabled', true);
        $('#events-reset').prop('disabled', true);
        $('#csv-download').prop('disabled', true);
        $eventsForm.find('.spinner').css('visibility', 'visible');

        $.get(sprOptions.ajaxUrl + '?action=spr_get_message_events', $(this).serialize())

            .done(function (response) {
                var html = '';

                if (response.data) {

                    response.data.forEach(function (item) {

                        if (item.campaign === undefined || item.campaign === null) {
                            item.campaign = "";
                        }

                        html += '<tr>';
                        html += '<th id="events-subject" class="manage-column column-title">' + item.subject + '</th>';
                        html += '<th id="events-type" class="manage-column column-title">' + item.type + '</td>';
                        html += '<th id="events-campaign" class="manage-column column-title">' + item.campaign + '</td>';
                        html += '<th id="events-from" class="manage-column column-title">' + item.friendly_from + '</td>';
                        html += '<th id="events-recipient" class="manage-column column-title">' + item.raw_rcpt_to + '</td>';
                        html += '<th id="events-timestamp" class="manage-column column-title">' + item.timestamp + '</td>';
                        html += '</tr>';
                    });
                }
                else {
                    alert('Sorry, no results found for the current query.');
                }

                $("#spr-events-table").trigger("destroy");
                $('#spr-events-table').find('tbody').empty().html(html);
                //$('#events-date-notice').find('thead').empty().html(datenotice);


                $("#spr-events-table")
                    .tablesorter({
                        widgets: ["zebra", "filter"],
                        headers: {
                            0: {sorter: true}
                        },
                        sortList: [[0, 0]],
                        widgetOptions: {
                            // filter_anyMatch replaced! Instead use the filter_external option
                            // Set to use a jQuery selector (or jQuery object) pointing to the
                            // external filter (column specific or any match)
                            filter_external: '#spr-events-all-search',
                            filter_columnFilters: true,
                            filter_searchDelay: 50
                        }
                    })
                    .tablesorterPager({
                        // target the pager markup - see the HTML block below
                        container: $("#spr-events-pager")
                    });

            })

            .fail(function (jqXHR) {
                alert('Unable to fetch results, please try again.');
            })

            .always(function () {
                $submit.prop('disabled', false);
                $('#events-reset').prop('disabled', false);
                $('#csv-download').prop('disabled', false);
                $eventsForm.find('.spinner').css('visibility', 'hidden');
            });

    });


    /*-----------------Message Events Ajax Handling End------------------------------------*/


    /*-----------------------------Events Table Sorter Start--------------------------------*/

    $("#spr-events-table")
        .tablesorter({
            widgets: ["zebra", "filter"],
            headers: {
                0: {sorter: true}
            },
            sortList: [[0, 0]],
            widgetOptions: {
                // filter_anyMatch replaced! Instead use the filter_external option
                // Set to use a jQuery selector (or jQuery object) pointing to the
                // external filter (column specific or any match)
                filter_external: '#spr-events-all-search',
                filter_columnFilters: true,
                filter_searchDelay: 50
            }
        })
        .tablesorterPager({
            // target the pager markup - see the HTML block below
            container: $("#spr-events-pager")
        });

    /*-----------------------------Events Table Sorter End--------------------------------*/


    /*---------------------Ajax call to download the reports in the csv format start------------*/

    //Ajax for filtered reports.
    var $downloadButton = $eventsForm.find('#csv-download');
    $downloadButton.on('click', function (e) {
        $('.errors').hide();
        $('#metrics-error').hide();
        $('#events-error').hide();
        /*----------------------------------------- validation start -----------------------------------------------------*/

        var $valid = false;

        $('#spr-message-events-filter-form :input[type=text]').each(function () {
            if ($(this).val()) {
                $valid = true;
                return false;
            }
        });

        $('#spr-message-events-filter-form :input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                $valid = true;
                return false;
            }
        });

        if ($valid === false) {
            $('#events-error').show();
            return false;
        }

        if ($('#spr-message-events-filter-form #events-to-date').val() && !$('#spr-message-events-filter-form #events-from-date').val()) {
            $('.events-from-date').find('.errors').show();
            return false;
        }

        if (!$('#spr-message-events-filter-form #events-to-date').val() && $('#spr-message-events-filter-form #events-from-date').val()) {
            $('.events-to-date').find('.errors').show();
            return false;
        }

        /*-------------------------------------------------------- validation end-----------------------------------------*/


        e.preventDefault();

        $('#events-submit').prop('disabled', true);
        $('#events-reset').prop('disabled', true);
        $downloadButton.prop('disabled', true);
        $eventsForm.find('.spinner').css('visibility', 'visible');

        $.get(sprOptions.ajaxUrl + '?action=spr_download_csv', $($eventsForm).serialize())

            .done(function (response) {
                var html = '';
                var datenotice = '';

                if (response.data && response.data.events) {
                    response.data.events.forEach(function (item) {
                        if (item.campaign === undefined || item.campaign === null) {
                            item.campaign = "";
                        }

                        html += '<tr>';
                        html += '<th id="events-subject" class="manage-column column-title">' + item.subject + '</th>';
                        html += '<th id="events-type" class="manage-column column-title">' + item.type + '</td>';
                        html += '<th id="events-campaign" class="manage-column column-title">' + item.campaign + '</td>';
                        html += '<th id="events-from" class="manage-column column-title">' + item.friendly_from + '</td>';
                        html += '<th id="events-recipient" class="manage-column column-title">' + item.raw_rcpt_to + '</td>';
                        html += '<th id="events-timestamp" class="manage-column column-title">' + item.timestamp + '</td>';
                        html += '</tr>';
                    });
                }

                $("#spr-events-table").trigger("destroy");
                $('#spr-events-table').find('tbody').empty().html(html);
                //$('#events-date-notice').find('thead').empty().html(datenotice);


                $("#spr-events-table")
                    .tablesorter({
                        widgets: ["zebra", "filter"],
                        headers: {
                            0: {sorter: true}
                        },
                        sortList: [[0, 0]],
                        widgetOptions: {
                            // filter_anyMatch replaced! Instead use the filter_external option
                            // Set to use a jQuery selector (or jQuery object) pointing to the
                            // external filter (column specific or any match)
                            filter_external: '#spr-events-all-search',
                            filter_columnFilters: true,
                            filter_searchDelay: 50
                        }
                    })
                    .tablesorterPager({
                        // target the pager markup - see the HTML block below
                        container: $("#spr-events-pager")
                    });

                if (response.data.file_url || response.data.file_url.length > 0) {
                    window.location.href = response.data.file_url;
                }


                // code to delete the downloaded reports.csv file
                // start
                var file_url = response.data.file_url;
                var url = file_url.substring(file_url.lastIndexOf('/') + 1);
                $.get(sprOptions.ajaxUrl + '?action=spr_delete_csv', 'url=' + url)

                    .done(function () {
                        console.log('done');
                    });
                // end

            })

            .fail(function (jqXHR) {
                alert('Unable to fetch results, please try again.');
            })

            .always(function () {
                $downloadButton.prop('disabled', false);
                $('#events-submit').prop('disabled', false);
                $('#events-reset').prop('disabled', false);
                $eventsForm.find('.spinner').css('visibility', 'hidden');
            });


    });

    /*---------------------Ajax call to download the reports in the csv format end------------*/

});
