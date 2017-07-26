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

define('TDMSPOT_DIRNAME', basename(dirname(__DIR__)));
define('TDMSPOT_URL', XOOPS_URL . '/modules/' . TDMSPOT_DIRNAME);
define('TDMSPOT_IMAGES_URL', TDMSPOT_URL . '/assets/images');
define('TDMSPOT_UPLOAD_URL', TDMSPOT_URL . '/upload');
define('TDMSPOT_CAT_URL', TDMSPOT_URL . '/upload/cat');
define('TDMSPOT_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMSPOT_DIRNAME);
define('TDMSPOT_UPLOAD_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMSPOT_DIRNAME . '/upload');
define('TDMSPOT_CAT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMSPOT_DIRNAME . '/upload/cat');

//define option du module
define('TDMSPOT_DISPLAY_CAT', $xoopsModuleConfig['tdmspot_cat_display']);

require_once TDMSPOT_ROOT_PATH . '/include/functions.php';
require_once TDMSPOT_ROOT_PATH . '/include/seo_functions.php';
require_once TDMSPOT_ROOT_PATH . '/include/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once TDMSPOT_ROOT_PATH . '/class/tree.php';
require_once TDMSPOT_ROOT_PATH . '/class/formselect.php';

$uploadFolders = array(
    TDMSPOT_UPLOAD_PATH,
    TDMSPOT_UPLOAD_PATH . '/photos',
    TDMSPOT_UPLOAD_PATH . '/photos/thumb',
    TDMSPOT_UPLOAD_PATH . '/photos/highlight',
    TDMSPOT_PATH . '/cache',
    TDMSPOT_PATH . '/cache/tmp'
);
