<?php
	
	error_reporting(E_ERROR);
	
	require_once '../BaseController.php';
	require_once '../HelperFactory.php';
	require_once '../Helper.php';
	require_once '../BaseHelper.php';

	require_once '../../ActionView/Exception/HelperNotFound.php';
	require_once '../../ActionView/Exception/InvalidHelpersObject.php';
	require_once '../../ActionView/Exception/TemplateNotFound.php';

	require_once '../../ActionView/HelperRegistry.php';
	require_once '../../ActionView/TemplateRegistry.php';
	require_once '../../ActionView/View.php';
	require_once '../../ActionView/ViewFactory.php';


	
	