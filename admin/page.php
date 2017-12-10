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

$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$blockHandler = new tdmspot\NewblocksHandler(); //xoops_getModuleHandler('tdmspot_newblocks', 'tdmspot');

$myts = \MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'weight';


require_once TDMSPOT_ROOT_PATH . '/class/SystemBreadcrumb.php';

switch ($op) {

    //sauv
    case 'save':

        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pages.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id'])) {
            $obj = $pageHandler->get($_REQUEST['id']);
        } else {
            $obj = $pageHandler->create();
        }

        $obj->setVar('title', $_REQUEST['title']);
        $obj->setVar('weight', $_REQUEST['weight']);
        $obj->setVar('visible', $_REQUEST['visible']);
        $var_cat = implode(',', $_REQUEST['cat']);
        $obj->setVar('cat', $var_cat);
        $obj->setVar('limit', $_REQUEST['limit']);

        if ($pageHandler->insert($obj)) {

            //perm
            $id = $obj->getVar('id');
            $gpermHandler = xoops_getHandler('groupperm');
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('gperm_itemid', $id, '='));
            $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
            $criteria->add(new Criteria('gperm_name', 'spot_pageview', '='));
            $gpermHandler->deleteAll($criteria);

            if (isset($_POST['groups_view'])) {
                foreach ($_POST['groups_view'] as $onegroup_id) {
                    $gpermHandler->addRight('spot_pageview', $id, $onegroup_id, $xoopsModule->getVar('mid'));
                }
            }
            //

            redirect_header('page.php', 2, _AM_TDMSPOT_BASEOK);
        }
        //require_once('../include/forms.php');
        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit':
        xoops_cp_header();

        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //Adminmenu(3, _AM_TDMSPOT_MANAGE_PAGE);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (3, _AM_TDMSPOT_MANAGE_PAGE);
        //}

        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/page.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_PAGE . '</strong></h3>';
        $currentFile = basename(__FILE__);
      $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        echo '</div><br>';
        $obj = $pageHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;

        break;

    case 'delete':
        $obj = $pageHandler->get($_REQUEST['id']);

        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('page.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            //supprime le genre
            if ($pageHandler->delete($obj)) {
                redirect_header('page.php', 2, _AM_TDMSPOT_BASEOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_cp_header();
            xoops_confirm(['ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMSPOT_BASESURE));
        }
        break;

    case _DELETE:

        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('page.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            $_POST['id'] = unserialize($_REQUEST['id']);
            $size = count($_POST['id']);
            $obj = $_POST['id'];
            for ($i = 0; $i < $size; ++$i) {
                $obj2 = $pageHandler->get($obj[$i]);
                //supprime
                if ($pageHandler->delete($obj2)) {
                    $erreur = true;
                } else {
                    echo $obj->getHtmlErrors();
                }
            }

            if (isset($erreur)) {
                redirect_header('page.php', 2, _AM_TDMSPOT_BASEOK);
            } else {
                echo $obj2->getHtmlErrors();
            }
        } else {
            xoops_cp_header();
            $title = print_r($_REQUEST['id'], true);
            xoops_confirm(
                ['ok' => 1, 'deletes' => 1, 'op' => $_REQUEST['op'], 'id' => serialize(array_map('intval', $_REQUEST['id']))],
                $_SERVER['REQUEST_URI'],
                sprintf(_AM_TDMSPOT_BASESUREDEL, $title)
            );
        }
        break;

    case 'list':
    default:
        xoops_cp_header();

        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //Adminmenu(3, _AM_TDMSPOT_MANAGE_PAGE);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (3, _AM_TDMSPOT_MANAGE_PAGE);
        //}

        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/page.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_PAGE . '</strong></h3>';

        $currentFile = basename(__FILE__);
      $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        echo '</div><br>';

        $xoBreadCrumb = new tdmspot\SystemBreadcrumb();
        $xoBreadCrumb->addTips(_AM_TDMSPOT_PAGEDESC);
        $xoBreadCrumb->render();
        //$numgenre = $pageHandler->getCount();
        //if ($numgenre == 0) {
        //redirect_header('page.php', 2, _AM_TDMSPOT_PAGEERROR);
        //}

        //Parameters
        $criteria = new \CriteriaCompo();
        $limit = 20;
        if (isset($_REQUEST['start'])) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
            $start = 0;
        }

        $criteria->setLimit($limit);
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $alb_arr = $pageHandler->getObjects($criteria);
        $numrows = $pageHandler->getCount();

        //nav
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list');
            $pagenav = $pagenav->renderNav(2);
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des catégories
        if ($numrows > 0) {
            echo '<form name="form" id="form" action="item.php" method="post"><table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center" width="5%"><input name="allbox" id="allbox" onclick="xoopsCheckAll(\'form\', \'allbox\');" type="checkbox" value="Check All"></th>';
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_VISIBLE, 'visible', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_WEIGHT, 'weight', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect('ID', 'id', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="45%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_TITLE, 'title', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMSPOT_ACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($alb_arr) as $i) {
                $class = ('even' === $class) ? 'odd' : 'even';
                $id = $alb_arr[$i]->getVar('id');

                $title = $myts->displayTarea($alb_arr[$i]->getVar('title'));

                $display = 1 == $alb_arr[$i]->getVar('visible') ? "<img src='./../assets/images/on.gif' border='0'>" : "<img src='./../assets/images/off.gif' border='0'>";

                echo '<tr class="' . $class . '">';
                echo '<td align="center"><input type="checkbox" name="id[]" id="id[]" value="' . $id . '"></td>';
                echo '<td align="center">' . $display . '</td>';
                echo '<td align="center">' . $alb_arr[$i]->getVar('weight') . '</td>';
                echo '<td align="center">' . $id . '</td>';
                echo '<td align="center">' . $title . '</td>';
                echo '<td align="center">';
                echo '<a href="page.php?op=edit&id=' . $id . '"><img src="./../assets/images/edit_mini.gif" border="0" alt="' . _AM_TDMSPOT_EDITER . '" title="' . _AM_TDMSPOT_EDITER . '"></a>';
                echo '<a href="page.php?op=delete&id=' . $id . '"><img src="./../assets/images/delete_mini.gif" border="0" alt="' . _AM_TDMSPOT_DELETE . '" title="' . _AM_TDMSPOT_DELETE . '"></a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table><input type="submit" name="op" value="' . _DELETE . '"></form><br><br>';
            echo '<div align=right>' . $pagenav . '</div><br>';
        }
        // Affichage du formulaire de cr?ation de cat?gories
        $obj = $pageHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

}
require_once __DIR__ . '/admin_footer.php';
