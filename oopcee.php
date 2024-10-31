<?php
/*
Plugin Name: Oopcee
Plugin URI: http://www.oopcee.com/
Description: Oopcee integration plugin for wordpress
Version: 1.02 
Author: Oopcee (www.oopcee.com)
Author URI: http://www.oopcee.com
*/


class Oopcee {

	private $oopceePluginPath;
	private $oopceeCode;
	private $oopceePass;
	private $oopceeSiteId;
	private $oopceeUserId;

	public function __construct() {
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

		register_activation_hook( __FILE__, array( $this , 'activateOopcee'));

		$this->oopceePluginPath	= plugin_dir_path( __FILE__ );
		$this->oopceeCode 		= get_option('oopceeCode');
		$this->oopceePass 		= get_option('oopceePass');
		$this->oopceeSiteId 	= get_option('oopceeSiteId');
		$this->oopceeUserId 	= get_option('oopceeUserId');
		
		add_action( 'admin_menu', array( $this , 'oopcee_admin_actions'));
		add_action( 'wp_footer',  array( $this , 'loadOopceeCode') );
	}

	public function loadOopceeCode() {
		$output = base64_decode($this->oopceeCode);
		$output = str_replace("***nl***", "", $output);
		echo $output;
	}

	public function activateOopcee(){
		require(plugin_dir_path( __FILE__ ).'configs.php');

		$data 				= array();
		$data['siteUrl'] 	= get_option('siteurl');
		$data['email']		= get_option('admin_email');

		$method				= "POST";
		$url 				= $nodeJSServer."/api/wp/register";

		$response 			= $this->oopceeapi($method, $url, $data);

		if (is_array($response)) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_settings_error(
		        'oopceeApiError',
		        esc_attr( 'oopcee_chat' ),
		        $response[0],
		        'error'
		    );
		    settings_errors( 'oopceeApiError' );
			die();
		}

		if ($this->oopceeCode !== false) delete_option('oopceeCode');
		if ($this->oopceePass !== false) delete_option('oopceePass');
		if ($this->oopceeSiteId !== false) delete_option('oopceeSiteId');
		if ($this->oopceeUserId !== false) delete_option('oopceeUserId');

		add_option('oopceeCode', $response->data->oopceeCode, "", "yes");
		add_option('oopceePass', $response->data->oopceePass, "", "yes");
		add_option('oopceeSiteId', $response->data->oopceeSiteId, "", "yes");
		add_option('oopceeUserId', $response->data->oopceeUserId, "", "yes");
		
	}


	public function oopcee_admin_actions() {
		$page_title 		= "oopcee";
		$menu_title 		= "oopcee";
		$capability 		= "manage_options";
		$menu_slug  		= "oopcee_recordings";
		$function   		= array( $this , "oopceerecordings" );
		$icon_url   		= plugins_url( 'views/images/icon-2-oopcee.png', __FILE__ );

		$position 			= null;
		$subpage1_title		= "Change password";
		$menu1_title 		= "Change password";
		$menu1_slug 		= "oopcee_change_password"; // to avoid duplicate submenu
		$function1 			= array( $this , "oopceechangepassword"); //callable

		add_menu_page( "", $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_submenu_page( $menu_slug, $subpage1_title, $menu1_title, $capability, $menu1_slug, $function1 );
	}


	public function oopceerecordings() {
		require($this->oopceePluginPath.'controllers/oopcee.php');
		$renderOopceeHomePage = new OopceeHomePage($this->oopceePluginPath);
	}

	public function oopceechangepassword() {
		require($this->oopceePluginPath.'controllers/changepassword.php');
		$renderOopceeHomePage = new OopceeChangePassword($this->oopceePluginPath);
	}


	public function oopceeapi($method, $url, $data){
	    global $errMessage;
	    
	    $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_VERBOSE, true);
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	    
	    if (!is_null($data)) {
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    }
	    //var_dump($ch);

	    $response = curl_exec($ch);

	    $curl_errno = curl_errno($ch);
	    $curl_error = curl_error($ch);

	    if ($curl_errno > 0) {
	        $errMessage = array('Communication with API failed. Curl error: '.$curl_error);
	        return ($errMessage);
	    }

	    if(is_null($response) || !$response || $response == "null") {
	        $errMessage = array('Communication with API failed. Null response from server.');
	        return ($errMessage);
	    }

	    $myResponse = json_decode($response);
	    
	    if (is_null($myResponse)) {
	        $errMessage = array("Invalid response from API (no json format).");
	        return $errMessage;
	    }

	    if (isset($myResponse->type) && !($myResponse->type)) {
	        $errMessage = array($myResponse->data);
	        return $errMessage;
	    }

	    return $myResponse;

	}

}

$oopcee = new Oopcee();