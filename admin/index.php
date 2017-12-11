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
// Display Admin header
xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();

//check or upload folders
$configurator = include __DIR__ . '/../include/config.php';
foreach (array_keys($configurator->uploadFolders) as $i) {
//    $utility::createFolder($configurator->uploadFolders[$i]);
    $adminObject->addConfigBoxLine($configurator->uploadFolders[$i], 'folder');
}


//-------------------------------------

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

$veriffile = '<span style="color: green;"><img src="./../assets/images/on.gif" >mod_rewrite OK</span>';
if (!in_array('mod_rewrite', @apache_get_modules())) {
    $veriffile = '<span style="color: red;"><img src="./../assets/images/off.gif">mod_rewrite ERROR</a></span>';
}



//count "total quotes"
//$quotesCount = $quotesHandler->getCount();
// InfoBox quotes
$adminObject->addInfoBox(_AM_TDMSPOT_MANAGE_ITEM);
// InfoBox quotes
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_ITEM, $numitem));
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_ITEM_WAITING, $item_waiting));
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_ITEM_TIME, $item_time));



// InfoBox quotes
$adminObject->addInfoBox(_AM_TDMSPOT_MANAGE_CAT);
// InfoBox quotes
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_CAT, $numcat));
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_CAT_WAITING, $cat_waiting));



// InfoBox quotes
$adminObject->addInfoBox(_AM_TDMSPOT_MANAGE_PAGE);
// InfoBox quotes
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_PAGE, $numpage));



// InfoBox quotes
$adminObject->addInfoBox(_AM_TDMSPOT_MANAGE_BLOCK);
// InfoBox quotes
$adminObject->addInfoBoxLine(sprintf(_AM_TDMSPOT_THEREARE_BLOCK, $numblock));




// InfoBox quotes
$adminObject->addInfoBox('Apache');
// InfoBox quotes
$adminObject->addInfoBoxLine($veriffile);




$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data ----------------------------
if ($helper->getConfig('displaySampleButton')) {
    xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once __DIR__ . '/../testdata/index.php';
    $adminObject->addItemButton(_AM_SYSTEM_MODULES_INSTALL_TESTDATA, '__DIR__ . /../../testdata/index.php?op=load', 'add');
    $adminObject->displayButton('left', '');
}
//------------- End Test Data ----------------------------

$adminObject->displayIndex();

echo $utility::getServerStats();

require_once __DIR__ . '/admin_footer.php';
