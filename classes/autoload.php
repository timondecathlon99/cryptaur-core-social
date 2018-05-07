<?php
function my_autoloader($class) {
    require( __DIR__ ."/$class.php");
}  
spl_autoload_register('my_autoloader');


