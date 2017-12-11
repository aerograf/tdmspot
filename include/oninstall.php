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
 * @param $module
 * @return bool
 */

use Xoopsmodules\tdmspot;

/**
 *
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_tdmspot(\XoopsModule $module)
{

    include __DIR__ . '/../preloads/autoloader.php';
    /** @var tdmspot\Utility $utility */
    $utility = new \Xoopsmodules\tdmspot\Utility();
    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !==  $phpSuccess) {
        $moduleTables =& $module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

function xoops_module_install_tdmspot(\XoopsModule $module)
{
    include __DIR__ . '/../preloads/autoloader.php';

    ///////////////////////////
    ///Creation des fichiers///
    ///////////////////////////
    global $xoopsModule, $xoopsDB;

    require_once  __DIR__ . '/../../../mainfile.php';
    require_once  __DIR__ . '/../include/config.php';

    $moduleDirName = basename(dirname(__DIR__));

    /** @var \Xoopsmodules\tdmspot\Helper $helper */
    $helper = tdmspot\Helper::getInstance();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');

//    $namemodule = 'spot';
//    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
//        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php';
//    } else {
//        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/english/admin.php';
//    }

    //Copie du plug
//    $indexFile = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/xoops_plugins/function.xoSpot.php';
//    copy($indexFile, XOOPS_ROOT_PATH . '/class/smarty/xoops_plugins/function.xoSpot.php');


    $configurator = new tdmspot\Configurator();
    /** @var tdmspot\Utility $utility */
    $utility = new tdmspot\Utility();

    // default Permission Settings ----------------------
    global $xoopsModule;
    $moduleId     = $xoopsModule->getVar('mid');
    $moduleId2    = $helper->getModule()->mid();
    $gpermHandler = xoops_getHandler('groupperm');
    // access rights ------------------------------------------
    $gpermHandler->addRight($moduleDirName . '_approve', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
    $gpermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);

    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->blankFiles) > 0) {
        $file = __DIR__ . '/../assets/images/blank.png';
        foreach (array_keys($configurator->blankFiles) as $i) {
            $dest = $configurator->blankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }
    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE `tpl_module` = '" . $xoopsModule->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $xoopsDB->queryF($sql);    
    
    
    
    
    
    
    
    
    
    
    return true;
}
