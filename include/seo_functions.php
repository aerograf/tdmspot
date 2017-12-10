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

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @param string $title
 * @param bool   $withExt
 * @return mixed|string
 */
function tdmspot_seo_title($title = '', $withExt = true)
{
    /**
     * if XOOPS ML is present, let's sanitize the title with the current language
     */
    $myts = \MyTextSanitizer::getInstance();
    if (method_exists($myts, 'formatForML')) {
        $title = $myts->formatForML($title);
    }

    // Transformation de la chaine en minuscule
    // Codage de la chaine afin d'�viter les erreurs 500 en cas de caract�res impr�vus
    $title = rawurlencode(strtolower($title));

    // Transformation des ponctuations
    //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
    $pattern = [
        '/%09/',
        '/%20/',
        '/%21/',
        '/%22/',
        '/%23/',
        '/%25/',
        '/%26/',
        '/%27/',
        '/%28/',
        '/%29/',
        '/%2C/',
        '/%2F/',
        '/%3A/',
        '/%3B/',
        '/%3C/',
        '/%3D/',
        '/%3E/',
        '/%3F/',
        '/%40/',
        '/%5B/',
        '/%5C/',
        '/%5D/',
        '/%5E/',
        '/%7B/',
        '/%7C/',
        '/%7D/',
        '/%7E/',
        "/\./"
    ];
    $rep_pat = ['-', '-', '', '', '', '-100', '', '-', '', '', '', '-', '', '', '', '-', '', '', '-at-', '', '-', '', '-', '', '-', '', '-', ''];
    $title = preg_replace($pattern, $rep_pat, $title);

    // Transformation des caract�res accentu�s
    //                  °        è        é        ê        ë        ç        à        â        ä        î        ï        ù        ü        û        ô        ö
    $pattern = ['/%B0/', '/%E8/', '/%E9/', '/%EA/', '/%EB/', '/%E7/', '/%E0/', '/%E2/', '/%E4/', '/%EE/', '/%EF/', '/%F9/', '/%FC/', '/%FB/', '/%F4/', '/%F6/'];
    $rep_pat = ['-', 'e', 'e', 'e', 'e', 'c', 'a', 'a', 'a', 'i', 'i', 'u', 'u', 'u', 'o', 'o'];
    $title = preg_replace($pattern, $rep_pat, $title);

    if (count($title) > 0) {
        return $title;
    }

    return '';
}

/**
 * @param        $op
 * @param        $id
 * @param string $short_url
 * @param null   $start
 * @param bool   $limit
 * @param bool   $tris
 * @return string
 */
function tdmspot_generateSeoUrl($op, $id, $short_url = '', $start = null, $limit = false, $tris = false)
{
    //$publisher =& PublisherPublisher::getInstance();

    $moduleHandler = xoops_getHandler('module');
    $xoopsModule = $moduleHandler->getByDirname('tdmspot');

    if (!isset($xoopsModuleConfig)) {
        $configHandler = xoops_getHandler('config');
        $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
    }

    if (1 == $xoopsModuleConfig['tdmspot_seo']) {
        if (!empty($short_url)) {
            $short_url = tdmspot_seo_title($short_url) . '.html';
        }

        if (1 == $xoopsModuleConfig['tdmspot_seo']) {
            // generate SEO url using htaccess
            $url = '';
            if (!empty($id)) {
                $url .= "/${id}";
            }
            if (null !== $start) {
                $url .= "/${start}";
            }
            if (!empty($limit)) {
                $url .= "/${limit}";
            }
            if (!empty($tris)) {
                $url .= "/${tris}";
            }

            return XOOPS_URL . '/' . $xoopsModuleConfig['tdmspot_seo_title'] . "/${op}$url/${short_url}";
        } else {
            die('Unknown SEO method.');
        }
    } else {
        // generate classic url

        //seo Map
        $seoMap = [
            $xoopsModuleConfig['tdmspot_seo_cat'] => 'viewcat.php',
            $xoopsModuleConfig['tdmspot_seo_item'] => 'item.php',
            'print' => 'print.php',
            'pdf' => 'pdf.php',
            'submit' => 'submit.php',
            'rss' => 'rss.php',
            'download' => 'download.php',
            'index' => 'index.php'
        ];

        $url = '';
        $id_item = '';
        $id_cat = '';
        if (!empty($id)) {
            $id_item = "itemid=${id}";
        }
        if (!empty($id)) {
            $id_cat = "LT=${id}";
        }
        if (null !== $start) {
            $url .= "&start=${start}";
        }
        if (!empty($limit)) {
            $url .= "&limit=${limit}";
        }
        if (!empty($tris)) {
            $url .= "&tris=${tris}";
        }

        switch ($op) {
            case $xoopsModuleConfig['tdmspot_seo_cat']:
                return XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/' . $seoMap[$op] . '?' . @$id_cat . "${url}";
            case $xoopsModuleConfig['tdmspot_seo_item']:
            case 'print':
            case 'pdf':
            case 'submit':
            case 'rss':
            case 'download':
            case 'index':
                return XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/' . $seoMap[$op] . '?' . @$id_item;
            default:
                die('Unknown SEO operation.');
        }
    }
}
