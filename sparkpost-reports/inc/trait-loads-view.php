<?php

namespace SparkPost\Plugins\Reports;

trait LoadsView
{

    protected function loadView($file, $data = [])
    {
        extract($data);
        require plugin_dir_path(SPR_BASE_FILE) . 'views/' . $file . '.php';
    }

}
