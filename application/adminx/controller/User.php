<?php 
namespace app\adminx\controller;

class User extends Admin{
    #列表
    public function index(){ 
        if (request()->isPost()) {
            $cateArr = db('Role')->where($map)->column('id,name');
            $result = model('User')->getList();
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['group']])) {
                    $result['data'][$key]['groupName'] = $cateArr[$value['group']];
                }                
            }
            echo json_encode($result);
        }else{
            return view();
        }       
    }

    #编辑
    public function pub(){
        if (request()->isPost()) {
            $data = input('post.');
            return model('User')->saveData( $data );            
        }else{
            $group = db('Role')->field('id,name')->select();
            $this->assign('group', $group);

            $id = input('get.id');
            if ($id!='' || is_numeric($id)) {
                $list = model('User')->find($id);
                if (!$list) {
                    $this->error('信息不存在');
                }
            }else{
                $list['status'] = 1;
            }
            $this->assign('list', $list);
            return view();
        }
    }

    //删除
    public function del() {
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('User')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }       
    }

    //管理员登陆日志
    public function log(){
        if (request()->isPost()){
            $uid = input('uid');
            $result = model('UserLog')->getList($uid);
            echo json_encode($result);
        }else{
            $user = db('User')->field('id,name')->select();
            $this->assign('user',$user);
            return view();
        }
    }

    //删除日志
    public function delog(){
        $id = explode(",",input('post.id'));
        if(count($id)==0){
            $this->error('您没有选择任何信息！');
        }else{
            model('UserLog')->delByID($id);
            $this->success('删除成功');
        }     
    }

    //修改密码
    function password(){
        if (request()->isPost()){
            $data = input('post.');
            return model('User')->password( $data );            
        }else{
            return view();
        }        
    }
}
?>