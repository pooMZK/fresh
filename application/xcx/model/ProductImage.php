<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/6
 * Time: 15:05
 */

namespace app\xcx\model;


class ProductImage extends BaseModel
{

    protected $hidden=['img_id','delete_time','product_id'];

    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}