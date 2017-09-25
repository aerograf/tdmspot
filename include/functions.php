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

function tdmspot_header()
{
    global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
    $myts = MyTextSanitizer::getInstance();

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
        $xoopsTpl->assign('xoops_module_header', $mp_module_header);
    }
}

/**
 * @param $cat
 */
function tdmspot_catselect($cat)
{
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    global $xoopsTpl, $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
    //perm
    $gpermHandler = xoops_getHandler('groupperm');

    $catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
    $arr = $catHandler->getall();
    //$mytree = new XoopsObjectTree($arr, 'id', 'pid');
    $mytree = new TdmObjectTree($arr, 'id', 'pid');

    $form = new XoopsThemeForm('', 'catform', $_SERVER['REQUEST_URI'], 'post', true);
    //$form->setExtra('enctype="multipart/form-data"');
    $tagchannel_select = new XoopsFormLabel(
        '',
        $mytree->makeSelBox('pid', 'title', '-', '', '-- ' . _MD_TDMSPOT_CATEGORY, 0, "OnChange='window.document.location=this.options[this.selectedIndex].value;'", 'spot_catview'),
        'pid'
    );
    $form->addElement($tagchannel_select);

    //$form->display();
    $form->assign($xoopsTpl);
}

/**
 * @param $page
 */
function tdmspot_pageselect($page)
{
    global $xoopsTpl, $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
    //perm
    $gpermHandler = xoops_getHandler('groupperm');

    $pageHandler = xoops_getModuleHandler('tdmspot_page', 'tdmspot');

    $form = new XoopsThemeForm('', 'pageform', $_SERVER['REQUEST_URI'], 'post', true);

    $cat_select = new TDMFormSelect('', 'page', $page, 0, false, "OnChange='window.document.location=this.options[this.selectedIndex].value;'");
    $cat_select->addOption(0, '- ' . _MD_TDMSPOT_PAGE);
    $cat_select->addOptionArray($pageHandler->getList());
    $form->addElement($cat_select);

    //$form->display();
    $form->assign($xoopsTpl);
}

/**
 * @param $cat
 * @param $tris
 * @return string
 */
function tdmspot_trisselect($cat, $tris)
{
    global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
    $catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
    $option = ['title' => _MD_TDMSPOT_TRITITLE, 'indate' => _MD_TDMSPOT_TRIDATE, 'counts' => _MD_TDMSPOT_TRICOUNTS, 'hits' => _MD_TDMSPOT_TRIHITS, 'comments' => _MD_TDMSPOT_TRICOMMENT];
    $select_tris = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
    //trouve le nom de la cat
    $cat = $catHandler->get($cat);
    foreach ($option as $key => $value) {
        $select = ($tris == $key) ? 'selected="selected"' : false;
        $cat_link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'), $start, $limit, $key);
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
function tdmspot_viewselect($cat, $limit)
{
    global $start, $tris, $xoopsModule, $xoopsModuleConfig;
    $catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
    $option = ['10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '100' => 100];
    $select_view = '<select name="limit" onchange="window.document.location=this.options[this.selectedIndex].value;">';
    //trouve le nom de la cat
    $cat = $catHandler->get($cat);
    foreach (array_keys($option) as $i) {
        $select = ($limit == $option[$i]) ? 'selected="selected"' : false;
        $view_link = tdmspot_generateSeoUrl($xoopsModuleConfig['tdmspot_seo_cat'], $cat->getVar('id'), $cat->getVar('title'), $start, $option[$i], $tris);
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
function tdmspot_redimage($img_src, $dst_w, $dst_h)
{
    // Lit les dimensions de l'image
    $redim = [];
    $size = @getimagesize($img_src);
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
function tdm_switchselect($text, $form_sort, $url)
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
function tdmspot_keywords($content)
{
    $myts = MyTextSanitizer::getInstance();

    $tmp = [];
    // Search for the "Minimum keyword length"
    $configHandler = xoops_getHandler('config');
    $xoopsConfigSearch =& $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
    $limit = $xoopsConfigSearch['keyword_min'];

    $myts = MyTextSanitizer::getInstance();
    $content = str_replace('<br>', ' ', $content);
    $content = $myts->undoHtmlSpecialChars($content);
    $content = strip_tags($content);
    $content = strtolower($content);
    $search_pattern = ['&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*'];
    $replace_pattern = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
    $content = str_replace($search_pattern, $replace_pattern, $content);
    $keywords = explode(' ', $content);
    $keywords = array_unique($keywords);
    foreach ($keywords as $keyword) {
        if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
            $tmp[] = $keyword;
        }
    }

    if (count($tmp) > 0) {
        $tmp = implode(',', $tmp);
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
function tdmspot_desc($content)
{
    $myts = MyTextSanitizer::getInstance();

    $title = $myts->displayTarea((strlen($content) > 128 ? substr($content, 0, 128) : $content));

    return $title;
}

/**
 * @param $size
 * @return string
 */
function tdmspot_PrettySize($size)
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

/**
 * admin menu
 * @param int $currentoption
 * @param string $breadcrumb
 */
function Adminmenu($currentoption = 0, $breadcrumb = '')
{
    /* Nice buttons styles */
    echo "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/tdmspot/assets/images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/tdmspot/assets/images/decos/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/tdmspot/assets/images/decos/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
        /* Commented Backslash Hack hides rule from IE5-Mac \*/
        #buttonbar a span {float:none;}
        /* End IE5-Mac hack */
        #buttonbar a:hover span { color:#333; }
        #buttonbar #current a { background-position:0 -150px; border-width:0; }
        #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
        #buttonbar a:hover { background-position:0% -150px; }
        #buttonbar a:hover span { background-position:100% -150px; }
        </style>
    ";

    global $xoopsModule, $xoopsConfig;
    $myts = MyTextSanitizer::getInstance();

    $tblColors = [];
    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
    $tblColors[$currentoption] = 'current';
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/english/modinfo.php';
    }

    echo "<div id='buttontop'>";
    echo '<table style="width: 100%; padding: 0; " cellspacing="0"><tr>';
    //echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SF_OPTS . "</a> | <a href=\"import.php\">" . _AM_SF_IMPORT . "</a> | <a href=\"../index.php\">" . _AM_SF_GOMOD . "</a> | <a href=\"../help/index.html\" target=\"_blank\">" . _AM_SF_HELP . "</a> | <a href=\"about.php\">" . _AM_SF_ABOUT . "</a></td>";
    echo "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>
    <a href='" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/index.php'>" . $xoopsModule->getVar('dirname') . '</a>
    </td>';
    echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($xoopsModule->name()) . '  </b> ' . $breadcrumb . ' </td>';
    echo '</tr></table>';
    echo '</div>';

    echo "<div id='buttonbar'>";
    echo '<ul>';
    echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php"><span>' . _MI_TDMSPOT_INDEX . '</span></a></li>';
    echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/page.php"><span>' . _MI_TDMSPOT_PAGE . '</span></a></li>';
    echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/block.php"><span>' . _MI_TDMSPOT_BLOCK . '</span></a></li>';
    echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/plug.php"><span>' . _MI_TDMSPOT_PLUG . '</span></a></li>';
    echo "<li id='" . $tblColors[4] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/permissions.php"><span>' . _MI_TDMSPOT_PERMISSIONS . '</span></a></li>';
    echo "<li id='" . $tblColors[5] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/about.php"><span>' . _MI_TDMSPOT_ABOUT . '</span></a></li>';
    echo "<li id='" . $tblColors[6] . "'><a href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "'><span>" . _MI_TDMSPOT_PREF . '</span></a></li>';
    echo '</ul></div>&nbsp;';
}
