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
require_once __DIR__ . '/../class/Utility.php';

xoops_cp_header();
//apelle du menu admin
//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//Adminmenu(0, _AM_TDMSPOT_MANAGE_INDEX);
//} else {
//require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//loadModuleAdminMenu (0, _AM_TDMSPOT_MANAGE_INDEX);
//}

//load class
$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$blockHandler = new tdmspot\NewblocksHandler(); //xoops_getModuleHandler('tdmspot_newblocks', 'tdmspot');

//compte les items
$numitem = $itemHandler->getCount();
//compte les items en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('display', 0));
$item_waiting = $itemHandler->getCount($criteria);
//compte les item en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('indate', time()), '>');
$item_time = $itemHandler->getCount($criteria);
//compte les categorie
$numcat = $catHandler->getCount();
//compte les categorie en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('display', 0));
$cat_waiting = $catHandler->getCount($criteria);
//compte les pages
$numpage = $pageHandler->getCount();
//compte les blocks
$numblock = $blockHandler->getCount();

if (!in_array('mod_rewrite', @apache_get_modules())) {
    $veriffile = '<span style="color: red;"><img src="./../assets/images/off.gif">mod_rewrite ERROR</a></span>';
} else {
    $veriffile = '<span style="color: green;"><img src="./../assets/images/on.gif" >mod_rewrite OK</span>';
}

if (PHP_VERSION >= 5) {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/SoundMenu.php';

    //showIndex();
    $menu = new tdmspot\SoundMenu();
    $menu->addItem('item', 'item.php', '../assets/images/decos/index.png', _AM_TDMSPOT_MANAGE_ITEM);
    $menu->addItem('cat', 'cat.php', '../assets/images/decos/cat.png', _AM_TDMSPOT_MANAGE_CAT);
    $menu->addItem('page', 'page.php', '../assets/images/decos/page.png', _AM_TDMSPOT_MANAGE_PAGE);
    $menu->addItem('artiste', 'block.php', '../assets/images/decos/block.png', _AM_TDMSPOT_MANAGE_BLOCK);
    $menu->addItem('about', 'about.php', '../assets/images/decos/about.png', _AM_TDMSPOT_MANAGE_ABOUT);
    $menu->addItem('update', '../../system/admin.php?fct=modulesadmin&op=update&module=' . $xoopsModule->getVar('name'), '../assets/images/decos/update.png', _AM_TDMSPOT_MANAGE_UPDATE);
    $menu->addItem('import', 'import.php', '../assets/images/decos/import.png', _AM_TDMSPOT_MANAGE_IMPORT);
    $menu->addItem('permissions', 'permissions.php', '../assets/images/decos/permissions.png', _AM_TDMSPOT_MANAGE_PERM);

    echo $menu->getCSS();
}
echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/index.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;">
        <h3><strong>' . _AM_TDMSPOT_MANAGE_INDEX . '</strong></h3>
    </div><br><table width="100%" border="0" cellspacing="10" cellpadding="4">
  <tr>';
if (PHP_VERSION >= 5) {
    echo '<td valign="top">
  ' . $menu->render() . '</td>';
} else {
    echo '<div class="errorMsg" style="text-align: left;">no menu</div>';
}
echo '<td valign="top" width="60%">

   <fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_MANAGE_ITEM . '</legend>
        <br>';
printf(_AM_TDMSPOT_THEREARE_ITEM, $numitem);
echo '<br><br>';
printf(_AM_TDMSPOT_THEREARE_ITEM_WAITING, $item_waiting);
echo '<br><br>';
printf(_AM_TDMSPOT_THEREARE_ITEM_TIME, $item_time);
echo '<br><br>
    </fieldset><br><br>

       <fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_MANAGE_CAT . '</legend>
        <br>';
printf(_AM_TDMSPOT_THEREARE_CAT, $numcat);
echo '<br><br>';
printf(_AM_TDMSPOT_THEREARE_CAT_WAITING, $cat_waiting);
echo '<br><br>
    </fieldset><br><br>



 <fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_MANAGE_PAGE . '</legend>
        <br>';
printf(_AM_TDMSPOT_THEREARE_PAGE, $numpage);
echo '<br><br>
    </fieldset><br><br>

     <fieldset><legend class="CPmediumTitle">' . _AM_TDMSPOT_MANAGE_BLOCK . '</legend>
        <br>';
printf(_AM_TDMSPOT_THEREARE_BLOCK, $numblock);
echo '<br><br>
    </fieldset><br> <br>

     <fieldset><legend class="CPmediumTitle">Apache</legend>
        <br>';
echo $veriffile;
echo '<br><br>
    </fieldset><br> <br>

    </td></tr></table>';
require_once __DIR__ . '/admin_footer.php';
