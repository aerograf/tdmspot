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
$GLOBALS['xoopsOption']['template_main'] = 'spot_viewcat.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/common.php';

$myts = \MyTextSanitizer::getInstance();

//load class
$itemHandler = new tdmspot\ItemHandler(); //xoops_getModuleHandler('tdmspot_item', 'tdmspot');
$catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');

//perm
$permHelper = new \Xmf\Module\Helper\Permission();
//permission
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
    $xd_uid = $xoopsUser->getVar('uid');
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
    $xd_uid = 0;
}

//permission d'afficher
if(!$permHelper->checkPermission('spot_view', 2)) {
    redirect_header(XOOPS_URL, 2, _MD_TDMSPOT_NOPERM);
}

if (1 == $xoopsModuleConfig['tdmspot_seo']) {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/seo.inc.php';
}

//variable post
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : $xoopsModuleConfig['tdmspot_page'];
$LT = isset($_REQUEST['LT']) ? $_REQUEST['LT'] : false;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$tris = isset($_REQUEST['tris']) ? $_REQUEST['tris'] : 'indate';
//

//mode de visualisation
$GLOBALS['xoopsTpl']->assign('tris', $tris);
$GLOBALS['xoopsTpl']->assign('limit', $limit);
$GLOBALS['xoopsTpl']->assign('baseurl', $_SERVER['PHP_SELF']);

