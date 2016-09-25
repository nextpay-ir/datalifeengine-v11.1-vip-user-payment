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


	echo <<<HTML

	<style>
	input,textarea,btn,select {
		font: 8pt tahoma;
		padding: 2px;
	}

</style>
HTML;


function menu_header() {
	echo <<<HTML
 <style type="text/css">
   ul,li {
	   margin:0;
	   padding:0;
   }
#navc li{list-style:circle; color:#7fa626; margin-right:17px; float: right; margin-left: 15px;}
#navc:hover{color:#6e9418}
table.amar td{
	padding:5px;
}
table.amar tr {
	border-bottom:1px dotted #ccc;

}
	</style>
	<div style="background: #fff; padding: 3px; width: 98%; background: #f7f7f7; border-bottom:2px solid #eddc8c; margin-right: 10px; float: right; overflow: hidden; -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;">
	<ul id="navc">
	<li><a href="$PHP_SELF?mod=vip_panel"> داشبورد</a> </li>
	<li><a href="$PHP_SELF?mod=vip_panel&action=add">افزودن پنل</a> </li>
	<li><a href="$PHP_SELF?mod=vip_nextpay"> اعضای VIP </a> </li>
	<li><a href="$PHP_SELF?mod=vip_panel&action=setting">تنظیمات</a> </li>
	</ul>

</div><div style="clear: both"></div><br>
HTML;
};


$tabel_head ="<div style=\"padding-top:5px;padding-bottom:2px;\">
	<table width=\"100%\">
	<tr>
	<td width=\"4\"><img src=\"engine/skins/images/tl_lo.gif\" width=\"4\" height=\"4\" border=\"0\"></td>
	<td background=\"engine/skins/images/tl_oo.gif\"><img src=\"engine/skins/images/tl_oo.gif\" width=\"1\" height=\"4\" border=\"0\"></td>
	<td width=\"6\"><img src=\"engine/skins/images/tl_ro.gif\" width=\"6\" height=\"4\" border=\"0\"></td>
	</tr>
	<tr>
	<td background=\"engine/skins/images/tl_lb.gif\"><img src=\"engine/skins/images/tl_lb.gif\" width=\"4\" height=\"1\" border=\"0\"></td>
	<td style=\"padding:5px;\" bgcolor=\"#FFFFFF\">
	<table width=\"100%\">
	<tr>
	<td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-right:10px;\"><div class=\"navigation\"><a href='$PHP_SELF?mod=vip_panel'> داشبورد  </a></div></td>
	</tr>
	</table>
	<div class=\"unterline\"></div>
";
$end_table = "	</td>
	<td background=\"engine/skins/images/tl_rb.gif\"><img src=\"engine/skins/images/tl_rb.gif\" width=\"6\" height=\"1\" border=\"0\"></td>
	</tr>
	<tr>
	<td><img src=\"engine/skins/images/tl_lu.gif\" width=\"4\" height=\"6\" border=\"0\"></td>
	<td background=\"engine/skins/images/tl_ub.gif\"><img src=\"engine/skins/images/tl_ub.gif\" width=\"1\" height=\"6\" border=\"0\"></td>
	<td><img src=\"engine/skins/images/tl_ru.gif\" width=\"6\" height=\"6\" border=\"0\"></td>
	</tr>
	</table>
	</div>
	";
?>
