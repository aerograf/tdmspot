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

require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/common.php';
require_once __DIR__ . '/../include/config.php';
require_once TDMSPOT_ROOT_PATH . '/class/SystemBreadcrumb.php';

$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

switch ($op) {

    //sauv
    case 'news':
        global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

        $error = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmspot_item') . "
  (id, cat, title, text, display, file, indate, hits, votes, counts, comments, poster)
    SELECT
    storyid, topicid, title, CONCAT(hometext, '{X_BREAK}' , bodytext) , " . $_REQUEST['display'] . " , '',
    published, counter, votes, rating, comments, uid
    FROM " . $xoopsDB->prefix('stories'));

        $error .= $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmspot_cat') . '
  ( id, pid, title, date, text, img, weight, display)
    SELECT
    topic_id, topic_pid, topic_title, ' . time() . ' , topic_description, topic_imgurl,
    0, ' . $_REQUEST['display'] . '
    FROM ' . $xoopsDB->prefix('topics'));

        if ($error) {
            redirect_header('import.php', 2, _AM_TDMSPOT_BASEOK);
        } else {
            redirect_header('import.php', 2, _AM_TDMSPOT_BASEERROR);
        }

        break;

    case 'smartsection':
        global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

        $error = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmspot_item') . "
  (id, cat, title, text, display, file, indate, hits, votes, counts, comments, poster)
    SELECT
      itemid, categoryid, title, CONCAT(summary, '{X_BREAK}' , body) , " . $_REQUEST['display'] . " , '',
    datesub, counter, '', '', comments, uid
    FROM " . $xoopsDB->prefix('smartsection_items'));

        $error .= $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmspot_cat') . '
  ( id, pid, title, date, text, img, weight, display)
    SELECT
    categoryid, parentid, name, ' . time() . ' , description, image,
    weight, ' . $_REQUEST['display'] . '
    FROM ' . $xoopsDB->prefix('smartsection_categories'));

        if ($error) {
            redirect_header('import.php', 2, _AM_TDMSPOT_BASEOK);
        } else {
            redirect_header('import.php', 2, _AM_TDMSPOT_BASEERROR);
        }

        break;

    case 'list':
    default:
        xoops_cp_header();
        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //TDMSound_adminmenu(20, _AM_TDMSPOT_MANAGE_IMPORT);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (20, _AM_TDMSPOT_MANAGE_IMPORT);
        //}

        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/import.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_IMPORT . '</strong></h3>';
        //        echo '</div><br>';

        $currentFile = basename(__FILE__);
      $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        $xoBreadCrumb = new tdmspot\SystemBreadcrumb();
        $xoBreadCrumb->addTips(_AM_TDMSPOT_IMPORTDESC);
        $xoBreadCrumb->render();

        $sq1 = 'SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . "` LIKE '" . $xoopsDB->prefix('stories') . "'";
        $result1 = $xoopsDB->queryF($sq1);
        $news = $xoopsDB->fetchArray($result1);

        $sq1 = 'SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . "` LIKE '" . $xoopsDB->prefix('topics') . "'";
        $result1 = $xoopsDB->queryF($sq1);
        $news_cat = $xoopsDB->fetchArray($result1);

        echo '<fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_IMPORT_NEWS . '</legend>

        <br>';
        if ($news > 0) {
            echo '<b><span style="color: green; padding-left: 20px;"><img src="./../assets/images/on.gif" > ' . $news['Name'] . ' : ' . tdmspot\Utility::getPrettySize($news['Data_length'] + $news['Index_length']) . ' | ' . $news_cat['Name'] . ' : ' . tdmspot\Utility::getPrettySize($news_cat['Data_length'] + $news_cat['Index_length']) . '</span></b> | <b><a href="import.php?op=news&display=1">' . _AM_TDMSPOT_IMPORT_INDISPLAY . '</a></b> - <b><a href="import.php?op=news&display=0">' . _AM_TDMSPOT_IMPORT_OUTDISPLAY . '</a></b>';
        } else {
            echo '<b><span style="color: red; padding-left: 20px;"><img src="./../assets/images/off.gif"> ' . _AM_TDMSPOT_IMPORT_NONE . '</a></span></b>';
        }
        echo '<br><br>
    </fieldset><br>';

        $sq1 = 'SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . "` LIKE '" . $xoopsDB->prefix('smartsection_items') . "'";
        $result1 = $xoopsDB->queryF($sq1);
        $smart = $xoopsDB->fetchArray($result1);

        $sq1 = 'SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . "` LIKE '" . $xoopsDB->prefix('smartsection_categories') . "'";
        $result1 = $xoopsDB->queryF($sq1);
        $smart_cat = $xoopsDB->fetchArray($result1);

        echo '<fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_IMPORT_SMARTSECTION . '</legend>
        <br>';
        if ($smart > 0) {
            echo '<b><span style="color: green; padding-left: 20px;"><img src="./../assets/images/on.gif" > ' . tdmspot\Utility::getPrettySize($smart['Data_length'] + $smart['Index_length']) . ' | ' . $smart_cat['Name'] . ' : ' . tdmspot\Utility::getPrettySize($smart_cat['Data_length'] + $smart_cat['Index_length']) . '</span></b> | <b><a href="import.php?op=smartsection&display=1">' . _AM_TDMSPOT_IMPORT_INDISPLAY . '</a></b> - <b><a href="import.php?op=smartsection&display=0">' . _AM_TDMSPOT_IMPORT_OUTDISPLAY . '</a></b>';
        } else {
            echo '<b><span style="color: red; padding-left: 20px;"><img src="./../assets/images/off.gif"> ' . _AM_TDMSPOT_IMPORT_NONE . '</a></span></b>';
        }
        echo '<br><br>
    </fieldset><br>';

        //$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("wfs_article")."'";
        //$result1=$xoopsDB->queryF($sq1);
        //$wf=$xoopsDB->fetchArray($result1);

        // echo '<fieldset><legend class="CPmediumTitle">'._AM_TDMSPOT_IMPORT_WFSECTION.'</legend>
        //      <br>';
        //      if ($wf > 0) {
        //      echo '<b><span style="color: green; padding-left: 20px;"><img src="./../assets/images/on.gif" > ' .  tdmspot\Utility::getPrettySize($wf['Data_length'] + $wf['Index_length']) . '</span></b> | <b><a href="index.php?op=wfsection">'._AM_TDMSPOT_IMPORT.'</a></b>';
        //      } else {
        //      echo '<b><span style="color: red; padding-left: 20px;"><img src="./../assets/images/off.gif"> '. _AM_TDMSPOT_IMPORT_NONE .'</a></span></b>';
        //      }
        //      echo '<br><br>
        //  </fieldset><br>';

        //$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("xfs_article")."'";
        //$result1=$xoopsDB->queryF($sq1);
        //$xf=$xoopsDB->fetchArray($result1);

        // echo '<fieldset><legend class="CPmediumTitle">'._AM_TDMSPOT_IMPORT_XFSECTION.'</legend>
        //      <br>';
        //      if ($xf > 0) {
        //      echo '<b><span style="color: green; padding-left: 20px;"><img src="./../assets/images/on.gif" > ' .  tdmspot\Utility::getPrettySize($xf['Data_length'] + $xf['Index_length']) . '</span></b> | <b><a href="index.php?op=xfsection">'._AM_TDMSPOT_IMPORT.'</a></b>';
        //      } else {
        //      echo '<b><span style="color: red; padding-left: 20px;"><img src="./../assets/images/off.gif"> '. _AM_TDMSPOT_IMPORT_NONE .'</a></span></b>';
        //      }
        //      echo '<br><br>
        //  </fieldset><br>';

        break;
}
require_once __DIR__ . '/admin_footer.php';
