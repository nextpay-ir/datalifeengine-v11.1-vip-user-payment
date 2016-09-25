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

if ( $action == "add" ) {

echoheader("Offer","Vip ویژه");

	echo $tabel_head;

 $query_plantime = $db->query("SELECT * FROM ".PREFIX."_vip_gorup order by id asc");
	while ( $row = $db->get_row($query_plantime))  {

		$option_plnt .= "
			<option value=\"".$row['id']."\"> $row[name] </option>
		";

	}


	echo<<<HTML
	<!--</script>-->
    <form method="post" style="margin:0; padding:0;" action="$PHP_SELF?mod=vip_panel&action=add_save" name="formname" enctype="multipart/form-data">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
        	<td width="60" height="25"> نام پنل :</td>
            <td><input type="text" class="edit bk" name="name" size="22" /> </td>
        </tr>
		<tr>
        	<td height="25"> قیمت پنل :</td>
            <td><input type="text" class="edit bk" name="price" size="22" /> <span style="color: #cc0000;"> (به تومان) </span></td>
        </tr>

		<tr>
        	<td height="25"> انتخاب پلان :</td>
            <td> <select name="plantime"> $option_plnt</select> </td>
        </tr>

	</table>



	<div style="margin-top: 10px;"> <input type="submit"  name="submit" value="افزودن"> </div>
	</form>



HTML;
//<?

	echo $end_table;
echofooter();

//save




} elseif ( $action == "setting" ) {

//setting

echoheader("Offer","Vip ویژه");

	echo $tabel_head;
$setting_res = $db->super_query("SELECT * FROM ".PREFIX."_vip_setting where id = '1'");

	 $groupquery = $db->query("SELECT * FROM ".PREFIX."_usergroups order by id desc");
	while ( $row = $db->get_row($groupquery))  {
		if ( $setting_res['group_id'] == $row['id'] ) {
		$option_group .= "
			<option value=\"".$row['id']."\" selected=\"selected\"> $row[group_name] </option>
		";
		} else {
			$option_group .= "
			<option value=\"".$row['id']."\" > $row[group_name] </option>
		";
		}

	}


	echo<<<HTML


<form method="post" style="margin:0; padding:0;" action="$PHP_SELF?mod=vip_panel&action=setting_save" name="formname" enctype="multipart/form-data">

	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
        	<td width="130" height="25"> کد پذیرنده /  Marchent id :</td>
            <td><input type="text" class="edit bk" value="$setting_res[api_key]" dir=ltr name="api_key" size="22" /> </td>
        </tr>


		<tr>
        	<td width="130" height="25"> انتقال کاربران  :</td>
            <td><select name="group"> <option value="$setting_res[group_id]"> -- انتخاب نمایید -- </option> $option_group  </select> <span class=red> ( گروه کاربری که می خواهید پس از عضویت ویژه کاربر به آن انتقال پیدا نماید مشخص نمایید.) </span> </td>
        </tr>


	</table>



	<div style="margin-top: 10px;"> <input type="submit"  name="submit" value="ذخیره تنظیمات"> </div>
	</form>






HTML;
	echo $end_table;
echofooter();



} elseif ( $action == "setting_save" ) {



	$db->query( "UPDATE " . PREFIX . "_vip_setting set `api_key`='".$_POST['api_key']."' where id = '1'");
	$db->query( "UPDATE " . PREFIX . "_vip_setting set `group_id`='".$_POST['group']."' where id = '1'");
	msg( "error", "ذخیره", "<strong>تنظیمات</strong> با موفقیت ذخیره گردید.", "javascript:history.go(-1)" );


} elseif ( $action == "add_save" ) {

	if ( $_POST['submit'] ) {
		if( empty( $_POST['name'] )) {
			alert( "error", "خطا", "فیلد نام نمی تواند خالی باشد.", "javascript:history.go(-1)" );
		}

		$db->query( "INSERT INTO " . PREFIX . "_vip_panel set `name`='".$_POST['name']."', `price`='".$_POST['price']."', `plantme`='".$_POST['plantime']."'");


	printf("Records deleted: %d\n", mysql_affected_rows());


	msg( "error", "ذخیره", "<strong>{$_POST['name']}</strong> با موفقیت ذخیره گردید.", "javascript:history.go(-2)" );


	}


//edit


	}elseif ( $action == "edit" ) {
	echoheader("Offer","Vip ویژه");

		$request_id = intval($_REQUEST['id']);

		$edit_row = $db->super_query( "SELECT * FROM ".PREFIX."_vip_panel where id = '$request_id'");
	echo $tabel_head;
	menu_header();

 $query_plantime = $db->query("SELECT * FROM ".PREFIX."_vip_gorup order by id asc");
	while ( $row = $db->get_row($query_plantime))  {
		if ( $row['id'] == $edit_row['plantme'] ){

		$option_plnt .= "
			<option value=\"".$row['plantime']."\" selected=\"selected\"> $row[name] </option>
		";
		} else {
		$option_plnt .= "
			<option value=\"".$row['plantime']."\"> $row[name] </option>
		";
		}

	}

	echo<<<HTML


  <form method="post" style="margin:0; padding:0;" action="$PHP_SELF?mod=vip_panel&action=edit_save" enctype="multipart/form-data">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
        	<td width="60" height="25">عنوان :</td>
            <td><input type="text" class="edit bk" name="name" size="22" value="$edit_row[name]" /> </td>
        </tr>

        	<tr>
        	<td height="25">قیمت :</td>
            <td><input type="text" class="edit bk" name="price" size="22" dir="ltr" value="$edit_row[price]" /> </td>
        </tr>

<tr>
        	<td height="25"> انتخاب پلان :</td>
            <td> <select name="plantime"> $option_plnt</select> </td>
        </tr>
	</table>



	<div style="margin-top: 10px;"><input type="hidden" name="id" value="$request_id">  <input type="submit" name="submit" value="ذخیره"> </div>
	</form>



HTML;


	echo $end_table;
	echofooter();

//edit_save



	}elseif ( $action == "edit_save" ) {


	$id = intval($_POST['id']);


		$db->query( "UPDATE " . PREFIX . "_vip_panel set `name`='".$_POST['name']."', `price`='".$_POST['price']."', `plantme`='".$_POST['plantime']."' where id = '$id'");



	msg( "error", "ذخیره", "<strong>{$_POST['name']}</strong> با موفقیت ذخیره گردید.", "javascript:history.go(-1)" );


//delete


} elseif ( $action == "delete" ) {



	$id = intval($_GET['id']);

	$db->query( "DELETE FROM " . PREFIX . "_vip_panel WHERE id='$id' limit 1" );

	msg( "info", "ذخیره", "مورد انتخاب گردیده با موفقیت حذف گردید.", "javascript:history.go(-1)" );






//home

} else {
echoheader("Offer","Vip ویژه");
	echo $tabel_head;
	menu_header();




    $query = $db->query("SELECT * FROM ".PREFIX."_vip_panel order by id desc");
	while ( $row = $db->get_row($query))  {

			$id = $row['id'];

		$list .="
		<tr>
		<td class=\"list\" style=\"padding:4px;\"> $row[id]</td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['name']}  </td>
		<td class=\"list\" style=\"padding:4px;\"> {$row['price']}</td>
		<td class=\"list\" style=\"padding:4px;\"> <a href='$PHP_SELF?mod=vip_panel&action=edit&id=$id' title='ویرایش'> <img src='engine/skins/images/edit_go.png' align='absmiddle'> </a> &nbsp;&nbsp; <a href='$PHP_SELF?mod=vip_panel&action=delete&id=$id' title='حذف'> <img src='engine/skins/images/remove.png' align='absmiddle'> </a></td>
		</tr>
		";
	}


	echo <<<HTML
	<table width="100%">
    <tr>
    <td>

	<table width="100%" id="newslist">
	<tr class="thead">
    <th width=100>ID</th>
	<th width=400>&nbsp;نام پنل&nbsp;</th>
	<th width=70%>&nbsp;قیمت&nbsp;</th>

	<th width=300> عملیات</th>
	</tr>
	<tr class="tfoot"><th colspan="4"><div class="hr_line"></div></td></th>
	{$list}
	<tr class="tfoot"><th colspan="4"><div class="hr_line"></div></td></th>

	</tr>
	</table>

	</td>
	</tr>
	</table>
	<table>
	<tr>
	<td style=" background: #eee;"> </td>
	<td style=" background: #eee; padding-right: 15px; padding-left: 15px;">  {$result_query_page}  </td>
	</tr>
	</table>



HTML;




 //$db->query( "DELETE FROM " . PREFIX . "_vip_nextpay WHERE `show`='1'" );


	echo $end_table;
	echofooter();
}

?>
