<?php
/*
nextpay
*/

error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_NOTICE);

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname (__FILE__));
define('ENGINE_DIR', ROOT_DIR.'/engine');

$config['charset'] = "utf8";

include ('engine/api/api.class.php');
require_once(ENGINE_DIR.'/inc/include/functions.inc.php');
require_once(ENGINE_DIR.'/skins/default.skin.php');

extract($_REQUEST, EXTR_SKIP);

echoheader("","");

if($_REQUEST['action']=="setup"){


define ("PREFIX", $dbprefix);
define ("COLLATE", $dbcollate);

$tableSchema = array();



$tableSchema[] = "
CREATE TABLE IF NOT EXISTS `" . PREFIX . "_vip_gorup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `plantime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

";
$tableSchema[] = "
INSERT INTO `" . PREFIX . "_vip_gorup` (`id`, `name`, `plantime`) VALUES
(1, '3 ماهه', 3),
(2, '6 ماهه', 6),
(3, '1 ساله', 1),
(4, '2 ساله', 2);

";
$tableSchema[] = "CREATE TABLE IF NOT EXISTS `" . PREFIX . "_vip_panel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `plantme` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$tableSchema[] = "CREATE TABLE IF NOT EXISTS `" . PREFIX . "_vip_nextpay` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `vip_panel` int(11) NOT NULL,
  `res` varchar(90) NOT NULL,
  `trans_id` varchar(90) NOT NULL,
  `order_id` varchar(90) NOT NULL,
  `price` varchar(90) NOT NULL,
  `date` varchar(155) NULL,
  `vip_time` varchar(90) NULL,
  `show` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$tableSchema[] = " CREATE TABLE IF NOT EXISTS `" . PREFIX . "_vip_setting` (
  `id` int(11) NOT NULL,
  `api_key` varchar(60) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$tableSchema[] = "INSERT INTO `" . PREFIX . "_vip_setting` (`id`, `api_key`, `group_id`) VALUES
(1, '0000000-000000-000000-000000', 0);
";

	@$db->query("ALTER TABLE `dle_users` ADD `viptime_start` INT( 11 ) NOT NULL AFTER `news_num`,
	ADD `viptime_plan` INT( 11 ) NOT NULL AFTER `news_num`,
	ADD `vip_approve` INT( 11 ) NOT NULL AFTER `news_num`");



  foreach($tableSchema as $table) {

        $db->query($table);

      }

echo <<<HTML
<form method=POST action="$PHP_SELF">
<div style="padding-top:5px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">پايان نصب</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;"><b>
		عملیت نصب افزونه با موفقیت به پایان رسید. /

		<br />

		» <a href="index.php?do=vip_user"> نمایش ماژول کاربران ویژه </a>
		</b>

        </td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
}else{
echo <<<HTML

	<style>
	.dstn_td td {
		 border:1px solid #ccc;
	}
	</style>
<form method=POST action="$PHP_SELF">
<div style="padding-top:5px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"> &nbsp; VIP نصب افزونه </div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;">

			<div style="line-height: 18px; padding: 5px; color: #505050;">

	<span style="color: #cc0000">	نصب نسخه 1 افزونه VIP  			/</span>


				مواردی که باید قبل از نصب انجام دهید :

				<table border="0" width="50%" class="dstn_td" style="margin-top: 5px; letter-spacing: 2px; text-align: center; padding: 3px; direction: ltr;">
				  <tbody style="text-align: center; background: #eee;">
					<tr>
					 <td> سطح دسترسی </td>
					 <td> مسیر شاخه و فایل </td>
					</tr>
				  </tbody>
					<tr>
					 <td> جهت نصب نیازی به تغییر دسترسی نمی باشد. </td>

					</tr>
			</table>

			</div>
		</td>
    </tr>
    <tr>
        <td style="padding:2px;"><input type=hidden name=action value="setup"><input style="font: 8pt tahoma; padding: 3px;" type=submit value="شروع نصب »"></td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
}
echofooter();

?>
