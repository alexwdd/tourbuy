<?php
namespace app\adminx\controller;

class Member extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Member')->getList();
			foreach ($result['data'] as $key => $value) {
                $fina = $this->getUserMoney($value['id']);
                $result['data'][$key]['point'] = $fina['point'];
            }
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#编辑
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        if(!$data['disable']) {
	        	$data['disable'] = 0;
	        }
	        if(!$data['team']) {
	        	$data['team'] = 0;
	        }
	        if(!$data['group']) {
	        	$data['group'] = 0;
	        }
	        if ($data['id']!='') {
	    		$user = db("Member")->where(array('id'=>$data['id']))->find();
	    		if ($user['mobile']!=$data['mobile'] && $data['mobile']!='') {
	    			$num = db('Member')->where(array('mobile'=>$data['mobile']))->count();
	    			if ($num>0) {
	    				return array('code'=>0,'msg'=>'手机号码重复');die;
	    			}   			
	    		}
	        }else{
	        	$res = db("Member")->where('mobile',$data['mobile'])->find();
	        	if ($res) {
	        		return array('code'=>0,'msg'=>'手机号码重复');die;
	        	}
	        }
	        return model('Member')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Member')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
			}
			$this->assign('list', $list);
			return view();
		}
	}

	#封号
	public function disable() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要封号的会员');
		}else{
			if(model('Member')->disable($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	#解封
	public function open() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要封号的会员');
		}else{
			if(model('Member')->open($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Member')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>