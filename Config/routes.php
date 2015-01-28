<?php 

$router->setNamespace('index',false,function($router){

	$router->addGet('home','/')->addValues(
		array(
			'action'=>'index',
			'template'=>'master',
			'format'=>'html'
		)
	);

},['directory'=>false]);





