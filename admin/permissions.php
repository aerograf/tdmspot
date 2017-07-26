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

require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once __DIR__ . '/../include/functions.php';

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
//require_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/mygrouppermform.php';

if (!empty($_POST['submit'])) {
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/permissions.php', 1, _AM_XD_GPERMUPDATED);
}

xoops_cp_header();
//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//Adminmenu(5, _AM_TDMSPOT_MANAGE_PERM);
//} else {
//require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//loadModuleAdminMenu (5, _AM_TDMSPOT_MANAGE_PERM);
//}

//menu
//echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/permissions.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>' . _AM_TDMSPOT_MANAGE_PERM . '</strong></h3>';

$currentFile = basename(__FILE__);
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation($currentFile);

echo '</div><br>';

$module_id = $xoopsModule->getVar('mid');

$perm_name = 'spot_view';
$perm_desc = _AM_TDMSPOT_MANAGE_PERM;

$global_perms_array = array(
    '2' => _AM_TDMSPOT_PERM_2,
    '4' => _AM_TDMSPOT_PERM_4,
    '8' => _AM_TDMSPOT_PERM_8,
    '16' => _AM_TDMSPOT_PERM_16,
    '32' => _AM_TDMSPOT_PERM_32,
    '64' => _AM_TDMSPOT_PERM_64,
    '128' => _AM_TDMSPOT_PERM_128,
    '256' => _AM_TDMSPOT_PERM_256
);

$permform = new XoopsGroupPermForm('', $module_id, $perm_name, '');

foreach ($global_perms_array as $perm_id => $perm_name) {
    $permform->addItem($perm_id, $perm_name);
}

echo $permform->render();

xoops_cp_footer();
