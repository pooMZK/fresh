<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/1/20
 * Time: 15:45
 */

namespace app\xcx\controller\v1;


use app\xcx\validate\IDCollection;
use app\xcx\validate\IDMustBePostiveInt;
use think\Controller;
use app\xcx\model\Theme as ThemeModel;
class Theme extends Controller
{
    /***
     * @url /theme?ids=id1,id2......
     * @return 一组theme模型
     */
    public function getSimpleList($ids){
        (new IDCollection())->gocheck($ids);

        $ids=explode(',',$ids);
        $ids=array_unique($ids);//删除重复的值

        $data=ThemeModel::with(['headImg','topicImg'])->select($ids);

     //   return $data;
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    public function getThemeProduct($id){
        if(!$id){
            echo 222;
        }
        (new IDMustBePostiveInt())->gocheck($id);

        $data=model('theme')->gethemeproduct($id);
        return $data;

    }

}