<?php

class Bar
{
    private $foo;

    public function __construct(Foo $foo, $surname = null)
    {
        $this->foo     = $foo;
        $this->surname = $surname;
    }

    public function hello($name)
    {
        return __CLASS__ . '::' . __FUNCTION__ . ' ' . $this->foo->hello($name);
    }

    public function hi(Another $another, $name)
    {
        return __CLASS__ . '::' . __FUNCTION__ . ' ' . $this->foo->hello($name . " " . $this->surname) . ' ' . $another->bye($name);
    }
}
