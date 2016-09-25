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

if( ! defined('DATALIFEENGINE') ) {
	die( "Hacking attempt!" );
}

  $this_time = time() + ($config['date_adjust'] * 60);


/*//   پرداخت
####################################################################################################################
*/


	if ( $member_id['vip_approve'] == 0 ) {


  if ( $doaction == "ok") {

      $trans_id = $_POST['trans_id'];
      $order_id = $_POST['order_id'];

      //$price = $_GET['price'];
      $renextpay = $db->super_query("SELECT * FROM ".PREFIX."_vip_nextpay where `trans_id`='$trans_id'");
      $res_plan = $db->super_query("SELECT * FROM ".PREFIX."_vip_panel where `id`='{$renextpay['vip_panel']}'");
      @require_once ROOT_DIR . '/engine/classes/nusoap/nusoap.php';
      $API_KEY = $setting_res['api_key'];
      $time_end = strtotime("+6 month");
      $this_time = time() + ($config['date_adjust'] * 60);
      $price= intval($renextpay['price']);
      $date_nextpay = date('Y/m/d H:m');
      
      $view_endTIME = date('Y/m/d', $time_end);
      $setting_res = $db->super_query("SELECT * FROM ".PREFIX."_vip_setting where id ='1'");

	$client = new nusoap_client('http://api.nextpay.org/gateway/verify.wsdl', 'wsdl');
	$result = $client->call("PaymentVerification", array(
		array(
		      'api_key'	 => $API_KEY ,
		      'trans_id' 	 => $trans_id ,
		      'amount'	 => $price ,
		      'order_id' =>  $order_id
	      )));
	if ($renextpay['res'] == 1 )
	  $result = '-1';
	if ($result['PaymentVerificationResult']['code'] == 0 ){
			$prompt="فرايند بازگشت با موفقيت انجام شد";
	}else {
		$db->query( "UPDATE " . PREFIX . "_vip_nextpay set `res`='{$result['PaymentVerificationResult']['code']}' where userid='{$member_id['user_id']}' and `trans_id`='".$trans_id."'  limit 1");

		$prompt="فراید خرید با مشکل مواجه شده است";
	}




	  if ( $result['PaymentVerificationResult']['code'] == 0 ) {

		  $result_payment = "<div class=\"success\">
		  	پرداخت و عضویت VIP شما با موفقیت انجام گردید.
			<br>

			<table width=\"100%\">
				<tr>
				 	<td> کد پیگیری  : </td>
					<td> <strong> $renextpay[order_id] </strong> </td>
				</tr>
				<tr>
				 	<td> پلان انتخابی: </td>
					<td> $res_plan[name] </td>
				</tr>
				<tr>
				 	<td> تاریخ شروع عضویت: </td>
					<td> $date_nextpay </td>
				</tr>

				<tr>
				 	<td> تاریخ اتمام عضویت: </td>
					<td> $view_endTIME </td>
				</tr>


				<tr>
				 	<td> مبلغ واریزی :  </td>
					<td> $renextpay[price] </td>
				</tr>


			</table>

		  </div>";



  	$db->query( "UPDATE " . PREFIX . "_vip_nextpay set `trans_id`='".$trans_id."',`res`='{$result['PaymentVerificationResult']['code']}', `date`='".$date_nextpay."', `vip_time`='".$time_end."', `show`='1' where userid='{$member_id['user_id']}'  limit 1");

	$db->query( "UPDATE " . PREFIX . "_users set `viptime_plan`='".$time_end."', `viptime_start`='".$this_time."' where user_id='{$member_id['user_id']}' limit 1");

	$db->query( "UPDATE " . PREFIX . "_users set `user_group`='{$setting_res['group_id']}' where user_id='{$member_id['user_id']}' limit 1");



	  } else {
		$result_payment = "  <div class=\"success\">
			خطا در پرداخت :  &nbsp;&nbsp; 
			$prompt 
			$result[code]
			<br>
			لطفا مجددا تلاش نمایید.

		  </div>";

		  //$db->query( "DELETE FROM " . PREFIX . "_vip_nextpay WHERE userid='$member_id[user_id]' and au='$au' and res!='1' limit 1" );

	  }






	$tpl->set( '{result}', $result_payment);
	$tpl->load_template( 'vip_success.tpl' );
	$tpl->compile( 'content' );
	$tpl->clear();


/*//   پرداخت
####################################################################################################################
*/

  } elseif ( $doaction == "payment" ) {

	  	if ( empty( $_POST['vipradio'])) {
	  	
			msgbox("خطا !"," گزینه ای برای پرداخت انتخاب نشده است.");
		} else {
		$id = intval($_POST['vipradio']);
	  	$select_row = $db->super_query("SELECT * FROM ".PREFIX."_vip_panel where id = '$id' limit 1");
	  	$setting_res = $db->super_query("SELECT * FROM ".PREFIX."_vip_setting where id = '1'");

	  @require_once ROOT_DIR . '/engine/classes/nusoap/nusoap.php';
	  $GLOBALS["RedirectURL"] = "".$config['http_home_url']."index.php?do=vip_user&doaction=ok&price=". $select_row['price']."";
	  $API_KEY = $setting_res['api_key'];
	  $price = $select_row['price'];

	$client = new nusoap_client('http://api.nextpay.org/gateway/token.wsdl', 'wsdl');
	$order_id = uniqid("vip_");
	$res = $client->call('TokenGenerator', array(
			array(
					'api_key' 		=> $API_KEY ,
					'amount' 		=> intval($price) ,
					'order_id' 		=> $order_id ,
					'callback_uri' 		=> $GLOBALS["RedirectURL"]

					) ));
	//Redirect to URL You can do it also by creating a form
if(intval($res['TokenGeneratorResult']['code'])==-1){
$db->query( "INSERT INTO " . PREFIX . "_vip_nextpay set `userid`='".$member_id['user_id']."', `vip_panel`='".$id."', `trans_id` = '".$res['TokenGeneratorResult']['trans_id']."', `price`='".$price."', `order_id`= '".$order_id."' ,`show`='0' ,`res`='-1'");

	Header("Location: http://api.nextpay.org/gateway/payment/" . $res['TokenGeneratorResult']['trans_id']);





echo "
<script language=\"javascript\">
location.href = \"http://api.nextpay.org/gateway/payment/".$res['TokenGeneratorResult']['trans_id']."\";
</script>

	<h2> در حال اتصال به بانک</h2>


</center>


";
}else{
	echo 'ERR: '.$res['TokenGeneratorResult']['code'];
}



		}

	  


/*//   خروجی پلان ها
####################################################################################################################
*/

  } else {







    $query = $db->query("SELECT * FROM ".PREFIX."_vip_panel order by id desc");
	while ( $row = $db->get_row($query))  {
		$price = number_format($row['price']);
		$list_panel .= "<label for=\"da$row[id]\"><li><input type=\"radio\" id='da$row[id]' name=\"vipradio\" value=\"".$row['id']."\"> $row[name] &nbsp; $price تومان </li>";

	}

/*
	@$db->query("ALTER TABLE `dle_users` ADD `viptime_start` INT( 11 ) NOT NULL AFTER `news_num`,
	ADD `viptime_plan` INT( 11 ) NOT NULL AFTER `news_num`");

*/




	$ON_FORM = "<form method=\"post\" action=\"".$config['http_home_url']."index.php?do=vip_user&doaction=payment\" enctype=\"multipart/form-data\">";  /* شروع فرم */
	$END_FORM = "</form>"; /* اتمام فرم */


	$tpl->set( '{form start}', $ON_FORM);
	$tpl->set( '{end form}', $END_FORM);
	$tpl->set( '{لیست پنل‏ها}', $list_panel);
	$tpl->load_template( 'vip_user.tpl' );
	$tpl->compile( 'content' );
	$tpl->clear();
  }
  } else {
  msgbox("خطا", "شما عضو VIP بوده و قادر به پرداخت و عضويت VIP مجدد نميباشيد. در صورت هرگونه مشکل با مديريت تماس حاصل نماييد."
  );
  }

?>
