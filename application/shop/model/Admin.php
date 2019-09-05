<?php
namespace app\shop\model;

use think\Db;
use think\Model;
use think\Session;

/**
 * 后台model基础类
 *
 * Class Admin
 * @package app\admin\model
 */
class Admin extends Model
{
    //格式化请求参数
    protected function fmtRequest( $request = [] )
    {
        if( empty($request) ) {
            return $request;
        }
        $offset = 0;
        if (isset($request['offset']) && is_numeric($request['offset']) ) {
            $offset = $request['offset'];
            unset($request['offset']);
        }
        $limit = 5;
        if (isset($request['limit']) && is_numeric($request['limit']) ) {
            $limit = $request['limit'];
            unset($request['limit']);
        }
        $ret = [
            'offset'=>$offset,
            'limit'=>$limit,
            'map'=>$request
        ];
        return $ret;
    }
}
