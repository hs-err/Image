<?php


namespace App\GD;


class Image implements JsAccessible
{
    public $gd;
    private function __construct($gd)
    {
        $this->gd=$gd;
        imagesavealpha($gd, true);
    }
    public function __clone()
    {
        $w = imagesx($this->gd);
        $h = imagesy($this->gd);
        $trans = imagecolortransparent($this->gd);
        if (imageistruecolor($this->gd)) {
            $clone = imagecreatetruecolor($w, $h);
            imagealphablending($clone, false);
            imagesavealpha($clone, true);
        } else {
            $clone = imagecreate($w, $h);
            if($trans >= 0) {
                $rgb = imagecolorsforindex($this->gd, $trans);
                imagesavealpha($clone, true);
                $trans_index = imagecolorallocatealpha($clone, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                imagefill($clone, 0, 0, $trans_index);
            }
        }
        imagecopy($clone, $this->gd, 0, 0, 0, 0, $w, $h);
        $this->gd=$clone;
    }
    public function destroy(){
        imagedestroy($this->gd);
    }

    public static function create(Vector $size){
        $gd=imagecreatetruecolor($size->x,$size->y);
        $image = new Image($gd);
        $image->fill(new Point(),new Color(0,0,0,127));
        return $image;
    }
    public static function fromString($data){
        $gd=imagecreatefromstring($data);
        $image=new Image($gd);
        return $image;
    }
    public static function fromFile($file){
        return self::fromString(Base::readFile(Base::res($file,'image')));
    }

    private function getColor(Color $color){
        return imagecolorallocatealpha($this->gd, $color->red, $color->green, $color->blue, $color->alpha);
    }

    public function getSize(){
        return new Vector($this->getWidth(),$this->getHeight());
    }
    public function getWidth(){
        return imagesx($this->gd);
    }
    public function getHeight(){
        return imagesy($this->gd);
    }

    public function getAsString(){
        ob_start();
        imagepng($this->gd);
        $op = ob_get_contents();
        ob_end_clean();
        return $op;
    }
    public function __toString(){
        return $this->getAsString();
    }

    public function fill(Point $s,Color $color){
        imagefill($this->gd, $s->x, $s->y,$this->getColor($color));
    }
    public function resize(Vector $new_size){
        $image=Image::create($new_size);
        $image->drawImage(
            $this,
            new Box(new Point(),$this->getSize()),
            new Box(new Point(),$new_size));
        imagedestroy($this->gd);
        $this->gd=$image->gd;
    }
    public function attachImage(Image $image, $from, $to, $alpha = 0){
        $result = $this->drawImage($image, $from, $to, $alpha);
        $image->destroy();
        return $result;
    }
    public function drawImage(Image $image, $from, $to, $alpha = 0)
    {
        $image = clone $image;
        if($from instanceof Point){
            $from=new Box($from,new Vector($image->getWidth(),$image->getHeight()));
        }
        if($to instanceof Point){
            $to=new Box($to,new Vector($image->getWidth(),$image->getHeight()));
        }
        imagealphablending($image->gd, false);
        $w = $image->getWidth();
        $h = $image->getHeight();
        $min_alpha = 127;
        for ($x = 0; $x < $w; $x++)
            for ($y = 0; $y < $h; $y++) {
                $per_alpha = (imagecolorat($image->gd, $x, $y) >> 24) & 0xFF;
                if ($per_alpha < $min_alpha) {
                    $min_alpha = $per_alpha;
                }
            }
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $color_xy = imagecolorat($image->gd, $x, $y);
                $per_alpha = ($color_xy >> 24) & 0xFF;
                if( $min_alpha !== 127 ){
                    $per_alpha = 127 + (127-$alpha) * ( $per_alpha - 127 ) / ( 127 - $min_alpha );
                } else {
                    $per_alpha = 127-$alpha;
                }
                $alpha_color_xy = imagecolorallocatealpha($image->gd, ($color_xy >> 16) & 0xFF, ($color_xy >> 8) & 0xFF, $color_xy & 0xFF, $per_alpha);
                if (!imagesetpixel($image->gd, $x, $y, $alpha_color_xy)) {
                    return false;
                }
            }
        }
        imagecopyresized(
            $this->gd, $image->gd,
            $to->from->x, $to->from->y,
            $from->from->x, $from->from->y,
            $to->way->x, $to->way->y,
            $from->way->x, $from->way->y);
        $image->destroy();
        return true;
    }
    public function drawText($text, Point $point, $size, Font $font, Color $color, $angle){
        $lines = explode("\n", $text);
        $x = $point->x;
        $y = $point->y;
        foreach ($lines as $line) {
            $raws = explode('§', $line);
            $color_now = $this->getColor($color);
            $nx = $x;
            $ny = $y;
            $isf = true;
            foreach ($raws as $raw) {
                if (!$isf && Base::color_code(substr($raw, 0, 1))) {
                    $color_code = Base::color_code(substr($raw, 0, 1));
                    $color_now = $this->getColor(new Color(
                        Base::code_color($color_code)[0],
                        Base::code_color($color_code)[1],
                        Base::code_color($color_code)[2],
                        $color->alpha
                    ));
                    $text = substr($raw, 1);
                } elseif ($isf) {
                    $text = $raw;
                } else {
                    $text = '§' . $raw;
                }
                $isf = false;
                $box = imagettfbbox($size, $angle, $font->path, $text);
                imagefttext($this->gd, $size, $angle, $nx, $ny - $box[7] + 2, $color_now, $font->path, $text);
                $nx += $box[2];
                $ny += $box[3] - $box[1];
            }
            $y += $size + 4;
        }
    }
}
