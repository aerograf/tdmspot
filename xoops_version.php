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

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

$moduleDirName = basename(__DIR__);

// ------------------- Informations ------------------- //
$modversion = [
    'version'             => 2.00,
    'module_status'       => 'Alpha 1',
    'release_date'        => '2017/01/23',
    'name'                => _MI_TDMSPOT_NAME,
    'description'         => _MI_TDMSPOT_DESC,
    'official'            => 0,
    //1 indicates official XOOPS module supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'TDM Team',
    'credits'             => 'XOOPS Development Team',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    // ------------------- Folders & Files -------------------
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog.txt",
    //
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    // images
    'image'               => 'assets/images/logoModule.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb/viewforum.php?forum=28/',
    'support_name'        => 'Support Forum',
    'submit_bug'          => 'https://github.com/XoopsModules25x/' . $moduleDirName . '/issues',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // ------------------- Min Requirements -------------------
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.9',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    // ------------------- Admin Menu -------------------
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Main Menu -------------------
    'hasMain'             => 1,
    'sub'                 => [
        [
            'name' => _MI_TDMSPOT_SEARCH,
            'url'  => 'index.php'
        ],
    ],

    // ------------------- Install/Update -------------------
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    //  'onUninstall'         => 'include/onuninstall.php',
    // -------------------  PayPal ---------------------------
    'paypal'              => [
        'business'      => 'foundation@xoops.org',
        'item_name'     => 'Donation : ' . _MI_TDMSPOT_NAME,
        'amount'        => 0,
        'currency_code' => 'USD'
    ],
    // ------------------- Search ---------------------------
    'hasSearch'           => 1,
    'search'              => [
        'file' => 'include/search.inc.php',
        'func' => $moduleDirName . '_' . 'search'
    ],
    // ------------------- Comments -------------------------
    'hasComments'         => 1,
    'comments'            => [
        'pageName'     => 'item.php',
        'itemName'     => 'itemid',
        'callbackFile' => 'include/comment_functions.php',
        'callback'     => [
            'approve' => $moduleDirName . '_' . 'comments_approve',
            'update'  => $moduleDirName . '_' . 'comments_update'
        ],
    ],
    // ------------------- Mysql -----------------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
    'tables'              => [
        $moduleDirName . '_' . 'newblocks',
        $moduleDirName . '_' . 'page',
        $moduleDirName . '_' . 'cat',
        $moduleDirName . '_' . 'item',
        $moduleDirName . '_' . 'vote',
    ],
];

// ------------------- Templates ------------------- //
$modversion['templates'] = [
    ['file' => 'spot_index.tpl', 'description' => 'index'],
    ['file' => 'spot_top.tpl', 'description' => ''],
    ['file' => 'spot_bottom.tpl', 'description' => ''],
    ['file' => 'spot_item.tpl', 'description' => 'Item'],
    ['file' => 'spot_viewcat.tpl', 'description' => 'viewcat'],
    ['file' => 'spot_viewitem.tpl', 'description' => 'viewitem'],
    ['file' => 'spot_rss.tpl', 'description' => 'rss'],
];
//config

