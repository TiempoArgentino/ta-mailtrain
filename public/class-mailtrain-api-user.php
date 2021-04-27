<?php

class Mailtrain_API_User extends Mailtrain_API_Curl
{
    public function __construct()
    {
        add_action('panel_user_tabs', [$this, 'user_panel_tab'], 7);
        add_action('panel_user_content', [$this, 'user_lists_content']);
    }

    public function user_panel_tab($tab = '')
    {
        if(has_filter( 'panel_tabs_newsletter' )) {
            apply_filters( 'panel_tabs_newsletter', $tab );
        } else {
            echo '<a href="#newsletter" class="tab-select" data-content="#newsletter">' . __('Newsletter', 'panel-user') . '</a> ';
        }
       
    }

    public function user_lists_content()
    {
        if (locate_template('mailtrain/user-panel/lists.php')) {
            /**
             * Create a folder in your theme called "user-panel", into that create other folder called page with file called page.php
             */
            require_once get_template_directory() . '/mailtrain/user-panel/lists.php';
        } else {
            /**
             * Default profile template
             */
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/user-panel/lists.php';
        }
    }

    public function user_lists()
    {
        $user_id = wp_get_current_user()->ID;

        $lists = get_user_meta( $user_id, '_user_mailtrain_lists_id',true );

        if($lists !== null || $lists !== ''){
            return $lists;
        }
        return false;
    }
}

function mailtrain_api_user()
{
    return new Mailtrain_API_User();
}

mailtrain_api_user();
