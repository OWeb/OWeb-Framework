<?php

namespace SBBCodeParser;

class Node_Text extends Node
{
	protected $text;


	public function __construct($text)
	{
		$this->text = $text;
	}

	public function get_html($nl2br=true, $htmlEntities = true)
	{
		if($htmlEntities)
			$text = htmlentities($this->text, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		else{
			$text = $this->text;
		}
			
		if(!$nl2br)
			return str_replace("  ", " &nbsp;", $text);

		$text2 = str_replace("\n\n", "\n", $text);
		$text2 = str_replace("\n", "<br/>", $text2);
		
		return str_replace("  ", " &nbsp;", $text2);
	}

	public function get_text()
	{
		return $this->text;
	}
}