$modversion['config'][] = [
    'name'        => 'tdmspot_seo',
    'title'       => '_MI_TDMSPOT_SEO',
    'description' => '_MI_TDMSPOT_SEO_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_seo_title',
    'title'       => '_MI_TDMSPOT_SEO_TITLE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'publication',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_seo_cat',
    'title'       => '_MI_TDMSPOT_SEO_CAT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'categorie',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_seo_item',
    'title'       => '_MI_TDMSPOT_SEO_ITEM',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'article',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_mimemax',
    'title'       => '_MI_TDMSPOT_MIMEMAX',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10485760',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_mimetype',
    'title'       => '_MI_TDMSPOT_MIMETYPE',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'options'     => 'application/x-compress|application/x-compressed|application/x-compressed|application/x-zip-compressed|application/zip|multipart/x-zip|image/png|text/xml|application/xml|application/msword|audio/wav|audio/x-wav|application/gnutar|application/x-compressed|application/x-tar|application/x-shockwave-flash|application/vnd.ms-powerpoint|application/mspowerpoint|image/png|application/pro_eng|video/mpeg|audio/mpeg3|audio/x-mpeg-3|video/mpeg|video/x-mpeg|video/quicktime|image/jpeg|image/pjpeg|image/jpeg|image/pjpeg|application/x-gzip|multipart/x-gzip|image/bmp|image/x-windows-bmp|application/x-troff-msvideo|video/avi|video/msvideo|video/x-msvideo|',
];

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$modversion['config'][] = [
    'name'        => 'tdmspot_editor',
    'title'       => '_MI_TDMSPOT_EDITOR',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmltextarea',
    'options'     => XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . '/class/xoopseditor'),
    'category'    => 'global',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_name',
    'title'       => '_MI_TDMSPOT_NAMES',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_present',
    'title'       => '_MI_TDMSPOT_PRESENT',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_nextprev',
    'title'       => '_MI_TDMSPOT_NEXTPREV',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_img',
    'title'       => '_MI_TDMSPOT_IMG',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
];

$modversion['config'][] = [
    //version 1.5
    'name'        => 'tdmspot_cat_display',
    'title'       => '_MI_TDMSPOT_CAT_DISPLAY',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'texte',
    'options'     => [
        _MI_TDMSPOT_CAT_DISPLAY_NONE    => 'none',
        _MI_TDMSPOT_CAT_DISPLAY_SUB     => 'sub',
        _MI_TDMSPOT_CAT_DISPLAY_SUBIMG  => 'subimg',
        _MI_TDMSPOT_CAT_DISPLAY_TEXT    => 'text',
        _MI_TDMSPOT_CAT_DISPLAY_TEXTIMG => 'textimg',
        _MI_TDMSPOT_CAT_DISPLAY_IMG     => 'img'
    ],
];

$modversion['config'][] = [
    'name'        => 'tdmspot_cat_cel',
    'title'       => '_MI_TDMSPOT_CAT_CEL',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 2,
];

$modversion['config'][] = [
    'name'        => 'tdmspot_cat_souscel',
    'title'       => '_MI_TDMSPOT_CAT_SOUSCEL',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];

$modversion['config'][] = [
    //
    'name'        => 'tdmspot_cat_width',
    'title'       => '_MI_TDMSPOT_CAT_WIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '80',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_cat_height',
    'title'       => '_MI_TDMSPOT_CAT_HEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '80',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_page',
    'title'       => '_MI_TDMSPOT_PAGES',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_blindate',
    'title'       => '_MI_TDMSPOT_BLINDATE',
    'description' => '_MI_TDMSPOT_FORNULL',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_blcounts',
    'title'       => '_MI_TDMSPOT_BLCOUNTS',
    'description' => '_MI_TDMSPOT_FORNULL',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_blhits',
    'title'       => '_MI_TDMSPOT_BLHITS',
    'description' => '_MI_TDMSPOT_FORNULL',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_blsimil',
    'title'       => '_MI_TDMSPOT_BLSIMIL',
    'description' => '_MI_TDMSPOT_FORNULL',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_blposter',
    'title'       => '_MI_TDMSPOT_BLPOSTER',
    'description' => '_MI_TDMSPOT_FORNULL',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_bltitle',
    'title'       => '_MI_TDMSPOT_BLTITLE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '150',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_description',
    'title'       => '_MI_TDMSPOT_DESCRIPTION',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];

$modversion['config'][] = [
    'name'        => 'tdmspot_keywords',
    'title'       => '_MI_TDMSPOT_KEYWORDS',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => '',
];

// Blocks
//*************************************************************

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_TITLE,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'title|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_DATE,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'date|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_HITS,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'hits|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_COUNTS,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'counts|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_COMMENT,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'comments|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'tdmspot_minitable.php',
    'name'        => _MI_TDMSPOT_BLOCK_RAND,
    'description' => '',
    'show_func'   => 'b_tdmspot',
    'edit_func'   => 'b_tdmspot_edit',
    'options'     => 'rand|5|25|0',
    'template'    => 'tdmspot_minitable.tpl',
];
