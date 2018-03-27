<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/24
 * Time: 14:08
 */

namespace app\xcx\validate;


class TokenGet extends BaseValidate
{
    protected $rule=[
        'code'=>'require|isNotEmpty'
    ];

    protected $message=[
        'code'=>'无code不得获取信息'
    ];


}