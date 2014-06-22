
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

<h1 class="uk-article-title">
    <a href="<?php echo $link; ?>" id="article<?php echo $this->article->getId() ?>_Title">
        <?php echo $this->article->getTitle($lang); ?>
    </a>
</h1>
<p class="uk-article-meta">
    <?php echo $date_day.'/'.$this->l($date_month).'/'.$date_year; ?> | <?php echo $this->l('by'); ?> : Oliverde8
</p>