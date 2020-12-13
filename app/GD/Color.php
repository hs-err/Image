<?php


namespace App\GD;


class Color implements JsAccessible
{
    public $red;
    public $green;
    public $blue;
    public $alpha;

    public function __construct($red=0, $green=0, $blue=0, $alpha=0)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    public static function fromHex($color,$alpha=0){
        $c = (new Color())->setAsHex($color);
        $c->alpha=$alpha;
        return $c;
    }

    public function getAsHex()
    {
        $r = dechex($this->red);
        $g = dechex($this->green);
        $b = dechex($this->blue);
        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        return '#'.$color;
    }

    public function setAsHex($color)
    {
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        if (strlen($color) == 6) {
            $r=$color[0].$color[1];
            $g=$color[2].$color[3];
            $b=$color[4].$color[5];
        }elseif(strlen($color) == 3){
            $r=$color[0].$color[0];
            $g=$color[1].$color[1];
            $b=$color[2].$color[2];
        }else{
            return false;
        }
        $this->red = hexdec($r);
        $this->green = hexdec($g);
        $this->blue = hexdec($b);
        return $this;
    }
}
