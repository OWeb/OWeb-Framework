<?php
	$this->addHeader('2Collone.css',\OWeb\manage\Headers::css); 
	$this->addHeader('articles.css',\OWeb\manage\Headers::css); 
	$this->addHeader('jquery_theme/jquery-ui-1.9.2.custom.min.css',\OWeb\manage\Headers::css); 
?>
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