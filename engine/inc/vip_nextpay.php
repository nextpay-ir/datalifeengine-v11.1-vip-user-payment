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
if(!defined('DATALIFEENGINE'))
{
  die("An Error Occurred!");
}

if($member_id['user_group'] !=1){ msg("error", $lang['addnews_denied'], $lang['db_denied']); }
include_once ENGINE_DIR . '/inc/vip_function.php';


//add

if ( $action == "move" ) {

echoheader("Offer","Vip ویژه");

	echo $tabel_head;

 $id = intval($_GET['id']);


		 $groupquery = $db->query("SELECT * FROM ".PREFIX."_usergroups order by id desc");
	while ( $row = $db->get_row($groupquery))  {
			$option_group .= "
			<option value=\"".$row['id']."\" > $row[group_name] </option>
		";
	}


	echo<<<HTML
	<!--</script>-->
    <form method="post" style="margin:0; padding:0;" action="$PHP_SELF?mod=vip_nextpay&action=move_save" name="formname" enctype="multipart/form-data">

         <p >  گروهی را که می خواهید این کاربر به آن منتقل شود انتخاب نمایید.</p>

    	<P> <select style="padding:4px;" name="groupid"> <option value="0"> -- انتخاب نمایید -- </option> $option_group </select>  </P>

	<div style="margin-top: 10px;"> <input type="hidden" value="$id" name="uid" /><input type="submit" style="padding:3px;"  name="submit" value="انتقال "> <a href="javascript:history.go(-1)"> <em>بازگشت</em> </a></div>
	</form>



HTML;
//<?

	echo $end_table;
echofooter();


//del


} elseif ( $action == "move_save" ) {



	$id = intval($_POST['uid']);

	  	$db->query( "UPDATE " . PREFIX . "_users set `user_group`='".$_POST['groupid']."' where user_id='$id' limit 1");

	msg( "info", "ذخیره", "انتقال کاربر با موفقیت انجام گردید.", "javascript:history.go(-1)" );






//hohe

} else {
echoheader("Offer","Vip ویژه");
	echo $tabel_head;
	menu_header();

	$db->query("SELECT * FROM ".PREFIX."_vip_nextpay where `show`='1'");
	$page_name="?mod=vip_nextpay&order=page";
	$start=$_GET['start'];
	if(strlen($start) > 0 and !is_numeric($start)){
	exit("Data Error");}
	$eu = ($start - 0);
	$limit = 40;
	$this1 = $eu + $limit;
	$back = $eu - $limit;
	$next = $eu + $limit;
	$query2= $db->query("SELECT * FROM ".PREFIX."_vip_nextpay where `show`='1' order by id desc");
	echo mysql_error();
	$nume= $db->num_rows( $query2 );
	$query_res = $db->query("SELECT * FROM ".PREFIX."_vip_nextpay where `show`='1' order by id desc limit $eu, $limit");
	while ( $row = $db->get_row($query_res) ) {
			$id = $row['id'];
		$resuser = $db->super_query("SELECT * FROM ".PREFIX."_users where user_id='".$row['userid']."'");
		$resplanvip = $db->super_query("SELECT * FROM ".PREFIX."_vip_panel where id='".$row['vip_panel']."'");

		$list .="
		<tr>
		<td class=\"list\" style=\"padding:4px;\"> $row[id]</td>
		<td class=\"list\" style=\"padding:4px;\"> <a href=\"index.php?subaction=userinfo&user={$resuser['name']}\">{$resuser['name']} </a>  </td>
		<td class=\"list\" style=\"padding:4px;\"> {$resplanvip['name']}</td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['date']}</td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['price']}</td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['au']}</td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['date']}</td>
		<td class=\"list\" style=\"padding:4px;\"> <a href='$PHP_SELF?mod=vip_nextpay&action=move&id=$row[userid]' title='انتقال دستی به گروه'> <img src=\"engine/skins/move.png\" align='absmiddle'> </a> </td>
		</tr>
		";

	}
	if($nume > $limit ){ // Let us display bottom links if sufficient records are there for paging
	if($back >=0) {
	$tt1 .= "<a href='$page_name&start=$back'>قبلی </a>";
	}
	$i=0;
	$l=1;
	for($i=0;$i < $nume;$i=$i+$limit){
	if($i <> $eu){
	$tt1 .= " <a href='$page_name&start=$i'>$l</a> ";
	} else $tt1 .= "$l";
	$l += 1;
	}
	if($this1 < $nume) {
	$tt1 .= "<a href='$page_name&start=$next'>بعدی</a>";}
	}
	$db->free();


    $query = $db->query("SELECT * FROM ".PREFIX."_vip_nextpay where `show`='1' order by id desc");
	while ( $row = $db->get_row($query))  {
		$resuser = $db->super_query("SELECT * FROM ".PREFIX."_users where user_id='".$row['userid']."'");
		$resplanvip = $db->super_query("SELECT * FROM ".PREFIX."_vip_panel where id='".$row['vip_panel']."'");
	}


	echo <<<HTML
	<table width="100%">
    <tr>
    <td>

	<table width="100%" id="newslist">
	<tr class="thead">
    <th width=100>ID</th>
	<th width=400>&nbsp;نام کاربری&nbsp;</th>
	<th width=400>&nbsp; پلان VIP &nbsp;</th>
	<th width=400>&nbsp; تاریخ ثبت	 &nbsp;</th>
	<th width=400>&nbsp; هزیه واریزی  &nbsp;</th>
	<th width=400>&nbsp; رسید بانکی &nbsp;</th>
	<th width=400>&nbsp; تاریخ ثبت	 &nbsp;</th>

	<th width=300> عملیات</th>
	</tr>
	<tr class="tfoot"><th colspan="8"><div class="hr_line"></div></td></th>
	{$list}
	<tr class="tfoot"><th colspan="8"><div class="hr_line"></div></td></th>

	</tr>
	</table>

	</td>
	</tr>
	</table>
	<table>
	<tr>
	<td style=" background: #eee;"> </td>
	<td style=" background: #eee; padding-right: 15px; padding-left: 15px;">  صفحات : $tt1  </td>
	</tr>
	</table>



HTML;




//$db->query( "DELETE FROM " . PREFIX . "_vip_nextpay WHERE `show`='1'" );


	echo $end_table;
	echofooter();
}

?>
