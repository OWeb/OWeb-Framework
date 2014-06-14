
<div class="ColloneDroite">
	<div>

		<h3><?= $this->l("Categories"); ?></h3>
		<?php
		$catTree = \OWeb\manage\SubViews::getInstance()->getSubView('\Controller\OWeb\widgets\TreeList');
		$catTree->addParams('tree', $this->cats)
				->addParams('class', 'articles_category')
				->addParams('classes', array('articles_category_1'))
				->addParams('link',
						$this->url(array('page' => 'articles\Categorie', 'catId' => '')));
		//->addParams('link', 'test'); 
		$catTree->display();
		?>

		<h3>eXpansion Realese In</h3>

		<div id="defaultCountdown">
			<div class="dash days_dash">
				<span class="dash_title">days</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash hours_dash">
				<span class="dash_title">hours</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash minutes_dash">
				<span class="dash_title">minutes</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash seconds_dash">
				<span class="dash_title">seconds</span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
		</div>
		<br/><br/>
		<p>Find more info on : <br/><a href="http://www.ml-expansion.com/">http://www.ml-expansion.com/</a></p>

		<?php
		$this->addHeader('jquery/jquery.lwtCountdown-1.0.js', \OWeb\manage\Headers::js);
		$this->addHeader('jquery_theme/jquery.countdown.css',
				\OWeb\manage\Headers::css);
		?>
		<script language="javascript" type="text/javascript">
			jQuery(document).ready(function() {
				$('#defaultCountdown').countDown({
					targetDate: {
						'day': 30,
						'month': 1,
						'year': 2014,
						'hour': 20,
						'min': 0,
						'sec': 0
					},
					omitWeeks: true
				});
			});
		</script>

	</div>
</div>
