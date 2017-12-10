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
 * Class CategoryHandler
 */
class CategoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * CategoryHandler constructor.
     * @param null|\XoopsDatabase $db
     */


    public function __construct(\XoopsDatabase $db = null, $table = '', $className = '', $keyName = '', $identifierName = '')
    {
        parent::__construct($db, 'tdmspot_cat', Category::class, 'id', 'title');
    }

    /**
     * @param \XoopsObject $obj
     * @param int                $val
     * @return bool
     */
    public function delete(\XoopsObject $object, $force = true)
    {
        return parent::delete($object);
    }
}
