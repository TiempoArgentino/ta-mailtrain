<?php

class Mailtrain_API_Templates
{
    public function __construct()
    {
        add_filter('template_include', [$this, 'mailtrain_loop'], 99);
    }
    /**
     * You must create a folder called "mailtrain" into your main theme and copy the php file to override then
     */
    public function suscription_load_template($filename = '')
    {
        if (!empty($filename)) {
            if (locate_template('mailtrain/' . $filename)) {
                /**
                 * Folder in theme for show the lists.
                 */
                $template = locate_template('mailtrain/' . $filename);
            } else {
                /**
                 * Default folder of templates
                 */
                $template = dirname(__FILE__) . '/partials/' . $filename;
            }
        }
        return $template;
    }

    

    public function mailtrain_loop($template)
    {
        if (is_page(get_option('mailtrain_loop_page')))
            $template = $this->suscription_load_template('pages/mailtrain.php');
        return $template;
    }

}

$mailtrain_api_templates = new Mailtrain_API_Templates();
