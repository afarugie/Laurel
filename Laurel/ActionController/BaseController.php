<?php //strict

abstract class BaseController
{

    private $capture;
    private $content;
    private $data;
    private $helpers;
    private $layout;
    private $layout_registry;
    private $section;
    private $template_registry;
    private $view;
    private $view_registry;
    private $header = array();

    public function __construct(
        TemplateRegistry $view_registry,
        TemplateRegistry $layout_registry,
        $helpers = null,
        $router=false
    ) {
        $this->data = array();
        $this->view_registry = $view_registry;
        $this->layout_registry = $layout_registry;
        if ($helpers && ! is_object($helpers)) {
            throw new Exception;
        }
        $this->helpers = $helpers;
    }


    public function __get($key)
    {
        return $this->data[$key];
    }


    public function __set($key, $val)
    {
        $this->data[$key] = $val;
    }


    public function __isset($key)
    {
        return isset($this->data[$key]);
    }


    public function __unset($key)
    {
        unset($this->data->$key);
    }


    public function __call($name, array $args)
    {
        return call_user_func_array(array($this->helpers, $name), $args);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function addData($data)
    {
        foreach ($data as $key => $val) {
            $this->data->$key = $val;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHelpers()
    {
        return $this->helpers;
    }


    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getLayoutRegistry()
    {
        return $this->layout_registry;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getViewRegistry()
    {
        return $this->view_registry;
    }


    protected function setTemplateRegistry(TemplateRegistry $template_registry)
    {
        $this->template_registry = $template_registry;
    }

    protected function getTemplate($name)
    {

        $tmpl = $this->template_registry->get($name);
        return $tmpl->bindTo($this, get_class($this));
    }

    protected function setContent($content)
    {
        $this->content = $content;
    }

    protected function getContent()
    {
        return $this->content;
    }

    protected function hasSection($name)
    {
        return isset($this->section[$name]);
    }

    protected function setSection($name, $body)
    {
        $this->section[$name] = $body;
    }

    protected function getSection($name)
    {
        return $this->section[$name];
    }

    protected function beginSection($name)
    {
        $this->capture[] = $name;
        ob_start();
    }

    protected function endSection()
    {
        $body = ob_get_clean();
        $name = array_pop($this->capture);
        $this->setSection($name, $body);
    }
    
    protected function addJavaScript($source,$async=false)
    {
    	$async = $async ? 'async' : '';
    	$this->header[] = "\t\t".'<script '.$async.' '.'src="'.JAVASCRIPT_PATH.$source.'" type="text/javascript"></script>'."\n";
    }
    
    protected function addMeta($name, $content)
    {
    	$this->header[] = "\t\t".'<meta name="'.$name.'" content="'.$content.'"/>'."\n";
    }
    
    protected function addCSS($href,$path=CSS_PATH,$media="all", $rel="stylesheet")
    {
    	$this->header[] = "\t\t".'<link href="'.$path.$href.'" media="'.$media.'" type="text/css" rel="'.$rel.'"/>'."\n";
    }
    
    public function addTitle($title)
    {
    	$this->header[] = '<title>'.$title.'</title>'."\n";
    }
    
    public function getHeader()
    {
    	foreach($this->header as $header)
    	{
    		echo $header;
    	}
    }
    
    public function __invoke($module=false)
    {
        $this->setTemplateRegistry($this->getViewRegistry());
        
        if(!$module) $this->setContent($this->render($this->getView(),$this->getData()));
		
		
        $layout = $this->getLayout();
        if (! $layout) {
            return $this->getContent();
        }
		
        $this->setTemplateRegistry($this->getLayoutRegistry());
        return $this->render($layout,$this->getData());
    }
	
	public function redirect_to($location,$code=302,$message="Found")
	{
		header('HTTP/1.0 '.$code.' '.$message);
		header('Location: '.$location);
		exit;
	}
	
    protected function render($name, array $vars = array())
    {
	    //$vars = $this->getData();
	    
        ob_start();
        $this->getTemplate($name)->__invoke($vars);
        return ob_get_clean();
    }
}
