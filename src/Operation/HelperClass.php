<?php

namespace Kitty\AppSlice\HelperClass;

class HelperClass
{
    public function out($str)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') return $str;
        return iconv("UTF-8", "GBK", $str);
    }

    public function in($str)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') return $str;
        return iconv("GBK","UTF-8", $str);
    }
}
