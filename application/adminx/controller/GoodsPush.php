<?php
namespace app\adminx\controller;

class GoodsPush extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('GoodsPush')->getList();
            $map['cate']=1;	
            $cateArr = db('OptionItem')->where($map)->column('value,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cateID']])) {
                    $result['data'][$key]['cate'] = $cateArr[$value['cateID']];
                }                
            }
			echo json_encode($result);
    	}else{
            $map['cate']=1;
            $cate = db('OptionItem')->field("id,name,value")->where($map)->order('value asc')->select();
            $this->assign('cate', $cate);
	    	return view();
    	}
	}    

    #删除
    public function del() {
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('GoodsPush')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>