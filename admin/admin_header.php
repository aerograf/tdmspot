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

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../../../class/xoopsformloader.php';
//require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/common.php';


$moduleDirName = basename(dirname(__DIR__));
$helper = \Xoopsmodules\tdmspot\Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

//$myts = \MyTextSanitizer::getInstance();
//
//if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
//    require_once $GLOBALS['xoops']->path('class/template.php');
//    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
//}

//$pathIcon16      = Xmf\Module\Admin::iconUrl('', 16);
//$pathIcon32      = Xmf\Module\Admin::iconUrl('', 32);
//$xoopsModuleAdminPath = $GLOBALS['xoops']->path('www/' . $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin'));
//require_once "{$xoopsModuleAdminPath}/moduleadmin.php";

//$myts = \MyTextSanitizer::getInstance();
//if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
//    require_once $GLOBALS['xoops']->path('class/template.php');
//    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
//}

//Module specific elements
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/Utility.php");
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/config.php");

//Handlers
//$XXXHandler = xoops_getModuleHandler('XXX', $moduleDirName);

//$GLOBALS['xoopsTpl']->assign('pathIcon16', $pathIcon16);
//$GLOBALS['xoopsTpl']->assign('pathIcon32', $pathIcon32);

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
//xoops_cp_header();

