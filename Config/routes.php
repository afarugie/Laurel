<?php 

$router->addGet('home','/')->addValues(
	array(
		'controller'=>'index',
		'action'=>'index',
		'template'=>'master',
		'format'=>'html'
	)
);



