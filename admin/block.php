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
require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$blockHandler = new tdmspot\NewblocksHandler(); //xoops_getModuleHandler('tdmspot_newblocks', 'tdmspot');

//verifie la presence des pages
$numgenre = $pageHandler->getCount();
if (0 == $numgenre) {
    redirect_header('page.php', 2, _AM_TDMSPOT_PAGEERROR);
}

$myts = \MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'weight';

switch ($op) {

    //sauv
    case 'save':

        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('block.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id'])) {
            $obj = $blockHandler->get($_REQUEST['id']);
        } else {
            $obj = $blockHandler->create();
        }

        if (isset($_REQUEST['options'])) {
            $options_count = count($_REQUEST['options']);
            if ($options_count > 0) {
                //Convert array values to comma-separated
                for ($i = 0; $i < $options_count; ++$i) {
                    if (is_array($_REQUEST['options'][$i])) {
                        $options[$i] = implode(',', $_REQUEST['options'][$i]);
                    }
                }
                $options = implode('|', $_REQUEST['options']);
                $obj->setVar('options', $options);
            }
        }

        $obj->setVar('bid', $_REQUEST['bid']);
        $obj->setVar('pid', $_REQUEST['pid']);
        $obj->setVar('title', $_REQUEST['title']);
        $obj->setVar('side', $_REQUEST['side']);
        $obj->setVar('weight', $_REQUEST['weight']);
        $obj->setVar('visible', $_REQUEST['visible']);

        if ($blockHandler->insert($obj)) {
            redirect_header('block.php', 2, _AM_TDMSPOT_BASEOK);
        }
        //require_once('../include/forms.php');
        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit':
        xoops_cp_header();

        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //Adminmenu(4, _AM_TDMSPOT_MANAGE_BLOCK);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (4, _AM_TDMSPOT_MANAGE_BLOCK);
        //}
        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/block.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_BLOCK . '</strong></h3>';
        //        echo '</div><br>';
        $obj = $blockHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;

        break;

    case 'delete':
        $obj = $blockHandler->get($_REQUEST['id']);

        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('block.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            //supprime le genre
            if ($blockHandler->delete($obj)) {
                redirect_header('block.php', 2, _AM_TDMSPOT_BASEOK);
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
                redirect_header('block.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            $_POST['id'] = unserialize($_REQUEST['id']);
            $size = count($_POST['id']);
            $obj = $_POST['id'];
            for ($i = 0; $i < $size; ++$i) {
                $obj2 = $blockHandler->get($obj[$i]);
                //supprime
                if ($blockHandler->delete($obj2)) {
                    $erreur = true;
                } else {
                    echo $obj->getHtmlErrors();
                }
            }

            if (isset($erreur)) {
                redirect_header('block.php', 2, _AM_TDMSPOT_BASEOK);
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

        $currentFile = basename(__FILE__);
      $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //Adminmenu(4, _AM_TDMSPOT_MANAGE_BLOCK);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (4, _AM_TDMSPOT_MANAGE_BLOCK);
        //}
        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/block.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_BLOCK . '</strong></h3>';
        //        echo '</div><br>';

        //Parameters
        $criteria = new CriteriaCompo();
        $limit = 10;
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
        $alb_arr = $blockHandler->getObjects($criteria);
        $numrows = $blockHandler->getCount();

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
            echo $GLOBALS['xoopsSecurity']->getTokenHTML(); //mb
            echo '<tr>';
            echo '<th align="center" width="5%"><input name="allbox" id="allbox" onclick="xoopsCheckAll(\'form\', \'allbox\');" type="checkbox" value="Check All"></th>';
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_VISIBLE, 'visible', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_WEIGHT, 'weight', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="25%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_PAGE, 'pid', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="30%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_TITLE, 'title', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMSPOT_ACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($alb_arr) as $i) {
                //nom de page
                $page_title = false;
                if ($page = $pageHandler->get($alb_arr[$i]->getVar('pid'))) {
                    $page_title = $page->getVar('title');
                }

                //trouve le block
                $block_arr = new \XoopsBlock($alb_arr[$i]->getVar('bid'));
                $title_block = $block_arr->getVar('name');

                $class = ('even' === $class) ? 'odd' : 'even';
                $id = $alb_arr[$i]->getVar('id');
                $title = $myts->displayTarea($alb_arr[$i]->getVar('title'));

                $display = 1 == $alb_arr[$i]->getVar('visible') ? $icons['1'] : "<border='0'>". $icons['0'];

                echo '<tr class="' . $class . '">';
                echo '<td align="center"><input type="checkbox" name="id[]" id="id[]" value="' . $id . '"></td>';
                echo '<td align="center" width="10%">' . $display . '</td>';
                echo '<td align="center" width="10%">' . $alb_arr[$i]->getVar('weight') . '</td>';
                echo '<td align="center" width="30%">' . $alb_arr[$i]->getVar('pid') . ' - ' . $page_title . '</td>';
                echo '<td align="center" width="30%">' . $title_block . ' - ' . $title . '</td>';
                echo '<td align="center" width="20%">';
                echo '<a href="block.php?op=edit&id=' . $id . '"><border="0" alt="' . _AM_TDMSPOT_EDITER . '" title="' . _AM_TDMSPOT_EDITER . '">'. $icons['edit'] .'</a>';
                echo '<a href="block.php?op=delete&id=' . $id . '"><border="0" alt="' . _AM_TDMSPOT_DELETE . '" title="' . _AM_TDMSPOT_DELETE . '">'. $icons['delete'] .'</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table><input type="submit" name="op" value="' . _DELETE . '"></form><br><br>';
            echo '<div align=right>' . $pagenav . '</div><br>';
        }
        // Affichage du formulaire de cr?ation de cat?gories
        $obj = $blockHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

}
require_once __DIR__ . '/admin_footer.php';
