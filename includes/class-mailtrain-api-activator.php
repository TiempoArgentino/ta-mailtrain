<?php

/**
 * Fired during plugin activation
 *
 * @link       https://genosha.com.ar
 * @since      1.0.0
 *
 * @package    Mailtrain_Api
 * @subpackage Mailtrain_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mailtrain_Api
 * @subpackage Mailtrain_Api/includes
 * @author     Juan <juan.e@genosha.com.ar>
 */
class Mailtrain_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::create_default_pages();

		add_action('admin_init',[self::class,'permisions']);
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
            $admin->add_cap($cap);
        }
    }
	/**
	 * Default pages querys, function base: https://developer.wordpress.org/reference/functions/post_exists/
	 */
	public static function page_exists($page_slug)
	{
		global $wpdb;
		$post_title = wp_unslash(sanitize_post_field('post_name', $page_slug, 0, 'db'));

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
		$args  = array();

		if (!empty($page_slug)) {
			$query .= ' AND post_name = %s';
			$args[] = $post_title;
		}

		if (!empty($args)) {
			return (int) $wpdb->get_var($wpdb->prepare($query, $args));
		}

		return 0;
	}
	/**
	 * Create pages
	 */
	public static function create_default_pages()
	{
		if(self::page_exists(get_option('mailtrain_loop_page', 'newsletter')) === 0){
			$page = self::create_mailtrain_page();
			update_option('mailtrain_loop_page', $page);
		}
	}
	/**
	 * Mailtrain Lists Page
	 */
	public static function create_mailtrain_page()
	{
		$args = [
			'post_title' => __('Newsletter','mailtrain-api'),
			'post_status'   => 'publish',
			'post_type'     => 'page',
			'post_content'  => 'This page is for the subscription template, please modify the content in your-theme/mailtrain/mailtrain.php',
			'post_author'   => 1,
		];

		$page = wp_insert_post($args);
		return $page;
	}

}
