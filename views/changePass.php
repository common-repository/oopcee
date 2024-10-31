<?php
/**
* Plugin Name: Oopcee
* Plugin URI: http://www.oopcee.com/
* Description: Oopcee integration plugin for wordpress
* Version: 1.0 
* Author: Oopcee (www.oopcee.com)
* Author URI: http://www.oopcee.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>

<style type="text/css">
	.oopceeAdminMainContainer{
		width: 100%;
	}
	.oopceeAdminHeaderContainer {
		width: 100%;
	}
	.oopceeAdminHeaderLogoContainer {
		width: 20%;
		height:40px;
		padding: 10px 10px 10px 40px;
	}
	.oopceeAdminHeaderEmailContainer {
		width: 20%;
		height:40px;
		float: right;
		padding: 20px 10px 0 10px;
		color: #6D727A;
	}
	.oopceeAdminHeaderLogoContainer img {
		height: 17px;
	    margin: 11px 7px 17px 17px;
	    width: 73px;
	}
	.oopceeAdminBodyContainer {
		width: 100%;
		margin: 40px 0 0 0;
		/*padding: 20px;*/
	}
	.oopceeAdminFreeTrialContainer {
		width: 100%;
	}
	.oopceeAdminButtonContainer .inputsubmit { 
		height:44px; 
		padding:15px 20px; 
		border:2px solid #FD7912; 
		border-radius:5px; 
		background-color:#FFFFFF; 
		font-size:14px; 
		color:#6D727A; 
		font-weight:bold; 
		text-align:center;
		text-decoration: none;
		cursor: pointer;
	}
	.oopceeAdminButtonContainer {
		margin: 40px 0 30px 0;
	}
	.oopceeAdminFreeTrialContainer span {
		font-size: 12px;
		color: #6D727A;
	}
	.oopceeAdminFreeTrialContainer .forminput {
		/*width: 80%;*/
		/*padding: 7px;
		border-radius: 5px;*/
		margin: 10px;
		width: 320px;
	    height: 42px;
	    line-height: 44px;
	    border: 1px solid #e4e4e4;
	    box-shadow: 1px 1px 4px 0 #e9ebf0 inset;
	    color: #f77940;
	    font-size: 15px;
	    font-weight: 400;
	    padding-left: 30px;
	    padding-right: 40px;
	}
	.oopceeAdminRecoverPasswordContainer {
		/*margin-bottom: 80px;
	    margin-top: 45px;*/
	    /*width: 400px;*/
	    border-top: 3px solid #f77940;
	    box-shadow: 10px 10px 6px 0 rgba(23, 24, 29, 0.05);
	    display: inline-block;
	    /*padding: 30px 0;*/
	}
	.oopceeAdminRecoverPasswordContainer label {
	    color: #6f727c;
	}


</style>

<div class="oopceeAdminMainContainer">
	<div class="oopceeAdminHeaderContainer">
		<div class="oopceeAdminHeaderEmailContainer">
			<strong>Your email:</strong>
			<span><?php echo $this->oopceeUser; ?></span>
		</div>
		<div class="oopceeAdminHeaderLogoContainer">
			<img src="<?php echo plugins_url( 'images/oopcee_logo_grey_huge.png', __FILE__ ); ?>">
		</div>
		
	</div>
	<div class="oopceeAdminBodyContainer" align="center">
		<div class="oopceeAdminFreeTrialContainer oopceeAdminRecoverPasswordContainer" style="width: 30%">
			<form method="POST" action="<?php echo "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
				<h1 style="margin: 40px 0;">Change your password</h1>
				<div style="color: red; font-size: 14px; margin: 20px 0;">
				<?php
					if (isset($myResponse) && is_array($myResponse)) {
						echo $myResponse[0];
					}
				?>
				</div>
	            <label>New password:</label><br />
	            <input class="forminput" type="password" name="password1"><br />
	            <label>Repeat password:</label><br />
	            <input class="forminput" type="password" name="password2">

				<div class="oopceeAdminButtonContainer">
					<input class="inputsubmit" type="submit" value="Update oopcee password" />
				</div>
			</form>
		</div>
	</div>
</div>
