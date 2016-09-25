<?PHP
/**
 * Created by NextPay.ir
 * author: Nextpay Company
 * ID: @nextpay
 * Date: 09/22/2016
 * Time: 5:05 PM
 * Website: NextPay.ir
 * Email: info@nextpay.ir
 * @copyright 2016
 * @package NextPay_Gateway
 * @version 1.0
 */

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
$dle_login_hash = "";
$allow_login = true;

if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "logout" ) {
	
	$dle_user_id = "";
	$dle_password = "";
	set_cookie( "dle_user_id", "", 0 );
	set_cookie( "dle_name", "", 0 );
	set_cookie( "dle_password", "", 0 );
	set_cookie( "dle_skin", "", 0 );
	set_cookie( "dle_newpm", "", 0 );
	set_cookie( "dle_hash", "", 0 );
	set_cookie( session_name(), "", 0 );
	@session_destroy();
	@session_unset();
	$is_logged = 0;
	
	header( "Location: ".str_replace("index.php","",$_SERVER['PHP_SELF']) );
	die();
}

$is_logged = 0;
$member_id = array ();

if( isset( $_POST['login'] ) AND $_POST['login_name'] AND $_POST['login_password'] AND $_POST['login'] == "submit" ) {

	



	$_POST['login_name'] = $db->safesql( $_POST['login_name'] );
	$_POST['login_password'] = @md5( $_POST['login_password'] );

	if ($config['login_log']) $allow_login = check_allow_login ($_IP, $config['login_log']);

	$allow_user = true;

	if ($config['auth_metod']) {

		if ( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\/|\\\|\&\~\*\+]/", $_POST['login_name']) ) $allow_user = false;
		$where_name = "email='{$_POST['login_name']}'";

	} else {

		if ( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_POST['login_name']) ) $allow_user = false;
		$where_name = "name='{$_POST['login_name']}'";

	}
	
	if( $allow_login AND $allow_user) {
	
		$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE {$where_name} AND password='" . md5( $_POST['login_password'] ) . "'" );
		
		if( $member_id['user_id'] ) {

			if ( intval($_POST['login_not_save']) ) {

				set_cookie( "dle_user_id", "", 0 );
				set_cookie( "dle_password", "", 0 );

			} else {			

				set_cookie( "dle_user_id", $member_id['user_id'], 365 );
				set_cookie( "dle_password", $_POST['login_password'], 365 );

			}
	
			$_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_password'] = $_POST['login_password'];
			$_SESSION['member_lasttime'] = $member_id['lastdate'];

			$dle_login_hash = md5( $_SERVER['HTTP_HOST'] . $member_id['user_id'] . sha1($_POST['login_password']) . $config['key'] . date( "Ymd" ) );
			
			if( $config['log_hash'] ) {
				
				$salt = "abchefghjkmnpqrstuvwxyz0123456789";
				$hash = '';
				srand( ( double ) microtime() * 1000000 );
				
				for($i = 0; $i < 9; $i ++) {
					$hash .= $salt{rand( 0, 33 )};
				}
				
				$hash = md5( $hash );
				
				$db->query( "UPDATE " . USERPREFIX . "_users set hash='" . $hash . "', lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='{$member_id['user_id']}'" );
				
				set_cookie( "dle_hash", $hash, 365 );
				
				$_COOKIE['dle_hash'] = $hash;
				$member_id['hash'] = $hash;
			
			} else
				$db->query( "UPDATE LOW_PRIORITY " . USERPREFIX . "_users set lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='{$member_id['user_id']}'" );

			$is_logged = TRUE;
		}
	}

} elseif( isset( $_SESSION['dle_user_id'] ) AND  intval( $_SESSION['dle_user_id'] ) > 0 AND $_SESSION['dle_password'] ) {
	
		$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE user_id='" . intval( $_SESSION['dle_user_id'] ) . "'" );
		
		if( $member_id['user_id'] AND $member_id['password'] AND $member_id['password'] == md5( $_SESSION['dle_password'] ) ) {
			
			$is_logged = TRUE;
			$dle_login_hash = md5( $_SERVER['HTTP_HOST'] . $member_id['user_id'] . sha1($_SESSION['dle_password']) . $config['key'] . date( "Ymd" ) );
		
		} else {
			
			$member_id = array ();
			$is_logged = false;
			if ($config['login_log']) $db->query( "INSERT INTO " . PREFIX . "_login_log (ip, count, date) VALUES('{$_IP}', '0', '".time()."') ON DUPLICATE KEY UPDATE count=count+1, date='".time()."'" );
		}

} elseif( isset( $_COOKIE['dle_user_id'] ) AND intval( $_COOKIE['dle_user_id'] ) > 0 AND $_COOKIE['dle_password']) {

	if ($config['login_log']) $allow_login = check_allow_login ($_IP, $config['login_log']);

	if ( $allow_login ) {
	
		$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE user_id='" . intval( $_COOKIE['dle_user_id'] ) . "'" );
		
		if( $member_id['user_id'] AND $member_id['password'] AND $member_id['password'] == md5( $_COOKIE['dle_password'] ) ) {
			
			$is_logged = TRUE;
			$dle_login_hash = md5( $_SERVER['HTTP_HOST'] . $member_id['user_id'] . sha1($_COOKIE['dle_password']) . $config['key'] . date( "Ymd" ) );
			
			$_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_password'] = $_COOKIE['dle_password'];
		
		} else {
			
			$member_id = array ();
			$is_logged = false;
			if ($config['login_log']) $db->query( "INSERT INTO " . PREFIX . "_login_log (ip, count, date) VALUES('{$_IP}', '0', '".time()."') ON DUPLICATE KEY UPDATE count=count+1, date='".time()."'" );
		
		}

		if( $config['log_hash'] and (($_COOKIE['dle_hash'] != $member_id['hash']) or ($member_id['hash'] == "")) ) {
			
			$member_id = array ();
			$is_logged = false;
		
		}

	}

}

