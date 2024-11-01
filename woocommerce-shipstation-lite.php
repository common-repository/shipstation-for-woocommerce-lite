<?php
/*
Plugin Name: WooCommerce - ShipStation - LITE version
Plugin URI: mailto:dev@ribbedtee.com
Version: 1.1
Description: Add ShipStation support to WooCommerce. Lite version has restricted functionality. Visit <a href="http://www.woothemes.com/products/shipstation-integration/" target="_blank">WooThemes.com Extension Store</a> for full version.
Author: RTD LLC Development
Author URI: http://ribbedtee.com/
Text Domain: woo-shipstation-lite
*/

// to keep access via hook
add_action( "parse_request", "woo_ss_parse_request" );

function woo_ss_parse_request( &$wp ) {
	if ( $wp->request == "woo-shipstation-lite-api" ) {
		include_once dirname( __FILE__ ) . "/handler.php";
		$hander = new WC_ShipStation_Handler();
		die();
	}
}

class WC_ShipStation {
	public $domain;

	public function __construct() {

		$this->domain = 'woo-shipstation-lite';
		$this->current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

		load_plugin_textdomain( $this->domain, false, basename( dirname( __FILE__ ) ) . '/languages' );

		add_action( 'woocommerce_settings_tabs', array( &$this, 'tab' ), 10 );
		add_action( 'woocommerce_settings_tabs_woo_ss', array( &$this, 'settings_tab_action' ) , 10 );
		add_action( 'woocommerce_update_options_woo_ss', array( &$this, 'save_settings' ) , 10 );
	}

	function tab() {
		$class = 'nav-tab';
		if ( $this->current_tab == 'woo_ss' ) $class .= ' nav-tab-active';
		echo '<a href="' . admin_url( 'admin.php?page=woocommerce&tab=woo_ss' ) . '" class="' . $class . '">ShipStation</a>';
	}

	function shipname( $ship_method ) {
		return str_replace( "_", " ", str_replace( "WC_", "", $ship_method ) );
	}

