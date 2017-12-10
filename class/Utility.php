<?php namespace Xoopsmodules\tdmspot;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module:  xSitemap
 *
 * @package      \module\xsitemap\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        File available since version 1.54
 */

use Xmf\Request;
use Xoopsmodules\tdmspot;
use Xoopsmodules\tdmspot\common;

require_once __DIR__ . '/common/VersionChecks.php';
require_once __DIR__ . '/common/ServerStats.php';
require_once __DIR__ . '/common/FilesManagement.php';

/**
 * Class Utility
 */
class Utility
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------

    public $db;

    /**
     * Do some basic file checks and stuff.
     */
    public static function getServerStats2()
    {
        //    global $xoopsModule;

        //        require_once dirname(__DIR__) . '/class/helper.php';
        //        $helper      = & Xoopsmodules\amreviews\Helper::getInstance();
        $helper = tdmspot\Helper::getInstance();
        //    $mainLang    = '_MD_' . strtoupper($helper->moduleDirName);
        //    $modinfoLang = '_MI_' . strtoupper($helper->moduleDirName);
        $adminLang = '_AM_' . strtoupper(basename(dirname(__DIR__)));

        echo '<fieldset>';
        echo '<legend style=\'color: #990000; font-weight: bold;\'>' . constant($adminLang . '_SERVERSTATS') . '</legend>';
        /*
            $photodir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/photos';
            $photothumbdir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/photos/thumb';
            $photohighdir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/photos/highlight';
            $cachedir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/cache';
            $tmpdir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/cache/tmp';

            if (file_exists($photodir)) {
                if (!is_writable($photodir)) {
                    echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> I am unable to write to: ' . $photodir . '<br>';
                } else {
                    echo '<span style=\' color: green; font-weight: bold;\'>OK:</span> ' . $photodir . '<br>';
                }
            } else {
                echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> ' . $photodir . ' does NOT exist!<br>';
            }
            // photothumbdir
            if (file_exists($photothumbdir)) {
                if (!is_writable($photothumbdir)) {
                    echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photothumbdir . '<br>';
                } else {
                    echo '<span style=\' color: green; font-weight: bold;\'>OK:</span> ' . $photothumbdir . '<br>';
                }
            } else {
                echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> ' . $photothumbdir . ' does NOT exist!<br>';
            }
            // photohighdir
            if (file_exists($photohighdir)) {
                if (!is_writable($photohighdir)) {
                    echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> I am unable to write to: ' . $photohighdir . '<br>';
                } else {
                    echo '<span style=\' color: green; font-weight: bold;\'>OK:</span> ' . $photohighdir . '<br>';
                }
            } else {
                echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> ' . $photohighdir . ' does NOT exist!<br>';
            }
            // cachedir
            if (file_exists($cachedir)) {
                if (!is_writable($cachedir)) {
                    echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> I am unable to write to: ' . $cachedir . '<br>';
                } else {
                    echo '<span style=\' color: green; font-weight: bold;\'>OK:</span> ' . $cachedir . '<br>';
                }
            } else {
                echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> ' . $cachedir . ' does NOT exist!<br>';
            }
            // tmpdir
            if (file_exists($tmpdir)) {
                if (!is_writable($tmpdir)) {
                    echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> I am unable to write to: ' . $tmpdir . '<br>';
                } else {
                    echo '<span style=\' color: green; font-weight: bold;\'>OK:</span> ' . $tmpdir . '<br>';
                }
            } else {
                echo '<span style=\' color: red; font-weight: bold;\'>Warning:</span> ' . $tmpdir . ' does NOT exist!<br>';
            }
        */

        /**
         * Some Upload info
         */
        $uploads = constant(ini_get('file_uploads') ? $adminLang . '_UPLOAD_ON' : $adminLang . '_UPLOAD_OFF');
        //    echo '<br>';
        echo '<ul>';
        echo '<li>' . constant($adminLang . '_UPLOADMAX') . '<b>' . ini_get('upload_max_filesize') . '</b></li>';
        echo '<li>' . constant($adminLang . '_POSTMAX') . '<b>' . ini_get('post_max_size') . '</b></li>';
        echo '<li>' . constant($adminLang . '_UPLOADS') . '<b>' . $uploads . '</b></li>';

        $gdinfo = gd_info();
        if (function_exists('gd_info')) {
            echo '<li>' . constant($adminLang . '_GDIMGSPPRT') . '<b>' . constant($adminLang . '_GDIMGON') . '</b></li>';
            echo '<li>' . constant($adminLang . '_GDIMGVRSN') . '<b>' . $gdinfo['GD Version'] . '</b></li>';
        } else {
            echo '<li>' . constant($adminLang . '_GDIMGSPPRT') . '<b>' . constant($adminLang . '_GDIMGOFF') . '</b></li>';
        }
        echo '</ul>';

        //$inithingy = ini_get_all();
        //print_r($inithingy);

        echo '</fieldset>';
    } // end function

    //----------------------------------------------------------------------------//

    /**
     * @return array
     */
    public function getModuleStats()
    {
        // amreview_reviews - amreview_cat - amreview_rate

        $summary  = [];
        $this->db = \XoopsDatabaseFactory::getDatabase();

        /**
         * As many of these will be "joined" at some point.
         */

        /**
         * Review count (total)
         */
        $result = $this->db->query('SELECT COUNT(id) AS revcount FROM ' . $this->db->prefix('amreviews_reviews') . ' ');
        list($revcount) = $this->db->fetchRow($result);// {

        if (!$result) {
            $summary['revcount'] = 0;
        } else {
            $summary['revcount'] = $revcount;
        }

        /**
         * Waiting validation.
         */
        $result2 = $this->db->query('SELECT COUNT(id) AS waitval FROM ' . $this->db->prefix('amreviews_reviews') . " WHERE validated='0'");
        list($waitval) = $this->db->fetchRow($result2);// {

        if ($waitval < 1) {
            $summary['waitval'] = '<span style=\'font-weight: bold;\'>0</span>';
        } else {
            $summary['waitval'] = '<span style=\'font-weight: bold; color: red;\'>' . $waitval . '</span>';
        }

        /**
         * Category count (total)
         */
        $result = $this->db->query('SELECT COUNT(id) AS catcount FROM ' . $this->db->prefix('amreviews_cat') . ' ');
        list($catcount) = $this->db->fetchRow($result);// {

        if (!$result) {
            $summary['catcount'] = 0;
        } else {
            $summary['catcount'] = $catcount;
        }
        unset($result);

        /**
         * Views count (total)
         */
        $result = $this->db->query('SELECT SUM(views) AS views FROM ' . $this->db->prefix('amreviews_reviews') . ' ');
        list($views) = $this->db->fetchRow($result);// {

        if (!$result) {
            $summary['views'] = 0;
        } else {
            $summary['views'] = $views;
        }
        unset($result);

        /**
         * Published (total)
         */
        $result = $this->db->query('SELECT count(id) AS published FROM ' . $this->db->prefix('amreviews_reviews') . " WHERE showme='1' AND validated='1'");
        list($published) = $this->db->fetchRow($result);// {

        if (!$result) {
            $summary['published'] = 0;
        } else {
            $summary['published'] = $published;
        }
        unset($result);

        /**
         * Hidden (total)
         */
        $result = $this->db->query('SELECT count(id) AS hidden FROM ' . $this->db->prefix('amreviews_reviews') . " WHERE showme='0' OR validated='0'");
        list($hidden) = $this->db->fetchRow($result);// {

        if (!$result) {
            $summary['hidden'] = 0;
        } else {
            $summary['hidden'] = $hidden;
        }
        unset($result);

        //print_r($summary);
        return $summary;
    } // end function

    /**
     * @param \XoopsDatabase $db
     * @param string         $id
     */
    public static function getRatings(\XoopsDatabase $db, $id = '0')
    {
        $sql    = ('SELECT * FROM ' . $db->prefix('amreviews_cat') . ' ');
        $result = $db->query($sql);
    }

    //-------------------------------------

    /**
     * Xoopstree thingy
     * getCategoryPath("Top/index caption", $catid, column name, path, separator, table name, cat ID tbl name, cat parent id name)
     * @param  string       $topcap
     * @param  string       $catid
     * @param  string       $columnname
     * @param               $path
     * @param  string       $delim
     * @param               $table
     * @param               $itemID
     * @param               $parID
     * @return mixed|string
     */
    public function getCategoryPath($topcap = 'Top', $catid = '0', $columnname = '', $path, $delim = ':', $table, $itemID, $parID)
    {
        //    require_once(dirname(__DIR__) . '/class/xoopstree.php');
        //$mytree = new \XoopsTree($this->db->prefix("amreview_cat"),"id","cat_parentid");
        $mytree = new \XoopsTree($this->db, $this->db->prefix("$table"), "$itemID", "$parID");

        $catPath = '<a href="index.php">' . $topcap . '</a>&nbsp;:&nbsp;'; // $xoopsModule->getVar('name') - _MD_AMR_NAVBCTOP
        $catPath .= $mytree->getNicePathFromId($catid, $columnname, $path);

        // Replace link/level separator
        $catPath = str_replace(':', $delim, $catPath);

        return $catPath;
    } // end function

    //----------------------------------------------------------------------------//

    /**
     * Get review count for category (not recursive)
     * @param string $catid
     * @return
     */

    /*
        public function getReviewCount($catid = '0')
        {
            $count  = 0;
            $sql    = ('SELECT COUNT(id) AS count FROM ' . $this->db->prefix('amreviews_reviews') . " WHERE catid='" . $catid . "'");
            $result = $this->db->query($sql);

            if ($this->db->getRowsNum($result) > 0) {
                while ($myrow = $this->db->fetchArray($result)) {
                    $count = $myrow['count'];
                }
            }

            return $count;
        } // end function
    */
    //----------------------------------------------------------------------------//

    /**
     * Get count on a field in DB (not recursive)
     * @param string     $aTable
     * @param string     $idField
     * @param string     $checkField
     * @param string     $checkFieldType
     * @param string|int $checkValue
     * @return int
     */
    public function getRowCount($aTable, $idField, $checkField, $checkFieldType, $checkValue)
    {
        $this->db = \XoopsDatabaseFactory::getDatabase();
        if (!isset($aTable) || !isset($idField) || !isset($checkField) || !isset($checkFieldType) || !isset($checkValue)) {
            redirect_header('javascript:history.go(-1)', 1, 'missing field values');
        }

        $count  = 0;
        $sql    = ('SELECT COUNT(' . $idField . ') AS count FROM ' . $this->db->prefix($aTable) . ' WHERE ' . $checkField . "='" . $checkValue . "'");
        $result = $this->db->query($sql);

        if ($this->db->getRowsNum($result) > 0) {
            while ($myrow = $this->db->fetchArray($result)) {
                $count = $myrow['count'];
            }
        }

        return $count;
    } // end function

    /**
     * Increment review views/reads
     * @param $id
     */
    public function incrementViews($id)
    {
        $this->db = \XoopsDatabaseFactory::getDatabase();
        $sql      = ('UPDATE ' . $this->db->prefix('amreview_reviews') . " SET views=views+1 WHERE id='" . (int)$id . "'");
        $this->db->queryF($sql);
    } // end function

    //----------------------------------------------------------------------------//

    /**
     * Get review count for category (not recursive)
     * @param  string $catid
     * @return string
     */
    public function getSubcats($catid = '0')
    {
        $this->db = \XoopsDatabaseFactory::getDatabase();
        $sql      = ('SELECT * FROM ' . $this->db->prefix('amreviews_cat') . " WHERE cat_parentid='" . $catid . "' ORDER BY cat_title ASC");
        $result   = $this->db->query($sql);

        $catlist = '';
        if ($this->db->getRowsNum($result) > 0) {
            while ($myrow = $this->db->fetchArray($result)) {
                //$count = $myrow['count'];
                $catlist .= '<a href="index.php?id=' . $myrow['id'] . '">' . $myrow['cat_title'] . '</a><br>';
            }
        }

        return $catlist;
    } // end function

    //----------------------------------------------------------------------------//
    /**
     * Return user rating for review.
     * @param \XoopsDatabase $db
     * @param string         $id
     * @return mixed
     */
    public static function getUserRating(\XoopsDatabase $db, $id = '0')
    {
        //    global $xoopsDB;

        $result = $db->query('SELECT AVG(rate_rating) AS rate, COUNT(rate_rating) AS votes FROM ' . $db->prefix('amreviews_rate') . " WHERE rate_review_id='" . (int)$id . "'");
        list($rate, $votes) = $db->fetchRow($result);// {

        if (!$result || $rate < 0.01) {
            $summary['rate']  = 0;
            $summary['votes'] = 0;
        } else {
            $summary['rate']  = @number_format($rate, 1); // @number_format($current_rating/$count,2)
            $summary['votes'] = $votes;
        }
        unset($result);

        return $summary;
    } // end function

    //    public function reportDelete($adminLang, $photo, $imageDelete, &$imgerr){

    /**
     * @param $photo
     * @param $imageDelete
     * @param $imgerr
     * @return string
     */
    public static function reportDelete($photo, $imageDelete, &$imgerr)
    {
        $adminLang = '_AM_' . strtoupper(basename(dirname(__DIR__)));

        $ret = '';
        if (@unlink($photo)) {
            $ret = constant($adminLang . $imageDelete) . ' <span style="color: green;">deleted</span><br>';
        } else {
            $ret    = constant($adminLang . $imageDelete) . ' <span style="color: red;">not deleted</span><br>';
            $imgerr = 1;
        }
        return $ret;
    }


    //======================================================================
    //======================================================================

    public static function getHeader()
    {
        global $xoopsConfig, $xoopsModule, $xoTheme;
        $myts = \MyTextSanitizer::getInstance();

        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/jquery-1.3.2.min.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/AudioPlayer.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/jquery-ui-1.7.2.custom.min.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/jquery.wslide.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/jquery.expander.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/js/jquery.pager.js');

            $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/css/smoothness/jquery-ui-1.7.2.custom.css');
            $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/css/tdmspot.css');
        } else {
            $mp_module_header = "
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery-1.3.2.min.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/AudioPlayer.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery.wslide.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery-ui-1.7.2.custom.min.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery.expander.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery.pager.js'></script>
<link rel='stylesheet' type='text/css' href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/smoothness/jquery-ui-1.7.2.custom.css'>
";
            $GLOBALS['xoopsTpl']->assign('xoops_module_header', $mp_module_header);
        }
    }

    /**
     * @param $cat
     */
    public static function getCategorySelect($cat)
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
        //perm
        $gpermHandler = xoops_getHandler('groupperm');

        $catHandler =new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
        $arr        = $catHandler->getAll();
        //$mytree = new \XoopsObjectTree($arr, 'id', 'pid');
        $mytree =new tdmspot\Tree($arr, 'id', 'pid');

        $form = new \XoopsThemeForm('', 'catform', $_SERVER['REQUEST_URI'], 'post', true);
        //$form->setExtra('enctype="multipart/form-data"');
        $tagchannel_select = new \XoopsFormLabel('', $mytree->makeSelBox('pid', 'title', '-', '', '-- ' . _MD_TDMSPOT_CATEGORY, 0, "OnChange='window.document.location=this.options[this.selectedIndex].value;'", 'spot_catview'), 'pid');
        $form->addElement($tagchannel_select);

        //$form->display();
        $form->assign($GLOBALS['xoopsTpl']);
    }

    /**
     * @param $page
     */
    public static function getPageSelect($page)
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
        //perm
        $gpermHandler = xoops_getHandler('groupperm');

        $pageHandler =new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');

        $form = new \XoopsThemeForm('', 'pageform', $_SERVER['REQUEST_URI'], 'post', true);

        $cat_select =new tdmspot\FormSelect('', 'page', $page, 0, false, "OnChange='window.document.location=this.options[this.selectedIndex].value;'");
        $cat_select->addOption(0, '- ' . _MD_TDMSPOT_PAGE);
        $cat_select->addOptionArray($pageHandler->getList());
        $form->addElement($cat_select);

        //$form->display();
        $form->assign($GLOBALS['xoopsTpl']);
    }

    /**
     * @param $cat
     * @param $tris
     * @return string
     */
    public static function getTrisSelect($cat, $tris)
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
        $catHandler  = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
        $option      = ['title' => _MD_TDMSPOT_TRITITLE, 'indate' => _MD_TDMSPOT_TRIDATE, 'counts' => _MD_TDMSPOT_TRICOUNTS, 'hits' => _MD_TDMSPOT_TRIHITS, 'comments' => _MD_TDMSPOT_TRICOMMENT];
        $select_tris = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
        //trouve le nom de la cat
        $cat = $catHandler->get($cat);
        foreach ($option as $key => $value) {
            $select      = ($tris == $key) ? 'selected="selected"' : false;
            $cat_link    = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'), $start, $limit, $key);
            $select_tris .= '<option ' . $select . ' value="' . $cat_link . '">' . $value . '</option>';
        }
        $select_tris .= '</select>';

        return $select_tris;
    }

    /**
     * @param $cat
     * @param $limit
     * @return string
     */
    public static function getViewSelect($cat, $limit)
    {
        global $start, $tris, $xoopsModule, $xoopsModuleConfig;
        $catHandler  = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
        $option      = ['10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '100' => 100];
        $select_view = '<select name="limit" onchange="window.document.location=this.options[this.selectedIndex].value;">';
        //trouve le nom de la cat
        $cat = $catHandler->get($cat);
        foreach (array_keys($option) as $i) {
            $select      = ($limit == $option[$i]) ? 'selected="selected"' : false;
            $view_link   = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'), $start, $option[$i], $tris);
            $select_view .= '<option ' . $select . ' value="' . $view_link . '">' . $option[$i] . '</option>';
        }
        $select_view .= '</select>';

        return $select_view;
    }

    /**
     * @param $img_src
     * @param $dst_w
     * @param $dst_h
     * @return array
     */
    public static function getRedImage($img_src, $dst_w, $dst_h)
    {
        // Lit les dimensions de l'image
        $redim = [];
        $size  = @getimagesize($img_src);
        $src_w = $size[0];
        $src_h = $size[1];
        // Teste les dimensions tenant dans la zone
        if ($src_w > 0) {
            $test_h = round(($dst_w / $src_w) * $src_h);
        } else {
            $test_h = 0;
        }
        if ($src_h > 0) {
            $test_w = round(($dst_h / $src_h) * $src_w);
        } else {
            $test_w = 0;
        }
        // Si Height final non pr�cis� (0)
        if (!$dst_h) {
            $dst_h = $test_h;
        } // Sinon si Width final non pr�cis� (0)
        elseif (!$dst_w) {
            $dst_w = $test_w;
        } // Sinon teste quel redimensionnement tient dans la zone
        elseif ($test_h > $dst_h) {
            $dst_w = $test_w;
        } else {
            $dst_h = $test_h;
        }
        $redim['dst_w'] = $dst_w;
        $redim['dst_h'] = $dst_h;

        // Affiche les dimensions optimales
        return $redim;
    }

    /**
     * @param $text
     * @param $form_sort
     * @param $url
     * @return string
     */
    public static function switchSelect($text, $form_sort, $url)
    {
        global $start, $order, $sort, $xoopsModule, $xoopsModuleConfig;

        $select_view = '<form name="form_switch" id="form_switch" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><span style="font-weight: bold;">' . $text . '</span>';
        //$sorts =  $sort ==  'asc' ? 'desc' : 'asc';
        if ($form_sort == $sort) {
            $sel1 = 'asc' === $order ? 'selasc.png' : 'asc.png';
            $sel2 = 'desc' === $order ? 'seldesc.png' : 'desc.png';
        } else {
            $sel1 = 'asc.png';
            $sel2 = 'desc.png';
        }
        $select_view .= '  <a href="' . $_SERVER['PHP_SELF'] . '?start=' . $start . '&sort=' . $form_sort . '&order=asc"><img src="' . $url . '/decos/' . $sel1 . '" title="ASC" alt="ASC"></a>';
        $select_view .= '<a href="' . $_SERVER['PHP_SELF'] . '?start=' . $start . '&sort=' . $form_sort . '&order=desc"><img src="' . $url . '/decos/' . $sel2 . '" title="DESC" alt="DESC"></a>';
        $select_view .= '</form>';

        return $select_view;
    }

    /**
     * @param $content
     * @return string
     */
    public static function getKeywords($content)
    {
        $myts = \MyTextSanitizer::getInstance();

        $tmp = [];
        // Search for the "Minimum keyword length"
        $configHandler     = xoops_getHandler('config');
        $xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $limit             = $xoopsConfigSearch['keyword_min'];

        $myts            = \MyTextSanitizer::getInstance();
        $content         = str_replace('<br>', ' ', $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = strip_tags($content);
        $content         = strtolower($content);
        $search_pattern  = ['&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*'];
        $replace_pattern = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $content         = str_replace($search_pattern, $replace_pattern, $content);
        $keywords        = explode(' ', $content);
        $keywords        = array_unique($keywords);
        foreach ($keywords as $keyword) {
            if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }

        if (count($tmp) > 0) {
            $tmp   = implode(',', $tmp);
            $title = $myts->displayTarea((strlen($tmp) > 100 ? substr($tmp, 0, 100) : $tmp));

            return $title;
        } else {
            return '';
        }
    }

    /**
     * @param $content
     * @return mixed
     */
    public static function tdmspot_desc($content)
    {
        $myts = \MyTextSanitizer::getInstance();

        $title = $myts->displayTarea((strlen($content) > 128 ? substr($content, 0, 128) : $content));

        return $title;
    }

    /**
     * @param $size
     * @return string
     */
    public static function getPrettySize($size)
    {
        $mb = 1024 * 1024;
        if ($size > $mb) {
            $mysize = sprintf('%01.2f', $size / $mb) . ' Mo';
        } elseif ($size >= 1024) {
            $mysize = sprintf('%01.2f', $size / 1024) . ' Ko';
        } else {
            $mysize = $size . ' oc';
        }

        return $mysize;
    }

}
