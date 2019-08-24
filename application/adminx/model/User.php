<?php
namespace app\adminx\model;

use think\Config;
use think\Db;
use think\Loader;
use think\Model;
use think\Session;

class User extends Model
{
	public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public function getUpdateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }
    
	/**
	 *  用户登录
	 */
	public function login(array $data)
	{
		$msg = '';
		$userValidate = validate('User');
		if(!$userValidate->scene('login')->check($data)) {
			return info($userValidate->getError(),0);
		}
		if($data['username']=='') {
			return info('请输入用户名',0);
		}
		$map = [
			'username' => $data['username'],
			'password' => md5($data['password'])
		];
		$userRow = $this->where($map)->find();
		if( empty($userRow) ) {
			return info('账户或密码错误',0);
		}
		return info('success', 1, '', $userRow);
	}


	public function getList()
	{
		$pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
		
        $pageSize = input('post.page',20);
        $field = input('post.field','id');
        $order = input('post.order','desc');

        $total = $this->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
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

	//获取单条
    public function find($id){
        $list = $this->get($id);
        if ($list) {
            return $list;
        }else{
            $this->error('信息不存在');
        }
    }

	public function saveData( $data )
	{
		if( isset( $data['id']) && !empty($data['id'])) {
			$result = $this->edit( $data );
		} else {
			$result = $this->add( $data );
		}
		return $result;
	}


	public function add(array $data = [])
	{
		$userValidate = validate('User');
		if(!$userValidate->scene('add')->check($data)) {
			return info($userValidate->getError());
		}
		if($data['repassword'] != $data['password']){
            return info('两次密码不同');
        }
		unset($data['repassword']);
		$data['password'] = md5($data['password']);
		$data['createTime'] = time();
		$data['updateTime'] = time();
		$this->allowField(true)->save($data);
		if($this->id > 0){
            model('RoleUser')->saveData($data['group'],$this->id);
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
	}

	public function edit(array $data = [])
	{
		$userValidate = validate('User');
		if(!$userValidate->scene('edit')->check($data)) {
			return info(lang($userValidate->getError()), 4001);
		}
		if($data['password']!=''){
            $data['password'] = MD5($data['password']);
        }else{
        	unset($data['password']);
        }
        $data['updateTime'] = time();
		$res = $this->allowField(true)->save($data,['id'=>$data['id']]);
		if($res == 1){
			model('RoleUser')->saveData($data['group'],$data['id']);
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
	}

	public function del($id)
	{
		$result = $this->destroy($id);
		if ($result) {
			model('RoleUser')->del($id);
			model('UserLog')->del($id);
			return info('操作成功', 1);
		}
	}

	public function password($data){

		$userValidate = validate('User');
		if(!$userValidate->scene('password')->check($data)) {
			return info($userValidate->getError(),0);
		}

		$user = Session::get('userinfo', 'admin');
		$oldpwd = db('User')->where(array('id'=>$user['id']))->value('password');
		if($oldpwd!=md5($data['oldpwd'])){
            return info('原始密码错误', 0);
        }else{        	
            $res = $this->allowField(true)->save(['password'=>md5($data['password'])],['id'=>$user['id']]);
            if ($res) {
            	return info('操作成功', 1);
            }else{
            	return info('操作失败', 0);
            }
        }
	}
}