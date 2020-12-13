<?php


namespace App\GD;


use ReflectionClass;

class StaticClass
{
    private $reflection;

    public function __construct(ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    public function instance(...$args){
        return $this->reflection->newInstanceArgs($args);
    }

    public function __get($name){
        return $this->reflection->getStaticPropertyValue($name);
    }

    public function __call($name,$args){
        $method = $this->reflection->getMethod($name);
        if($method->isStatic()){
            return $method->invokeArgs(null,$args);
        }
    }
}
