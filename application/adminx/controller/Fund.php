<?php
namespace app\adminx\controller;

class Fund extends Admin {

	#列表
	public function index() {
        if (request()->isPost()) {
            $result = model('Fund')->getList();
            echo json_encode($result);
        }else{
	       return view();
        }
	}

	#添加
    public function pub() {
        if(request()->isPost()){
            set_time_limit(1800);
            $date = input('post.date');
            if($date==''){
                $this->error("月份错误");
            }
            $res = db("Fund")->where('date',$date)->find();
            if($res){
                $this->error("重复返利");
            }
            $list = db("Member")->field('id')->select();
            $config = tpCache('member');
            $total = 0;
            foreach ($list as $key => $value) {
                $fina = $this->getUserMoney($value['id']);
                $result = getFundBack($fina['point']);
                $bar = $result['bar'];

                $money = round(($bar * $fina['fund'] /100),2);
                if($money>0){
                    $data['type'] = 7;
                    $data['money'] = $money;
                    $data['memberID'] = $value['id'];
                    $data['doID'] = $this->admin['id'];
                    $data['admin'] = 1;
                    $data['msg'] = $date.'，'.$fina['point'].'积分，基金'.$fina['fund'].'元，返利'.$money.'元';
                    $data['createTime'] = time();                    
                    $result = db("Finance")->insert($data);
                    $total += $money;
                }

                if($fina['point']>0){
                    $data['type'] = 8;
                    $data['money'] = $fina['point'];
                    $data['memberID'] = $value['id'];
                    $data['doID'] = $this->admin['id'];
                    $data['admin'] = 1;
                    $data['msg'] = $date.'每月积分清零';
                    $data['createTime'] = time();                    
                    $result = db("Finance")->insert($data);
                }

                if($fina['fund']>0){
                    $data['type'] = 6;
                    $data['money'] = $fina['point'];
                    $data['memberID'] = $value['id'];
                    $data['doID'] = $this->admin['id'];
                    $data['admin'] = 1;
                    $data['msg'] = $date.'每月基金清零';
                    $data['createTime'] = time();                    
                    $result = db("Finance")->insert($data);
                }
            }

            $fundData = [
                'date'=>$date,
                'money'=>$total,
                'createTime'=>time()
            ];

            db("Fund")->insert($fundData);
            $this->success("操作成功");
        }else{
            return view();
        }
    }
}
?>