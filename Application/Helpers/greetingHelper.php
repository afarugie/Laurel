<?php

class greetingHelper
{
    public function __invoke($name)
    {
        echo "Hello ".$name."!";
    }
}