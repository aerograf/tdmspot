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

$moduleDirName = basename(dirname(__DIR__));

$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname($moduleDirName);
$pathIcon32 = '../../' . $module->getInfo('icons32');
xoops_loadLanguage('modinfo', $module->dirname());

$xoopsModuleAdminPath = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin');
if (!file_exists($fileinc = $xoopsModuleAdminPath . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $xoopsModuleAdminPath . '/language/english/main.php';
}
require_once $fileinc;

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link' => 'admin/index.php',
    'icon' => $pathIcon32 . '/home.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMSPOT_INDEX,
    'link' => 'admin/main.php',
    'icon' => $pathIcon32 . '/manage.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMSPOT_CAT,
    'link' => 'admin/cat.php',
    'icon' => $pathIcon32 . '/category.png'
);
$adminmenu[] = array(
    'title' => _MI_TDMSPOT_ITEM,
    'link' => 'admin/item.php',
    'icon' => $pathIcon32 . '/content.png'
);
$adminmenu[] = array(
    'title' => _MI_TDMSPOT_PAGE,
    'link' => 'admin/page.php',
    'icon' => $pathIcon32 . '/index.png'
);
$adminmenu[] = array(
    'title' => _MI_TDMSPOT_BLOCK,
    'link' => 'admin/block.php',
    'icon' => $pathIcon32 . '/block.png'
);
$adminmenu[] = array(
    'title' => _MI_TDMSPOT_PLUGINS,
    'link' => 'admin/plug.php',
    'icon' => $pathIcon32 . '/add.png'
);
$adminmenu[] = array(
    'title' => _MI_TDMSPOT_IMPORT,
    'link' => 'admin/import.php',
    'icon' => $pathIcon32 . '/compfile.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMSPOT_PERMISSIONS,
    'link' => 'admin/permissions.php',
    'icon' => $pathIcon32 . '/permissions.png'
);

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $pathIcon32 . '/about.png'
);
