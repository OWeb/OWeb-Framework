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

namespace Extension\bbcode;

require_once OWEB_DIR_MAIN . '/defaults/extensions/bbcode/SBBCodeParser/SBBCodeParser.php';

/**
 * Description of JBBCode
 *
 * @author oliverde8
 */
class SBBCodeParser extends \OWeb\types\Extension {

	public static $tree = array();
	private $parser = null;

	protected function init() {
		$this->addAlias('getBBHtml', 'getBBHtml');
		$this->addAlias('getBBText', 'getBBText');
	}

	public function getNewParser() {
		$parser = new \SBBCodeParser\Node_Container_Document();

		$bbcode = new \SBBCodeParser\BBCode('title',
				function($content, $attribs) {
			
			$tag = trim(htmlspecialchars($content));
			$att = $attribs['default'];
			if ($att == 'h1') {
				SBBCodeParser::$tree[] = array(1, $content, $tag);
				return "<h1 class=\"uk-h2\" id=\"$tag\">" . $content . "</h1>";
			}
			else if ($att == 'h2') {
				SBBCodeParser::$tree[] = array(2, $content, $tag);
				return "<h2 class=\"uk-h3\" id=\"$tag\">" . $content . "</h2>";
			}
			else if ($att == 'h3') {
				SBBCodeParser::$tree[] = array(3, $content, $tag);
				return "<h3 class=\"uk-h4\" id=\"$tag\">" . $content . "</h2>";
			}
			else if ($att == 'h4') {
				SBBCodeParser::$tree[] = array(4, $content, $tag);
				return "<h4 class=\"uk-h5\" id=\"$tag\">" . $content . "</h4>";
			}
			else if ($att == 'h5') {
				SBBCodeParser::$tree[] = array(5, $content, $tag);
				return "<h5 class=\"uk-h6\" id=\"$tag\">" . $content . "</h5>";
			}
		}, \SBBCodeParser\BBCode::BLOCK_TAG, false, array(), array('text_node'),
				\SBBCodeParser\BBCode::AUTO_DETECT_EXCLUDE_ALL);

		$parser->add_bbcode($bbcode);

		$bbcode = new \SBBCodeParser\BBCode('codes',
				function($content, $attribs) {

			
			if(isset($attribs['default']) && !empty($attribs['default']))
				$att = strtolower ($attribs['default']);
			else
				$att= 'text';
			
			$brushes = array();
			$brushes['actionscript3']='shBrushAS3.js';
			$brushes['as3,actionscript3']='shBrushAS3.js';
			$brushes['bash']='shBrushBash.js';
			$brushes['shell']='shBrushBash.js';
			$brushes['cf,coldfusion']='shBrushColdFusion.js';
			$brushes['cf']='shBrushColdFusion.js';
			$brushes['csharp']='shBrushCSharp.js';
			$brushes['c-sharp']='shBrushCSharp.js';
			$brushes['cpp']='shBrushCpp.js';
			$brushes['c']='shBrushCpp.js';
			$brushes['css']='shBrushCss.js';
			$brushes['delphi']='shBrushDelphi.js';
			$brushes['pas']='shBrushDelphi.js';
			$brushes['pascal']='shBrushDelphi.js';
			$brushes['patch']='shBrushDiff.js';
			$brushes['diff']='shBrushDiff.js';
			$brushes['erlang']='shBrushErlang.js';
			$brushes['erl']='shBrushErlang.js';
			$brushes['groovy']='shBrushGroovy.js';
			$brushes['js']='shBrushJScript.js';
			$brushes['jscript,']='shBrushJScript.js';
			$brushes['javascript']='shBrushJScript.js';
			$brushes['java']='shBrushJava.js';
			$brushes['javafx']='shBrushJavaFX.js';
			$brushes['jfx']='shBrushJavaFX.js';
			$brushes['pl']='shBrushPerl.js';
			$brushes['perl']='shBrushPerl.js';
			$brushes['php']='shBrushPhp.js';
			$brushes['text']='shBrushPlain.js';
			$brushes['plain']='shBrushPlain.js';
			$brushes['ps']='shBrushPowerShell.js';
			$brushes['powershell']='shBrushPowerShell.js';
			$brushes['py']='shBrushPython.js';
			$brushes['python']='shBrushPython.js';
			$brushes['rails']='shBrushRuby.js';
			$brushes['ror']='shBrushRuby.js';
			$brushes['ruby']='shBrushRuby.js';
			$brushes['scala']='shBrushScala.js';
			$brushes['sql']='shBrushSql.js';
			$brushes['vb']='shBrushVb.js';
			$brushes['vbnet']='shBrushVb.js';
			$brushes['xml']='shBrushXml.js';
			$brushes['xhtml']='shBrushXml.js';
			$brushes['xslt']='shBrushXml.js';
			$brushes['html']='shBrushXml.js';
			$brushes['xhtml']='shBrushXml.js';

			if(isset($brushes[$att]))
				$js = $brushes[$att];
			else{
				$att = 'plain';
				$js = $brushes['plain'];
			}
			
			$headers = \OWeb\manage\Headers::getInstance();
			$headers->addHeader('syntaxHighlighter/shCore.js',	\OWeb\manage\Headers::javascript);
			$headers->addHeader('syntaxHighlighter/'.$js, \OWeb\manage\Headers::js);
			$headers->addHeader('syntaxHighlighter/shCoreDefault.css', \OWeb\manage\Headers::css);
			
			\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add(
					"SyntaxHighlighter.config.bloggerMode = true;
							SyntaxHighlighter.defaults['toolbar'] = true;
							SyntaxHighlighter.all();");
			$content = str_replace("<br />", "", $content);
			
			return '<pre class="brush: '.$att.';">' . $content . '</pre>';
		}, \SBBCodeParser\BBCode::BLOCK_TAG, false, array(), array(),
				\SBBCodeParser\BBCode::AUTO_DETECT_EXCLUDE_ALL);

		$parser->add_bbcode($bbcode);
		
		$parser->add_emoticons(array(
			':D' => OWEB_HTML_URL_IMG . '/icons/01.png',
			'OD' => OWEB_HTML_URL_IMG . '/icons/02.png',
			':)' => OWEB_HTML_URL_IMG . '/icons/03.png',
			';)' => OWEB_HTML_URL_IMG . '/icons/04.png',
			'8)' => OWEB_HTML_URL_IMG . '/icons/05.png',
		));

		return $parser;
	}

	public function getParser() {
		return $this->parser == null ? $this->getNewParser() : $this->parser;
	}

	public function getBBHtml($text, $nl2br = true, $htmlEntities = true) {
		return $this->getParser()->parse($text)
						->detect_emails()
						->detect_emoticons()
						->get_html($nl2br, $htmlEntities);
	}

	public function getBBText($text) {
		return $this->getParser()->parse($text)->get_text();
	}

}

?>
