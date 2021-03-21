<?php


class Mailtrain_API_Config
{
    private $mailtrain_api_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'mailtrain_api_add_plugin_page'));
        add_action('admin_init', array($this, 'mailtrain_api_page_init'));

        add_action('show_user_profile', [$this, 'mailtrain_user']);
    }

    public function mailtrain_api_add_plugin_page()
    {
        add_menu_page(
            'Mailtrain API', // page_title
            'Mailtrain API', // menu_title
            'manage_options', // capability
            'mailtrain-api', // menu_slug
            array($this, 'mailtrain_api_create_admin_page'), // function
            'dashicons-email-alt', // icon_url
            20 // position
        );
    }

    public function mailtrain_api_create_admin_page()
    {
        $this->mailtrain_api_options = get_option('mailtrain_api_option_name'); ?>

        <div class="wrap">
            <h2>Mailtrain API</h2>
            <p></p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                settings_fields('mailtrain_api_option_group');
                do_settings_sections('mailtrain-api-admin');
                submit_button();
                ?>
            </form>
        </div>
<?php }

    public function mailtrain_api_page_init()
    {
        register_setting(
            'mailtrain_api_option_group', // option_group
            'mailtrain_api_option_name', // option_name
            [$this, 'mailtrain_api_sanitize'] // sanitize_callback
        );

        add_settings_section(
            'mailtrain_api_setting_section', // id
            'Settings', // title
            [$this, 'mailtrain_api_section_info'], // callback
            'mailtrain-api-admin' // page
        );

        add_settings_field(
            'url_mailtrain', // id
            'URL Mailtrain', // title
            [$this, 'url_callback'], // callback
            'mailtrain-api-admin', // page
            'mailtrain_api_setting_section' // section
        );

        add_settings_field(
            'access_token_0', // id
            'API Access Token', // title
            [$this, 'access_token_0_callback'], // callback
            'mailtrain-api-admin', // page
            'mailtrain_api_setting_section' // section
        );

        add_settings_field(
            'lists_page', // id
            __('Lists main page', 'mailtrain-api'), // title
            [$this, 'lists_page_input'], // callback
            'mailtrain-api-admin', // page
            'mailtrain_api_setting_section' // section
        );

        add_settings_field(
            'terms_page', // id
            __('Terms and Conditions', 'mailtrain-api'), // title
            [$this, 'terms_page_input'], // callback
            'mailtrain-api-admin', // page
            'mailtrain_api_setting_section' // section
        );
    }

    public function mailtrain_api_section_info()
    {
    }

    public function get_pages()
    {
        $args = [
            'post_type' => 'page',
            'status'    => 'publish',
            'numberposts' => -1
        ];
        $pages = get_posts($args);

        return $pages;
    }

    public function mailtrain_api_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['access_token_0'])) {
            $sanitary_values['access_token_0'] = sanitize_text_field($input['access_token_0']);
        }

        if (isset($input['url_mailtrain'])) {
            $sanitary_values['url_mailtrain'] = sanitize_text_field($input['url_mailtrain']);
        }

        if (isset($_POST['mailtrain_loop_page'])) {
            update_option('mailtrain_loop_page', $_POST['mailtrain_loop_page']);
        }

        if (isset($_POST['mailtrain_terms_page'])) {
            update_option('mailtrain_terms_page', $_POST['mailtrain_terms_page']);
        }

        return $sanitary_values;
    }

    public function url_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="mailtrain_api_option_name[url_mailtrain]" id="url_mailtrain" value="%s"><p>' . __('ex: https://newsletter.yourdomain.com, without end slash', 'mailtrain-api') . '</p>',
            isset($this->mailtrain_api_options['url_mailtrain']) ? esc_attr($this->mailtrain_api_options['url_mailtrain']) : ''
        );
    }

    public function access_token_0_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="mailtrain_api_option_name[access_token_0]" id="access_token_0" value="%s"><p>' . __('See in https://newsletter.yourdomain.com/users/api, if not exist, generate it', 'mailtrain-api') . '</p>',
            isset($this->mailtrain_api_options['access_token_0']) ? esc_attr($this->mailtrain_api_options['access_token_0']) : ''
        );
    }

    public function lists_page_input()
    {
        $page_slug = get_option('mailtrain_loop_page');
        $pages = $this->get_pages();

        $select = '<select name="mailtrain_loop_page">';
        $select .= '<option value=""> -- select a page -- </option>';
        foreach ($pages as $p) {
            $select .= '<option value="' . $p->ID . '" ' . selected($page_slug, $p->ID, false) . '>' . $p->post_title . '</option>';
        }
        $select .= '</select>';
        echo $select;
    }

    public function terms_page_input()
    {
        $page_slug = get_option('mailtrain_terms_page');
        $pages = $this->get_pages();

        $select = '<select name="mailtrain_terms_page">';
        $select .= '<option value=""> -- select a page -- </option>';
        foreach ($pages as $p) {
            $select .= '<option value="' . $p->ID . '" ' . selected($page_slug, $p->ID, false) . '>' . $p->post_title . '</option>';
        }
        $select .= '</select>';
        echo $select;
    }

    public function mailtrain_user($user)
    {

        $lists = json_decode(mailtrain_api()->get_lists_user($user->user_email));
        var_dump($lists);
        $field = '<h3>' . __('Mailtrain Lists', 'mailtrain-api') . '</h3>';

        $field .= '<table class="form-table">';
        $field .= '<tr> 
        <th scope="row">' . __('Newsletter Lists', 'mailtrain-api') . '</th>';
        $field .= '<td>';
        foreach($lists->{'data'} as $data) {
            $field .= $data->{'name'}.'<br />';
        }
        $field .= '</td>';
        $field .= '</tr></table>';
        echo $field;
    }
}
function mailtrain_config()
{
    return new Mailtrain_API_Config();
}
mailtrain_config();
