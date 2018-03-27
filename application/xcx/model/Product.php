<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/20
 * Time: 15:47
 */

namespace app\xcx\model;
class Product extends BaseModel
{

    protected $hidden=['from','create_time','delete_time','update_time','pivot'];
    public function simg(){
        return $this->belongsTo('Image','img_id','id');
    }

    /***
     * 最新商品
     * @param $count:需要取的数据的数量
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getByTime($count){
        $data=$this->order('create_time desc')
            ->limit($count)
            ->select();
        return $data;
    }

    public function getByCatId($id){

        $data=$this->where('category_id','eq',$id)
            ->select();
        return $data;
    }


    public function imgs(){//产品 详情图关系，一对多
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function properties(){//产品 属性关系，一对多
        return $this->hasMany('ProductProperty','product_id','id');
    }
    public function getProductDetail($id){
        $res=$this->with('properties')
            ->with([//利用闭包函数查询构造器进行排序
                'imgs'=>function($query){
                           $query->with(['imgUrl'])
                           ->order('order','asc');
                }
            ])
            ->find($id);
        return $res;


    }

}