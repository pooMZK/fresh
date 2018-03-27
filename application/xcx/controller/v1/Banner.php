<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/12
 * Time: 16:02
 */

namespace app\xcx\controller\v1;


use app\lib\exception\BannerMissException;
use app\lib\exception\MyException;
use app\xcx\validate\IDMustBePostiveInt;
use think\Controller;
use think\Log;

class Banner extends Controller
{

    /***
     * 获取id的banner信息
     * @id banner的id号
     * @url /banner/:id;
     * @http get请求方式
     */
    public function getBanner($id){


        $validate=new IDMustBePostiveInt();
        $validate->gocheck();

//      $data=model('Banner')->with(['items','items.img'])->find($id);
//        $mo=new \app\xcx\model\Banner();
//      $data=$mo::with('items')->find($id);
        $data=model('banner')->getBannerById($id);
        if(!$data){
            throw new BannerMissException();
        }
        return $data;




    }

}