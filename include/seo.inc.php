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

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$seoOp = @$_GET['seoOp'];
$seoArg = @$_GET['seoArg'];
$seoStart = @$_GET['seoStart'];
$seoLimit = @$_GET['seoLimit'];
$seoTris = @$_GET['seoTris'];

$moduleHandler = xoops_getHandler('module');
$xoopsModule = $moduleHandler->getByDirname('tdmspot');

if (!isset($xoopsModuleConfig)) {
    $configHandler = xoops_getHandler('config');
    $xoopsModuleConfig = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
}

if (empty($seoOp) && @$_SERVER['PATH_INFO']) {

    // SEO mode is path-info
    /*
    Sample URL for path-info
    http://localhost/modules/publisher/seo.php/item.2/can-i-turn-the-ads-off.html
    */
    $data = explode('/', $_SERVER['PATH_INFO']);

    $seoParts = explode('.', $data[1]);
    $seoOp = $seoParts[0];
    $seoArg = $seoParts[1];
    $seoStart = $seoParts[2];
    $seoLimit = $seoParts[3];
    $seoTris = $seoParts[4];
    // for multi-argument modules, where itemid and catid both are required.
    // $seoArg = substr($data[1], strlen($seoOp) + 1);
}

$seoMap = array(
    $xoopsModuleConfig['tdmspot_seo_cat'] => 'viewcat.php',
    $xoopsModuleConfig['tdmspot_seo_item'] => 'item.php',
    'print' => 'print.php',
    'pdf' => 'pdf.php',
    'submit' => 'submit.php',
    'rss' => 'rss.php',
    'download' => 'download.php',
    'index' => 'index.php'
);

if (!empty($seoOp) && isset($seoMap[$seoOp])) {
    // module specific dispatching logic, other module must implement as
    // per their requirements.

    $path = pathinfo($_SERVER['REQUEST_URI']);
    $url_arr = explode('/modules/', $_SERVER['PHP_SELF']);

    if (@preg_match('comment/i', $path['filename'])) {
        $newUrl = $url_arr[0] . '/modules/' . TDMSPOT_DIRNAME . '/' . $path['filename'];
        //echo $data[5];
        $seoMap[$seoOp] = $path['filename'] . '.php';
    } else {
        $newUrl = $url_arr[0] . '/modules/' . TDMSPOT_DIRNAME . '/' . $seoMap[$seoOp];
    }
    $_ENV['PHP_SELF'] = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    $_SERVER['PHP_SELF'] = $newUrl;
    switch ($seoOp) {
        case  $xoopsModuleConfig['tdmspot_seo_cat']:
            $_SERVER['REQUEST_URI'] = $newUrl . '?LT=' . $seoArg . '&start=' . $seoStart . '&limit=' . $seoLimit . '&tris=' . $seoTris;
            $_REQUEST['LT'] = $seoArg;
            $_REQUEST['start'] = $seoStart;
            $_REQUEST['limit'] = $seoLimit;
            $_REQUEST['tris'] = $seoTris;
            break;
        case  $xoopsModuleConfig['tdmspot_seo_item']:
        case 'print':
        case 'pdf':
        case 'submit':
        case 'rss':
        case 'download':
        case 'index':
        default:
            $_SERVER['REQUEST_URI'] = $newUrl . '?itemid=' . $seoArg;
            $_GET['itemid'] = $seoArg;
            $_REQUEST['itemid'] = $seoArg;
    }
    require_once XOOPS_ROOT_PATH . '/modules/' . TDMSPOT_DIRNAME . '/' . $seoMap[$seoOp];
    exit;
}
