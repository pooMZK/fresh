<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 16:53
 */

namespace app\xcx\validate;
class Count extends BaseValidate
{

    protected $rule = [

        'count' => 'isPositiveInteger|between:1,20'
    ];
    protected $message = [
        'count.isPositiveInteger' => '请输入正整数',
       // 'count.between' => '你要上天啊？'

    ];

}