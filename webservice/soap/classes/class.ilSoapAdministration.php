<?php
  /*
   +-----------------------------------------------------------------------------+
   | ILIAS open source                                                           |
   +-----------------------------------------------------------------------------+
   | Copyright (c) 1998-2001 ILIAS open source, University of Cologne            |
   |                                                                             |
   | This program is free software; you can redistribute it and/or               |
   | modify it under the terms of the GNU General Public License                 |
   | as published by the Free Software Foundation; either version 2              |
   | of the License, or (at your option) any later version.                      |
   |                                                                             |
   | This program is distributed in the hope that it will be useful,             |
   | but WITHOUT ANY WARRANTY; without even the implied warranty of              |
   | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
   | GNU General Public License for more details.                                |
   |                                                                             |
   | You should have received a copy of the GNU General Public License           |
   | along with this program; if not, write to the Free Software                 |
   | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
   +-----------------------------------------------------------------------------+
  */


  /**
   * soap server
   * Base class for all SOAP registered methods. E.g ilSoapUserAdministration
   *
   * @author Stefan Meyer <smeyer@databay.de>
   * @version $Id$
   *
   * @package ilias
   */

include_once './webservice/soap/lib/nusoap.php';


class ilSoapAdministration
{
	/*
	 * object which handles php's authentication
	 * @var object
	 */
	var $sauth = null;

	/*
	 * Defines type of error handling (PHP5 || NUSOAP)
	 * @var object
	 */
	var $error_method = null;


	function ilSoapAdministration($use_nusoap = true)
	{
		define('USER_FOLDER_ID',7);
		define('NUSOAP',1);
		define('PHP5',2);

		if($use_nusoap)
		{
			$this->error_method = NUSOAP;
		}
	}

	// PROTECTED
	function __checkSession($sid)
	{
		list($sid,$client) = $this->__explodeSid($sid);

		$this->__initAuthenticationObject();

		$this->sauth->setClient($client);
		$this->sauth->setSid($sid);

		if(!$this->sauth->validateSession())
		{
			return false;
		}			
		return true;
	}


	function __explodeSid($sid)
	{
		$exploded = explode('::',$sid);

		return is_array($exploded) ? $exploded : array('sid' => '','client' => '');
	}


	function __setMessage($a_str)
	{
		$this->message = $a_str;
	}
	function __getMessage()
	{
		return $this->message;
	}
	function __appendMessage($a_str)
	{
		$this->message .= isset($this->message) ? ' ' : '';
		$this->message .= $a_str;
	}


	function __initAuthenticationObject()
	{
		include_once './webservice/soap/classes/class.ilSoapAuthentication.php';
		
		return $this->sauth = new ilSoapAuthentication();
	}
		

	function __raiseError($a_message,$a_code)
	{
		switch($this->error_method)
		{
			case NUSOAP:

				return new soap_fault($a_code,'',$a_message);
		}
	}

	
}
?>