<?php


//
// Expire a user session after a matter of time - just an idea
//

if(!isset($_SESSION['expire']))
{
	//not set
	$_SESSION['expire'] = time();
}
else if(time() - $_SESSION['expire'] > SESSION_EXPIRE * 60)
{
	unset($_SESSION['user_id']);
	//expired
	session_destroy();
}
else
{
	// page refresh or navigation
	$_SESSION['expire'] = time();
}