	function init_form_fields()	{
		$this->form_fields = array(
			array(	'name' => __( 'ShipStation plugin for Woocommerce (Lite Version)', $this->domain ),
				'type' => 'title',
				'desc' => __( "Plugin Features:\n1) Exports orders ready for shipment to ShipStation. \n2) Updates orders inside WooCommerce with shipping and tracking information.\n3) Please refer to the 'ShipStation for WooCommerce' documentation on docs.woothemes.com for full Installation, Config, and Testing instructions.", $this->domain ),
				'id' => 'about' ),
			array( 'type' => 'sectionend', 'id' => 'about' ),

			array(	'name' => __( 'Settings', $this->domain ),
				'type' => 'title',
				'desc' => '<font color="red">' . __( 'Enter same values in ShipStation -> Settings -> Stores -> "Add New Store" -> WooCommerce Store', $this->domain ) . '</font>',
				'id' => 'settings' ),
				array(
					'name' => __( 'Username', $this->domain ),
					'desc' => __( '', $this->domain ),
					'tip' => '',
					'id' => 'woo_ss_username',
					'css' => '',
					'std' => '',
					'type' => 'text',
				),
				array(
					'name' => __( 'Password', $this->domain ),
					'desc' => __( '', $this->domain ),
					'tip' => '',
					'id' => 'woo_ss_password',
					'css' => '',
					'std' => '',
					'type' => 'text',
				),
				array(
					'name' => __( 'Url to custom page', $this->domain ),
					'desc' => __( 'Set Permalinks in Settings>Permalinks. Do NOT use Default', $this->domain ),
					'tip' => '',
					'id' => 'woo_ss_api_url',
					'css' => 'min-width:300px;color:grey',
					'std' => $this->url,
					'default' => $this->url, //for Woocommerce 2.0
					'type' => 'text',
				),
				array(
					'name' => __( 'Log requests', $this->domain ),
					'desc' => "<a target='_blank' href='{$this->url_log}'>" . __( 'View Log', $this->domain ) . "</a>",
					'tip' => '',
					'id' => 'woo_ss_log_requests',
					'css' => '',
					'std' => '',
					'type' => 'checkbox',
				),
			array( 'type' => 'sectionend', 'id' => 'settings' ),

			array(	'name' => __( 'Alternate Authentication', $this->domain ),
				'type' => 'title',
				'desc' => '<font color="red">' . __( 'For use on webservers which run PHP in CGI mode. Add "?auth_key=value" to test url', $this->domain ) . '</font>',
				'id' => 'testing' ),
				array(
					'name' => __( 'Authentication Key', $this->domain ),
					'desc' => __( 'Enter long, random string here.', $this->domain ),
					'tip' => '',
					'id' => 'woo_ss_auth_key',
					'css' => 'min-width:300px;color:grey',
					'std' => '',
					'type' => 'text',
				),
			array( 'type' => 'sectionend', 'id' => 'altAuth' ),

			array(	'name' => __( 'Export', $this->domain ),
				'type' => 'title',
				'desc' => '',
				'id' => 'export' ),
		);

		//add checkboxes for export status
		$export_statuses = array( 'processing' );
		$count_status = count( $export_statuses );
		for ( $i = 0; $i < $count_status; $i++ ) {
			$status = $export_statuses[ $i ];
			$mode = "";
			if ( $i == 0 )
				$mode = "start";
			if ( ( $i == $count_status - 1 ) AND ( $count_status != 1 ) )
				$mode = "end";

			$this->form_fields[] =
				array(
					'name' => __( 'Order Status to look for when importing into ShipStation', $this->domain ),
					'desc' => ucwords( $status ),
					'tip' => '',
					'id' => 'woo_ss_export_status_' . md5( $status ),
					'css' => '',
					'std' => '',
					'checkboxgroup' => $mode,
					'type' => 'checkbox',
				);
		}

		// for export shipment
		$count_status = count( $this->shipping_methods );
		reset( $this->shipping_methods );
		$method = current( $this->shipping_methods );
		for ( $i = 0; $i < $count_status; $i++ ) {
			$mode = "";
			if ( $i == 0 )
				$mode = "start";
			if ( ( $i == $count_status - 1 ) AND ( $count_status != 1 ) )
				$mode = "end";

			$this->form_fields[] =
				array(
					'name' => __( 'Shipping Methods to expose to ShipStation', $this->domain ),
					'desc' => $method->title,
					'tip' => '',
					'id' => 'woo_ss_export_shipping_' . $method->id,
					'css' => '',
					'std' => '',
					'checkboxgroup' => $mode,
					'type' => 'checkbox',
				);
			$method = next( $this->shipping_methods );
		}


		$this->form_fields = array_merge( $this->form_fields, array(
			array( 'type' => 'sectionend', 'id' => 'export' ),
			array(	'name' => __( 'Import', $this->domain ),
				'type' => 'title',
				'desc' => $this->ship_details_plugin . $this->shipment_tracking_plugin,
				'id' => 'import' ),
				array(
					'name' => __( 'Order Status to move it to when the shipnotify action is presented', $this->domain ),
					'desc' => '',
					'tip' => '',
					'id' => 'woo_ss_import_status',
					'css' => '',
					'std' => '',
					'type' => 'select',
					'options' => $this->order_statuses
				),
			array( 'type' => 'sectionend', 'id' => 'import' ),
		) );
	}

	function load_settings() {
		global $woocommerce;

		$this->shipment_tracking_plugin = $this->ship_details_plugin = "";

		$url = site_url();
		if ( substr( $url, -1 ) != "/" )
			$url .= "/";
		$url .= "woo-shipstation-lite-api";
		$this->url = $url;

		$folder = basename( dirname( __FILE__ ) );
		$this->url_log = site_url() . "/wp-content/plugins/$folder/handler.txt";

		$order_statuses = get_terms( "shop_order_status", "hide_empty=0" );
		$this->order_statuses = array();
		foreach ( $order_statuses as $status ) {
			$this->order_statuses[] = $status->name ;
		}

		$flat = new stdClass;
		$flat->id = 'flat_rate';
		$flat->title = 'Flat Rate';
		$this->shipping_methods = array( $flat );
	}

	function settings_tab_action() {
		global $woocommerce_settings;
		$current_tab = 'woo_ss';

		$this->load_settings();

		// Display settings for this tab ( make sure to add the settings to the tab ).
		$this->init_form_fields();
		$woocommerce_settings[ $current_tab ] = $this->form_fields;
		woocommerce_admin_fields( $woocommerce_settings[ $current_tab ] );
	}



	function save_settings() {
		global $woocommerce_settings;
		$current_tab = 'woo_ss';

		$this->load_settings();

		$this->init_form_fields();
		$woocommerce_settings[ $current_tab ] = $this->form_fields;
		woocommerce_update_options( $woocommerce_settings[ $current_tab ] );

		delete_option( 'woo_ss_api_url' ); // don't update api url
		return true;
	}
}

$GLOBALS['WC_ShipStation'] = new WC_ShipStation();
