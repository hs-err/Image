<?php


namespace App\GD;


use ReflectionClass;

class DMI implements JsAccessible
{
    /** @var Image $main */
    public $main;

    public function res($file,$type){
        return Base::res($file,$type);
    }

    public function resData($file,$type){
        return Base::readFile(Base::res($file,$type));
    }

    public function __call($name, $args)
    {
        if (preg_match("/^[A-Z0-9]*$/", substr($name,0,1))) {
            $reflection = new ReflectionClass('App\\GD\\' . $name);
            if ($reflection->isSubclassOf(JsAccessible::class)) {
                return $reflection->newInstanceArgs($args);
            }
        }
    }
    public function __get($name){
        if (preg_match("/^[A-Z0-9]*$/", substr($name,0,1))) {
            $reflection = new ReflectionClass('App\\GD\\' . $name);
            if ($reflection->isSubclassOf(JsAccessible::class)) {
                return new StaticClass($reflection);
            }
        }
    }
}