if( isset( $_POST['login'] ) and !$is_logged AND $allow_login) {
	
	if ($config['login_log']) $db->query( "INSERT INTO " . PREFIX . "_login_log (ip, count, date) VALUES('{$_IP}', '0', '".time()."') ON DUPLICATE KEY UPDATE count=count+1, date='".time()."'" );
	
	if ($config['auth_metod']) msgbox( $lang['login_err'], $lang['login_err_3'] ); else msgbox( $lang['login_err'], $lang['login_err_1'] );
}

if ( !$allow_login ) {
	msgbox( $lang['login_err'], $lang['login_err_2'] );
}

if( $is_logged ) {
	
	###################################################################
	 $this_time = time() + ($config['date_adjust'] * 60);
	 

			 $alert = <<<HTML
<div id="anewpm" title="پایان اعتبار VIP" style="display:none;" ><br />
دوست عزیز <strong>$member_id[name]</strong> دوره اعتبار VIP شما به اتمام رسید.  
<br /><br />
شما به گروه قبل خود ( عضو سایت ) بازگشتید. 
<br /><br />
ارسال کننده :  مدیر سایت</div>
<script type="text/javascript">    
$(function(){

    $('#anewpm').dialog({
		autoOpen: true,
		show: 'fade',
		hide: 'fade',
		width: 450,
		height: 270,
		buttons: {
			
			"مشاهده نمودم": function() {
				$(this).dialog("close");		
			}
		}
	});
});
</script>
HTML;
//<?
			
	$setting_res = $db->super_query("SELECT * FROM ".PREFIX."_vip_setting where id = '1'");
	 if ( $member_id['vip_approve'] == 1 ) {
			
		if ( $member_id['viptime_plan'] >= $this_time ) {
			
	$ajax .= <<<HTML
	
		{$alert}	
HTML;
			
	$db->query( "UPDATE " . PREFIX . "_users set `viptime_plan`='0', `viptime_start`='0', vip_approve='0', user_group='4' where user_id='$member_id[user_id]' limit 1");
			
		} 
		
	 }
################################################## ///

	
	if( !$_SESSION['member_lasttime'] ) {
		
		$_SESSION['member_lasttime'] = $member_id['lastdate'];
		
		if( ($member_id['lastdate'] + (3600 * 4)) < $_TIME ) {
			
			$db->query( "UPDATE LOW_PRIORITY " . USERPREFIX . "_users SET lastdate='{$_TIME}' where user_id='$member_id[user_id]'" );
		
		}
	}
	
	if( ! allowed_ip( $member_id['allowed_ip'] ) ) {
		
		$is_logged = 0;
		
		msgbox( $lang['login_err'], $lang['ip_block_login'] );
	
	}
	
	if( $config['ip_control'] == '2' and ! check_netz( $member_id['logged_ip'], $_IP ) and ! isset( $_POST['login'] ) ) $is_logged = 0;
	elseif( $config['ip_control'] == '1' and $user_group[$member_id['user_group']]['allow_admin'] and ! check_netz( $member_id['logged_ip'], $_IP ) and ! isset( $_POST['login'] ) ) $is_logged = 0;

}

if( !$is_logged ) {
	
	$member_id = array ();
	set_cookie( "dle_user_id", "", 0 );
	set_cookie( "dle_password", "", 0 );
	set_cookie( "dle_hash", "", 0 );
	$_SESSION['dle_user_id'] = 0;
	$_SESSION['dle_password'] = "";

}
?>