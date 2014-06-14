<?php


\OWeb\manage\SubViews::getInstance()->getSubView('Controller\articles\widgets\show_article\Def')
			->addParams('article', $this->article)
			->addParams('short', $this->short)
			->addParams('image_level', $this->image_level)
			->display();

$this->ext_bbCode->getParser()->parse('[title=h1]'.$this->l('pros_cons').'[/title]');
echo $this->ext_bbCode->getParser()->getAsHtml();
?>

<div class="pros_cons">
	<div class="Pros">
		<ul>
		<?php 
			$pros = explode('*',$this->article->getAttribute("pros"));
			foreach($pros as $v)
				if($v != "")
					echo "<li><span>$v</span></li>"
		?>
		</ul>
	</div>

	<div class="Cons">
		<ul>
		<?php 
			$pros = explode('*',$this->article->getAttribute("cons"));
			foreach($pros as $v)
				if($v != "")
					echo "<li><span>$v</span></li>"
		?>
		</ul>
	</div>

</div>