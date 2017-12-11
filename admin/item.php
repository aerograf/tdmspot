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

$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');

//verifie la presence des categorie
$numcat = $catHandler->getCount();
if (0 == $numcat) {
    redirect_header('cat.php', 2, _AM_TDMSPOT_CATERROR);
}

$myts = \MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$display = isset($_REQUEST['display']) ? $_REQUEST['display'] : 1;
$indate = isset($_REQUEST['indate']) ? $_REQUEST['indate'] : 0;
$cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : 0;
$itemid = isset($_REQUEST['itemid']) ? $_REQUEST['itemid'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'indate';

//compte les item
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('display', 1));
$numitem = $itemHandler->getCount($criteria);
//compte les item en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('display', 0));
$item_waiting = $itemHandler->getCount($criteria);
//compte les item en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('indate', time(), '>'));
$item_time = $itemHandler->getCount($criteria);

switch ($op) {

    //sauv
    case 'save':

        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id'])) {
            $obj = $itemHandler->get($_REQUEST['id']);
        } else {
            $obj = $itemHandler->create();
        }

        //upload
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        //cree le chemin

        $uploaddir = TDMSPOT_UPLOAD_PATH . '/images/';
        $mimetype = explode('|', $xoopsModuleConfig['tdmspot_mimetype']);
        $uploader = new \XoopsMediaUploader($uploaddir, $mimetype, $xoopsModuleConfig['tdmspot_mimemax'], null, null);

        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('file', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('file', $_REQUEST['file']);
        }
        $obj->setVar('title', $_REQUEST['title']);
        $obj->setVar('cat', $_REQUEST['cat']);
        $obj->setVar('text', $_REQUEST['text']);
        $obj->setVar('display', $_REQUEST['display']);
        $obj->setVar('indate', strtotime($_REQUEST['indate']['date']) + (int)$_REQUEST['indate']['time']);
        $obj->setVar('poster', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

        if ($itemHandler->insert($obj)) {
            redirect_header('item.php', 2, _AM_TDMSPOT_BASEOK);
        }
        //require_once('../include/forms.php');
        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit':
        xoops_cp_header();
        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //TDMSound_adminmenu(2, _AM_TDMSPOT_MANAGE_ITEM);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (2, _AM_TDMSPOT_MANAGE_ITEM);
        //}

        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/item.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_ITEM . '</strong></h3>';

        $currentFile = basename(__FILE__);
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        echo '</div><br><div class="head" align="center">';
        echo (0 != $display) ? '<a href="item.php?op=list&display=0">' . sprintf(
            _AM_TDMSPOT_THEREARE_ITEM_WAITING,
                $item_waiting
        ) . '</a> | ' : '<a href="item.php?op=list">' . sprintf(_AM_TDMSPOT_THEREARE_ITEM, $numitem) . '</a> | ';
        echo (0 != $indate) ? '<a href="item.php?op=list">' . sprintf(
            _AM_TDMSPOT_THEREARE_ITEM,
                $numitem
        ) . '</a>' : '<a href="item.php?op=list&indate=' . time() . '">' . sprintf(_AM_TDMSPOT_THEREARE_ITEM_TIME, $item_time) . '</a>';
        echo '</div><br>';
        $obj = $itemHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;

        break;

    case 'delete':
        $obj = $itemHandler->get($_REQUEST['id']);

        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('item.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $uploaddir = TDMSPOT_UPLOAD_PATH . '/images/';

            //supprime l'album
            if ($itemHandler->delete($obj)) {
                @unlink($uploaddir . $obj->getVar('file'));
                redirect_header('item.php', 2, _AM_TDMSPOT_BASEOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_cp_header();
            xoops_confirm(['ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMSPOT_BASESURE, $obj->getVar('title')));
        }
        break;

    case _DELETE:

        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('item.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            $_POST['id'] = unserialize($_REQUEST['id']);
            $size = count($_POST['id']);
            $obj = $_POST['id'];
            for ($i = 0; $i < $size; ++$i) {
                $obj2 = $itemHandler->get($obj[$i]);
                //supprime
                if ($itemHandler->delete($obj2)) {
                    $erreur = true;
                } else {
                    echo $obj->getHtmlErrors();
                }
            }

            if (isset($erreur)) {
                redirect_header('item.php', 2, _AM_TDMSPOT_BASEOK);
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

    case 'update':
        $obj = $itemHandler->get($_REQUEST['id']);
        $obj->setVar('display', 1);
        if ($itemHandler->insert($obj)) {
            redirect_header('item.php', 2, _AM_TDMSPOT_BASEOK);
        }
        break;

    case 'list':
    default:
        xoops_cp_header();
        //if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
        //TDMSound_adminmenu(2, _AM_TDMSPOT_MANAGE_ITEM);
        //} else {
        //require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
        //loadModuleAdminMenu (2, _AM_TDMSPOT_MANAGE_ITEM);
        //}

        //menu
        //        echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/item.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_ITEM . '</strong></h3>';
        $currentFile = basename(__FILE__);
      $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);
        echo '</div><br><div class="head" align="center">';
        echo (0 != $display) ? '<a href="item.php?op=list&display=0">' . sprintf(
            _AM_TDMSPOT_THEREARE_ITEM_WAITING,
                $item_waiting
        ) . '</a> | ' : '<a href="item.php?op=list">' . sprintf(_AM_TDMSPOT_THEREARE_ITEM, $numitem) . '</a> | ';
        echo (0 != $indate) ? '<a href="item.php?op=list">' . sprintf(
            _AM_TDMSPOT_THEREARE_ITEM,
                $numitem
        ) . '</a>' : '<a href="item.php?op=list&indate=' . time() . '">' . sprintf(_AM_TDMSPOT_THEREARE_ITEM_TIME, $item_time) . '</a>';
        echo '</div><br>';

        //creation du formulaire de tris
        $form = new \XoopsThemeForm(_AM_TDMSPOT_SEARCH, 'tris', 'item.php');

        $form->addElement(new \XoopsFormHidden('op', 'list'));
        $form->addElement(new \XoopsFormHidden('display', $display));
        $form->addElement(new \XoopsFormHidden('indate', $indate));

        $cat_select = new \XoopsFormSelect(_AM_TDMSPOT_CATEGORY, 'cat', $cat);
        $cat_select->addOption(0, _AM_TDMSPOT_ALL);
        $cat_select->addOptionArray($catHandler->getList());
        $form->addElement($cat_select);

        $form->addElement(new \XoopsFormText(_AM_TDMSPOT_ID, 'itemid', 8, 8, $itemid));

        $button_tray = new \XoopsFormElementTray(_AM_TDMSPOT_ACTION, '');
        $button_tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->addElement($button_tray);

        $form->display();

        echo '<br>';

        $uploaddir = TDMSPOT_UPLOAD_PATH . '/images/';

        //Parameters
        $criteria = new CriteriaCompo();
        $limit = 20;
        if (isset($_REQUEST['start'])) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
            $start = 0;
        }

        if (isset($display)) {
            $criteria->add(new Criteria('display', $display));
        }

        if (isset($_REQUEST['cat']) && 0 != $_REQUEST['cat']) {
            $criteria->add(new Criteria('cat', $_REQUEST['cat']));
        }

        if (isset($_REQUEST['itemid']) && 0 != $_REQUEST['itemid']) {
            $criteria->add(new Criteria('id', $_REQUEST['itemid']));
        }

        if (isset($_REQUEST['indate']) && 0 != $_REQUEST['indate']) {
            $criteria->add(new Criteria('indate', $_REQUEST['indate'], '>'));
        }

        $criteria->setLimit($limit);
        $criteria->setSort($sort);
        $criteria->setOrder($order);

        $alb_arr = $itemHandler->getObjects($criteria);
        $numrows = $itemHandler->getCount($criteria);

        //nav

        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&itemid=' . $itemid . '&cat=' . $cat . '&display=' . $display . '&indate=' . $indate);
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
            echo '<th align="center" width="10%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_VISIBLE, 'display', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="20%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_INDATE, 'indate', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="20%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_CATEGORY, 'cat', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="35%">' . tdmspot\Utility::switchSelect(_AM_TDMSPOT_TITLE, 'title', TDMSPOT_IMAGES_URL) . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMSPOT_ACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($alb_arr) as $i) {
                $class = ('even' === $class) ? 'odd' : 'even';
                $id = $alb_arr[$i]->getVar('id');
                $title = $myts->displayTarea($alb_arr[$i]->getVar('title'));
                $indate = formatTimestamp($alb_arr[$i]->getVar('indate'), 'm');

                //trouve la categorie
                $cat_title = 'NONE';
                if ($cat = $catHandler->get($alb_arr[$i]->getVar('cat'))) {
                    $cat_title = $cat->getVar('title');
                }
                //
                if (1 == $alb_arr[$i]->getVar('display') && $alb_arr[$i]->getVar('indate') < time()) {
                    $display =  $icons['1'];
                } else {
                    $display = "<a href='item.php?op=update&id=" . $id . "'><img alt='" . _AM_TDMSPOT_UPDATE . "' title='" . _AM_TDMSPOT_UPDATE . " border='0'>". $icons['0'] . '</a>';
                }



                //on test l'existance de l'image
                $imgpath = TDMSPOT_UPLOAD_PATH . '/images/' . $alb_arr[$i]->getVar('file');
                if (file_exists($imgpath)) {
                    $file =TDMSPOT_UPLOAD_URL . '/images/' . $alb_arr[$i]->getVar('file');
                } else {
                    $file = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/images/blank.png';
                }

                echo '<tr class="' . $class . '">';
                echo '<td align="center"><input type="checkbox" name="id[]" id="id[]" value="' . $id . '"></td>';
                echo '<td align="center">' . $display . '</td>';
                echo '<td align="center">' . $indate . '</td>';
                echo '<td align="center">' . $cat_title . '</td>';
                echo '<td align="center">' . $title . '</td>';
                echo '<td align="center">';
                echo '<a href="item.php?op=edit&id=' . $id . '"><border="0" alt="' . _AM_TDMSPOT_EDITER . '" title="' . _AM_TDMSPOT_EDITER . '">'. $icons['edit'] .'</a>';
                echo '<a href="item.php?op=delete&id=' . $id . '"><border="0" alt="' . _AM_TDMSPOT_DELETE . '" title="' . _AM_TDMSPOT_DELETE . '">'. $icons['delete'] .'</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table><input type="submit" name="op" value="' . _DELETE . '"></form><br><br>';
            echo '<div align=right>' . $pagenav . '</div><br>';
        }
        // Affichage du formulaire de cr?ation de cat?gories
        $obj = $itemHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

}
require_once __DIR__ . '/admin_footer.php';
