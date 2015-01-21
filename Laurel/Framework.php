<?php


/**
* @package Laurel
* 
* Laurel Framework Loader
* Version 0.1.0
* Created: August 14th, 2014 by BJA
* Last Modified: N/A
* Description:
*	This include file loads all the required files for the Laurel Framework
*   to execute.
*/

require_once 'ActionDispatch/ActionDispatch.inc.php';
require_once 'ActionController/ActionController.inc.php';
require_once 'ActionView/ActionView.inc.php';
require_once 'ActiveRecord/ActiveRecord.php';
require_once CONFIG_PATH.'/routes.php';

