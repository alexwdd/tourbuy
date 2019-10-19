<?php
namespace app\shop\controller;

class Index extends Admin {

    public function index(){  
    	return view();
    }

    public function console(){
        $map['shopID'] = $this->admin['id'];
        $goods = db("Goods")->where($map)->count();

        $map['payStatus'] = 1;
        $order = db("Order")->where($map)->count();

        unset($map);
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $map['createTime'] = array('between',array($beginToday,$endToday));
        $map['payStatus'] = 1;
        $map['shopID'] = $this->admin['id'];
        $today = db('Order')->where($map)->sum('total');

        $this->assign('goods',$goods);
        $this->assign('order',$order);
        $this->assign('today',$today);
        return view();
    }

    public function ajax(){
        if(request()->isPost()){
            $type = input('post.type');
            $dateArr = [];
            $moneyArr = [];

            if($type=='day'){
                //当日销量
                for ($i=0; $i < 24 ; $i++) { 
                    unset($map);
                    $start = date("Y-m-d ".$i.":0:0");
                    $end = date("Y-m-d ".$i.":59:59"); 
                    $start=strtotime($start);
                    $end=strtotime($end);
                    $map['createTime'] = array('between',array($start,$end));
                    $map['payStatus'] = 1;
                    $money = db('Order')->where($map)->sum('total');
                    array_push($dateArr, date("H时",$start));
                    array_push($moneyArr, $money);
                } 
            }

            if($type=='week'){
                //本月销量
                $weekarray=array("一","二","三","四","五","六","日");
                $time = $data['time'];
                $end = date("Y-m-d 23:59:59",strtotime("$time Sunday"));
                $start = date("Y-m-d 00:00:00",strtotime("$end - 6 days"));     
                for ($i=0; $i < 7 ; $i++) { 
                    unset($map);
                    $end = date('Y-m-d H:i:s', strtotime("$start +1 day -1 second")); 
                    $start=strtotime($start);
                    $end=strtotime($end);
                    $map['createTime'] = array('between',array($start,$end));
                    $map['payStatus'] = 1;
                    $money = db('Order')->where($map)->sum('total');
                    array_push($dateArr, '周'.$weekarray[$i]);
                    array_push($moneyArr, $money);
                    $start = date("Y-m-d H:i:s",$start);
                    $start = date('Y-m-d H:i:s', strtotime("$start +1 day")); 
                } 
            }

            if($type=='month'){
                //本月销量
                $dayNumber = date('t', strtotime(date("Y-m")));                
                for ($i=1; $i <= $dayNumber ; $i++) { 
                    unset($map);
                    $start = date("Y-m").'-'.$i;
                    $end = date('Y-m-d H:i:s', strtotime("$start +1 day -1 second")); 
                    $start=strtotime($start);
                    $end=strtotime($end);
                    $map['createTime'] = array('between',array($start,$end));
                    $map['payStatus'] = 1;
                    $money = db('Order')->where($map)->sum('total');
                    array_push($dateArr, date("m-d",$start));
                    array_push($moneyArr, $money);
                } 
            }

            if($type=='month'){
                //本月销量
                $dayNumber = date('t', strtotime(date("Y-m")));                
                for ($i=1; $i <= $dayNumber ; $i++) { 
                    unset($map);
                    $start = date("Y-m").'-'.$i;
                    $end = date('Y-m-d H:i:s', strtotime("$start +1 day -1 second")); 
                    $start=strtotime($start);
                    $end=strtotime($end);
                    $map['createTime'] = array('between',array($start,$end));
                    $map['payStatus'] = 1;
                    $money = db('Order')->where($map)->sum('total');
                    array_push($dateArr, date("m-d",$start));
                    array_push($moneyArr, $money);
                } 
            }
            
            $dateArr = implode(",",$dateArr);
            $moneyArr = implode(",",$moneyArr);
            $monthData = [
                'date'=>$dateArr,
                'money'=>$moneyArr
            ];
            echo json_encode($monthData);
        }
    }

    public function getMenu(){
        if ($this->admin['administrator']!=1){
            $nodeArr = db('Access')->where(array('role_id'=>$this->admin['group']))->column('node_id');
            $map['id'] = array('in',$nodeArr);
        }
        $obj = db('Node');
        $map['display'] = 1;
        $list = $obj->where($map)->order('sort asc , id asc')->select();
        foreach ($list as $key => $value) {
            unset($map);
            $map['pid'] = $value['id'];
            $default = $obj->where($map)->find();
            if ($default) {
                $list[$key]['url'] = url($default['value']);
            }else{
                if ($value['level']==2) {
                    $list[$key]['url'] = url($value['value'].'/index');
                }else{
                    $list[$key]['url'] = url($value['value']);
                }
                
            }
        }
        $menu = $this->getTree($list,0);
        return $menu;
    }

    public function getTree($obj,$data='0'){
        $arr = array();
        foreach ($obj as $key=>$value){
            if($value['pid']==$data){
                $obj[$key]['childrens'] = $this->getTree($obj,$obj[$key]['id']);                
                if(count($obj[$key]['childrens'])==0){
                    $obj[$key]['childrens']=array();
                    $obj[$key]['leaf']=0;
                }else{
                    $obj[$key]['leaf']=1;
                }
                $arr[] = $obj[$key];
            }
        }
        return $arr;
    }    

    //清除缓存
    public function clearcache(){
        $this->delDirAndFile($_SERVER['DOCUMENT_ROOT']."/runtime");
        $this->success("操作成功");
    }

    public function delDirAndFile($path){
        $path=str_replace('\\',"/",$path);
        if (is_dir($path)) {
            $handle = opendir($path);
            if ($handle) {
                while (false !== ( $item = readdir($handle) )) {
                    if ($item != "." && $item != "..")
                        is_dir("$path/$item") ? $this->delDirAndFile("$path/$item") : unlink("$path/$item");
                }
                closedir($handle);
            }
        } else {
            return false;
        }
    }
}