
<?php

if(!$this->article->checkLang($this->getLang()))
	$lang = \OWeb\types\Language::$default_language;
else 
	$lang = $this->getLang();

$date_year = date("Y", $this->article->getDate());
$date_day = date("d", $this->article->getDate());
$date_month = date("M", $this->article->getDate());

$link = $this->url();
$link->addParam('page', 'articles\Article')
	->addParam('id', $this->article->getId());

?>

<div class="ArticleTitle">
	
	<div>
		<p class="date">
			<?php echo $date_day.'<br/>'.$this->l($date_month).'<br/>'.$date_year; ?>
		</p>
		
		<h2>
			<a href="<?php echo $link; ?>" id="article<?php echo $this->article->getId() ?>_Title">
			<?php echo $this->article->getTitle($lang); ?>
			</a>
		</h2>
		<p class="by">
			<?php echo $this->l('by'); ?> : Oliverde8
		</p>
	</div>
	
	<hr/>
</div>