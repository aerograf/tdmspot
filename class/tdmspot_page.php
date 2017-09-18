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
 * Class TDMSpot_page
 */
class TDMSpot_page extends XoopsObject
{
    // constructor
    /**
     * TDMSpot_page constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('visible', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('cat', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('limit', XOBJ_DTYPE_INT, null, false, 5);
    }

    //    public function TDMSpot_page()
    //    {
    //        $this->__construct();
    //    }

    /**
     * @param bool $action
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_AM_TDMSPOT_ADD) : sprintf(_AM_TDMSPOT_EDITER);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_AM_TDMSPOT_TITLE, 'title', 100, 255, $this->getVar('title')), true);
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('id')));
        }

        //genre
        $catHandler = xoops_getModuleHandler('tdmspot_cat', 'tdmspot');
        $var_cat = explode(',', $this->getVar('cat'));
        $cat_select = new XoopsFormSelect(_AM_TDMSPOT_CATEGORY, 'cat', $var_cat, 5, true);
        $cat_select->addOptionArray($catHandler->getList());
        $cat_select->addOption(0, 'ALL');
        $form->addElement($cat_select);

        $form->addElement(new XoopsFormText(_AM_TDMSPOT_LIMIT, 'limit', 5, 5, $this->getVar('limit')), false);
        // Permissions
        $memberHandler = xoops_getHandler('member');
        $group_list = $memberHandler->getGroupList();
        $gpermHandler = xoops_getHandler('groupperm');
        $full_list = array_keys($group_list);

        if (!$this->isNew()) {      // Edit mode
            $groups_ids = $gpermHandler->getGroupIds('spot_pageview', $this->getVar('id'), $xoopsModule->getVar('mid'));
            $groups_ids = array_values($groups_ids);
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_TDMSPOT_PERM_2, 'groups_view[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_TDMSPOT_PERM_2, 'groups_view[]', $full_list);
        }
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_view_checkbox);

        $form->addElement(new XoopsFormText(_AM_TDMSPOT_WEIGHT, 'weight', 10, 10, $this->getVar('weight')));
        $form->addElement(new XoopsFormRadioYN(_AM_TDMSPOT_VISIBLE, 'visible', $this->getVar('visible'), _YES, _NO));
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }

    /**
     * @param bool $action
     * @return XoopsThemeForm
     */
    public function getPlug($action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_AM_TDMSPOT_ADD) : sprintf(_AM_TDMSPOT_EDITER);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('id')));
        }

        //load page
        $pageHandler = xoops_getModuleHandler('tdmspot_page', 'tdmspot');
        $page_select = new XoopsFormSelect(_AM_TDMSPOT_PLUGDEF, 'default', '');
        $page_select->addOptionArray($pageHandler->getList());
        $page_select->addOption(0, _AM_TDMSPOT_PLUGNONE);
        $form->addElement($page_select);
        //
        //load page
        $page2_select = new XoopsFormSelect(_AM_TDMSPOT_PLUGPAGE, 'page', '', '5', true);
        $page2_select->addOptionArray($pageHandler->getList());
        $page2_select->addOption(0, _AM_TDMSPOT_PLUGALL);
        $form->addElement($page2_select, true);
        //

        //display
        $channel = [1 => _AM_TDMSPOT_PLUGTABS, 2 => _AM_TDMSPOT_PLUGSELECT, 3 => _AM_TDMSPOT_PLUGTEXT, 4 => 'Accordion', 5 => 'wslide', 0 => _AM_TDMSPOT_PLUGNONE];
        $channel_select = new XoopsFormSelect(_AM_TDMSPOT_PLUGDISPLAY, 'display', 0);
        //$channel_select->setDescription(_AM_TDMSPOT_PLUGSTYLE_DESC);
        $channel_select->addOptionArray($channel);
        $form->addElement($channel_select);

        // style display
        $tagchannel = [
            'cupertino' => 'cupertino',
            'lightness' => 'lightness',
            'darkness' => 'darkness',
            'smoothness' => 'smoothness',
            'start' => 'start',
            'redmond' => 'redmond',
            'sunny' => 'sunny',
            'pepper' => 'pepper',
            'eggplant' => 'eggplant',
            'dark-hive' => 'dark-hive',
            'excite' => 'excite',
            'vader' => 'vader',
            'trontastic' => 'trontastic'
        ];

        //$tagchannel = array('black-menu' => 'black', 'blue-menu' => 'blue', 'bluesprite-menu' => 'bluesprite', 'chrome-menu' => 'chrome', 'green-menu' => 'green', 'indentmenu-menu' => 'indentmenu', 'jquery-menu' => 'jquery', 'marron-menu' => 'marron', 'modernbricksmenu-menu' => 'modernbricksmenu' ,
        //'mytabsdefault-menu' => 'mytabsdefault', 'shadetabs-menu' => 'shadetabs', 'slate-menu' => 'slate', 'stylefour-menu' => 'stylefour', 'time4bed-menu' => 'time4bed' );
        $tagchannel_select = new XoopsFormSelect(_AM_TDMSPOT_PLUGSTYLE, 'tdmspot_style', 'cupertino');
        //$tagchannel_select->setDescription(_AM_TDMSPOT_PLUGSTYLE_DESC);
        $tagchannel_select->addOptionArray($tagchannel);
        //$tagchannel_select->setExtra("onchange='xoopsGetElementById(\"NewsColorSelect\").className = \"\" + this.options[this.selectedIndex].value;'");
        $form->addElement($tagchannel_select);

        //$form->addElement( new XoopsFormLabel('', "<div id='NewsColorSelect' class=''><div id='tabs'><ul><li style='list-style-type: none;'><a href='javascript:;' title='test1'>Test1</a></li><li><a href='javascript:;' title='test2'>Test2</a></li><li><a href='javascript:;' title='test3'>Test3</a></li></ul></div></div>" ));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

/**
 * Class TDMSpottdmspot_pageHandler
 */
class TDMSpottdmspot_pageHandler extends XoopsPersistableObjectHandler
{
    /**
     * TDMSpottdmspot_pageHandler constructor.
     * @param null|object|XoopsDatabase $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'tdmspot_page', 'TDMSpot_page', 'id', 'title');
    }
}
