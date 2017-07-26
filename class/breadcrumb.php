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

class SystemBreadcrumb
{
    /* Variables */
    public $_bread = array();
    public $_tips;

    /**
     * SystemBreadcrumb constructor.
     */
    public function __construct()
    {
    }

    /**
     * Add Tips
     * @param $value
     */
    public function addTips($value)
    {
        $this->_tips = $value;
    }

    /**
     * Render System BreadCrumb
     *
     */
    public function render()
    {
        global $xoopsModuleConfig;

        $out = '<style type="text/css">
    <!--
.tips{
    color:#000000;
    border:1px solid #00cc00;
    padding:8px 8px 8px 35px;
    background:#f8fff8 url("../assets/images/decos/idea.png") no-repeat 5px 4px;
}
    //-->
    </style>';

        if ($this->_tips) {
            $out .= '<div class="tips">' . $this->_tips . '</div><br>';
        }
        echo $out;
    }
}
