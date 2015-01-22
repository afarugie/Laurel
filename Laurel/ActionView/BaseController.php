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


    public function __construct(
        TemplateRegistry $view_registry,
        TemplateRegistry $layout_registry,
        $helpers = null
    ) {
        $this->data = array();
        $this->view_registry = $view_registry;
        $this->layout_registry = $layout_registry;
        if ($helpers && ! is_object($helpers)) {
            throw new InvalidHelpersObject;
        }
        $this->helpers = $helpers;
    }


    public function __get($key)
    {
        return $this->data->$key;
    }


    public function __set( $key, $val)
    {
        $this->data->$key = $val;
    }


    public function __isset($key)
    {
        return isset($this->data->$key);
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
        $this->data = (object) $data;
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

    protected function setSection( $name, $body)
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
    
    public function __invoke()
    {
        $this->setTemplateRegistry($this->getViewRegistry());
        $this->setContent($this->render($this->getView()));

        $layout = $this->getLayout();
        if (! $layout) {
            return $this->getContent();
        }

        $this->setTemplateRegistry($this->getLayoutRegistry());
        return $this->render($layout);
    }

    protected function render($name,$vars = array())
    {
        ob_start();
        $this->getTemplate($name)->__invoke($vars);
        return ob_get_clean();
    }
}