switch ($op) {

    case 'list':
    default:

        //securiter si aucun n'est choisis
        if (!isset($LT)) {
            redirect_header('index.php', 2, _MD_TDMSPOT_QUERYNOPERM);
        }

        //perm
    if(!$permHelper->checkPermission('tdmspot_catview', $LT)) {
            redirect_header('index.php', 2, _MD_TDMSPOT_QUERYNOPERM);
        }

        // ************************************************************
        // Liste des Categories
        // ************************************************************
        $cat_arr = $catHandler->getAll();
        $mytree = new tdmspot\Tree($cat_arr, 'id', 'pid');
        //asigne les URL
//        define('TDM_CAT_URL', TDMSPOT_CAT_IMAGE_URL);
//        define('TDM_CAT_PATH', TDMSPOT_CAT_IMAGE_PATH);
        $cat_display = $xoopsModuleConfig['tdmspot_cat_display'];
        $cat_cel = $xoopsModuleConfig['tdmspot_cat_cel'];
        $display_cat = $mytree->makeCatBox($itemHandler, 'title', '-', $LT);
        $GLOBALS['xoopsTpl']->assign('display_cat', $display_cat);

        //navigation
        $navigation = '';

        $GLOBALS['xoopsTpl']->assign('cat_view', true);
        $GLOBALS['xoopsTpl']->assign('selectcat', tdmspot\Utility::getCategorySelect((int)$LT));
        $GLOBALS['xoopsTpl']->assign('selectpage', tdmspot\Utility::getPageSelect(false));
        $GLOBALS['xoopsTpl']->assign('selecttris', tdmspot\Utility::getTrisSelect((int)$LT, $tris));
        $GLOBALS['xoopsTpl']->assign('selectview', tdmspot\Utility::getViewSelect((int)$LT, $limit));
        //sous cat
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('display', 1));
        $souscat_arr = $catHandler->getObjects($criteria);
        $mytree = new \XoopsObjectTree($souscat_arr, 'id', 'pid');
        $nav_parent_id = $mytree->getAllParent((int)$LT);
        $nav_parent_id = array_reverse($nav_parent_id);

        foreach (array_keys($nav_parent_id) as $i) {
            $navigation .= "<a href='" . tdmspot_generateSeoUrl(
                $xoopsModuleConfig['tdmspot_seo_cat'],
                $nav_parent_id[$i]->getVar('id'),
                    $nav_parent_id[$i]->getVar('title')
            ) . "'>" . $nav_parent_id[$i]->getVar('title') . '</a>&nbsp;>&nbsp;';
        }
        //categorie en cour
        $cat = $catHandler->get($_REQUEST['LT']);

        //on test l'existance de l'image
        $imgpath = TDMSPOT_CAT_IMAGE_PATH . '/' . $cat->getVar('img');
        if (file_exists($imgpath)) {
            $redim = tdmspot\Utility::getRedImage($imgpath, $xoopsModuleConfig['tdmspot_cat_width'], $xoopsModuleConfig['tdmspot_cat_height']);
            $cat_img = '<img src=' . TDMSPOT_CAT_IMAGE_URL . '/' . $cat->getVar('img') . " height='" . $redim['dst_h'] . "' width='" . $redim['dst_w'] . "'>";
            //$cat_img = XOOPS_URL. "/modules/".$xoopsModule->dirname()."/upload/cat/".$cat->getVar("img");
        } else {
            $cat_img = false;
        }

        $GLOBALS['xoopsTpl']->assign('cat_title', $myts->displayTarea($cat->getVar('title')));
        $meta_title = $cat->getVar('title');
        $meta_keywords = $cat->getVar('title');
        $meta_description = $cat->getVar('title');
        $GLOBALS['xoopsTpl']->assign('cat_text', $myts->displayTarea($cat->getVar('text')));
        $GLOBALS['xoopsTpl']->assign('cat_id', $cat->getVar('id'));
        $GLOBALS['xoopsTpl']->assign('cat_img', $cat_img);
        //$navigation .= "<a href='viewcat.php?LT=".$cat->getVar('id')."&tris=".$tris."&limit=".$limit."'>".$myts->displayTarea($cat->getVar('title'))."</a>&nbsp;>&nbsp;";

        $GLOBALS['xoopsTpl']->assign('nav_bar', $navigation);

        if (1 == $xoopsModuleConfig['tdmspot_seo']) {
            $GLOBALS['xoopsTpl']->assign('nav', "<a href='" . XOOPS_URL . '/' . $xoopsModuleConfig['tdmspot_seo_title'] . "/'>" . $xoopsModuleConfig['tdmspot_seo_title'] . '</a>');
        } else {
            $GLOBALS['xoopsTpl']->assign('nav', "<a href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "'>" . $xoopsModule->name() . '</a>');
        }
        unset($criteria);

        // ************************************************************
        // Liste des derniers fichier
        // ************************************************************

        //+ r�cents
        if (0 != $xoopsModuleConfig['tdmspot_blindate']) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->add(new Criteria('cat', (int)$_REQUEST['LT']));
            $criteria->setSort('indate');
            $criteria->setOrder('DESC');
            $criteria->setLimit($xoopsModuleConfig['tdmspot_blindate']);
            $item_arr = $itemHandler->getAll($criteria);
            foreach (array_keys($item_arr) as $i) {
                $title = $myts->htmlSpecialChars($item_arr[$i]->getVar('title'));
                if (strlen($title) >= $xoopsModuleConfig['tdmspot_bltitle']) {
                    $title = substr($title, 0, $xoopsModuleConfig['tdmspot_bltitle']) . '...';
                }
                $indate = formatTimestamp($item_arr[$i]->getVar('indate'), 's');
                $link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $item_arr[$i]->getVar('id'), $item_arr[$i]->getVar('title'));
                $GLOBALS['xoopsTpl']->append('tpitem_blindate', ['id' => $item_arr[$i]->getVar('id'), 'cat' => $item_arr[$i]->getVar('cat'), 'indate' => $indate, 'title' => $title, 'link' => $link]);
            }
            unset($criteria);
        }

        //plus populaire
        if ($xoopsModuleConfig['tdmspot_blcounts'] > 0) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->add(new Criteria('cat', (int)$_REQUEST['LT']));
            $criteria->setSort('counts');
            $criteria->setOrder('DESC');
            $criteria->setLimit($xoopsModuleConfig['tdmspot_blcounts']);
            $item_arr = $itemHandler->getAll($criteria);
            foreach (array_keys($item_arr) as $i) {
                $title = $myts->htmlSpecialChars($item_arr[$i]->getVar('title'));
                if (strlen($title) >= $xoopsModuleConfig['tdmspot_bltitle']) {
                    $title = substr($title, 0, $xoopsModuleConfig['tdmspot_bltitle']) . '...';
                }
                $link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $item_arr[$i]->getVar('id'), $item_arr[$i]->getVar('title'));
                $GLOBALS['xoopsTpl']->append(
                    'tpitem_blcounts',
                    ['id' => $item_arr[$i]->getVar('id'), 'cat' => $item_arr[$i]->getVar('cat'), 'counts' => $item_arr[$i]->getVar('counts'), 'title' => $title, 'link' => $link]
                );
            }
        }
        //plus vue
        if ($xoopsModuleConfig['tdmspot_blhits'] > 0) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->add(new Criteria('cat', (int)$_REQUEST['LT']));
            $criteria->setSort('hits');
            $criteria->setOrder('DESC');
            $criteria->setLimit($xoopsModuleConfig['tdmspot_blhits']);
            $item_arr = $itemHandler->getAll($criteria);
            foreach (array_keys($item_arr) as $i) {
                $title = $myts->htmlSpecialChars($item_arr[$i]->getVar('title'));
                if (strlen($title) >= $xoopsModuleConfig['tdmspot_bltitle']) {
                    $title = substr($title, 0, $xoopsModuleConfig['tdmspot_bltitle']) . '...';
                }
                $link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $item_arr[$i]->getVar('id'), $item_arr[$i]->getVar('title'));
                $GLOBALS['xoopsTpl']->append(
                    'tpitem_blhits',
                    ['id' => $item_arr[$i]->getVar('id'), 'cat' => $item_arr[$i]->getVar('cat'), 'hits' => $item_arr[$i]->getVar('hits'), 'title' => $title, 'link' => $link]
                );
            }
        }

        //afficher fichier
        $criteria3 = new CriteriaCompo();
        $criteria3->add(new Criteria('display', 1));
        $criteria3->add(new Criteria('indate', time(), '<'));
        $criteria3->add(new Criteria('cat', $LT));
        $criteria3->setStart($start);
        $criteria3->setLimit($limit);
        $criteria3->setSort($tris);

        $criteria3->setOrder('DESC');
        $item_arr = $itemHandler->getObjects($criteria3);
        $numitem = $itemHandler->getCount($criteria3);
        $GLOBALS['xoopsTpl']->assign('numitem', $numitem);

        if ($numitem > 0) {
            foreach (array_keys($item_arr) as $i) {

                //trouve la categorie
                if ($cat = $catHandler->get($item_arr[$i]->getVar('cat'))) {
                    $tpitem['cat_title'] = $cat->getVar('title');
                    $tpitem['cat_link'] = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'));
                    $tpitem['cat_id'] = $cat->getVar('id');
                    if (1 == $xoopsModuleConfig['tdmspot_img']) {
                        $imgpath = TDMSPOT_CAT_IMAGE_PATH . '/' . $cat->getVar('img');
                        if (file_exists($imgpath)) {
                            $redim = tdmspot\Utility::getRedImage($imgpath, $xoopsModuleConfig['tdmspot_cat_width'], $xoopsModuleConfig['tdmspot_cat_height']);
                            $tpitem['img'] = '<img src=' . TDMSPOT_CAT_IMAGE_URL . '/' . $cat->getVar('img') . " height='" . $redim['dst_h'] . "' width='" . $redim['dst_w'] . "'>";
                            //$tpitem['img'] = XOOPS_URL. "/modules/".$xoopsModule->dirname()."/upload/cat/".$cat->getVar("img");
                        } else {
                            $tpitem['img'] = false;
                        }
                    }
                }

                $tpitem['id'] = $item_arr[$i]->getVar('id');
                $tpitem['title'] = $myts->displayTarea($item_arr[$i]->getVar('title'));

                $tpitem['link'] = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $item_arr[$i]->getVar('id'), $item_arr[$i]->getVar('title'));

                if (strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')) {
                    $more = substr($item_arr[$i]->getVar('text'), strpos($item_arr[$i]->getVar('text'), '{X_BREAK}') + 11);
                    $tpitem['text'] = substr($item_arr[$i]->getVar('text'), 0, strpos($item_arr[$i]->getVar('text'), '{X_BREAK}')) . "<a href='" . $tpitem['link'] . "' rel='nofollow'>[...]</a>";
                    $tpitem['more'] = strlen($more);
                } else {
                    $tpitem['text'] = $item_arr[$i]->getVar('text');
                }

                $tpitem['hits'] = $item_arr[$i]->getVar('hits');
                $tpitem['postername'] = XoopsUser::getUnameFromId($item_arr[$i]->getVar('poster'));
                $tpitem['poster'] = $item_arr[$i]->getVar('poster');
                //test si l'user a un album
                //$item['useralb'] = tdmpiLTure_useralb($item_arr[$f]->getVar('item_uid'));
                //
                $tpitem['indate'] = formatTimestamp($item_arr[$i]->getVar('indate'), 'S');
                //nombre de vote
                $tpitem['votes'] = $item_arr[$i]->getVar('votes');
                //total des votes
                $tpitem['counts'] = $item_arr[$i]->getVar('counts');
                $tpitem['comments'] = $item_arr[$i]->getVar('comments');

                //on test l'existance de fichier
                if($permHelper->checkPermission('spot_view', 256)) {
                    $imgpath = TDMSPOT_UPLOAD_PATH . '/' . $item_arr[$i]->getVar('file');
                    if (file_exists($imgpath)) {
                        $tpitem['file'] = $item_arr[$i]->getVar('file');
                        $tpitem['file_url'] = tdmspot_generateSeoUrl('download', $item_arr[$i]->getVar('id'), 'download_' . $item_arr[$i]->getVar('file'));
                    } else {
                        $tpitem['file'] = false;
                    }
                }

                //moyen des votes
                @$moyen = ceil($tpitem['votes'] / $tpitem['counts']);
                if (0 == @$moyen) {
                    $tpitem['moyen'] = '';
                } else {
                    $tpitem['moyen'] = "<img src='" . TDMSPOT_IMAGES_URL . '/rate' . $moyen . ".png'>";
                }

                $GLOBALS['xoopsTpl']->append('tpitem', $tpitem);
            }

            //navigation
            if ($numitem > $limit) {
                $pagenav = new tdmspotPageNav($numitem, $limit, $start, 'start');
                $GLOBALS['xoopsTpl']->assign('nav_page', $pagenav->renderNav(2));
            }
        }

        break;

}

