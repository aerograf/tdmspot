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

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');


/**
 * Class PageHandler
 */
class PageHandler extends \XoopsPersistableObjectHandler
{
    /**
     * PageHandler constructor.
     * @param null|object|\XoopsDatabase $db
     * @param string                     $table
     * @param string                     $className
     * @param string                     $keyName
     * @param string                     $identifierName
     */
    public function __construct(\XoopsDatabase $db = null, $table = '', $className = '', $keyName = '', $identifierName = '')
    {
        parent::__construct($db, 'tdmspot_page', Page::class, 'id', 'title');
    }
}
