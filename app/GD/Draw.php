<?php

namespace App\GD;

class Draw
{
    private $main;
    public $replace = [];
    public $img = [];
    public $draw = [];
    public $input;
    public $script;

    public function __construct($str,$script,$input)
    {
        $this->input = $input;
        $this->draw = json_decode($str,true);
        $this->script=$script;
    }
    public function main(){
        $this->main = Image::fromString(Base::readFile(Base::res($this->draw['background'], 'image')));
        $dmi=new DMI();
        $dmi->main=$this->main;
        $script=$this->script;
        (function()use($dmi,$script){
            eval($script);
        })->call($this);
        return $this->main->getAsString();
    }
}
