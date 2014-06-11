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
class ListItem extends \Extension\bbcode\JBBCode\CodeDefinition{
	
	public function __construct()
    {
        parent::__construct();
        $this->setTagName("li");
		$this->setParseContent(true);
		$this->setUseOption(true);
    }
	
	public function asHtml( \Extension\bbcode\JBBCode\ElementNode $el ){		
		$content = "";
        foreach( $el->getChildren() as $child )
            $content .= $child->getAsHTML();
		
		$att = $el->getAttribute();
		$contents = explode($att, $content);
		
		$content = '<li><strong>'.$contents[0].' : </strong>';
		for($i = 1; $i<sizeof($contents); $i++){
			$content .= $contents[$i].':';
		}
		
		$content = substr($content, 0, strlen($content)-1);
		
		return $content.'</li>';
	}
	
	
}

?>
