<?php //strict

class RouterFactory
{
	public function newInstance()
	{
		$router = new Router(new RouteCollection(new RouteFactory()),new RouteGenerator());

		return $router;
	}
	
}