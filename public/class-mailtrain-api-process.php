<?php

class Mailtrain_API_Process extends Mailtrain_API_Curl
{


    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'ajax_function']);

        add_action('wp_ajax_nopriv_mailtrain', [$this, 'process_subscribe']);
        add_action('wp_ajax_mailtrain', [$this, 'process_subscribe']);
    }

    public function ajax_function()
    {
        wp_enqueue_script('mailtrain_js', plugin_dir_url(__FILE__) . 'js/ajax.js', array('jquery'), '1', true);
        $this->subscribe();
    }

    public function ajas_vars($var_data, $data)
    {
        $fields = [
            'url'    => admin_url('admin-ajax.php'),
            'nonce'  =>  wp_create_nonce('my-ajax-nonce'),
            'action' => 'mailtrain'
        ];

        $fields = array_merge($fields, $data);

        wp_localize_script('mailtrain_js', $var_data, $fields);
    }

    public function subscribe()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $lists = isset($_POST['lists']) ? $_POST['lists'] : '';
        $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

        $fields = [
            'name' => $name,
            'email' => $email,
            'lists' => $lists,
            'terms' => $terms
        ];

        return $this->ajas_vars('ajax_mailtrain', $fields);
    }

    public function process_subscribe()
    {
        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field($_POST['nonce']);

            if (!wp_verify_nonce($nonce, 'my-ajax-nonce')) {
                die('Busted!');
            }

            if (isset($_POST['name']) && isset($_POST['email'])) {

                if (!isset($_POST['name'])) {
                    echo __('Name missing', 'mailtrain-api');
                    wp_die();
                }

                if (!is_email($_POST['email'])) {
                    echo __('Wrong email', 'mailtrain-api');
                    wp_die();
                }

                if (!isset($_POST['terms']) && $_POST['terms'] !== 'yes') {
                    echo __('You must acept terms and conditions', 'mailtrain-api');
                    wp_die();
                }

                $lists = $_POST['lists'];
                foreach ($lists as $key => $val) {
                    $add = $this->add_subscriber($val, sanitize_text_field($_POST['name']), sanitize_text_field($_POST['email']));
                }
                $add = json_decode($add);
                if ($add->{'data'}->{'id'}) {
                    echo 'ok';
                } else {
                    echo __('Error', 'mailtrain-api');
                }
            }
            wp_die();
        }
    }
}

function mailtrain_process()
{
    return new Mailtrain_API_Process();
}

mailtrain_process();
