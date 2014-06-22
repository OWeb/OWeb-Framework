<?php

$js = "
    $('span.OWebForm_input').css('display', 'block').hide();
    $('i.OWebForm_input').click(function(){
        var desc = $(this).parent().parent().parent().find('span.OWebForm_input_desc');
        if(desc.is(':visible'))
                desc.fadeOut();
        else {
                desc.fadeIn();
        }
    });
";
\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add($js);

$id = clone $this->htmlIdentifier;
$id->addHtmlClass('OWebForm_input_def');

$idDesc = clone $this->htmlIdentifier;
$idDesc->addHtmlClass('OWebForm_input_desc');
$idDesc->addHtmlClass('uk-alert');
$idDesc->addHtmlClass('uk-alert-warning');

$idDescIcon = clone $this->htmlIdentifier;
$idDescIcon->addHtmlClass('OWebForm_input_desc');
$idDescIcon->addHtmlClass('uk-icon-question');

$idErr = clone $this->htmlIdentifier;
$idErr->addHtmlClass('uk-alert uk-alert-danger');
$idErr->addHtmlClass('OWebForm_input_err');
$idErr->addHtmlClass('uk-description-list');
$idErr->addHtmlClass('uk-description-list-horizontal');

?>
<div class="uk-grid">
    <?php

    $this->displayController();

    if ($this->desc != null) {
        ?>
        <div class="uk-width-1-10 ">
            <div class="uk-badge uk-badge-warning">
                <i <?= $idDescIcon ?>></i>
            </div>
        </div>
        <div class="uk-width-10-10">
            <span <?= $idDesc ?> ><?= $this->desc ?> </span>
        </div>

    <?php
    }

    if (!empty($this->errMessages)) {
        ?>
        <div  class="uk-width-10-10 ">
            <dl <?= $idErr ?> >

                <?php
                foreach ($this->errMessages as $i => $msg) {
                    ?>

                    <dt><?= $msg ?> </dt>
                    <dd><?= $this->errDescriptions[$i] ?></dd>

                <?php
                }

                ?>

            </dl>
        </div>
    <?php } ?>
</div>