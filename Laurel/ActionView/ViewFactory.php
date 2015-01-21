<?php //strict

class ViewFactory
{
    public function newInstance($helpers = null)
    {
        if (! $helpers) {
            $helpers = new HelperRegistry;
        }

        return new View(
            new TemplateRegistry,
            new TemplateRegistry,
            $helpers
        );
    }
}
