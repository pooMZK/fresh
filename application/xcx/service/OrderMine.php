<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/7
 * Time: 19:32
 */

namespace app\xcx\service;


use app\lib\exception\MyException;
use app\xcx\model\Product;

 class OrderMine
{
    protected $oproducts;
    protected $products;
    protected $uid;

    public  function order($uid,$oproducts){
        $this->oproducts=$oproducts;
        $this->uid=$uid;
        $this->products=$this->getProducts($oproducts);

        $orderStatus=$this->orderStatus();
        //库存可以不抛异常，方便前段来，可以给个-1
        if($orderStatus['pass']==-1){
            $noids=$orderStatus['nopassID'];
            $noids=implode(',',$noids);
            throw new MyException('无id为'.$noids.'的商品',404,80000);
        }

        return $orderStatus;

    }


    /**
     * 根据商品id取出数据
     * @param $oproducts
     * @return mixed
     */
    private function getProducts($oproducts){
        $ids=[];
        foreach ($oproducts as $val){
            $ids[]=$val['id'];
        }
        $data=Product::all($ids)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $data;
    }


    public function orderStatus()
    {


        $Status = [
            'pass' => 1,//默认为通过状态，如无产品则为-1，添加不通过id（array），不建议直接抛，看情况返回数据
            'haveStock' => 1,//库存，跟上一行一样的逻辑
            'pStatus' => [],//商品信息：子数组为单个商品含数量
            //如果无商品这里会多一个字段：nopassID,已数组形式返回无商品的ID号，后期会在缓存数据库中写一组id对name的缓存数据
            //由于无商品这个情况可能性：数据库被人修改，缓存数据库与mysql数据因时间不一致等，属于少数情况所以可以直接抛异常，这类情况属于可控的不合产品逻辑事件
            //如果库存不足这里会多一个字段noStock，返回库存不足的商品id号（数组），不建议直接抛，告知前端这个情况就行，前端可自行处理，如果前端懒就给他以throw exception方式抛json回去

        ];


        foreach ($this->oproducts as $oproduct) {
            $i = 0;
            $ids = [];

            foreach ($this->products as $product) {

                $ids[] = $product['id'];


                if ($oproduct['id'] == $product['id']) {
                    $pStatus[$i]['id'] = $product['id'];
                    $pStatus[$i]['name'] = $product['name'];
                    $pStatus[$i]['count'] = $oproduct['count'];
                    $pStatus[$i]['totalPrice'] = $product['price'] * $oproduct['count'];


                    $pStatus[$i]['Stock'] = $product['stock'] - $oproduct['count'];

                    if ($pStatus[$i]['Stock'] < 0) {
                        $Status['haveStock'] = -1;
                        $Status['noStock'][] = $pStatus[$i]['id'];
                        //    throw new MyException($oproduct['id'].'商品库存不足',404,80000);
                    }

                }

                $i++;


            }


            if (in_array($oproduct['id'], $ids) == false) {
                $Status['pass'] = -1;
                $Status['nopassID'][] = $oproduct['id'];

            }


        }
        $Status['pStatus']=$pStatus;
        return $Status;
    }

}