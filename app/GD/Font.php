<?php


namespace App\GD;


use Swoft\Co;
use Swoft\Stdlib\Helper\Dir;

class Font implements JsAccessible
{
    public $path;

    public function __construct($name)
    {
        $this->path = Base::res($name,'font');
    }
}
