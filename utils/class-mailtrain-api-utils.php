<?php

class Mailtrain_API_Utils
{
    public function __construct()
    {
        $this->utils();
    }

    public function utils()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'utils/class-mailtrain-api-sessions.php';
    }
}

function mailtrain_utils()
{
    return new Mailtrain_API_Utils();
}

mailtrain_utils();
