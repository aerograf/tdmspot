<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package       tdmspot
 * @since
 * @author       TDM   - TEAM DEV MODULE FOR XOOPS
 * @author       XOOPS Development Team
 */

use Xoopsmodules\tdmspot;

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';

global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

if (!isset($_REQUEST['itemid'])) {
    redirect_header('index.php', 2, _MD_TDMSPOT_NOPERM);
}

switch ($option) {

    case 'default':
    default:
        //load class
    $itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');    
        //perm
        $gpermHandler = xoops_getHandler('groupperm');

        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $user_uid = $xoopsUser->getVar('uid');
            $user_name = $xoopsUser->getVar('name');
            $user_uname = $xoopsUser->getVar('uname');
            $user_email = $xoopsUser->getVar('email');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $user_uid = 0;
            $user_name = XOOPS_GROUP_ANONYMOUS;
            $user_uname = XOOPS_GROUP_ANONYMOUS;
            $user_email = XOOPS_GROUP_ANONYMOUS;
        }

        //si pas le droit d'exporter
    if(!$permHelper->checkPermission('spot_view', 16)) {
            redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
        }

        $file = $itemHandler->get($_REQUEST['itemid']);
        //prepare les reponse
        $newsletter_title = $file->getVar('title');
        //text
        $body = str_replace('{X_BREAK}', '<br><br>', $file->getVar('text'));
        $body = str_replace('{X_NAME}', $user_name, $body);
        $body = str_replace('{X_UNAME}', $user_uname, $body);
        $body = str_replace('{X_UEMAIL}', $user_email, $body);
        $body = str_replace('{X_ADMINMAIL}', $xoopsConfig['adminmail'], $body);
        $body = str_replace('{X_SITENAME}', $xoopsConfig['sitename'], $body);
        $body = str_replace('{X_SITEURL}', XOOPS_URL, $body);

        $newsletter_text = $body;
        $newsletter_indate = formatTimestamp($file->getVar('indate'), 'm');
        $color = '#CCCCCC';

        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<title>' . $xoopsConfig['sitename'] . "</title>\n";
        echo '<meta http-equiv="Content-Type" content="text/html;  charset=utf-8">';
        echo '<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '">';
        echo "<meta name='COPYRIGHT' content='Copyright (c) " . date('Y') . ' by ' . $xoopsConfig['sitename'] . "'>\n";
        echo "<meta name='DESCRIPTION' content='" . $xoopsConfig['slogan'] . "'>\n";
        echo "<meta name='GENERATOR' content='" . XOOPS_VERSION . "'>\n\n\n";
        echo "<body bgcolor='#ffffff' text='#000000' onload='window.print()'>
    <div style='width: 750px; border: 1px solid #000; padding: 20px;'>
           <div style='text-align: center; display: block; margin: 0 0 6px 0;'>
         <h3>" . $xoopsConfig['sitename'] . '</h3><h5>' . $xoopsConfig['slogan'] . '</h5>
          <br>
          ';

        echo '<h4 style=\'margin: 0;\'>' . $newsletter_title . '</h4>
    <div align=\'center\'><small><b>' . $newsletter_indate . '</b></small></div><br><br>
    <div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
        <div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
            <div style=\'text-align: left\'><tr valign="top" style="font:12px;"><td>' . $newsletter_text . '</div><br>
                    <div style=\'text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0;\'></div>
    <br>
    <br><a href="' . XOOPS_URL . '/">' . XOOPS_URL . '</a><br>
        </div></div>
        </body>
        </html>';
        break;
}

/**
 * @param $text
 * @return mixed
 */
function Chars($text)
{
    $myts = \MyTextSanitizer::getInstance();

    return preg_replace(['/&#039;/i', '/&#233;/i', '/&#232;/i', '/&#224;/i', '/&quot;/i', '/<br \>/i', '/&agrave;/i', '/&#8364;/i'], ["'", '�', '�', '�', '"', "\n", '�', '�'], $text);
}
