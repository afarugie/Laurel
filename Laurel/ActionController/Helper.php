<?php //strict


class Helper
{

    protected $map = array();
    protected $helpers = array();


    public function __construct($map = array())
    {
        $this->map = $map;
    }

    public function __call($name,$args)
    {
        return call_user_func_array(
            $this->get($name),
            $args
        );
    }

    public function set($name, $callable)
    {
        $this->map[$name] = $callable;
        unset($this->helpers[$name]);
    }

    public function has($name)
    {
        return isset($this->map[$name]);
    }

    public function get($name)
    {
        if (! $this->has($name)) {
            throw new HelperNotFound($name);
        }

        if (! isset($this->helpers[$name])) {
            $factory = $this->map[$name];
            $this->helpers[$name] = $factory();
        }

        return $this->helpers[$name];
    }
}
