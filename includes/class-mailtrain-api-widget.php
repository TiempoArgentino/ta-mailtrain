<?php

class Mailtrain_API_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mailtrain_api_widget',
            __('Mailtrain Form', 'mailtrain-api'),
            ['customize_selective_refresh' => true]
        );

    }

    public function form($instance)
    {
        $msg = isset($instance['msg']) ? esc_attr($instance['msg']) : '';
        $list_option = isset($instance['default_list']) ? esc_attr($instance['default_list']) : '';

        $form_option = '<div class="widget-content"><p><label for="' . $this->get_field_id('msg') . '">' . __('Text or title', 'mailtrain-api') . '</label>';
        $form_option .= '<textarea name="' . $this->get_field_name('msg') . '" id="' . $this->get_field_id('msg') . '" class="widefat">' . $msg . '</textarea></p>';
        $form_option .= '<p><label for="' . $this->get_field_id('default_list') . '">' . __('Select a list', 'mailtrain-api') . '</label>';
        $form_option .= '<select class="widefat code content" name="' . $this->get_field_name('default_list') . '" id="' . $this->get_field_id('default_list') . '">';
        $form_option .= '<option value=""> -- select a list -- </option>';
        foreach (mailtrain_api()->get_all_lists()->{'data'} as $list) {
            $list_data = json_decode(mailtrain_api()->get_list_by_id($list->{'id'}));
            $form_option .= '<option value="' . $list_data->{'cid'} . '" ' . selected($list_option, $list_data->{'cid'}, false) . '>' . $list_data->{'name'} . '</option>';
        }
        $form_option .= '</select></p></div>';
        echo $form_option;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['msg'] = sanitize_text_field($new_instance['msg']);
        $instance['default_list'] = sanitize_text_field($new_instance['default_list']);
        return $instance;
    }

    public function widget($args, $instance)
    {
        $the_list = isset($instance['default_list']) ? esc_attr($instance['default_list']) : '';
        $msg = isset($instance['msg']) ? esc_attr($instance['msg']) : '';
        $view = '';
        if($msg !== '') {
            $view .= $msg;
        }
        
        if($the_list !== '') {
            $view .= '<form method="post" id="mailtrain-form-front">';
            $view .= '<input type="email" name="the_email" id="the_email" placeholder="'.__('Your email','mailtrain-api').'" value="" />';
            $view .= '<input type="hidden" name="the_list" id="the_list" value="'.$the_list.'" />';
            $view .= '<button type="button" class="mt-3" name="button-ma-widget-front" id="button-ma-widget-front">'.__('SEND','mailtrain-api').'</button>';
            $view .= '</form>';
            $view .= '<div id="response-widget"></div>';
        } else {
            $view .= __('This form is not configured','mailtrain-api');
        }

        echo $view;
    }

}

function mailtrain_api__widget()
{
    register_widget('Mailtrain_API_Widget');
}
add_action('widgets_init', 'mailtrain_api__widget');
