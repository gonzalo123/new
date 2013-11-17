<?php

use G\Builder;

include __DIR__ . "/fixtures/Foo.php";
include __DIR__ . "/fixtures/Bar.php";
include __DIR__ . "/fixtures/Another.php";
include __DIR__ . "/fixtures/Baz/Foo.php";

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_instance()
    {
        $container = new Pimple();
        $builder = new Builder($container);
        /** @var Foo $foo */
        $foo = $builder->create('Foo');
        $this->assertInstanceOf('Foo', $foo);
        $this->assertEquals('Foo::bar', $foo->bar());
    }

    public function test_calling_function_with_arguments()
    {
        $container = new Pimple();
        $builder = new Builder($container);
        /** @var Foo $foo */
        $foo = $builder->create('Foo');
        $this->assertInstanceOf('Foo', $foo);
        $callable = [$foo, 'hello'];
        $this->assertEquals('Hello Gonzalo', $builder->call($callable, ['name' => 'Gonzalo']));
    }

    public function test_calling_function_with_arguments_from_container()
    {
        $container = new Pimple();
        $container['name'] = 'Gonzalo';
        $builder = new Builder($container);
        /** @var Foo $foo */
        $foo = $builder->create('Foo');
        $this->assertInstanceOf('Foo', $foo);
        $callable = [$foo, 'hello'];
        $this->assertEquals('Hello Gonzalo', $builder->call($callable));
    }

    public function test_calling_function_with_arguments_from_container_namespaced()
    {
        $container = new Pimple();
        $container['name'] = 'Gonzalo';
        $builder = new Builder($container);
        /** @var Baz\Foo $foo */
        $foo = $builder->create('Baz\Foo');
        $this->assertInstanceOf('Baz\Foo', $foo);
        $callable = [$foo, 'hello'];
        $this->assertEquals('Hello Gonzalo from Baz', $builder->call($callable));
    }

    public function test_instance_with_DI_in_constructor()
    {
        $container = new Pimple();
        $builder = new Builder($container);
        /** @var Bar $bar */
        $bar = $builder->create('Bar', ['foo' => new Foo()]);
        $this->assertInstanceOf('Bar', $bar);
    }

    public function test_call_with_DI_in_constructor()
    {
        $container = new Pimple();
        $builder = new Builder($container);
        /** @var Bar $bar */
        $bar = $builder->create('Bar', ['foo' => new Foo()]);
        $this->assertInstanceOf('Bar', $bar);
        $callable = [$bar, 'hello'];

        $this->assertEquals('Bar::hello Hello Gonzalo', $builder->call($callable, ['name' => 'Gonzalo']));
    }

    public function test_call_with_DI_in_constructor_with_parameters_from_container()
    {
        $container = new Pimple();
        $container['name'] = 'Gonzalo';
        $container['Foo'] = function ($c) {
            return new Foo();
        };
        $builder = new Builder($container);
        /** @var Bar $bar */
        $bar = $builder->create('Bar');
        $this->assertInstanceOf('Bar', $bar);
        $callable = [$bar, 'hello'];

        $this->assertEquals('Bar::hello Hello Gonzalo', $builder->call($callable));
    }

    public function test_call_with_DI_in_constructor_with_parameters_from_container_with_parameters_in_function_too()
    {
        $container = new Pimple();
        $container['name'] = 'Gonzalo';
        $container['Foo'] = function () {
            return new Foo();
        };
        $container['Another'] = function () {
            return new Another();
        };

        $builder = new Builder($container);
        /** @var Bar $bar */
        $bar = $builder->create('Bar');
        $this->assertInstanceOf('Bar', $bar);
        $callable = [$bar, 'hi'];
        $this->assertEquals('Bar::hi Hello Gonzalo  Bye Gonzalo', $builder->call($callable));
    }
}