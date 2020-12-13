<?php


namespace App\GD;


class Box implements JsAccessible
{
    public $from;
    public $way;

    public function __construct(Point $from=null, Vector $way=null)
    {
        $this->from = $from?:new Point();
        $this->way = $way?:new Vector();
        $this->from=new Point(
            $this->way->x < 0 ? $this->from->x + $this->way->x : $this->from->x,
            $this->way->y < 0 ? $this->from->y + $this->way->y : $this->from->y
        );
        $this->way=new Vector(
            abs($way->x),
            abs($way->y)
        );
    }
}
