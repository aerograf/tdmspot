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
 */

/**
 * @param $options
 * @return array
 */
function b_tdmspot($options)
{
    global $xoopsModuleConfig, $xoopsModule;

    require_once XOOPS_ROOT_PATH . '/modules/tdmspot/include/common.php';

    $moduleHandler = xoops_getHandler('module');
    $xoopsModule   = $moduleHandler->getByDirname('tdmspot');

    if (!isset($xoopsModuleConfig)) {
        $configHandler     = xoops_getHandler('config');
        $xoopsModuleConfig = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
    }

    if (1 == $xoopsModuleConfig['tdmspot_seo']) {
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/seo.inc.php';
    }

    $blocks       = [];
    $type_block   = $options[0];
    $nb_document  = $options[1];
    $lenght_title = $options[2];
    $myts         = MyTextSanitizer::getInstance();
    $itemHandler  = xoops_getModuleHandler('tdmspot_item', 'tdmspot');

    $criteria = new CriteriaCompo();
    $criteria->setLimit($nb_document);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    if (!(1 == count($options) && 0 == $options[0])) {
        $criteria->add(new Criteria('cat', addCatSelect($options), 'IN'));
    }

    switch ($type_block) {
        //title
        case 'title':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('title');
            $criteria->setOrder('DESC');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/tdmspot/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;
        //recents
        case 'date':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('indate');
            $criteria->setOrder('DESC');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/tdmspot/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;
        // populaire
        case 'hits':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('hits');
            $criteria->setOrder('DESC');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/tdmspot/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;
        case 'counts':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('counts');
            $criteria->setOrder('DESC');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/TDMSound/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;
        case 'comments':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('comments');
            $criteria->setOrder('DESC');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/tdmspot/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;
        case 'rand':
            $criteria->add(new Criteria('display', 1));
            $criteria->add(new Criteria('indate', time(), '<'));
            $criteria->setSort('RAND()');
            $assoc_arr = $itemHandler->getall($criteria);
            foreach (array_keys($assoc_arr) as $i) {
                $blocks[$i]['id']    = $assoc_arr[$i]->getVar('id');
                $blocks[$i]['title'] = $myts->displayTarea((strlen($assoc_arr[$i]->getVar('title')) > $lenght_title ? substr($assoc_arr[$i]->getVar('title'), 0, $lenght_title)
                                                                                                                      . '...' : $assoc_arr[$i]->getVar('title')));
                //$blocks[$i]['title'] =  "<a href='".XOOPS_URL. "/modules/tdmspot/item.php?itemid=".$assoc_arr[$i]->getVar('id')."'>".$title."</a>";
                $blocks[$i]['link']   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_item'], $assoc_arr[$i]->getVar('id'), $assoc_arr[$i]->getVar('title'));
                $blocks[$i]['counts'] = $assoc_arr[$i]->getVar('counts');
                $blocks[$i]['hits']   = $assoc_arr[$i]->getVar('hits');
                $blocks[$i]['indate'] = formatTimestamp($assoc_arr[$i]->getVar('indate'), 'm');
            }
            break;

    }

    return $blocks;
}

/**
 * @param $options
 * @return string
 */
function b_tdmspot_edit($options)
{
    $catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
    $criteria   = new CriteriaCompo();
    $criteria->add(new Criteria('display', 1));
    $criteria->setSort('title');
    $criteria->setOrder('ASC');
    $assoc_arr = $catHandler->getall($criteria);
    $form      = _MI_TDMSPOT_BLOCK_LIMIT . "&nbsp;\n";
    $form      .= '<input type="hidden" name="options[0]" value="' . $options[0] . '">';
    $form      .= '<input name="options[1]" size="5" maxlength="255" value="' . $options[1] . '" type="text">&nbsp;<br>';
    $form      .= _MI_TDMSPOT_BLOCK_TEXTE . ' : <input name="options[2]" size="5" maxlength="255" value="' . $options[2] . '" type="text"><br><br>';
    array_shift($options);
    array_shift($options);
    array_shift($options);
    $form .= _MI_TDMSPOT_BLOCK_CAT . '<br><select name="options[]" multiple="multiple" size="5">';
    $form .= '<option value="0" ' . (false === array_search(0, $options) ? '' : 'selected="selected"') . '>All</option>';
    foreach (array_keys($assoc_arr) as $i) {
        $form .= '<option value="' . $assoc_arr[$i]->getVar('id') . '" ' . (false === array_search($assoc_arr[$i]->getVar('id'), $options) ? '' : 'selected="selected"') . '>'
                 . $assoc_arr[$i]->getVar('title') . '</option>';
    }
    $form .= '</select>';

    return $form;
}
