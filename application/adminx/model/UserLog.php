<?php
namespace app\adminx\model;
use \think\Config;
use think\Db;
use \think\Model;
use \think\Session;
/**
 * 角色权限
 *
 * @author chengbin
 */
class UserLog extends Admin
{

    public function getLoginTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public function getList($userid=null)
    {
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');

        if ($userid!='' && is_numeric($userid)) {
            $map['uid'] = $userid;
        }
        $total = $this->where($map)->count();        
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $list[$key]['username'] = db('User')->where(array('id'=>$value['uid']))->value("username");
            }
        }
        $result = array(
            'code'=>0,
            'data'=>$list,
            "pageNum"=>$pageNum,
            "pageSize"=>$pageSize,
            "pages"=>$pageSize,
            "count"=>$total
        );
        return $result; 
    }    

    public function del($userid){
    	$this->where('uid','in',$userid)->delete();
    }

    public function delByID($id){
        return $this->destroy($id);
    }

}
