<?php
	
trait FrameworkTrait
{
	public  $templateMap;
	public  $viewMap;
	public  $router_factory;
	public  $router;
	public  $view_factory;
	public  $view;
	public  $view_registry;
	public  $layout_registry;
	
	public  $helpers;
	public  $helper;
	public  $helperInfo;
	public  $helperName;

    protected function setUp()
    {
		populate_traits($this);
	}	
		
}