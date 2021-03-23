<?php


class Mailtrain_API_List
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'menu_lists']);
        add_action('init', [$this, 'post_type_mailtrain_lists']);

        add_action('add_meta_boxes', [$this, 'lists_metabox']);
        add_action('save_post_mailtrain_lists', [$this, 'save']);

        add_action('admin_enqueue_scripts', [$this, 'ajax_function']);

        add_action('wp_ajax_nopriv_event-list', [$this, 'list_info']);
        add_action('wp_ajax_event-list', [$this, 'list_info']);
    }

    public function ajax_function()
    {
        wp_enqueue_script('my_js', plugin_dir_url(__FILE__) . 'js/ajax.js', array('jquery'), '1', true);
        wp_localize_script('my_js', 'ajax_var', array(
            'url'    => admin_url('admin-ajax.php'),
            'nonce'  => wp_create_nonce('my-ajax-nonce'),
            'action' => 'event-list'
        ));
    }

    public function list_info()
    {
       
        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field($_POST['nonce']);

            if (!wp_verify_nonce($nonce, 'my-ajax-nonce')) {
                die('Busted!');
            }
            echo mailtrain_api()->get_list_by_id($_POST['id']);
            wp_die();
        }
    }

    public function menu_lists()
    {
        add_submenu_page('mailtrain-api', __('Lists', 'mailtrain-api'), __('Lists', 'mailtrain-api'), 'edit_posts', 'edit.php?post_type=mailtrain_lists');
    }

    public function post_type_mailtrain_lists()
    {

        /**
         * Post Type: Lists.
         */

        $labels = [
            'name' => __('Lists', 'mailtrain-api'),
            'singular_name' => __('List', 'mailtrain-api'),
        ];

        $args = [
            'label' => __('Lists', 'mailtrain-api'),
            'labels' => $labels,
            'description' => '',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'has_archive' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => ['slug' => 'mailtrain_lists', 'with_front' => true],
            'query_var' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
        ];

        register_post_type('mailtrain_lists', $args);
    }
    /**
     * List metabox
     */
    public function lists_metabox($post_type)
    {
        $post_types = array('mailtrain_lists');

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'some_meta_box_name',
                __('Options - Configuration', 'mailtrain-api'),
                array($this, 'render_meta_box_content'),
                $post_type,
                'advanced',
                'default'
            );
        }
    }

    public function save($post_id)
    {
        if (!isset($_POST['mailtarin_api_box__nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['mailtarin_api_box__nonce'];


        if (!wp_verify_nonce($nonce, 'mailtarin_api_box_')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $list_id = sanitize_text_field($_POST['list_id']);
        $list_cid = sanitize_text_field($_POST['list_cid']);
        $frecuency = sanitize_text_field($_POST['frecuency']);
        $email = sanitize_text_field($_POST['email_label']);
        $author = sanitize_text_field($_POST['author']);
        $first_name = isset($_POST['first_name']) && $_POST['first_name'] === '1' ? 1 : 0;
        $last_name = isset($_POST['last_name']) && $_POST['last_name'] === '1' ? 1 : 0;
        $force = isset($_POST['force']) && $_POST['force'] === '1' ? 1 : 0;


        if (isset($_POST['extra-field']) && isset($_POST['extra-field-merge'])) {

            $extra_field = $_POST['extra-field'];
            $extra_field_merge = $_POST['extra-field-merge'];

            $custom_field = array_map(function ($extra_field, $extra_field_merge) {
                return array_combine(
                    ['extra-field', 'merge'],
                    [$extra_field, $extra_field_merge]
                );
            }, $extra_field, $extra_field_merge);
            update_post_meta($post_id, '_custom_fields', $custom_field);
        }

        update_post_meta($post_id, '_frecuency', $frecuency);
        update_post_meta($post_id, '_email_label', $email);
        update_post_meta($post_id, '_author_newsletter', $author);
        update_post_meta($post_id, '_list_id', $list_id);
        update_post_meta($post_id, '_list_cid', $list_cid);
        update_post_meta($post_id, '_first_name', $first_name);
        update_post_meta($post_id, '_last_name', $last_name);
        update_post_meta($post_id, '_force', $force);
        update_post_meta($post_id, '_confirmation', $_POST['confirmation']);
    }

    public function render_meta_box_content($post)
    {

        wp_nonce_field('mailtarin_api_box_', 'mailtarin_api_box__nonce');

        $value = get_post_meta($post->ID, '_list_cid', true);
        $email = get_post_meta($post->ID, '_email_label', true) !== "" ? get_post_meta($post->ID, '_email_label', true) : 'Your Email';
        $author = get_post_meta($post->ID, '_author_newsletter', true);
        $frecuency = get_post_meta($post->ID, '_frecuency', true);
        $first_name = get_post_meta($post->ID, '_first_name', true);
        $last_name = get_post_meta($post->ID, '_last_name', true);
        $force = get_post_meta($post->ID, '_force', true);
        $confirmation = get_post_meta($post->ID, '_confirmation', true);

        $extra = get_post_meta($post->ID, '_custom_fields', true);



        $form = '<table class="form-table" id="extra_fields">
        <tbody>
            <tr>
                <th scope="row"><label>' . __('Select a list', 'mailtrain-api') . '</label></th>
                <td>' . mailtrain_api()->lists($post->ID) . '</td>
            </tr>
            <tr>
                <th></th>
                <td>' . $this->list_info() . '<input type="hidden" class="large-text" name="list_cid" id="list_cid" value="' . $value . '" /></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Frequency', 'mailtrain-api') . '</label></th>
                <td><input type="text" name="frecuency" value="' . $frecuency . '"></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Email field label', 'mailtrain-api') . '</label></th>
                <td><input type="text" name="email_label" value="' . $email . '"></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Author', 'mailtrain-api') . '</label></th>
                <td><input type="text" name="author" value="' . $author . '"></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Show First Name Field', 'mailtrain-api') . '</label></th>
                <td><input type="checkbox" name="first_name" value="1" ' . checked(1, $first_name, false) . ' ></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Show Last Name Field', 'mailtrain-api') . '</label></th>
                <td><input type="checkbox" name="last_name" value="1" ' . checked(1, $last_name, false) . ' ></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Force suscribe?', 'mailtrain-api') . '</label></th>
                <td><input type="checkbox" name="force" value="1" ' . checked(1, $force, false) . ' ></td>
            </tr>
            <tr>
                <th scope="row"><label>' . __('Require confirmation?', 'mailtrain-api') . '</label></th>
                <td>
                    <select name="confirmation">
                        <option value="">' . __('-- select --', 'mailtrain-api') . '</option>
                        <option value="no" ' . selected($confirmation, 'no', false) . '>No</option>
                        <option value="yes" ' . selected($confirmation, 'yes', false) . '>Yes</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><h4>' . __('Extra Form Fields', 'mailtrain-api') . '</h4></th>
                <td><button id="add_field" class="components-button edit-post-header-toolbar__inserter-toggle is-primary has-icon" type="button">' . __('ADD EXTRA FIELD', 'mailtrain-api') . '</button><p>' . __('Merge fields or custom fields for your list, these fields must exist in your list ') . '</p></td>
            </tr>';
        if ($extra !== "") {
            $i = 15;
            foreach ($extra as $value) {
                $i++;
                $form .= '<tr class="extra-field" id="extra-field-' . $i . '">
                   <th scope="row" class="label-row"><label>Label</label></th><td><input type="text" class="large-text" name="extra-field[]" value="' . $value['extra-field'] . '"  /></td>
                   <th scope="row" class="label-row"><label>Merge</label></th><td><input type="text" class="large-text medium merge-field" name="extra-field-merge[]" value="' . $value['merge'] . '" /><span class="dashicons-trash dashicons remove-price" data-id="#extra-field-' . $i . '" style="cursor:pointer"></span></td>
                   </tr>';
            }
        }
        $form .= '</tbody> </table>';

        echo $form;
    }
}

function mailtrain_lists()
{
    return new Mailtrain_API_List();
}
mailtrain_lists();
