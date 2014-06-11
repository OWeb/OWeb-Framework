<?php
/**
 * @author      Oliver de Cramer (oliverde8 at gmail.com)
 * @copyright    GNU GENERAL PUBLIC LICENSE
 *                     Version 3, 29 June 2007
 *
 * PHP version 5.3 and above
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see {http://www.gnu.org/licenses/}.
 */
namespace Extension\bbcode\JBBCode\myCodes;

/**
 * Description of Titles
 *
 * @author oliverde8
 */
class Titles extends \Extension\bbcode\JBBCode\CodeDefinition{
	
	public static $tree = array();
	
	public function __construct()
    {
        parent::__construct();
        $this->setTagName("title");
		$this->setUseOption(true);
    }
	
	public function asHtml( \Extension\bbcode\JBBCode\ElementNode $el ){		
		$content = "";
        foreach( $el->getChildren() as $child )
            $content .= $child->getAsBBCode();
		
		$att = $el->getAttribute();
		$tag = trim(htmlspecialchars($content));
		
		if($att == 'h1'){
			self::$tree[] = array(1, $content, $tag);
			return "<h1 id=\"$tag\">".$content."</h1>";
		}else if($att == 'h2'){
			self::$tree[] = array(2, $content, $tag);
			return "<h2 id=\"$tag\">".$content."</h2>";
		}else if($att == 'h3'){
			self::$tree[] = array(3, $content, $tag);
			return "<h3 id=\"$tag\">".$content."</h2>";
		}else if($att == 'h4'){
			self::$tree[] = array(4, $content, $tag);
			return "<h4 id=\"$tag\">".$content."</h4>";
		}else if($att == 'h5'){
			self::$tree[] = array(5, $content, $tag);
			return "<h4 id=\"$tag\">".$content."</h5>";
		}
	}
}

?>
