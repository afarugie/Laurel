<?php

class Router
{

	protected $debug = array();
	protected $matched_route = null;
	protected $failed_route = null;
	protected $routes;
	protected $generator;
	
	public function __construct(RouteCollection $routes, RouteGenerator $generator)
	{
 		$this->routes = $routes;
		$this->generator = $generator;	
	}
	
	public function __call($func, $args)
	{
		return call_user_func_array(array($this->routes, $func), $args);
	}
	
	public function match($path, array $server = array())
	{
		$this->debug = array();
		$this->failed_route = null;
		
		foreach ($this->routes as $route)
		{
		
			$this->debug[] = $route;
			
			$match = $route->isMatch($path, $server);
			
			if ($match)
			{
				$this->matched_route = $route;
				return $route;
			}
			
			$better_match = ! $this->failed_route
				|| $route->score > $this->failed_route->score;
				
			if ($better_match)
			{
				$this->failed_route = $route;
			}
		}
		
		$this->matched_route = false;
		
		return false;
	}
	
	public function getFailedRoute()
	{
		return $this->failed_route;
	}
	
	public function getMatchedRoute()
	{
		return $this->matched_route;
	}
	
	public function generate($name, $data = array())
	{
		$route = $this->getRouteForGenerate($name);
		return $this->generator->generate($route, $data);
	}

	public function generateRaw($name, $data = array())
	{
		$route = $this->getRouteForGenerate($name);
		return $this->generator->generateRaw($route, $data);
	}
	
	public function setRoutes(RouteCollection $routes)
	{
		$this->routes = $routes;
	}
	
	public function getRoutes()
	{
		return $this->routes;
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
	
	protected function getRouteForGenerate($name)
	{
		if (! $this->routes->offsetExists($name))
		{
			throw new RouteNotFound($name);
		}
		return $this->routes->offsetGet($name);
	}

}