<?php //strict

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class RouteCollection extends AbstractSpec implements ArrayAccess,Countable,IteratorAggregate
{
	 protected $routes = array();
	 protected $route_factory;
	 protected $name_prefix = null;
	 protected $path_prefix = null;
	 protected $resource_callable = null;
	 protected $route_callable = null;
	 
	public function __construct(RouteFactory $route_factory,array $routes = array()) 
	{
		$this->route_factory = $route_factory;
		$this->routes = $routes;
		$this->setResourceCallable(array($this, 'resourceCallable'));
		$this->setRouteCallable(array($this, 'routeCallable'));
	}
	
	public function offsetGet($name)
	{
		return $this->routes[$name];
	}
	
	public function offsetSet($name, $route)
	{
		if (! $route instanceof Route)
		{
			throw new UnexpectedValue;
		}
		
		$this->routes[$name] = $route;
		
	}
	
	public function offsetExists($name)
	{
		return isset($this->routes[$name]);
	}
	
	public function offsetUnset($name)
	{
		unset($this->routes[$name]);
	}
	
	public function count()
	{
		return count($this->routes);
	}
	
	public function getIterator()
	{
		return new ArrayIterator($this->routes);
	}
	
	public function add($name, $path)
	{
	
		// create the route with the full path, name, and spec
		$route = $this->route_factory->newInstance(
					$path,
					$name,
					$this->getSpec()
		);
		
		// add the route
		if (! $route->name) {
			$this->routes[] = $route;
		} else {
			$this->routes[$route->name] = $route;
		}
		
		// modify newly-added route
		call_user_func($this->route_callable, $route);
		
		// done; return for further modification
		return $route;
		
	}
	
	public function addGet($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('GET');
		return $route;
	}
	
	public function addDelete($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('DELETE');
		return $route;
	}
	
	public function addHead($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('HEAD');
		return $route;
	}
	
	public function addOptions($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('OPTIONS');
		return $route;
	}
	
	public function addPatch($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('PATCH');
		return $route;
	}
	
	public function addPost($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('POST');
		return $route;
	}
	
	public function addPut($name, $path)
	{
		$route = $this->add($name, $path);
		$route->addMethod('PUT');
		return $route;
	}
	
	public function setRouteCallable($callable)
	{
		$this->route_callable = $callable;
		return $this;
	}
	
	protected function routeCallable(Route $route)
	{
		if ($route->name && ! isset($route->values['action'])) {
			$route->addValues(array('action' => $route->name));
		}
	}
	
	public function attach($name, $path, $callable)
	{
		// save current spec
		$spec = $this->getSpec();
		
		// append to the name prefix, with delimiter if needed
		if ($this->name_prefix) {
			$this->name_prefix .= '.';
		}
		
		$this->name_prefix .= $name;
		
		// append to the path prefix
		$this->path_prefix .= $path;
		
		// invoke the callable, passing this RouteCollection as the only param
		call_user_func($callable, $this);
		
		// restore previous spec
		$this->setSpec($spec);
	}
	
	protected function getSpec()
	{
		$vars = array(
			'tokens',
			'server',
			'method',
			'accept',
			'values',
			'secure',
			'wildcard',
			'routable',
			'is_match',
			'generate',
			'name_prefix',
			'path_prefix',
			'resource_callable',
			'route_callable',
		);
		
		$spec = array();
		
		foreach ($vars as $var) {
			$spec[$var] = $this->$var;
		}
		
		return $spec;
	}
	
	protected function setSpec($spec)
	{
		foreach ($spec as $key => $val) {
			$this->$key = $val;
		}
	}
	
	public function attachResource($name, $path)
	{
		$this->attach($name, $path, $this->resource_callable);
	}
	
	public function setResourceCallable($resource)
	{
		$this->resource_callable = $resource;
		return $this;
	}
	
	protected function resourceCallable(RouteCollection $router)
	{
		// add 'id' and 'format' if not already defined
		$tokens = array();
		
		if (! isset($router->tokens['id'])) {
			$tokens['id'] = '\d+';
		}
		
		if (! isset($router->tokens['format'])) {
			$tokens['format'] = '(\.[^/]+)?';
		}
		
		if ($tokens) {
			$router->addTokens($tokens);
		}
		
		// add the routes
		$router->addGet('browse', '{format}');
		$router->addGet('read', '/{id}{format}');
		$router->addGet('edit', '/{id}/edit{format}');
		$router->addGet('add', '/add');
		$router->addDelete('delete', '/{id}');
		$router->addPost('create', '');
		$router->addPatch('update', '/{id}');
		$router->addPut('replace', '/{id}');
		$router->addOptions('options', '');
	}
}