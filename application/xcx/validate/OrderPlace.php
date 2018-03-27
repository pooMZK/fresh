<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/7
 * Time: 17:22
 */

namespace app\xcx\validate;


use app\lib\exception\MyException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected  $singleRule=[
        'product_id'=>'require|isPositiveInteger',
        'count'=>'require|isPositiveInteger',

    ];

    protected function checkProducts($values){

        if(is_array($values)){
            throw new MyException('参数不正确');
        }
        if(empty($values)){
            throw new MyException('商品不能为空');
        }

        foreach ($values as $value){

            $this->checkproduct($value);
        }
        return true;

    }


    protected function checkproduct($value){
        $validate=new BaseValidate($this->singleRule);
        $res=$validate->check($value);
        if($res!==true){
            throw new MyException('参数不正确');
        }

    }

}