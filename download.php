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
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/common.php';

$myts = MyTextSanitizer::getInstance();
$itemHandler = xoops_getModuleHandler('tdmspot_item', 'tdmspot');

//perm
$gpermHandler = xoops_getHandler('groupperm');
//permission
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
    $user_uid = 0;
}

if (!isset($_REQUEST['itemid'])) {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

if (!$gpermHandler->checkRight('spot_view', 256, $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

$document = $itemHandler->get($_REQUEST['itemid']);

if (!$document) {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

//on test l'existance du fichier
$imgpath = TDMSPOT_UPLOAD_PATH . '/' . $document->getVar('file');
if (file_exists($imgpath)) {
    $document_file = TDMSPOT_UPLOAD_URL . '/' . $document->getVar('file');
} else {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

//$dl = $document->getVar('file_dl');
//++$dl;
//$document->setVar('file_dl', $dl);
//$fileHandler->insert($document);

//header('content-disposition: attachment; filename='.$document_file.'');
//header("Pragma: no-cache");
//header("Expires: 0");
//readfile($document_file);
header('content-disposition: attachment; filename=' . $document_file . '');
//echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$document_file."\"></meta></head><body></body></html>";
