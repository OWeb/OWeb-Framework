<?php

	$error = \OWeb\manage\SubViews::getInstance()->getSubView('\Controller\OWeb\Error');
	$error->addParams('Title', "404")
			->addParams('Description', 'This page couldn\' be found. It might have been deleted' )
			->addParams('img', OWEB_HTML_DIR_CSS.'/_OWeb/errors/images/NotFound.jpg');
	$error->display();

?>
