<?php

namespace Kitty\AppSlice\Operation;

class HelperClass
{
    public function out($str)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') return $str . "\n";
        return iconv("UTF-8", "GBK", $str . "\r\n");
    }

    public function in($str)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') return $str . "\n";
        return iconv("GBK", "UTF-8", $str . "\r\n");
    }

    function insertToStr($str, $i, $substr)
    {
        //指定插入位置前的字符串
        $startstr = "";
        for ($j = 0; $j < $i; $j++) {
            $startstr .= $str[$j];
        }
        //指定插入位置后的字符串
        $laststr = "";
        for ($j = $i; $j < strlen($str); $j++) {
            $laststr .= $str[$j];
        }
        //将插入位置前，要插入的，插入位置后三个字符串拼接起来
        return $startstr . $substr . $laststr;
    }

    public function strEndPlace($str, $substr)
    {
        $star = strpos($str, $substr);
        return $star + strlen($substr);
    }

}
