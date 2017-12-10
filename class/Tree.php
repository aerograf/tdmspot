<?php namespace Xoopsmodules\tdmspot;
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

use Criteria;
use CriteriaCompo;

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

require_once XOOPS_ROOT_PATH . '/class/tree.php';

/**
 * Class TdmObjectTree
 */
class Tree extends \XoopsObjectTree
{
    //function __constrcut(){
    //}
    /**
     * @param        $fieldName
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    public function _makeArrayTreeOptions($fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);
            $ret[$value] = $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }

        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeArrayTreeOptions($fieldName, $childkey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * @param        $fieldName
     * @param string $prefix
     * @param int    $key
     * @return array
     */
    public function makeArrayTree($fieldName, $prefix = '-', $key = 0)
    {
        $ret = [];
        $this->_makeArrayTreeOptions($fieldName, $key, $ret, $prefix);

        return $ret;
    }

    /**
     * @param        $itemHandler
     * @param        $fieldName
     * @param        $selected
     * @param        $key
     * @param        $ret
     * @param        $ret2
     * @param        $prefix_orig
     * @param string $prefix_curr
     * @param        $chcount
     */
    public function _makeCatBoxOptions($itemHandler, $fieldName, $selected, $key, &$ret, &$ret2, $prefix_orig, $prefix_curr = '', $chcount)
    {
        global $xoopsModule, $xoopsModuleConfig, $cat_display, $cat_cel, $groups, $start, $limit, $tris;

        $gpermHandler = xoops_getHandler('groupperm');
        $parent = '';

        if ($key > 0 && $gpermHandler->checkRight('tdmspot_catview', $this->tree[$key]['obj']->getVar('id'), $groups, $xoopsModule->getVar('mid'))) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);

            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('cat', $this->tree[$key]['obj']->getVar('id')));
            $criteria->add(new \Criteria('display', 1));
            $count = $itemHandler->getCount($criteria);

            $cat_link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris);

            //recherche image
            $imgpath = TDM_CAT_PATH . '/' . $this->tree[$key]['obj']->getVar('img');
            if (file_exists($imgpath) && 'blank.gif' !== $this->tree[$key]['obj']->getVar('img')) {
                $picture = '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '"><img src="' . TDM_CAT_URL . '/' . $this->tree[$key]['obj']->getVar('img') . '" class="img" width="' . $xoopsModuleConfig['tdmspot_cat_width'] . '"  height="' . $xoopsModuleConfig['tdmspot_cat_height'] . '"></a>';
            } else {
                $picture = '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '"><img src="' . TDM_CAT_URL . '/no_picture.png" class="img" width="' . $xoopsModuleConfig['tdmspot_cat_width'] . '"  height="' . $xoopsModuleConfig['tdmspot_cat_height'] . '"></a>';
            }

            //echo $selected;
            if (isset($selected) && $value == $selected) {
                if (isset($this->tree[$this->tree[$key]['parent']]['obj'])) {
                    $parent_link = tdmspot_generateSeoUrl(
                        $xoopsModuleConfig['tdmspot_seo_cat'],
                        $this->tree[$this->tree[$key]['parent']]['obj']->getVar('id'),
                        $this->tree[$this->tree[$key]['parent']]['obj']->getVar('title'),
                        $start,
                        $limit,
                        $tris
                    );
                    $parent = '<div align="right"><a href ="' . $parent_link . '" title="' . $this->tree[$this->tree[$key]['parent']]['obj']->getVar('title') . '">' . $this->tree[$this->tree[$key]['parent']]['obj']->getVar('title') . '</a></div>';
                }

                $ret2 = '<br>' . $parent . '
        <table cellpadding="0" class="outer tdmcat" cellspacing="0"><tr><tr><td>
        <ul>' . $picture . '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a> (' . $count . ')
        <br style="clear: both;"></ul></td></tr></table><br>';
            }

            if ('sub' === $cat_display || 'subimg' === $cat_display) {
                $cat_sub = true;
            } else {
                $cat_sub = false;
            }

            if ((!$prefix_curr && $cat_sub) || (!$cat_sub && $this->tree[$key]['obj']->getVar('pid') == $selected)) {
                switch ($cat_display) {

                    case 'text':
                        $ret .= '<td><ul><a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a> (' . $count . ')';
                        break;

                    case 'textimg':
                        $ret .= '<td><ul>' . $picture . '<br><a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a> (' . $count . ')';
                        break;

                    case 'sub':
                        $ret .= '<td><ul><a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a> (' . $count . ')';
                        break;

                    case 'subimg':
                        $ret .= '<td><ul>' . $picture . '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a> (' . $count . ')';

                        break;

                    case 'img':
                        $ret .= '<td><ul>' . $picture;
                        break;

                }
            } else {
                if ($cat_sub) {
                    $ret .= '<li> (' . $count . ') ' . $prefix_curr . ' <a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('title') . '"> ' . $this->tree[$key]['obj']->getVar($fieldName) . '</a></li>';
                }
            }

            //}
            $prefix_curr .= $prefix_orig;

            //}
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $childkey, $ret, $ret2, $prefix_orig, $prefix_curr, $chcount);
                if (!$prefix_curr) {
                    $ret .= '<br style="clear: both; visibility: hidden;"></ul></td>';

                    if ($chcount == $cat_cel) {
                        $ret .= '</tr><tr>';
                        $chcount = 1;
                    } else {
                        ++$chcount;
                    }
                }
            }
        }
    }

    /**
     * @param        $fieldName
     * @param        $selected
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     * @param        $perm
     */
    public function _makeSelBoxOptions($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', $perm)
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;

        $gpermHandler = xoops_getHandler('groupperm');
        if ($key > 0) {
            //$value = $this->tree[$key]['obj']->getVar( $this->myId );
            $value = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris);

            if (!empty($perm) && $gpermHandler->checkRight($perm, $this->tree[$key]['obj']->getVar('id'), $groups, $xoopsModule->getVar('mid'))) {
                $ret .= '<option value="' . $value . '"';

                if ($value == $selected) {
                    $ret .= ' selected="selected"';
                }
                $ret .= '>' . $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName) . '</option>';
            }
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptions($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $perm);
            }
        }
    }

    //makeCatBox($itemHandler,name cat, )

    /**
     * @param        $itemHandler
     * @param        $fieldName
     * @param string $prefix
     * @param string $selected
     * @param int    $key
     * @return string
     */
    public function makeCatBox($itemHandler, $fieldName, $prefix = '-', $selected = '', $key = 0)
    {
        global $cat_display;
        if ('none' !== $cat_display) {
            $ret = '<div style="text-align:right"><a href="javascript:;" onclick="masque(\'1\')" >+-</a></div><table cellpadding="0" id="masque_1" class="outer tdmcat_' . $cat_display . '" cellspacing="0"><tr>';
            $chcount = 1;
            $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $key, $ret, $ret2, $prefix, '', $chcount);
            $ret .= '</tr></table><br>';

            return $ret . $ret2;
        }
        return '';
    }

    /**
     * @param string $name
     * @param string $fieldName
     * @param string $prefix
     * @param string $selected
     * @param bool   $addEmptyOption
     * @param int    $key
     * @param string $extra
     * @param bool   $perm
     * @return string
     */
    public function makeSelBox($name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0, $extra = '', $perm = false)
    {
        $ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
        if (false != $addEmptyOption) {
            $ret .= '<option value="0">' . $addEmptyOption . '</option>';
        }
        $this->_makeSelBoxOptions($fieldName, $selected, $key, $ret, $prefix, $prefix, $perm);

        return $ret . '</select>';
    }
}
