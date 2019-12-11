<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/Exceptions/classes/class.ilException.php';

/**
 * Page already exists exception
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @version $Id$
 *
 */
class ilCOPageAlreadyExists extends ilException
{
    /**
     * Constructor
     *
     * A message is not optional as in build in class Exception
     *
     * @param string $a_message message
     */
    public function __construct($a_message)
    {
        parent::__construct($a_message);
    }
}
