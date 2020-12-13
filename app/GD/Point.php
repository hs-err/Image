<?php


namespace App\GD;


class Point implements JsAccessible
{
    public $x;
    public $y;

    public function __construct($x=0, $y=0)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
