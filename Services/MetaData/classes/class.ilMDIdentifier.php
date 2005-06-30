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
* Meta Data class (element identifier)
*
* @package ilias-core
* @version $Id$
*/
include_once 'class.ilMDBase.php';

class ilMDIdentifier extends ilMDBase
{

	function ilMDIdentifier($a_rbac_id = 0,$a_obj_id = 0,$a_obj_type = '')
	{
		parent::ilMDBase($a_rbac_id,
						 $a_obj_id,
						 $a_obj_type);
	}

	// SET/GET
	function setCatalog($a_catalog)
	{
		$this->catalog = $a_catalog;
	}
	function getCatalog()
	{
		return $this->catalog;
	}
	function setEntry($a_entry)
	{
		$this->entry = $a_entry;
	}
	function getEntry()
	{
		return $this->entry;
	}


	function save()
	{
		if($this->db->autoExecute('il_meta_identifier',
								  $this->__getFields(),
								  DB_AUTOQUERY_INSERT))
		{
			$this->setMetaId($this->db->getLastInsertId());

			return $this->getMetaId();
		}
		return false;
	}

	function update()
	{
		if($this->getMetaId())
		{
			if($this->db->autoExecute('il_meta_identifier',
									  $this->__getFields(),
									  DB_AUTOQUERY_UPDATE,
									  "meta_identifier_id = '".$this->getMetaId()."'"))
			{
				return true;
			}
		}
		return false;
	}

	function delete()
	{
		if($this->getMetaId())
		{
			$query = "DELETE FROM il_meta_identifier ".
				"WHERE meta_identifier_id = '".$this->getMetaId()."'";
			
			$this->db->query($query);
			
			return true;
		}
		return false;
	}
			

	function __getFields()
	{
		return array('rbac_id'	=> $this->getRBACId(),
					 'obj_id'	=> $this->getObjId(),
					 'obj_type'	=> ilUtil::prepareDBString($this->getObjType()),
					 'parent_type' => $this->getParentType(),
					 'parent_id' => $this->getParentId(),
					 'catalog'	=> ilUtil::prepareDBString($this->getCatalog()),
					 'entry'	=> ilUtil::prepareDBString($this->getEntry()));

	}

	function read()
	{
		if($this->getMetaId())
		{
			$query = "SELECT * FROM il_meta_identifier ".
				"WHERE meta_identifier_id = '".$this->getMetaId()."'";

			$res = $this->db->query($query);
			while($row = $res->fetchRow(DB_FETCHMODE_OBJECT))
			{
				$this->setRBACId($row->rbac_id);
				$this->setObjId($row->obj_id);
				$this->setObjType($row->obj_type);
				$this->setParentId($row->parent_id);
				$this->setParentType($row->parent_type);
				$this->setCatalog(ilUtil::stripSlashes($row->catalog));
				$this->setEntry(ilUtil::stripSlashes($row->entry));
			}
		}
		return true;
	}
				
	/*
	 * XML Export of all meta data
	 * @param object (xml writer) see class.ilMD2XML.php
	 * 
	 */
	function toXML(&$writer)
	{
		$entry = $this->getEntry();

		if ($this->getExportMode() && $this->getCatalog() == "ILIAS")
		{
			$entry = ($this->getObjId() == 0)
				? "il_".IL_INST_ID."_".$this->getObjType()."_".$this->getRBACId()
				: "il_".IL_INST_ID."_".$this->getObjType()."_".$this->getObjId();
		}

		$writer->xmlElement('Identifier',array('Catalog' => $this->getCatalog(),
												'Entry'	 => $entry));
	}


	// STATIC
	function _getIds($a_rbac_id,$a_obj_id,$a_parent_id,$a_parent_type)
	{
		global $ilDB;

		$query = "SELECT meta_identifier_id FROM il_meta_identifier ".
			"WHERE rbac_id = '".$a_rbac_id."' ".
			"AND obj_id = '".$a_obj_id."' ".
			"AND parent_id = '".$a_parent_id."' ".
			"AND parent_type = '".$a_parent_type."'";


		$res = $ilDB->query($query);
		while($row = $res->fetchRow(DB_FETCHMODE_OBJECT))
		{
			$ids[] = $row->meta_identifier_id;
		}
		return $ids ? $ids : array();
	}
}
?>