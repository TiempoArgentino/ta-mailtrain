<?php

class Mailtrain_API_Sessions
{
    /***
     * Set Flash messages
     */
    public function set_flash_session($class, $msg)
    {
        /**
         * Init sessions if not
         */
        if (!session_id()) {
            session_start();
        }
        /**
         * Create session if not exist
         */
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = ["hola"];
        }

        $_SESSION['flash_messages'] = [
            'name' => $class,
            'msg' => $msg
        ];

        return $_SESSION['flash_messages'];
    }
    /**
     * Show Flash Messages
     */
    public function show_flash_session()
    {
        if (isset($_SESSION['flash_messages']) && !empty($_SESSION['flash_messages'])) {

            echo '<div class="alert alert-' . $_SESSION['flash_messages']['name'] . ' is-dismissible">
                    <p>' . $_SESSION['flash_messages']['msg'] . '</p>
                </div>';
        }
        unset($_SESSION['flash_messages']);
    }
}

function mailtrain_sessions()
{
    return new Mailtrain_API_Sessions();
}

mailtrain_sessions();
