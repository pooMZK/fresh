<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/15
 * Time: 13:56
 */

namespace app\xcx\model;


use think\Db;
use think\Model;

class Category extends BaseModel
{

    protected $hidden=['delete_time','update_time'];

    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}