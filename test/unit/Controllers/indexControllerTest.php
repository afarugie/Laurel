<?php

class indexControllerTest extends PHPUnit_Framework_TestCase
{
	
	use FrameworkTrait;	
	
	protected $controller;
	
	//
	// This is overloaded from the FrameworkTrait
	//
    protected function setUp()
    {
		populate_traits($this);
		
		$this->controller = new indexController($this->view_registry,$this->layout_registry,$this->helper);

	}	
	
	//
	// indexController -> index() action test
	//
	public function testIndexAction()
	{
		
		//the home page URL - simulate get request
		$route = $this->router->match('/',['REQUEST_METHOD'=>'GET']);
		
		//validate a route has matched
		$this->assertInstanceOf('Route',$route);

		// validate the route name matches the view specified in the controller action
		$this->assertSame('home',$route->name);
				
		//verify that we are using the index action
		$this->assertSame('index',$route->params['action']);
		
		//run the controller method to verify the files to be loaded
		$this->controller->index();
		
		//make sure the view loaded is the index.index view ( not laurel.default )
		$this->assertSame('index.index',$this->controller->getView());

		$view = $this->view_registry->get('index.index');

		// verify the view file was found - views are closures, null will be returned if a view file
		// does not exist
		$this->assertInstanceOf('Closure',$view);

		
	}
	
	
	
	
}