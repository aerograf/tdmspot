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
$GLOBALS['xoopsOption']['template_main'] = 'spot_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
require_once __DIR__ .  '/include/common.php';

$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //$catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
$blockHandler = new tdmspot\NewblocksHandler(); //xoops_getModuleHandler('tdmspot_newblocks', 'tdmspot');
$gpermHandler = xoops_getHandler('groupperm');

if (1 == $xoopsModuleConfig['tdmspot_seo']) {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/seo.inc.php';
}

//permission
$groups = XOOPS_GROUP_ANONYMOUS;
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
}

//permission d'afficher
if (!$gpermHandler->checkRight('spot_view', 2, $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : $xoopsModuleConfig['tdmspot_page'];
$tris = isset($_REQUEST['tris']) ? $_REQUEST['tris'] : 'indate';
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$itemid = isset($_REQUEST['itemid']) ? $_REQUEST['itemid'] : 0;

$myts = \MyTextSanitizer::getInstance();

// ************************************************************
// Liste des Categories
// ************************************************************
$cat_arr = $catHandler->getAll();
$mytree = new tdmspot\Tree($cat_arr, 'id', 'pid');
//asigne les URL
define('TDM_CAT_URL', TDMSPOT_URL);
define('TDM_CAT_PATH', TDMSPOT_PATH);
$cat_display = $xoopsModuleConfig['tdmspot_cat_display'];
$cat_cel = $xoopsModuleConfig['tdmspot_cat_cel'];
$display_cat = $mytree->makeCatBox($itemHandler, 'title', '-', $cat = false);
$GLOBALS['xoopsTpl']->assign('display_cat', $display_cat);

// ************************************************************
// Liste des Pages
// ************************************************************

$criteria = new CriteriaCompo();

if (!empty($itemid) && is_numeric($itemid)) {
    $itemid = (int)$itemid;
    $criteria->add(new Criteria('id', $itemid));
}

$criteria->add(new Criteria('visible', 1));
$criteria->setSort('weight');
$criteria->setOrder('ASC');
$criteria->setLimit(1);
$page_arr = $pageHandler->getObjects($criteria);

$page = [];
$tptabs = [];

foreach (array_keys($page_arr) as $p) {
    if ($gpermHandler->checkRight('spot_pageview', $page_arr[$p]->getVar('id'), $groups, $xoopsModule->getVar('mid'))) {
        if (1 == $xoopsModuleConfig['tdmspot_name']) {
            $page['title'] = $page_arr[$p]->getVar('title');
        }
        $page['id'] = $page_arr[$p]->getVar('id');
        //tabs
        $tptabs['title'] = $page_arr[$p]->getVar('title');
        $tptabs['id'] = $page_arr[$p]->getVar('id');
        //cherche les blocks
        $criteria = new CriteriaCompo();

        $criteria->add(new Criteria('visible', 1));
        $criteria->add(new Criteria('pid', $page_arr[$p]->getVar('id')));
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $block_arr = $blockHandler->getObjects($criteria);
        //$tpblock = array();
        foreach (array_keys($block_arr) as $b) {
            $tpblock['title'] = $block_arr[$b]->getVar('title');
            $tpblock['side'] = $block_arr[$b]->getVar('side');
            $tpblock['pid'] = $block_arr[$b]->getVar('pid');

            //cr�e le block
            $xoopsblock_arr = new \XoopsBlock($block_arr[$b]->getVar('bid'));

            @require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsblock_arr->getVar('dirname') . '/blocks/' . $xoopsblock_arr->getVar('func_file');
            @require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsblock_arr->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/blocks.php';

            $opt = $block_arr[$b]->getVar('options') ?: $xoopsblock_arr->getVar('options');

            $show_func = $xoopsblock_arr->getVar('show_func');
            $options = explode('|', $opt);
            $block = $show_func($options);
            $GLOBALS['xoopsLogger']->startTime($block_arr[$b]->getVar('title'));
            $GLOBALS['xoopsTpl']->assign('block', $block);
            $tpblock['content'] = $GLOBALS['xoopsTpl']->fetch('db:' . $xoopsblock_arr->getVar('template'));
            $GLOBALS['xoopsLogger']->stopTime($block_arr[$b]->getVar('title'));
            $page['tpblock'][] = $tpblock;
        }

        // ************************************************************
        // Liste des News
        // ************************************************************
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('display', 1));
        $criteria->add(new Criteria('indate', time(), '<'));
        $criteria->setSort('indate');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat', '(' . $page_arr[$p]->getVar('cat') . ')', 'IN'));
        $item_arr = $itemHandler->getAll($criteria);
        foreach (array_keys($item_arr) as $i) {
            if ($gpermHandler->checkRight('tdmpicture_catview', $item_arr[$i]->getVar('cat'), $groups, $xoopsModule->getVar('mid'))) {
                $tpitem['id'] = $item_arr[$i]->getVar('id');
                $tpitem['title'] = $item_arr[$i]->getVar('title');
                $tpitem['cat'] = $item_arr[$i]->getVar('cat');
                //trouve la categorie
                if ($cat = $catHandler->get($item_arr[$i]->getVar('cat'))) {
                    $tpitem['cat_title'] = $cat->getVar('title');
                    $tpitem['cat_link'] = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'));
                    $tpitem['cat_id'] = $cat->getVar('id');
                    if (1 == $xoopsModuleConfig['tdmspot_img']) {
                        $imgpath = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/upload/cat/' . $cat->getVar('img');
                        if (file_exists($imgpath)) {
                            $redim = tdmspot\Utility::getRedImage($imgpath, $xoopsModuleConfig['tdmspot_cat_width'], $xoopsModuleConfig['tdmspot_cat_height']);
                            $tpitem['img'] = '<img src=' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/upload/cat/' . $cat->getVar('img') . " height='" . $redim['dst_h'] . "' width='" . $redim['dst_w'] . "'>";
                            //$tpitem['img'] = XOOPS_URL. "/modules/".$xoopsModule->dirname()."/upload/cat/".$cat->getVar("img");
                        } else {
                            $tpitem['img'] = false;
                        }
                    }
                }

                $tpitem['link'] = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $item_arr[$i]->getVar('id'), $item_arr[$i]->getVar('title'));

                if (strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')) {
                    $more = substr($item_arr[$i]->getVar('text'), strpos($item_arr[$i]->getVar('text'), '{X_BREAK}') + 11);
                    $tpitem['text'] = substr($item_arr[$i]->getVar('text'), 0, strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')) . "<a href='" . $tpitem['link'] . "' rel='nofollow'>[...]</a>";
                    $tpitem['more'] = strlen($more);
                } else {
                    $tpitem['text'] = $item_arr[$i]->getVar('text');
                }

                $tpitem['indate'] = formatTimestamp($item_arr[$i]->getVar('indate'), 'm');
                $tpitem['hits'] = $item_arr[$i]->getVar('hits');
                $tpitem['votes'] = $item_arr[$i]->getVar('votes');
                $tpitem['counts'] = $item_arr[$i]->getVar('counts');
                $tpitem['postername'] = XoopsUser::getUnameFromId($item_arr[$i]->getVar('poster'));
                $tpitem['poster'] = $item_arr[$i]->getVar('poster');
                $tpitem['comments'] = $item_arr[$i]->getVar('comments');

                //on test l'existance de fichier
                if ($gpermHandler->checkRight('spot_view', 256, $groups, $xoopsModule->getVar('mid'))) {
                    $imgpath = TDMSPOT_UPLOAD_PATH . '/' . $item_arr[$i]->getVar('file');
                    if (file_exists($imgpath)) {
                        $tpitem['file'] = $item_arr[$i]->getVar('file');
                        $tpitem['file_url'] = tdmspot_generateSeoUrl('download', $item_arr[$i]->getVar('id'), 'download_' . $item_arr[$i]->getVar('file'));
                    } else {
                        $tpitem['file'] = false;
                    }
                }
                //moyen des vote
                @$moyen = ceil($tpitem['votes'] / $tpitem['counts']);
                if (0 == @$moyen) {
                    $tpitem['moyen'] = '';
                } else {
                    $tpitem['moyen'] = "<img src='" . TDMSPOT_IMAGES_URL . '/rate' . $moyen . ".png'>";
                }

                $page['tpitem'][] = $tpitem;
            }
        }
    }

    $GLOBALS['xoopsTpl']->append('page', $page);
    unset($page['tpblock'], $page['tpitem']);
}
//nav
$GLOBALS['xoopsTpl']->assign('selectcat', tdmspot\Utility::getCategorySelect(false));

$GLOBALS['xoopsTpl']->assign('selectpage', tdmspot\Utility::getPageSelect($itemid));

if (1 == $xoopsModuleConfig['tdmspot_seo']) {
    $GLOBALS['xoopsTpl']->assign('nav', "<a href='" . XOOPS_URL . '/' . $xoopsModuleConfig['tdmspot_seo_title'] . "/'>" . $xoopsModuleConfig['tdmspot_seo_title'] . '</a>');
} else {
    $GLOBALS['xoopsTpl']->assign('nav', "<a href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "'>" . $xoopsModule->name() . '</a>');
}
//perm
if ($gpermHandler->checkRight('spot_view', 4, $groups, $xoopsModule->getVar('mid'))) {
    $GLOBALS['xoopsTpl']->assign('perm_submit', "<a href='" . TDMSPOT_URL . "/submit.php'>" . _MD_TDMSPOT_PERM_4 . '</a>');
}
if ($gpermHandler->checkRight('spot_view', 128, $groups, $xoopsModule->getVar('mid'))) {
    $GLOBALS['xoopsTpl']->assign('perm_rss', "<a href='" . TDMSPOT_URL . "/rss.php'><img src=" . TDMSPOT_IMAGES_URL . '/rss.png alt=' . _MD_TDMSPOT_EXPRSS . ' title=' . _MD_TDMSPOT_EXPRSS . '></a>');
}
$GLOBALS['xoopsTpl']->assign('perm_vote', $gpermHandler->checkRight('spot_view', 32, $groups, $xoopsModule->getVar('mid')) ? true : false);
$GLOBALS['xoopsTpl']->assign('perm_export', $gpermHandler->checkRight('spot_view', 16, $groups, $xoopsModule->getVar('mid')) ? true : false);
$GLOBALS['xoopsTpl']->assign('perm_social', $gpermHandler->checkRight('spot_view', 64, $groups, $xoopsModule->getVar('mid')) ? true : false);

tdmspot\Utility::getHeader();
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()));

if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'keywords', tdmspot\Utility::getKeywords($xoopsModuleConfig['tdmspot_keywords']));
    $xoTheme->addMeta('meta', 'description', tdmspot\Utility::tdmspot_desc($xoopsModuleConfig['tdmspot_description']));
} else {    // Compatibility for old Xoops versions
    $GLOBALS['xoopsTpl']->assign('xoops_meta_keywords', tdmspot\Utility::getKeywords($xoopsModuleConfig['tdmspot_keywords']));
    $GLOBALS['xoopsTpl']->assign('xoops_meta_description', tdmspot\Utility::tdmspot_desc($xoopsModuleConfig['tdmspot_description']));
}
require_once XOOPS_ROOT_PATH . '/footer.php';
