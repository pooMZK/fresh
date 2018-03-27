<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/20
 * Time: 19:42
 */

namespace app\xcx\controller\v1;


use app\lib\exception\MyException;
use app\xcx\validate\Count;
use think\Controller;
use app\xcx\validate\IDMustBePostiveInt;

class Product extends Controller
{

    public function getRencent($count=15){
        (new Count())->gocheck($count);


        $data=model('product')->getByTime($count);
        if($data->isEmpty()){//tp5对collection的判空，数组可以用!$data
            new MyException('无所需数据，请检查参数');
        }
     //   $collection=collection($data)->hidden(['summary']);//tp5的collection方法将select出的一组对象收集为一个对象
         $data=$data->hidden(['summary']);//database配置下'resultset_type'  => 'collection',    array为数组  非数组下注意判空，数据只是对象的一个属性
     //   return json_encode($data,JSON_UNESCAPED_UNICODE);

        return $data;
      //  var_dump($collection);
    }

    public function getByCatId($id){
        (new IDMustBePostiveInt())->gocheck($id);



        $data=model('product')->getByCatId($id);

        if($data->isEmpty()){
           throw new MyException('无id为'.$id.'数据，请更改参数',404,5000);
        }

        return $data;
    }

    public function getOne($id){
        (new IDMustBePostiveInt())->gocheck($id);
        $data=model('Product')->getProductDetail($id);
       // halt($data);//test $id=1122  $data=null  用isEmpty显示方法不存在
        if(!$data){
            throw new MyException('无id为'.$id.'数据，请更改参数',404,5000);
        }
        return $data;

    }
}