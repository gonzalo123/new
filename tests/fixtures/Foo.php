<?php

class Foo
{
    public function bar()
    {
        return __CLASS__ . '::' . __FUNCTION__;
    }

    public function hello($name)
    {
        return "Hello {$name}";
    }
}