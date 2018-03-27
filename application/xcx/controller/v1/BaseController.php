<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/7
 * Time: 17:00
 */

namespace app\xcx\controller\v1;

use app\xcx\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{

    /**
     * 必须是用户权限检查
     */
    protected function checkPrimaryScope(){
        TokenService::UserSuperScope();
    }


    /**
     * 必须是用户或管理员权限检查
     */
    protected function checkExclusiveScope(){
        TokenService::needUserScope();

    }
}