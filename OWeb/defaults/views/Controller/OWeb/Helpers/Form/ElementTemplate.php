<?php

$js = "
	$('span.OWebForm_input').css('display', 'block').hide();
	$('img.OWebForm_input').click(function(){
		if( $(this).next().is(':visible') )
			$(this).next().fadeOut();
		else {
			$(this).next().fadeIn();
		}
	});

";
\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add($js);

$id = clone $this->htmlIdentifier;
$id->addHtmlClass('OWebForm_input_def');

$idDesc = clone $this->htmlIdentifier;
$idDesc->addHtmlClass('OWebForm_input_desc');

$idErr = clone $this->htmlIdentifier;
$idErr->addHtmlClass('OWebForm_input_err');

?>
<div <?=$this->htmlIdentifier?>>

	<?php
	
		$this->displayController();
	
		if($this->desc != null){
	?>
			<img <?=$idDesc?> src="<?= OWEB_HTML_DIR_CSS ?>/images/Helpers_Form/description.png" />

			<span <?=$idDesc?> ><?= $this->desc ?> </span>

	<?php
		}

		if(!empty($this->errMessages)){
	?>
			<div <?=$idErr?> >
				<ul <?=$idErr?> >

					<?php
						foreach($this->errMessages as $i => $msg){
					?>

					<li>
						<strong><?=$msg?> </strong>
						<?=$this->errDescriptions[$i]?>
					</li>

					<?php
						}

					?>

				</ul>	
			</div>
	<?php } ?>

</div>

