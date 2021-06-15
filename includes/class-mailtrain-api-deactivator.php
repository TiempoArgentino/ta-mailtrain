<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Mailtrain_Api
 * @subpackage Mailtrain_Api/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mailtrain_Api
 * @subpackage Mailtrain_Api/includes
 * @author     Juan <juan.e@genosha.com.ar>
 */
class Mailtrain_Api_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() 
	{
		add_action('admin_init',[self::class,'permisions']);
		self::delete_page();
	}

	public static function delete_page()
	{
		if(get_option('ailtrain_loop_page')) {
			wp_delete_post(get_option('ailtrain_loop_page'));
			delete_option('ailtrain_loop_page');
		}
	}
	/**
	 * caps
	 */
	public static function permisions()
    {
        $admin = get_role( 'administrator' );
        
        $admin_cap = [
            'edit_list',
            'edit_lists',
            'delete_list',
            'delete_lists',
            'publish_lists',
            'edit_published_lists'
        ];

        foreach( $admin_cap as $cap ) {
            $admin->remove_cap($cap);
        }
    }

}
