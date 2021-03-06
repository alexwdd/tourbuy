<?php
namespace app\adminx\controller;
use think\Session;

class Shop extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Shop')->getList();
			$map['cate']=5;	
            $cateArr = db('OptionItem')->where($map)->column('id,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cid']])) {
                    $result['data'][$key]['shopCate'] = $cateArr[$value['cid']];
                }                
            }
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        if($data['status']==0 && $data['id']!=''){
	        	db("Flash")->where('shopID',$data['id'])->delete();
                cache('flash', NULL);

                db('Goods')->where('shopID',$data['id'])->setField('show',0);
	        }
	        return model('Shop')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Shop')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
				if ($list['image']) {
                    $image = explode(",", $list['image']);
                } else {
                    $image = [];
                }
                $this->assign('image', $image);

                if ($list['cate']) {
                    $cateIds = explode(",", $list['cate']);
                } else {
                    $cateIds = [];
                }
                $this->assign('cateIds', $cateIds);
			}else{
				$list['status'] = 1;
				$list['group'] = 0;
			}
			$this->assign('list', $list);

			$city = db("City")->select();
			$this->assign('city', $city);

			$cate = db("GoodsCate")->where('fid',0)->order('sort asc , id asc')->select();
			$this->assign('cate',$cate);

			unset($map);
			$map['cate']=5;
            $shopCate = db('OptionItem')->field("id,name")->where($map)->order('value asc')->select();
            $this->assign('shopCate', $shopCate);
			return view();
		}
	}

	public function go(){
		$id = input('get.id');		
		$list = model('Shop')->find($id);
		if (!$list) {
			$this->error('信息不存在');
		}
        Session::set('shopinfo', $list, 'shop');
        $this->redirect('shop/index/index');
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Shop')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	public function shop(){
        if (request()->isPost()) {
            $result = model('ShopComment')->getList($map);
            echo json_encode($result);
        }else{
            return view();
        }
    }

    public function checkShopComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要取消的数据');
        }else{
            $map['id'] = array('in',$id);
            db("ShopComment")->where($map)->setField('status',1);
            $this->success("操作成功");
        }
    }

    public function delShopComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('ShopComment')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>