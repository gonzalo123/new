Creating new instances auto injecting dependencies from a DIC

```php
class Foo
{
    public function hi($name)
    {
        return "Hi $name";
    }
}

class Another
{
    public function bye($name)
    {
        return "Bye $name";
    }
}

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
        return $this->foo->hi($name . " " . $this->surname) . ' ' . $another->bye($name);
    }
}

$container = new Pimple\Container   ();
$container['name'] = "Gonzalo2";

$builder = new G\Builder($container);

/** @var Bar $bar */
$bar = $builder->create('Bar', ['surname' => 'Ayuso']);
var_dump($builder->call([$bar, 'hi']));

var_dump($bar->hi(new Another(), 'xxxxx'));
```
