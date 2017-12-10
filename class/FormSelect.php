<?php namespace Xoopsmodules\tdmspot;
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Xoops Form Class Elements
 *
 * @copyright       {@link https://xoops.org 2001-2017 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license 2.0 or later}
 * @package         class
 * @package         kernel
 * @subpackage      form
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          John Neill <catzwolf@xoops.org>
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('XoopsFormElement');

/**
 * A select field
 *
 * @author      Kazumi Ono <onokazu@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      John Neill <catzwolf@xoops.org>
 * @copyright   {@link https://xoops.org 2001-2017 XOOPS Project}
 * @package     kernel
 * @subpackage  form
 * @access      public
 */
class FormSelect extends \XoopsFormElement
{
    /**
     * Options
     *
     * @var array
     * @access private
     */
    public $_options = [];

    /**
     * Allow multiple selections?
     *
     * @var bool
     * @access private
     */
    public $_multiple = false;

    /**
     * Number of rows. "1" makes a dropdown list.
     *
     * @var int
     * @access private
     */
    public $_size;

//    public $_extra;

    /**
     * Pre-selcted values
     *
     * @var array
     * @access private
     */
    public $_value = [];

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param mixed $value Pre-selected value (or array of them).
     * @param int $size Number or rows. "1" makes a drop-down-list
     * @param bool $multiple Allow multiple selections?
     * @param string $extra
     */
    public function __construct($caption, $name, $value = null, $size = 1, $multiple = false, $extra = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_multiple = $multiple;
        $this->_size = (int)$size;
        $this->_extra = $extra;
        if (isset($value)) {
            $this->setValue($value);
        }
    }

    //    public function TDMFormSelect($caption, $name, $value = null, $size = 1, $multiple = false, $extra = '')
    //    {
    //        parent::__construct($caption, $name, $value, $size, $multiple, $extra);
    //    }
    /**
     * Are multiple selections allowed?
     *
     * @return bool
     */
    public function isMultiple()
    {
        return $this->_multiple;
    }

    /**
     * Get the size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @param bool $encode
     * @return string
     */
    public function getExtra($encode = false)
    {
        return $this->_extra;
    }

    /**
     * Get an array of pre-selected values
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getValue($encode = false)
    {
        if (!$encode) {
            return $this->_value;
        }
        $value = [];
        foreach ($this->_value as $val) {
            $value[] = $val ? htmlspecialchars($val, ENT_QUOTES) : $val;
        }

        return $value;
    }

    /**
     * Set pre-selected values
     *
     * @param  $value mixed
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->_value[] = $v;
            }
        } elseif (isset($value)) {
            $this->_value[] = $value;
        }
    }

    /**
     * Add an option
     *
     * @param string $value "value" attribute
     * @param string $name "name" attribute
     */
    public function addOption($value, $name = '')
    {
        if ('' != $name) {
            $this->_options[$value] = $name;
        } else {
            $this->_options[$value] = $value;
        }
    }

    /**
     * Add multiple options
     *
     * @param array $options Associative array of value->name pairs
     */
    public function addOptionArray($options)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->addOption($k, $v);
            }
        }
    }

    /**
     * Get an array with all the options
     *
     * Note: both name and value should be sanitized. However for backward compatibility, only value is sanitized for now.
     *
     * @param bool|int $encode To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
     * @return array Associative array of value->name pairs
     */
    public function getOptions($encode = false)
    {
        if (!$encode) {
            return $this->_options;
        }
        $value = [];
        foreach ($this->_options as $val => $name) {
            $value[$encode ? htmlspecialchars($val, ENT_QUOTES) : $val] = ($encode > 1) ? htmlspecialchars($name, ENT_QUOTES) : $name;
        }

        return $value;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;

        $gpermHandler = xoops_getHandler('groupperm');

        $ele_name = $this->getName();
        $ele_title = $this->getTitle();
        $ele_value = $this->getValue();
        $ele_options = $this->getOptions();
        $ret = '<select size="' . $this->getSize() . '"' . $this->getExtra();

        if (false != $this->isMultiple()) {
            $ret .= ' name="' . $ele_name . '[]" id="' . $ele_name . '" title="' . $ele_title . '" multiple="multiple">';
        } else {
            $ret .= ' name="' . $ele_name . '" id="' . $ele_name . '" title="' . $ele_title . '">';
        }
        foreach ($ele_options as $value => $name) {
            $cat_link = tdmspot_generateSeoUrl('index', $value, $name);
            $ret .= '<option value="' . $cat_link . '"';
            if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . $name . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
     */
    public function renderValidationJS()
    {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode("\n", $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = sprintf(_FORM_ENTER, empty($eltcaption) ? $eltname : $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));

            return "\nvar hasSelected = false; var selectBox = myform.{$eltname};" . 'for (i = 0; i < selectBox.options.length; i++) { if (selectBox.options[i].selected === true) { hasSelected = true; break; } }' . "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
        }

        return '';
    }
}