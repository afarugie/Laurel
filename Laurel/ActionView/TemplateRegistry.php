<?php //strict

class TemplateRegistry
{

    protected $map = array();

    public function __construct($map = array())
    {
        $this->map = $map;
    }

    public function set($name, $spec)
    {
        if (is_string($spec)) {
            $__FILE__ = $spec;
            $spec = function (array $__VARS__ = array()) use ($__FILE__) {
                extract($__VARS__, EXTR_SKIP);
                require $__FILE__;
            };
        }
        $this->map[$name] = $spec;
    }

    public function has($name)
    {
        return isset($this->map[$name]);
    }

    public function get($name)
    {

        return $this->map[$name];
    }
}
