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
* QTI matimage class
*
* @author Helmut Schottmüller <hschottm@gmx.de>
* @version $Id$
*
* @package assessment
*/
class ilQTIMatimage
{
    public const EMBEDDED_BASE64 = 'base64';

    /** @var string|null */
    public $imagetype;

    /** @var string|null */
    public $label;

    /** @var string|null */
    public $height;

    /** @var string|null */
    public $width;

    /** @var string|null */
    public $uri;

    /** @var string|null */
    public $embedded;

    /** @var string|null */
    public $x0;

    /** @var string|null */
    public $y0;

    /** @var string|null */
    public $entityref;

    /** @var string|null */
    public $content;
    
    public function __construct()
    {
    }

    /**
     * @param string $a_imagetype
     */
    public function setImagetype($a_imagetype) : void
    {
        $this->imagetype = $a_imagetype;
    }

    /**
     * @return string|null
     */
    public function getImagetype()
    {
        return $this->imagetype;
    }

    /**
     * @param string $a_label
     */
    public function setLabel($a_label) : void
    {
        $this->label = $a_label;
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $a_height
     */
    public function setHeight($a_height) : void
    {
        $this->height = $a_height;
    }

    /**
     * @return string|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $a_width
     */
    public function setWidth($a_width) : void
    {
        $this->width = $a_width;
    }

    /**
     * @return string|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $a_embedded
     */
    public function setEmbedded($a_embedded) : void
    {
        $this->embedded = $a_embedded;
    }

    /**
     * @return string|null
     */
    public function getEmbedded()
    {
        return $this->embedded;
    }

    /**
     * @param string $a_uri
     */
    public function setUri($a_uri) : void
    {
        $this->uri = $a_uri;
    }

    /**
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }
    
    public function setX0($a_x0) : void
    {
        $this->x0 = $a_x0;
    }
    
    public function getX0()
    {
        return $this->x0;
    }
    
    public function setY0($a_y0) : void
    {
        $this->y0 = $a_y0;
    }
    
    public function getY0()
    {
        return $this->y0;
    }

    /**
     * @param string $a_entityref
     */
    public function setEntityref($a_entityref) : void
    {
        $this->entityref = $a_entityref;
    }

    /**
     * @return string|null
     */
    public function getEntityref()
    {
        return $this->entityref;
    }

    /**
     * @param string|null $a_content
     */
    public function setContent($a_content) : void
    {
        $this->content = $a_content;
    }

    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string|null|false
     */
    public function getRawContent()
    {
        switch ($this->getEmbedded()) {
            case self::EMBEDDED_BASE64:
                
                return base64_decode($this->getContent());
        }
        
        return $this->getContent();
    }
}
