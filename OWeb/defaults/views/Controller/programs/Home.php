<?php
$this->addHeader('articles.css', \OWeb\manage\Headers::css);
$this->addHeader('programs.css', \OWeb\manage\Headers::css);

$this->addHeader('Page.Programing.css', \OWeb\manage\Headers::css);
$this->addHeader('jquery_theme/jquery.jscrollpane.css',
		\OWeb\manage\Headers::css);
$this->addHeader('jquery_theme/jquery.carousel.css', \OWeb\manage\Headers::css);

$this->addHeader('jquery/jquery.easing.1.3.js', \OWeb\manage\Headers::javascript);
$this->addHeader('jquery/jquery.mousewheel.js', \OWeb\manage\Headers::javascript);
$this->addHeader('jquery/jquery.contentcarousel.js',
		\OWeb\manage\Headers::javascript);

$carousel_id = 0;
?>

<h1>Newest Programs</h1>
<?php
$prog_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\programs\widgets\ProgramCard');

$box_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Box');
$box_display->addParams('ctr', $prog_display)
		->addParams('SecondBoxHeight', 500)
		->addParams('Html Class', 'programBox');

foreach ($this->newest as $program) {
	$prog_display->addParams('prog', $program);
	$box_display->addParams('SecondBoxContent',
			$program->getShortDescription("eng"));
	$box_display->display();
}
?>
<div class="ColloneClean"></div>
<h1>Last Updated</h1>

<?php
foreach ($this->updated as $program) {
	$prog_display->addParams('prog', $program);
	$box_display->addParams('SecondBoxContent',
			$program->getShortDescription("eng"));
	$box_display->display();
}

$nbT = 0;
while (isset($this->previews_title[$nbT])) {
	?>
	<h1>
	<?php echo $this->previews_title[$nbT]; ?>
	</h1>

	<div id="prog_carousel_<?php echo $nbT + 1; ?>" class="ca-container">
		<div class="ca-wrapper">

	<?php
	$num = 0;
	foreach ($this->previews[$nbT] as $prog) {
		$num++;
		if (!$prog->getFront_page())
			continue;
		if (!$prog->checkLang($this->getLang()))
			$lang = \OWeb\types\Language::$default_language;
		else
			$lang = $this->getLang();
		?>

				<div class="ca-item ca-item-<?php echo $nbT . "-" . $num; ?>">
					<div class="ca-item-main">
						<div class="ca-icon">
							<img src="<?php echo OWEB_HTML_URL_IMG . $prog->getImg(); ?>" />
						</div>
						<h3><?php echo $prog->getName(); ?></h3>
						<h4>
							<span><?php echo $prog->getVeryShortDescription($lang); ?></span>
						</h4>
						<a href="#" class="ca-more">more...</a>
					</div>
					<div class="ca-content-wrapper">
						<div class="ca-content">
							<a href="#" class="ca-close">close</a>
							<div class="ca-content-text">
		<?php echo $prog->getShortDescription($lang); ?>
							</div>
								<?php
								$link = $this->url(array('page' => 'programs\Program', 'prgId' => $prog->getId()));
								echo '<p><a href="' . $link . '" class="ca-content-more">More Information & Download</a></p>';
								?>

						</div>
					</div>
				</div>

		<?php
	}
	?>

		</div>
	</div>

	<?php
	$nbT++;
	\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add('$(\'#prog_carousel_' . $nbT . '\').contentcarousel();');
}
?>
