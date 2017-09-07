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

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/common.php';

$myts = MyTextSanitizer::getInstance();
$gpermHandler = xoops_getHandler('groupperm');
//permission
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

//load class
$itemHandler = xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'submit';

global $xoopsUser, $xoopsModule, $xoopsModuleConfig;

switch ($op) {

    case 'submit':

        //perm
        if (!$gpermHandler->checkRight('tdmspot_view', 4, $groups, $xoopsModule->getVar('mid')) && !$gpermHandler->checkRight('tdmspot_view', 8, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header(TDMSPOT_URL, 2, _MD_TDMSPOT_NOPERM);
        } else {
            // Affichage du formulaire de cr?ation de cat?gories
            $obj = $itemHandler->create();
            $form = $obj->getForm();
            $form->display();
        }
        break;

    case 'save':

        //perm
        if (!$gpermHandler->checkRight('tdmspot_view', 4, $groups, $xoopsModule->getVar('mid')) && !$gpermHandler->checkRight('tdmspot_view', 8, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header(TDMSPOT_URL, 2, _MD_TDMSPOT_NOPERM);
        } else {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            $obj = $itemHandler->create();

            //upload
            require_once XOOPS_ROOT_PATH . '/class/uploader.php';
            //cree le chemin

            $uploaddir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/upload/';
            $mimetype = explode('|', $xoopsModuleConfig['tdmspot_mimetype']);
            $uploader = new XoopsMediaUploader($uploaddir, $mimetype, $xoopsModuleConfig['tdmspot_mimemax'], null, null);

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
                redirect_header(TDMSPOT_URL, 2, _MD_TDMSPOT_BASEOK);
            }
            //require_once('../include/forms.php');
            echo $obj->getHtmlErrors();
            $form =& $obj->getForm();
            $form->display();
        }
        break;

}

require_once __DIR__ . '/../../footer.php';
