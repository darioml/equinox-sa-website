<?php

class Twig_Environment_Mod extends Twig_Environment
{
    var $__globals;
    
    function render($name, array $context = array())
    {
        return parent::render($name, array_merge($this->__globals, $context));
    }
}