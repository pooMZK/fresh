<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/23
 * Time: 15:26
 */

namespace app\xcx\controller\v1;


use app\lib\exception\MyException;
use think\Controller;

class Category extends Controller
{

    public function getAll(){
        $data=model('category')->all([],'img');
        if($data->isEmpty()){
            new MyException('无数据，请检查参数',400,50000);
        }
        return $data;
    }
}