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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class to facilitate navigation in a multi page document/list
 *
 * @package       kernel
 * @subpackage    util
 * @author        Kazumi Ono <onokazu@xoops.org>
 * @author        John Neill <catzwolf@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class tdmspotPageNav
{
    /**
     * *#@+
     *
     * @access private
     */
    public $total;
    public $perpage;
    public $current;
    public $url;
    /**
     * *#@-
     */

    /**
     * Constructor
     *
     * @param int $total_items Total number of items
     * @param int $items_perpage Number of items per page
     * @param int $current_start First item on the current page
     * @param string $start_name Name for "start" or "offset"
     * @param string $extra_arg Additional arguments to pass in the URL
     */
    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '')
    {
        global $cat, $xoopsModuleConfig, $tris;
        $this->total = (int)$total_items;
        $this->perpage = (int)$items_perpage;
        $this->current = (int)$current_start;
        $this->extra = $extra_arg;
        if ('' != $extra_arg && ('&amp;' !== substr($extra_arg, -5) || '&' !== substr($extra_arg, -1))) {
            $this->extra = '&amp;' . $extra_arg;
        }
        //$this->url = $_SERVER['PHP_SELF'] . '?' . trim($start_name) . '=';
        $this->url = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'), $current_start, $items_perpage, $tris);
    }
    //    public function tdmspotPageNav($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "")
    //    {
    //        parent::__construct($total_items, $items_perpage, $current_start, $start_name, $extra_arg);
    //    }
    /**
     * Create text navigation
     *
     * @param  integer $offset
     * @return string
     */
    public function renderNav($offset = 4)
    {
        global $cat, $xoopsModuleConfig, $tris;
        $ret = '';
        if ($this->total <= $this->perpage) {
            return $ret;
        }
        $total_pages = ceil($this->total / $this->perpage);
        if ($total_pages > 1) {
            $ret .= '<div id="xo-pagenav">';
            $prev = $this->current - $this->perpage;
            if ($prev >= 0) {
                $ret .= '<a class="xo-pagarrow" href="' . tdmspot_generateSeoUrl(
                    $xoopsModuleConfig['tdmspot_seo_cat'],
                    $cat->getVar('id'),
                    $cat->getVar('title'),
                    $prev,
                    $this->perpage,
                        $tris
                ) . '"><u>&laquo;</u></a> ';
            }
            $counter = 1;
            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);
            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $ret .= '<strong class="xo-pagact" >(' . $counter . ')</strong> ';
                } elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || 1 == $counter || $counter == $total_pages) {
                    if ($counter == $total_pages && $current_page < $total_pages - $offset) {
                        $ret .= '... ';
                    }
                    $ret .= '<a class="xo-counterpage" href="' . tdmspot_generateSeoUrl(
                        $xoopsModuleConfig['tdmspot_seo_cat'],
                        $cat->getVar('id'),
                        $cat->getVar('title'),
                        ($counter - 1) * $this->perpage,
                            $this->perpage,
                        $tris
                    ) . '">' . $counter . '</a> ';
                    if (1 == $counter && $current_page > 1 + $offset) {
                        $ret .= '... ';
                    }
                }
                ++$counter;
            }
            $next = $this->current + $this->perpage;
            if ($this->total > $next) {
                $ret .= '<a class="xo-pagarrow" href="' . tdmspot_generateSeoUrl(
                    $xoopsModuleConfig['tdmspot_seo_cat'],
                    $cat->getVar('id'),
                    $cat->getVar('title'),
                    $next,
                    $this->perpage,
                        $tris
                ) . '"><u>&raquo;</u></a> ';
            }
            $ret .= '</div> ';
        }

        return $ret;
    }
}
