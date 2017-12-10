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

require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once __DIR__ . '/../class/Utility.php';

xoops_cp_header();

//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//Adminmenu(3, _AM_TDMSPOT_MANAGE_PLUG);
//} else {
//require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//loadModuleAdminMenu (3, _AM_TDMSPOT_MANAGE_PLUG);
//}

$pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
$blockHandler = new tdmspot\NewblocksHandler(); //xoops_getModuleHandler('tdmspot_newblocks', 'tdmspot');

$myts = \MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$tdmspot_style = isset($_REQUEST['tdmspot_style']) ? $_REQUEST['tdmspot_style'] : 'cupertino';

echo "<link rel='stylesheet' type='text/css' href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/css/' . $tdmspot_style . "/jquery-ui-1.7.2.custom.css'>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery-1.3.2.min.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery-ui-1.7.2.custom.min.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/js/jquery.wslide.js'></script>
";

//menu
//echo '<div class="CPbigTitle" style="background-image: url(../assets/images/decos/plug.png); background-repeat: no-repeat; background-position: left; padding-left: 50px;"><strong>' . _AM_TDMSPOT_MANAGE_PLUG . '</strong>';

$currentFile = basename(__FILE__);
$indexAdmin = new ModuleAdmin();
$adminObject->displayNavigation($currentFile);

echo '</div><br>';

switch ($op) {

    //sauv
    case 'save':

        //<{xoSpot page=3-4 default=4 display=1 style=none}>
        $plug = '<{xoSpot ';

        if (isset($_REQUEST['default']) && $_REQUEST['default'] > 0) {
            $plug .= ' default=' . $_REQUEST['default'];
        }

        if ($_REQUEST['page'] && !in_array(0, $_REQUEST['page'])) {
            $plug .= ' page=';
            $paging = '';
            foreach ($_REQUEST['page'] as $page) {
                $paging .= $page . '-';
            }
            $plug .= substr($paging, 0, -1);
        }

        if (isset($_REQUEST['display']) && $_REQUEST['display'] > 0) {
            $plug .= ' display=' . $_REQUEST['display'];

            echo "<table class='outer'><tr><th align='center'>" . _AM_TDMSPOT_PLUG_DESC . '</th></tr><tr>';

            switch ($_REQUEST['display']) {

                case 1:

                    echo "<script type='text/javascript'>
        var $tdmspot = jQuery.noConflict();
        $tdmspot(document).ready( function() {
        $tdmspot('#tabs').tabs();
        });
        </script>";

                    echo "<td align='center' class='odd'><div id='tabs'><ul><li style='list-style-type: none;'><a href='javascript:;' title='Test1'>Test1</a></li><li style='list-style-type: none;'><a href='javascript:;' title='Test2'>Test2</a></li><li style='list-style-type: none;'><a href='javascript:;' title='Test3'>Test3</a></li></ul></div></td>";

                    break;

                case 2:

                    echo "<td align='center' class='odd'><select class='class='ui-state-default' id='test' name='various' >
    <option value='Test1'>Test1</option>
    <option value='Test2'>Test2</option>
    <option value='Test3'>Test3</option>
    </select></td>";

                    break;

                case 3:

                    echo "<td align='center' class='odd'><div style='padding: 5px;' class='ui-state-default'>Test1 | Test2 | Test3</div></td>";

                    break;

                case 4:

                    echo '<script type="text/javascript">
    $(function() {
        $("#accordion").accordion();
    });
    </script>';

                    echo "<td align='center' class='odd'><div id='accordion'><h3><a href='#'>Test1</a></h3></a><div>Test1</div><h3><a href='#'>Test2</a></h3><div>Test2</div><h3><a href='#'>Test3</a></h3><div>Test3</div></div></td>";

                    break;

                case 5:

                    echo '<script type="text/javascript">
    $(function() {
    $("#wslide").wslide({
    width: 250,
    height: 150,
    pos: 4,
    horiz: true
});
    });
    </script>';

                    echo "<td align='center' class='odd'><div id='wslide'><ul><li style='list-style-type: none;'><a href='javascript:;' title='Test1'>Test1</a></li><li style='list-style-type: none;'><a href='javascript:;' title='Test2'>Test2</a></li><li style='list-style-type: none;'><a href='javascript:;' title='Test3'>Test3</a></li></ul></div></td>";

                    $plug .= ' width=250 height=150';

                    break;
            }

            echo '</tr></table><br><br>';
        }

        if (isset($tdmspot_style)) {
            $plug .= ' style=' . $tdmspot_style;
        }

        $plug .= '}>';

        echo "<table class='outer'><tr><th align='center'>" . _AM_TDMSPOT_PLUG_DESC . "</th></tr><tr><td align='center' class='odd'><b>" . $plug . '</b></td></tr></table><br>';

        $obj = $pageHandler->create();
        $form = $obj->getPlug();
        $form->display();
        break;

    case 'list':
    default:
        // Affichage du formulaire de cr?ation de cat?gories
        $obj = $pageHandler->create();
        $form = $obj->getPlug();
        $form->display();
        break;

}

xoops_cp_footer();
