<?php

class Bar
{
    private $foo;

    public function __construct(Foo $foo, $surname = null)
    {
        $this->foo     = $foo;
        $this->surname = $surname;
    }

    public function hi(Another $another, $name)
    {
        return $this->foo->hello($name . " " . $this->surname) . ' ' . $another->bye($name);
    }
}
