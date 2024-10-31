<?php
class OopceeHomePage extends Oopcee{

	private $oopceePluginPath;
	private $oopceeUser;
	private $siteUrl;
	private $myResponse; 			//for debug only
	private $oopceeSiteId;
	private $token;
	
	public function __construct($oopceePath) {
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
		
		if (!is_user_logged_in()) {
			die("You are not loged in !");
		}

		define( 'WP_USE_THEMES', false );

		/**
		 * Get all oopcee params
		 */
		$this->oopceePluginPath 	= $oopceePath;
		$this->oopceeUser 			= get_option('admin_email');
		$this->oopceePass 			= get_option('oopceePass');
		$this->oopceeSiteId 		= get_option('oopceeSiteId');
		$this->siteUrl 	  			= get_option('siteurl');
		$this->oopceeSiteId 		= get_option('oopceeSiteId');

		$this->doLogin();
		$this->renderRecordingsView();
	}

	public function doLogin(){
		require($this->oopceePluginPath.'configs.php');

		$data 		   		= array();
		$data['email'] 		= $this->oopceeUser;
		$data['password'] 	= $this->oopceePass;
		$data['fromWp'] 	= true;

		$url 	  			= $nodeJSServer."/api/user/login";
		$myResponse 		= $this->oopceeapi("POST", $url, $data);

		if (is_array($myResponse)) {
			$this->isLogedIn = false;
			add_settings_error(
		        'oopceeApiError',
		        esc_attr( 'oopcee_chat' ),
		        $myResponse[0],
		        'error'
		    );
		    settings_errors( 'oopceeApiError' );
			die();
		}

		$this->token = $myResponse->data;
	}


	public function renderRecordingsView(){
		require($this->oopceePluginPath.'configs.php');

		$myObject 				= array();
		$myObject['email'] 		= $this->oopceeUser;
		$myObject['password']  	= $this->oopceePass;
		$myObject['url']   		= $this->siteUrl;
		$myObject['token'] 		= $this->token;
		$myObject['siteId'] 	= $this->oopceeSiteId;
		$myObject['fromWp'] 	= true;

		$getParamForOopcee 		= base64_encode( json_encode($myObject) );

		require ($this->oopceePluginPath.'views/oopcee.php');
	}


}

?>