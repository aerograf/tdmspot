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

function xoops_module_install_spot(&$module)
{
    include __DIR__ . '/../preloads/autoloader.php';

    ///////////////////////////
    ///Creation des fichiers///
    ///////////////////////////
    global $xoopsModule, $xoopsConfig, $xoopsDB;

    $namemodule = 'spot';
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/english/admin.php';
    }

    //Copie du plug
    $indexFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/xoops_plugins/function.xoSpot.php';
    copy($indexFile, XOOPS_ROOT_PATH . '/class/smarty/xoops_plugins/function.xoSpot.php');

    return true;
}
