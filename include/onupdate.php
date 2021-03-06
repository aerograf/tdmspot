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

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

/**
 * @param      $xoopsModule
 * @param null $oldVersion
 * @return bool
 */
function xoops_module_update_tdmspot(&$xoopsModule, $oldVersion = null)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

    $moduleDirName = basename(dirname(__DIR__));

    $moduleHandler = xoops_getHandler('module');
    $module = $moduleHandler->getByDirname($moduleDirName);

    if ($oldVersion < 102) {
        $xoopsDB->queryFromFile(XOOPS_ROOT_PATH . '/modules/tdmspot/sql/mysql1.01.sql');
    }

    if ($oldversion < 210) {
        // remove old html template files
        $templateDirectory = XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'n') . '/templates/';
        $template_list = array_diff(scandir($templateDirectory, SCANDIR_SORT_NONE), ['..', '.']);
        foreach ($template_list as $k => $v) {
            $fileinfo = new SplFileInfo($templateDirectory . $v);
            if ('html' === $fileinfo->getExtension() && 'index.html' !== $fileinfo->getFilename()) {
                @unlink($templateDirectory . $v);
            }
        }

        xoops_load('xoopsfile');

        //remove /images directory
        $imagesDirectory = XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'n') . '/images/';
        $folderHandler = XoopsFile::getHandler('folder', $imagesDirectory);
        $folderHandler->delete($imagesDirectory);

        //delete .html entries from the tpl table
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
        $xoopsDB->queryF($sql);
    }

    return true;
}

/**
 * @param $fieldname
 * @param $table
 * @return bool
 */
function FieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * @param $tablename
 * @return bool
 */
function TableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}
