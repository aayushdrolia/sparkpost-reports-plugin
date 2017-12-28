<div class="wrap">
    <h1>SparkPost settings</h1>
    <?php settings_errors(); ?>

    <div class="option-page-wrap">
        <form action="<?php echo admin_url('options.php') ?>" method="post" id="sparkpost-form" novalidate>
            <?php
            //wp inbuilt nonce field , etc
            settings_fields($option_group);
            ?>

            <table class="form-table">
                <tr>
                    <th scope="row">API Key*</th>
                    <td>
                        <input type="text"
                               class="full-width"
                               placeholder="API KEY"
                               name="sparkpost_options[api_key]"
                               value="<?php echo esc_attr($db['api_key']); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Sub account id*</th>
                    <td>
                        <input type="text"
                               class="full-width"
                               placeholder="Sub account id"
                               name="sparkpost_options[sub_account_id]"
                               value="<?php echo esc_attr($db['sub_account_id']); ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button() ?>
        </form>
    </div>
</div>
