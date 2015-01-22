<?php

	require_once 'application_autoload.php';
	
	//
	// Application
	//
	
	AutoLoader::registerDirectory('../Application/Controllers');
	AutoLoader::registerDirectory('../Application/Helpers');
	AutoLoader::registerDirectory('../Application/Models');
	
	//
	// Include Laurel Framework and Config Settings
	//
	
	require_once '../Config/config.inc.php';
	require_once FRAMEWORK_PATH.'/Framework.php';
	require_once CONFIG_PATH.'/database.php';
	
	require_once 'traits.php';
	
	
	//
	// Autoload objects
	//
	spl_autoload_register(array('AutoLoader', 'loadClass'));
	
	function populate_traits(&$class=false)
	{
		if($class)
		{
			foreach($GLOBALS as $property=>$value)
			{
				
				if(property_exists($class,$property))
				{
					$class->$property = $value;
				}
				
			}	
		}	
	}