<?php

if(!$this->article->checkLang($this->getLang()))
	$lang = \OWeb\manage\Languages::getDefLang();
else 
	$lang = $this->getLang();

$img_found = false;
$img_url = "";
$img_class = "";

if($this->short){
	$content = substr(strip_tags ($this->article->getContent($lang)), 0, 1000);
	$img_class = "article_image_short";
	$content = $this->getBBText($content);
}else{
	$content = $this->getBBHtml($this->article->getContent($lang), false, false);
}



switch($this->image_level){
	
	case 0 : 
		break;
	case 2 : 
	case 1 : 
		if($this->article->getImg() != NULL){
			$img_found = true;
			$img_url =  OWEB_HTML_URL_IMG."/".$this->article->getImg();
		}else if($this->image_level == 2){
			$cats[] = $this->article->getCategorie();
			
			if($this->article->getCategorie()->getImg() != NULL){
				$img_found = true;
				$img_url =  OWEB_HTML_URL_IMG."/".$this->article->getCategorie()->getImg();
			}
			else{
				foreach($this->article->getCategories() as $cat){
					if($cat->getImg() != NULL){
						$img_found = true;
						$img_url =  OWEB_HTML_URL_IMG."/".$this->article->getCategorie()->getImg();
						break;
					}
					$cats[] = $cat;
				}
				foreach($cats as $cat){
					while($cat != null){
						if($cat->getImg() != NULL){
							$img_found = true;
							$img_url =  OWEB_HTML_URL_IMG."/".$this->article->getCategorie()->getImg();
							break;
						}
						$cat = $cat->getParent();
					}
				}
			}
		}
		break;
}


$link = $this->url();
$link->addParam('page', 'articles\Article')
	->addParam('id', $this->article->getId());




?>

<div class="articleContent">
	<div>		
		<div id="article<?php echo $this->num; ?>" >

		
			<div id="article<?php echo $this->num; ?>_tabs-1" data-title="<?php echo $this->article->getTitle($lang); ?>"  >

				<?php if($img_found)
						echo '<img class="article_logo uk-float-right '.$img_class.'"src = "'.$img_url.'" />';
				?>

				<p>
				<?php 
					if(!$this->short){ 
						
						\OWeb\manage\SubViews::getInstance()->getSubView('\Controller\articles\widgets\Article_tree')
															->addParams('values', \Extension\bbcode\SBBCodeParser::$tree)
															->display();
						echo $content;
						echo '</p>';
					}else{
						echo $content;
				?>		
						...
						</p>

						<p class="readmore"><a href="<?php echo $link?>">
							<?php echo $this->l('readMore'); ?>
							</a>
						</p>
				<?php } ?>

			</div>

		</div>
	</div>
</div>
