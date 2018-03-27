<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/15
 * Time: 13:56
 */

namespace app\xcx\model;

use think\Model;

class BannerItem extends Model
{
    //protected $hidden=['id'];
    protected $visible=['img'];
    public function img(){

        return $this->belongsTo('image','img_id','id');
    }


}