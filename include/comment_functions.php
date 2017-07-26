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
    die('XOOPS root path not defined');
}

// comment callback functions

/**
 * @param $id
 * @param $total_num
 */
function tdmspot_comments_update($id, $total_num)
{
    global $xoopsDB;

    $itemHandler = xoops_getModuleHandler('tdmspot_item', 'TDMspot');
    $view = $itemHandler->get($id);
    $hits = $view->getVar('comments');
    ++$hits;
    $obj =& $itemHandler->get($id);
    $obj->setVar('comments', $hits);
    $ret = $itemHandler->insert($obj);

    return $ret;
}

/**
 * @param $comment
 */
function tdmspot_comments_approve(&$comment)
{
    // notification mail here
}
