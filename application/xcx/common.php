<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 17:37
 */

/**
 * 自用日志，用于第三方如微信请求数据查看
 * @param $data
 */



/**
 * 订单号生成
 * @return string
 */
function makeOrderNo(){
    $yCode=array('A','B','C','D','E','F','G','H','I','J');
    $orderSn=
        $yCode[intval(date('Y'))-2018].strtoupper(dechex(date('m'))).date('d')
        .substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
    return $orderSn;
}



/**
 * 随机字符串
 * @param $length
 * @return null|string
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

