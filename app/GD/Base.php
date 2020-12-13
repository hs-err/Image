<?php

namespace App\GD;

use Swoft\Co;

class Base
{
    public static function res($file, $type)
    {
        if (!$file) {
            $file=self::formt_dir(\Swoft::app()->getBasePath().DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR);
        } else {
            $file=self::formt_file($file);
            $file=self::formt_file(\Swoft::app()->getBasePath().DIRECTORY_SEPARATOR.'resource'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file);
        }
        //var_dump($file);
        return $file;
    }

    public static function color_code($color)
    {
        $color_code = [
            '0' => 'black',
            'black' => '0',
            '1' => 'dark_blue',
            'dark_blue' => '1',
            '2' => 'dark_green',
            'dark_green' => '2',
            '3' => 'dark_aqua',
            'dark_aqua' => '3',
            '4' => 'dark_red',
            'dark_red' => '4',
            '5' => 'dark_purple',
            'dark_purple' => '5',
            '6' => 'gold',
            'gold' => '6',
            '7' => 'gray',
            'gray' => '7',
            '8' => 'dark_gray',
            'dark_gray' => '8',
            '9' => 'blue',
            'blue' => '9',
            'a' => 'green',
            'green' => 'a',
            'b' => 'aqua',
            'aqua' => 'b',
            'c' => 'red',
            'red' => 'c',
            'd' => 'light_purple',
            'light_purple' => 'd',
            'e' => 'yellow',
            'yellow' => 'e',
            'f' => 'white',
            'white' => 'f',
            'r' => 'white'
        ];
        return $color_code[$color];
    }

    public static function code_color($color)
    {
        $code_color = [
            'black' => [0, 0, 0],
            'dark_blue' => [0, 0, 170],
            'dark_green' => [0, 170, 0],
            'dark_aqua' => [0, 170, 170],
            'dark_red' => [170, 0, 0],
            'dark_purple' => [170, 0, 170],
            'gold' => [255, 170, 0],
            'gray' => [170, 170, 170],
            'dark_gray' => [85, 85, 85],
            'blue' => [85, 85, 255],
            'green' => [85, 255, 85],
            'aqua' => [85, 255, 255],
            'red' => [255, 85, 85],
            'light_purple' => [255, 85, 255],
            'yellow' => [255, 255, 85],
            'white' => [255, 255, 255],
        ];
        return $code_color[$color];
    }

    public static function get_absolute_path($path)
    {
        $front='';
        if (substr($path, 0, 7)=='phar://') {
            $path=substr($path, 7);
            $front='phar://';
        }
        if (DIRECTORY_SEPARATOR=='\\') {
            $path=str_ireplace('/', '\\', $path);
        }
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return $front.(substr($path, 0, 1)==DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').implode(DIRECTORY_SEPARATOR, $absolutes);
    }
    public static function formt_dir($dir)
    {
        $dir=self::get_absolute_path($dir);
        if (DIRECTORY_SEPARATOR=='\\') {
            $dir=str_ireplace('/', '\\', $dir);
            if (substr($dir, strlen($dir)-1)=='\\') {
                return $dir;
            } else {
                return $dir.'\\';
            }
        } else {
            if (substr($dir, strlen($dir)-1)=='/') {
                return $dir;
            } else {
                return $dir.'/';
            }
        }
    }
    public static function formt_file($file)
    {
        $file=self::get_absolute_path($file);
        if (DIRECTORY_SEPARATOR=='\\') {
            $file=str_ireplace('/', '\\', $file);
            if (substr($file, strlen($file)-1)=='\\') {
                return substr($file, 0, strlen($file)-1);
            } else {
                return $file;
            }
        } else {
            if (substr($file, strlen($file)-1)=='/') {
                return substr($file, 0, strlen($file)-1);
            } else {
                return $file;
            }
        }
    }

    public static function readFile($file)
    {
        return Co::readFile($file);
    }
}
