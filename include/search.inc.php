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

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */
function tdmspot_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    //load class
    $itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');

    $ret = [];
    //cherche le fichier
    $criteria = new \CriteriaCompo();
    $criteria->setSort('title');
    $criteria->setOrder('ASC');
    $criteria->add(new \Criteria('display', 1));
    $criteria->add(new \Criteria('indate', time(), '<'));
    $criteria->add(new \Criteria('title', '%' . isset($queryarray[0]) ?: '' . '%', 'LIKE'));
    $criteria->setStart($offset);
    $criteria->setLimit($limit);
    $item_arr = $itemHandler->getObjects($criteria);

    $i = 0;
    //while ($myrow = $xoopsDB->fetchArray($result)) {
    foreach (array_keys($item_arr) as $f) {
        $ret[$i]['image'] = 'images/search_file.gif';
        $ret[$i]['link'] = 'item.php?itemid=' . $item_arr[$f]->getVar('id');
        $ret[$i]['title'] = $item_arr[$f]->getVar('title');
        $ret[$i]['time'] = $item_arr[$f]->getVar('indate');
        $ret[$i]['uid'] = $item_arr[$f]->getVar('poster');
        ++$i;
    }

    return $ret;
}
