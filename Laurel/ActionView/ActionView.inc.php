<?php //strict

require_once 'Exception/HelperNotFound.php';
require_once 'Exception/InvalidHelpersObject.php';
require_once 'Exception/TemplateNotFound.php';

require_once 'HelperRegistry.php';
require_once 'TemplateRegistry.php';
require_once 'View.php';
require_once 'ViewFactory.php';

$view_factory = new ViewFactory();

$view = $view_factory->newInstance();
$view_registry = $view->getViewRegistry();
$layout_registry = $view->getLayoutRegistry();

foreach($templateMap as $alias=>$file)
{
	if(file_exists(TEMPLATE_PATH.'/'.$file))
	{
		$layout_registry->set($alias, TEMPLATE_PATH.'/'.$file);
	}

	
}

foreach($viewMap as $alias=>$file)
{

	if(file_exists(VIEW_PATH.'/'.$file))
	{
		$view_registry->set($alias,VIEW_PATH.'/'.$file);
	}

	
	
}