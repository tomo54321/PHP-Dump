# PHP Dump
A lightweight script to provide an easy to read var_dump. Includes a class and two functions which provide a styled version of var_dump. This is heavily influenced by the Symfony VarDumper(https://symfony.com/doc/current/components/var_dumper.html).

## Functions
If the two below functions already exist in your project then they will not be overwritten, see the class below to create your own function.
### ``dump(...$vars)``
Provides the dump inline with other content, this command also adds styles to style it all up. You can provide more than one variable to dump in the functions arguments.
### ``dd(...$vars)``
The dump and die function does the same as the dump function except it clears the existing output and only displays the dump.

## Class
The only class is in the ``Tomo`` namespace and is ``DebugDump``. You can create your own function or use the class to render the dump using the example below:

```php
<?php
$var1 = [
  "Hello" => "World"
];
$var2 = "Hello world!";

(new \Tomo\DebugDump($var1, $var2))->render();
```
