<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://waplugin.com/
 * @since      1.0.0
 *
 * @package    Waplugin
 * @subpackage Waplugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Waplugin
 * @subpackage Waplugin/admin
 * @author     WAPLUGIN <waplugin@gmail.com>
 */
class Waplugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $api_requestor;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $endpoint   WAPLUGIN API ENDPOINT
	 */
	public function __construct( $plugin_name, $version, $api_requestor ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_requestor = $api_requestor;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Waplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Waplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$chkhook = strpos($hook, 'waplugin');
		if ($chkhook !== false) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/waplugin-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Waplugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Waplugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$chkhook = strpos($hook, 'waplugin');
		if ($chkhook !== false) {
			$params = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce(date('H')),
			);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/waplugin-admin.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'ajax_object', $params );
		}

	}

	public function waplugin_menu()
	{
	    add_menu_page( 
	        esc_html( 'WAPLUGIN', 'waplugin' ),
	        esc_html( 'WAPLUGIN', 'waplugin' ),
	        'manage_options',
	        'wapluginoverview',
	        array($this, 'waplugin_admin_overview'),
	        plugins_url( 'images/waplugin.png', __FILE__ ),
	        26
	    );
	}

	public function waplugin_admin_overview()
	{
		wp_cache_delete('waplugin_api', 'options');
		wp_cache_delete('waplugin_account_id', 'options');
		$waplugin_api = get_option( 'waplugin_api' );
		$waplugin_account_id = get_option( 'waplugin_account_id' );
		$accounts = [];
		if (false !== $waplugin_api) {
			try {
				$resultAccount = $this->api_requestor->get('/account', $waplugin_api, null);
				if (isset($resultAccount['results']['data'])) {
					$accounts = $resultAccount['results']['data'];
				}
			} catch (\Exception $e) {
				// error
			}
		}
		include_once 'partials/waplugin-admin-display.php';
	}

	/*Ajax here*/
	public function check_api_key()
	{
		check_ajax_referer( date('H'), 'sid' );

		try {
			$result = $this->api_requestor->get('/account', $_POST['waplugin_api'], null);

			if (isset($result['code']) && $result['code'] == 200) {
				// Valid API
				wp_cache_delete('waplugin_api', 'options');
				$waplugin_api = get_option( 'waplugin_api' );
				if (false === $waplugin_api) {
					add_option('waplugin_api', $_POST['waplugin_api'], '', 'no');
				} else {
					update_option('waplugin_api', $_POST['waplugin_api']);
				}
				echo json_encode(array('success' => true, 'msg' => esc_html( 'Valid', 'waplugin' )));
			} else {
				// Invalid API
				echo json_encode(array('success' => false, 'msg' => esc_html( 'Invalid', 'waplugin' )));
			}
		} catch (\Exception $e) {
			echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
		}
		wp_die();
	}

	public function add_account()
	{
		check_ajax_referer( date('H'), 'sid' );

		wp_cache_delete('waplugin_api', 'options');
		wp_cache_delete('waplugin_account_id', 'options');
		$waplugin_api = get_option( 'waplugin_api' );

		try {
			$result = $this->api_requestor->get('/account/'.$_POST['waplugin_account_id'], $waplugin_api, null);

			if (isset($result['code']) && $result['code'] == 200) {
				// Valid Account
				$waplugin_account_id = get_option( 'waplugin_account_id' );
				if (false === $waplugin_account_id) {
					add_option('waplugin_account_id', $_POST['waplugin_account_id'], '', 'no');
				} else {
					update_option('waplugin_account_id', $_POST['waplugin_account_id']);
				}
				echo json_encode(array('success' => true, 'msg' => esc_html( 'Valid', 'waplugin' )));
			} else {
				// Invalid Account
				echo json_encode(array('success' => false, 'msg' => esc_html( 'Invalid', 'waplugin' )));
			}
		} catch (\Exception $e) {
			echo json_encode(array('success' => false, 'msg' => $e->getMessage()));
		}
		wp_die();
	}

	/*Setting tabs here*/
	public function waplugin_settings_tab($settings_tabs)
	{
        $settings_tabs['settings_tab_waplugin'] = __( 'WAPLUGIN', 'waplugin' );
        return $settings_tabs;
	}

	public function waplugin_settings_tab_content()
	{
		woocommerce_admin_fields( $this->waplugin_get_settings() );
	}

	public function waplugin_get_settings()
	{
	    $settings = array(
	        'section_title' => array(
	            'name'     => __( 'Notification Template', 'waplugin' ),
	            'type'     => 'title',
	            'desc'     => '',
	            'id'       => 'waplugin_tab_section_title'
	        ),
	        'new_order' => array(
	            'name' => __( 'New Order', 'waplugin' ),
	            'type' => 'textarea',
	            'desc' => '',
	            'css' => 'height: 100px;',
	            'desc_tip' => __('Send notifications to customers when they successfully place an order', 'waplugin'),
	            'id'   => 'waplugin_tab_new_order'
	        ),
	        'order_status_changed' => array(
	            'name' => __( 'Order Status Changed', 'waplugin' ),
	            'type' => 'textarea',
	            'desc' => '',
	            'css' => 'height: 100px;',
	            'desc_tip' => __('Send notifications to customers when order status changes', 'waplugin'),
	            'id'   => 'waplugin_tab_order_status_changed'
	        ),
	        'section_end' => array(
				'type' => 'sectionend',
				'id' => 'waplugin_tab_section_end'
	        )
	    );
	    return apply_filters( 'wc_settings_tab_waplugin_settings', $settings );
	}

	public function waplugin_tab_update_settings()
	{
		woocommerce_update_options( $this->waplugin_get_settings() );
	}

	public function waplugin_notif_order_status_changed($order_id)
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-waplugin-notif.php';
		(new Waplugin_Notif)->send($order_id, $this->api_requestor);
	}

}
