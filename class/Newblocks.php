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

use Xoopsmodules\tdmspot;

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');


/**
 * Class TdmspotNewblocks
 */
class Newblocks extends \XoopsObject
{
    // constructor
    /**
     * TdmspotNewblocks constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('bid', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('options', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('side', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('visible', XOBJ_DTYPE_INT, null, false, 10);
    }

    //    public function tdmspot_newblocks()
    //    {
    //        $this->__construct();
    //    }

    /**
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $xoopsOption;
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = sprintf($this->isNew() ? _AM_TDMSPOT_ADD : _AM_TDMSPOT_EDITER);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new \XoopsFormText(_AM_TDMSPOT_TITLE, 'title', 100, 255, $this->getVar('title')), true);
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new \XoopsFormHidden('id', $this->getVar('id')));
            //load option
            require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
            $block_arr = new \XoopsBlock($this->getVar('bid'));
            require_once XOOPS_ROOT_PATH . '/modules/' . $block_arr->getVar('dirname') . '/blocks/' . $block_arr->getVar('func_file');
            require_once XOOPS_ROOT_PATH . '/modules/' . $block_arr->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/blocks.php';

            if ($edit_func = $block_arr->getVar('edit_func')) {
                $opt = $this->getVar('options') ?: $block_arr->getVar('options');

                $options = explode('|', $opt);

                //$form->insertBreak($edit_func($options), 'odd');
                $form->addElement(new \XoopsFormLabel(_AM_TDMSPOT_OPTION, $edit_func($options)));
            } else {
                $form->insertBreak(_AM_TDMSPOT_NOOPTION, 'odd');
            }
        }

        //load block
        $block_arr = new \XoopsBlock();
        $form_arr = $block_arr->getAllBlocks();

        $cat_select = new \XoopsFormSelect(_AM_TDMSPOT_BLOCK, 'bid', $this->getVar('bid'));
        foreach (array_keys($form_arr) as $i) {
            $productcat_title = $form_arr[$i]->getVar('title');
            $productcat_name = $form_arr[$i]->getVar('dirname');
            $cat_select->addOption($form_arr[$i]->getVar('bid'), $productcat_name . ' *** ' . $productcat_title);
        }

        $form->addElement($cat_select);
        //

        //centrage
        $tagchannel = [
            'spot_topcenter' => _AM_TDMSPOT_CENTERCCOLUMN,
            'spot_topleft' => _AM_TDMSPOT_CENTERLCOLUMN,
            'spot_topright' => _AM_TDMSPOT_CENTERRCOLUMN,
            'spot_bottomcenter' => _AM_TDMSPOT_BOTTOMCCOLUMN,
            'spot_bottomleft' => _AM_TDMSPOT_BOTTOMLCOLUMN,
            'spot_bottomright' => _AM_TDMSPOT_BOTTOMRCOLUMN
        ];
        $tagchannel_select = new \XoopsFormSelect(_AM_TDMSPOT_CENTER, 'side', $this->getVar('side'));
        $tagchannel_select->addOptionArray($tagchannel);
        $form->addElement($tagchannel_select);

        //upload
        if ($this->isNew()) {
            $form->insertBreak('<div align="center">' . _AM_TDMSPOT_OPTIONDESC . '</div>', 'odd');
        }
        //load page
        $pageHandler = new tdmspot\PageHandler(); //xoops_getModuleHandler('tdmspot_page', 'tdmspot');
        $page_select = new \XoopsFormSelect(_AM_TDMSPOT_PAGE, 'pid', $this->getVar('pid'));
        $page_select->addOptionArray($pageHandler->getList());
        $form->addElement($page_select, true);
        //
        //

        $form->addElement(new \XoopsFormText(_AM_TDMSPOT_WEIGHT, 'weight', 10, 10, $this->getVar('weight')));
        $form->addElement(new \XoopsFormRadioYN(_AM_TDMSPOT_VISIBLE, 'visible', $this->getVar('visible'), _YES, _NO));
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}
