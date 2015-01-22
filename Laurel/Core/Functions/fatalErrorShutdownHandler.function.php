<?php
	
//
// Fatal error handler
//
function fatalErrorShutdownHandler()
{
	
  $last_error = error_get_last();

  if ($last_error['type'] === E_ERROR) {
    handle_error(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}
