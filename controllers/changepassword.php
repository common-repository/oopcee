<?php
class OopceeChangePassword extends Oopcee{

	private $oopceePluginPath;
	private $oopceeUser;
	private $siteUrl;
	private $myResponse; 			//for debug only
	private $oopceeSiteId;
	private $token;
	private $reqMethod;
	
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
		$this->reqMethod 			= $_SERVER['REQUEST_METHOD'];

		$this->renderChangePassword();
	}


	public function renderChangePassword(){
		require($this->oopceePluginPath.'configs.php');



		$myObject 				= array();
		$myObject['email'] 		= $this->oopceeUser;
		$myObject['password']  	= $this->oopceePass;
		$myObject['url']   		= $this->siteUrl;
		$myObject['token'] 		= $this->token;
		$myObject['siteId'] 	= $this->oopceeSiteId;
		$myObject['fromWp'] 	= true;

		$getParamForOopcee 		= base64_encode( json_encode($myObject) );


		if ($this->reqMethod == "GET") {
			require ($this->oopceePluginPath.'views/changePass.php');
		} else {
			$myObject['pass1'] 	= sanitize_text_field( $_POST['password1'] );
			$myObject['pass2'] 	= sanitize_text_field( $_POST['password2'] );

			$url 	  			= $nodeJSServer."/api/user/changepassexternal";
			$myResponse 		= $this->oopceeapi("POST", $url, $myObject);

			if (is_array($myResponse)) {
				//deactivate_plugins( plugin_basename( __FILE__ ) );
				add_settings_error(
			        'oopceeApiError',
			        esc_attr( 'oopcee_chat' ),
			        $myResponse[0],
			        'error'
			    );
			    settings_errors( 'oopceeApiError' );
				require ($this->oopceePluginPath.'views/changePass.php');
			} else {
				if (isset($myResponse->type) && $myResponse->type) {
					delete_option('oopceePass');
					add_option('oopceePass', $myResponse->myNewPass, "", "yes");
					require ($this->oopceePluginPath.'views/changePassSuccess.php');
				} else {
					add_settings_error(
				        'oopceeApiError',
				        esc_attr( 'oopcee_chat' ),
				        $myResponse->errorMessage,
				        'error'
				    );
				    settings_errors( 'oopceeApiError' );
					require ($this->oopceePluginPath.'views/changePass.php');
				}
			}
		}
	}


}

?>