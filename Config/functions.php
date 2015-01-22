<?php //strict

//
// puts fatal errors into the stack
//
error_reporting(1);
set_error_handler('handle_error');
register_shutdown_function('fatalErrorShutdownHandler');


//
// Loads an extension from the Extensions directory
// Extension name, Extension Include File
// Extension/xhp/init.php
// load_extension('xhp')
//
function load_extension($name,$extension=".php")
{
	require_once EXTENSION_PATH.'/'.$name.''.$extension;
}


//
// returns an object instance so methods can be executed against it
// $user = with(new User())->update('name','Ben')
//
function with($object)
{
	return $object;
}

function current_user()
{
	return isset($_SESSION['author_id']) ? Author::find_by_id($_SESSION['author_id']) : false;
}