//perm
if($permHelper->checkPermission('spot_view', 4)) {
    $GLOBALS['xoopsTpl']->assign('perm_submit', "<a href='" . TDMSPOT_URL . "/submit.php'>" . _MD_TDMSPOT_PERM_4 . '</a>');
}
if($permHelper->checkPermission('spot_view', 128)) {
    $GLOBALS['xoopsTpl']->assign('perm_rss', "<a href='" . TDMSPOT_URL . "/rss.php'><img src=" . TDMSPOT_IMAGES_URL . '/rss.png alt=' . _MD_TDMSPOT_EXPRSS . ' title=' . _MD_TDMSPOT_EXPRSS . '></a>');
}
$GLOBALS['xoopsTpl']->assign('perm_vote', $permHelper->checkPermission('spot_view', 32) ? true : false);
$GLOBALS['xoopsTpl']->assign('perm_export', $permHelper->checkPermission('spot_view', 16) ? true : false);
$GLOBALS['xoopsTpl']->assign('perm_social', $permHelper->checkPermission('spot_view', 64) ? true : false);
//config

tdmspot\Utility::getHeader();
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name() . ' : ' . $meta_title));

if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'keywords', tdmspot\Utility::getKeywords($meta_keywords));
    $xoTheme->addMeta('meta', 'description', tdmspot\Utility::tdmspot_desc($meta_description));
} else {    // Compatibility for old Xoops versions
    $GLOBALS['xoopsTpl']->assign('xoops_meta_keywords', tdmspot\Utility::getKeywords($meta_keywords));
    $GLOBALS['xoopsTpl']->assign('xoops_meta_description', tdmspot\Utility::tdmspot_desc($meta_description));
}

require_once XOOPS_ROOT_PATH . '/footer.php';
