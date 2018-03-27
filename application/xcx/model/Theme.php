<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/20
 * Time: 15:48
 */

namespace app\xcx\model;


class Theme extends BaseModel
{



    public function topicImg(){

        return $this->belongsTo('image','topic_img_id','id');//一对一关系中该出发表有外键用belongsstoTbelongO，没有外键用hasOne
    }

    public function headImg(){

       return $this->belongsTo('Image','head_img_id','id');

        //return $this->hasOne('Image','head_img_id','id');
    }

    public function withProduct(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public function gethemeproduct(){
       $data=self::with(['headImg','withProduct'])
           ->find();
       return $data;
    }
}