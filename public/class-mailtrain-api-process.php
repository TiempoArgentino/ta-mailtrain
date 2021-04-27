<?php

class Mailtrain_API_Process extends Mailtrain_API_Curl
{
    public $nonce = 'mailtrain_nonce';
    public $action = 'mailtrain_ajax_action';
    public $url;

    public function __construct()
    {
        $this->url = admin_url('admin-ajax.php');

        add_action('wp_enqueue_scripts', [$this, 'ajax_function']);

        add_action('wp_ajax_nopriv_mailtrain', [$this, 'process_subscribe']);
        add_action('wp_ajax_mailtrain', [$this, 'process_subscribe']);

        add_action('wp_ajax_nopriv_mtwidget', [$this, 'front_widget']);
        add_action('wp_ajax_mtwidget', [$this, 'front_widget']);
    }

    public function ajax_function()
    {
        wp_enqueue_script('mailtrain_js', plugin_dir_url(__FILE__) . 'js/front-ajax.js', array('jquery'), '1.2', true);
        $this->add_widget_vars();
        $this->subscribe();
        
    }

    public function ajas_vars($var_data, $data)
    {
        $fields = [
            'url'    => $this->url,
            '_ajax_nonce'  => wp_create_nonce($this->nonce),
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
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $ids = isset($_POST['ids']) ? $_POST['ids'] : '';

        $fields = [
            'action' => 'mailtrain',
            'name' => $name,
            'email' => $email,
            'lists' => $lists,
            'terms' => $terms,
            'id' => $id,
            'ids' => $ids
        ];

        return $this->ajas_vars('ajax_mailtrain', $fields);
    }

    public function process_subscribe()
    {
        if (isset($_POST['_ajax_nonce'])) {
            
            $nonce = sanitize_text_field($_POST['_ajax_nonce']);

            if (!wp_verify_nonce($nonce, $this->nonce)) {
                die('Busted!');
            }

            if (isset($_POST['name']) && isset($_POST['email'])) {

                if (!isset($_POST['name'])) {
                    echo __('<div class="text-center alert alert-danger">Falta el nombre.</div>', 'mailtrain-api');
                    wp_die();
                }

                if (!is_email($_POST['email'])) {
                    echo __('<div class="text-center alert alert-danger">Falta el email o es incorrecto.</div>', 'mailtrain-api');
                    wp_die();
                }

                if (!isset($_POST['terms']) && $_POST['terms'] !== 'yes') {
                    echo __('<div class="text-center alert alert-danger">Debés aceptar los términos y condiciones.</div>', 'mailtrain-api');
                    wp_die();
                }

                $lists = $_POST['lists'];

                if (!$lists || empty($lists)) {
                    echo __('<div class="text-center alert alert-danger">Debés seleccionar al menos una lista.</div>', 'mailtrain-api');
                    wp_die();
                }

                foreach ($lists as $key => $val) {
                    $add = $this->add_subscriber($val, sanitize_text_field($_POST['name']), sanitize_text_field($_POST['email']));
                }
                $add = json_decode($add);
                if ($add->{'data'}->{'id'}) {
                    if (!empty($_POST['id'])) {
                        update_user_meta($_POST['id'], '_user_mailtrain_lists', $lists);
                        update_user_meta($_POST['id'], '_user_mailtrain_lists_id', $_POST['ids']);
                    }
                    echo 'ok';
                } else {
                    echo __('Error', 'mailtrain-api');
                }
            }
            wp_die();
        }
    }

    /**
     * Function add in widget
     */
    public function add_widget_vars()
    {
        $the_list = isset($_POST['the_list']) ? $_POST['the_list'] : '';
        $the_email = isset($_POST['the_email']) ? $_POST['the_email'] : '';
        $widget = isset($_POST['widget']) ? $_POST['widget'] : '';

        $fields = [
            'action' => 'mtwidget',
            'widget' => $widget,
            'the_list' => $the_list,
            'the_email' => $the_email,
        ];

        return $this->ajas_vars('widget_front_ajax', $fields);
    }
    /**
     * function add widget
     */
    public function front_widget()
    {
 
        if (isset($_POST['widget'])) {
     
            $nonce = sanitize_text_field($_POST['_ajax_nonce']);

            if (!wp_verify_nonce($nonce, $this->nonce)) {
                die('hay un error');
            }

            if (isset($_POST['the_email']) && isset($_POST['the_list'])) {
                $add = $this->add_subscriber($_POST['the_list'], '', $_POST['the_email']);
                $add = json_decode($add);
                if(isset($add->{'error'})){
                    echo wp_send_json_error(__('Invalid email address!', 'mailtrain-api'));
                    wp_die();
                }
                if (isset($add->{'data'}->{'id'})) {
                    echo wp_send_json_success(__('Thank you','mailtrain-api'),200);
                    wp_die();
                }
            } else {
                echo wp_send_json_error(__('All fields are required', 'mailtrain-api'));
                wp_die();
            }
        }
    }
}

function mailtrain_process()
{
    return new Mailtrain_API_Process();
}

mailtrain_process();
