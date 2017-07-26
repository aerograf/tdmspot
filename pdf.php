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

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/fpdf/fpdf.php';

global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

$myts = &MyTextSanitizer:: getInstance(); // MyTextSanitizer object

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

if (!isset($_REQUEST['itemid'])) {
    redirect_header('index.php', 2, _MD_TDMSPOT_NOPERM);
    exit();
}

switch ($option) {

    case 'default':
    default:
        //load class
        $itemHandler = xoops_getModuleHandler('tdmspot_item', 'tdmspot');
        //perm
        $gpermHandler = xoops_getHandler('groupperm');

        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $user_uid = $xoopsUser->getVar('uid');
            $user_name = $xoopsUser->getVar('name');
            $user_uname = $xoopsUser->getVar('uname');
            $user_email = $xoopsUser->getVar('email');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $user_uid = 0;
            $user_name = XOOPS_GROUP_ANONYMOUS;
            $user_uname = XOOPS_GROUP_ANONYMOUS;
            $user_email = XOOPS_GROUP_ANONYMOUS;
        }

        //si pas le droit d'exporter
        if (!$gpermHandler->checkRight('spot_view', 16, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
            exit();
        }

        $file =& $itemHandler->get($_REQUEST['itemid']);

        $newsletter_title = utf8_decode(Chars($file->getVar('title')));
        //text
        $body = str_replace('{X_BREAK}', '<br>', $file->getVar('text'));
        $body = str_replace('{X_NAME}', $user_name, $body);
        $body = str_replace('{X_UNAME}', $user_uname, $body);
        $body = str_replace('{X_UEMAIL}', $user_email, $body);
        $body = str_replace('{X_ADMINMAIL}', $xoopsConfig['adminmail'], $body);
        $body = str_replace('{X_SITENAME}', $xoopsConfig['sitename'], $body);
        $body = str_replace('{X_SITEURL}', XOOPS_URL, $body);

        $newsletter_text = utf8_decode(Chars($body));
        $newsletter_indate = formatTimestamp($file->getVar('indate'), 'm');
        $color = '#CCCCCC';
        $pdf = new FPDF();
        $pdf->AddPage();
        //titre
        $pdf->SetFont('Arial', 'B', 15);
        $w = $pdf->GetStringWidth($xoopsConfig['sitename']) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell($w, 8, Chars($xoopsConfig['sitename']), 0, 1, 'C', true);
        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'B', 10);
        $w = $pdf->GetStringWidth($xoopsConfig['slogan']) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->SetLineWidth(0.2);
        $pdf->Cell($w, 8, Chars($xoopsConfig['slogan']), 0, 1, 'C', true);
        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 15);
        $w = $pdf->GetStringWidth($newsletter_title) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->SetDrawColor(204, 204, 204);
        $pdf->SetFillColor($color['r'], $color['v'], $color['b']);
        $pdf->SetLineWidth(0.2);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($w, 8, Chars($newsletter_title), 1, 1, 'C', true);
        $pdf->Ln(6);
        //Sauvegarde de l'ordonn�e

        // date
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(50, 8, $newsletter_indate, 1, 1, 'L', true);
        $pdf->Ln(6);

        //content
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(239, 239, 239);
        $pdf->MultiCell(190, 10, $newsletter_text, 1, 1, 'C', true);
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 10);
        $w = $pdf->GetStringWidth(XOOPS_URL) + 6;
        $pdf->Cell($w, 8, Chars(XOOPS_URL), 0, 0, 'C', true);
        $pdf->Output();

        break;
}
//

/**
 * @param $text
 * @return mixed
 */
function Chars($text)
{
    $myts = &MyTextSanitizer:: getInstance();

    return preg_replace(array('/&#039;/i', '/&#233;/i', '/&#232;/i', '/&#224;/i', '/&quot;/i', '/<br \>/i', '/&agrave;/i', '/&#8364;/i'), array("'", '�', '�', '�', '"', "\n", '�', '�'), $text);
}
