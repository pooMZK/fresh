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

class Banner extends Model
{

    protected $hidden=['delete_time','update_time'];
    public function getBannerById($id){
        //TODO:根据id号获取banner
//        $data=Db::table('banner_item')->where('banner_id',1)->select();
//        return $data;
        $data=$this->with('items.img')
            ->find($id);
        return $data;
    }

    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
}