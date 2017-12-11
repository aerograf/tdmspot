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

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = tdmspot\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


$adminmenu[] = [
    'title' => _MI_TDMSPOT_INDEX,
    'link' => 'admin/index.php',
    'icon' => $pathIcon32 . '/home.png'
];

//$adminmenu[] = [
//    'title' => _MI_TDMSPOT_INDEX,
//    'link' => 'admin/main.php',
//    'icon' => $pathIcon32 . '/manage.png'
//];

$adminmenu[] = [
    'title' => _MI_TDMSPOT_CAT,
    'link' => 'admin/cat.php',
    'icon' => $pathIcon32 . '/category.png'
];
$adminmenu[] = [
    'title' => _MI_TDMSPOT_ITEM,
    'link' => 'admin/item.php',
    'icon' => $pathIcon32 . '/content.png'
];
$adminmenu[] = [
    'title' => _MI_TDMSPOT_PAGE,
    'link' => 'admin/page.php',
    'icon' => $pathIcon32 . '/index.png'
];
$adminmenu[] = [
    'title' => _MI_TDMSPOT_BLOCK,
    'link' => 'admin/block.php',
    'icon' => $pathIcon32 . '/block.png'
];
$adminmenu[] = [
    'title' => _MI_TDMSPOT_PLUGINS,
    'link' => 'admin/plug.php',
    'icon' => $pathIcon32 . '/add.png'
];
$adminmenu[] = [
    'title' => _MI_TDMSPOT_IMPORT,
    'link' => 'admin/import.php',
    'icon' => $pathIcon32 . '/compfile.png'
];

$adminmenu[] = [
    'title' => _MI_TDMSPOT_PERMISSIONS,
    'link' => 'admin/permissions.php',
    'icon' => $pathIcon32 . '/permissions.png'
];

$adminmenu[] = [
    'title' => _MI_TDMSPOT_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $pathIcon32 . '/about.png'
];
