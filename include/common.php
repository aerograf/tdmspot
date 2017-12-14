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
 * @copyright     {@link https://xoops.org/ XOOPS Project}
 * @license       {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package       tdmspot
 * @since
 * @author        TDM   - TEAM DEV MODULE FOR XOOPS
 * @author        XOOPS Development Team
 */

use Xoopsmodules\tdmspot;
include __DIR__ . '/../preloads/autoloader.php';


$moduleDirName = basename(dirname(__DIR__));

require_once __DIR__ . '/../class/Helper.php';
require_once __DIR__ . '/../class/Utility.php';

$db     = \XoopsDatabaseFactory::getDatabase();
$helper = tdmspot\Helper::getInstance();

/** @var tdmspot\Utility $utility */
$utility = new tdmspot\Utility();

//defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

//$helper       = tdmspot\Helper::getInstance();
//$utility      = new tdmspot\Utility();

$catHandler   = new \Xoopsmodules\tdmspot\CategoryHandler($db);
$itemHandler  = new \Xoopsmodules\tdmspot\ItemHandler($db);
$blockHandler = new \Xoopsmodules\tdmspot\NewblocksHandler($db);
$pageHandler  = new \Xoopsmodules\tdmspot\PageHandler($db);
$voteHandler  = new \Xoopsmodules\tdmspot\VoteHandler($db);

if (!defined('TDMSPOT_DIRNAME')) {
    define('TDMSPOT_DIRNAME', basename(dirname(__DIR__)));
    define('TDMSPOT_URL', XOOPS_URL . '/modules/' . TDMSPOT_DIRNAME);
    define('TDMSPOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMSPOT_DIRNAME);
    define('TDMSPOT_IMAGES_URL', TDMSPOT_URL . '/assets/images');
    define('TDMSPOT_ADMIN_URL', TDMSPOT_URL . '/admin');
    define('TDMSPOT_ADMIN_PATH', TDMSPOT_PATH . '/admin/index.php');
    define('TDMSPOT_ROOT_PATH', $GLOBALS['xoops']->path('modules/' . TDMSPOT_DIRNAME));
    define('TDMSPOT_AUTHOR_LOGOIMG', TDMSPOT_URL . '/assets/images/logo.png');
    define('TDMSPOT_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . TDMSPOT_DIRNAME); // WITHOUT Trailing slash
    define('TDMSPOT_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . TDMSPOT_DIRNAME); // WITHOUT Trailing slash
    define('TDMSPOT_CAT_IMAGE_URL', XOOPS_UPLOAD_URL . '/' .  TDMSPOT_DIRNAME . '/images/category');
    define('TDMSPOT_CAT_IMAGE_PATH', XOOPS_UPLOAD_PATH . '/' .  TDMSPOT_DIRNAME . '/images/category');
}

//define option du module
define('TDMSPOT_DISPLAY_CAT', $helper->getConfig('tdmspot_cat_display', 'none'));

require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/seo_functions.php';
require_once __DIR__ . '/../class/PageNav.php';

require_once XOOPS_ROOT_PATH . '/class/tree.php';

require_once __DIR__ . '/../class/Tree.php';
require_once __DIR__ . '/../class/FormSelect.php';

//$uploadFolders = [
//    TDMSPOT_UPLOAD_PATH,
//    TDMSPOT_UPLOAD_PATH . '/photos',
//    TDMSPOT_UPLOAD_PATH . '/photos/thumb',
//    TDMSPOT_UPLOAD_PATH . '/photos/highlight',
//    TDMSPOT_PATH . '/cache',
//    TDMSPOT_PATH . '/cache/tmp'
//];

$helper->loadLanguage('common');

$debug = false;

// Load only if module is installed
//if (is_object($helper->getModule())) {
//    // Find if the user is admin of the module
//    $publisherIsAdmin = publisher\Utility::userIsAdmin();
//    // get current page
//    $publisherCurrentPage = publisher\Utility::getCurrentPage();
//}

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "0.png' alt='" . 0 . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "1.png' alt='" . 1 . "' align='middle'>",
];

//$debug = false;

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

// Local icons path
$GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
$GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
