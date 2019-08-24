<?php 
namespace app\adminx\controller;

class Role extends Admin{
    #列表
    public function index(){ 
        if (request()->isPost()) {
            $result = model('Role')->getList();
            echo json_encode($result);
        }else{
            return view();
        }       
    }

    #编辑
    public function pub(){
        if (request()->isPost()) {
            $data = input('post.');
            return model('Role')->saveData( $data );            
        }else{
            $id = input('get.id');
            if ($id!='' || is_numeric($id)) {
                $list = model('Role')->find($id);
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
            if(model('Role')->del($id)){                
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }       
    }

    //设置权限
    public function access(){
        if (request()->isPost()) {
            $data = input('post.');
            return model('Access')->saveData( $data );            
        }else{
            $id = (int) input('get.id');
            if (!isset ($id)) {
                $this->error('参数错误');
            }
            $role = model('Role')->find($id);
            $this->assign('role', $role);

            $nodeArr = model('Access')->getRuleNodeID($role['id']);
            $this->assign('nodeArr', $nodeArr); 
            
            $list = model('Node')->getLevelData();
            $this->assign('list', $list);
            return view();
        }
    }
}
?>