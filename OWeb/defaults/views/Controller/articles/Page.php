
<div id="twoCollone">
	<div>

<?php
	if($this->content != null){
		if(!$this->content->checkLang($this->getLang()))
			$lang = \OWeb\types\Language::$default_language;
		else 
			$lang = $this->getLang();
		
		echo $this->content->getContent($lang);
	}?>

	</div>
</div>