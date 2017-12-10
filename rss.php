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

require_once __DIR__ . '/../../mainfile.php';
require_once $GLOBALS['xoops']->path('class/template.php');
$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

header('Content-Type:text/xml; charset=utf-8');

$tpl = new \XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);

if (!$tpl->is_cached('db:spot_rss.tpl')) {
    xoops_load('XoopsLocal');
    $tpl->assign('channel_title', \XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
    $tpl->assign('channel_link', XOOPS_URL . '/');
    $tpl->assign('channel_desc', \XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_category', 'News');
    $tpl->assign('channel_generator', 'XOOPS');
    $tpl->assign('channel_language', _LANGCODE);
    $tpl->assign('image_url', XOOPS_URL . '/images/logo.png');
    $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
    //cherche les news
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('display', 1));
    $criteria->add(new Criteria('indate', time(), '<'));
    $criteria->setSort('indate');
    $criteria->setOrder('ASC');
    $item_arr = $itemHandler->getAll($criteria);
    $tpitem = [];
    foreach (array_keys($item_arr) as $i) {
        $tpitem['id'] = $item_arr[$i]->getVar('id');
        $tpitem['title'] = $item_arr[$i]->getVar('title');
        $tpitem['cat'] = $item_arr[$i]->getVar('cat');
        //trouve la categorie
        if ($cat = $catHandler->get($item_arr[$i]->getVar('cat'))) {
            $tpitem['cat_title'] = $cat->getVar('title');
            $tpitem['cat_id'] = $cat->getVar('id');
        }

        if (strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')) {
            $more = substr($item_arr[$i]->getVar('text'), strpos($item_arr[$i]->getVar('text'), '{X_BREAK}') + 11);
            $tpitem['text'] = substr(
                $item_arr[$i]->getVar('text'),
                0,
                    strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')
            ) . "<a href='./item.php?itemid=" . $tpitem['id'] . "' rel='nofollow'>[...]</a>";
        } else {
            $tpitem['text'] = $item_arr[$i]->getVar('text');
        }

        $tpitem['indate'] = formatTimestamp($item_arr[$i]->getVar('indate'), 'm');
        $tpitem['link'] = XOOPS_URL . '/modules/tdmspot/item.php?itemid=' . $item_arr[$i]->getVar('id');
        $tpitem['guid'] = XOOPS_URL . '/modules/tdmspot/item.php?itemid=' . $item_arr[$i]->getVar('id');

        $tpl->append('tpitem', $tpitem);
    }
}
$tpl->display('db:spot_rss.tpl');
