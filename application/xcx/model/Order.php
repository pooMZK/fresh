<?php
/**
 * Created by PhpStorm.
 * User: my
 * Date: 2018/2/9
 * Time: 15:33
 */

namespace app\xcx\model;


class Order extends BaseModel
{

    protected $hidden=['user_id','delete_time','update_time'];
}