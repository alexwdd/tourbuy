<?php
namespace app\adminx\controller;

class Chongzhi extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Finance')->getList(3);
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

    #添加
    public function pub() {
        if(request()->isPost()){
            $data['money'] = input('post.money');
            $account = input('post.account');

            $user = db('Member');
            $map['id'] = $account;
            $rs = $user->where($map)->find();

            if (!$rs) {
                $this->error('会员不存在！');
            }
            
            $data['type'] = 3;
            $data['memberID'] = $rs['id'];
            $data['doID'] = $this->admin['id'];
            $data['admin'] = 1;
            $data['msg'] = '账户充值'.$data['money'].'元';
            $data['createTime'] = time();
            
            $result = db("Finance")->insert($data);
            if ($result) {
                $this->success('充值成功');
            }else{
                $this->error('充值失败');
            }
        }else{
            return view();
        }
    }

	#删除
    public function del() {
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('Finance')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>