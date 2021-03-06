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

use XoopsFormButton;
use XoopsFormCheckBox;
use XoopsFormElementTray;
use XoopsFormFile;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsFormRadioYN;
use XoopsFormSelect;
use XoopsFormText;
use XoopsLists;
use XoopsObjectTree;
use Xoopsmodules\tdmspot;

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * Class Category
 */
class Category extends \XoopsObject
{
    // constructor
    /**
     * TdmspotCat constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('date', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('text', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('img', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('display', XOBJ_DTYPE_INT, null, false, 1);
    }

    /**
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = sprintf($this->isNew() ? _AM_TDMSPOT_ADD : _AM_TDMSPOT_EDITER);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormText(_AM_TDMSPOT_TITLE, 'title', 80, 255, $this->getVar('title')));

        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new \XoopsFormHidden('id', $this->getVar('id')));
        }

        //categorie
        $catHandler = new tdmspot\CategoryHandler(); //xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
        $arr = $catHandler->getAll();
        $mytree = new \XoopsObjectTree($arr, 'id', 'pid');

//        $form->addElement(new \XoopsFormLabel(_AM_TDMSPOT_PARENT, $mytree->makeSelBox('pid', 'title', '-', $this->getVar('pid'), true)));



        if (tdmspot\Utility::checkVerXoops($xoopsModule, '2.5.9')) {
            $catSelect = $mytree->makeSelectElement('pid', 'title', '--', $this->getVar('pid'), true, 0, '', _AM_TDMSPOT_PARENT);
            $form->addElement($catSelect);
        } else {
            $form->addElement(new \XoopsFormLabel(_AM_TDMSPOT_PARENT, $mytree->makeSelBox('pid', 'title', '-', $this->getVar('pid'), true)));
        }



        //editor
        //   $editor_configs=array();
        //  $editor_configs["name"] ="text'";
        //  $editor_configs["value"] = $this->getVar('text', 'e');
        //  $editor_configs["rows"] = 20;
        //  $editor_configs["cols"] = 80;
        //  $editor_configs["width"] = "100%";
        //  $editor_configs["height"] = "400px";
        //  $editor_configs["editor"] = $xoopsModuleConfig["tdmspot_editor"];
        //  $form->addElement( new \XoopsFormEditor(_AM_TDMSPOT_TEXT, "cat_text", $editor_configs), false );

        //upload
        $img = $this->getVar('img') ?: 'blank.png';
        $uploadirectory = TDMSPOT_CAT_IMAGE_PATH . '/';
        $imgtray = new \XoopsFormElementTray(_AM_TDMSPOT_IMG, '<br>');
        $imgpath = sprintf(_AM_TDMSPOT_PATH, $uploadirectory);
        $imageselect = new \XoopsFormSelect($imgpath, 'img', $img);
        $topics_array = XoopsLists:: getImgListAsArray($uploadirectory);
        foreach ($topics_array as $image) {
            $imageselect->addOption("$image", $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"img\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $img . "' name='image3' id='image3' alt=''>"));

        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(_AM_TDMSPOT_UPLOAD, 'attachedfile', $xoopsModuleConfig['tdmspot_mimemax']), false);
        $fileseltray->addElement(new \XoopsFormLabel(''), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        //

        //poit
        $form->addElement(new \XoopsFormText(_AM_TDMSPOT_WEIGHT, 'weight', 10, 11, $this->getVar('weight')));

        // Permissions
        $memberHandler = xoops_getHandler('member');
        $group_list = $memberHandler->getGroupList();
        $gpermHandler = xoops_getHandler('groupperm');
        $full_list = array_keys($group_list);

        if (!$this->isNew()) {      // Edit mode
            $groups_ids = $gpermHandler->getGroupIds('tdmspot_catview', $this->getVar('id'), $xoopsModule->getVar('mid'));
            $groups_ids = array_values($groups_ids);
            $groups_news_can_view_checkbox = new \XoopsFormCheckBox(_AM_TDMSPOT_PERM_2, 'groups_view[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_view_checkbox = new \XoopsFormCheckBox(_AM_TDMSPOT_PERM_2, 'groups_view[]', $full_list);
        }
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_view_checkbox);
        //
        $form->addElement(new \XoopsFormRadioYN(_AM_TDMSPOT_VISIBLE, 'display', $this->getVar('display'), _YES, _NO));

        $form->addElement(new \XoopsFormHidden('op', 'save_cat'));
        $form->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

