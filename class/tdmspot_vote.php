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

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class TDMSpot_vote
 */
class TDMSpot_vote extends XoopsObject
{
    // constructor
    /**
     * TDMSpot_vote constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('vote_id', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_file', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_album', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_artiste', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('vote_ip', XOBJ_DTYPE_TXTBOX, null, false);
    }

    //    public function TDMSpot_vote()
    //    {
    //        $this->__construct();
    //    }
}

/**
 * Class TDMSpottdmspot_voteHandler
 */
class TDMSpottdmspot_voteHandler extends XoopsPersistableObjectHandler
{
    /**
     * TDMSpottdmspot_voteHandler constructor.
     * @param null|object|XoopsDatabase $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'tdmspot_vote', 'TDMSpot_vote', 'vote_id', 'vote_ip');
    }
}
