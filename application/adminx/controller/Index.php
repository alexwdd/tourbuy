<?php
namespace app\adminx\controller;

class Index extends Admin {

    public function index(){
        $menu = $this->getMenu();
        $this->assign('menu',$menu);                
    	return view();
    }

    public function console(){
        $member = db("Member")->count();
        $shop = db("Shop")->count();
        $map['payStatus'] = 1;
        $order = db("Order")->where($map)->count();

        unset($map);
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $map['createTime'] = array('between',array($beginToday,$endToday));
        $map['payStatus'] = 1;
        $today = db('Order')->where($map)->sum('total');

        $this->assign('member',$member);
        $this->assign('shop',$shop);
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
            
            $dateArr = implode(",",$dateArr);
            $moneyArr = implode(",",$moneyArr);
            $monthData = [
                'date'=>$dateArr,
                'money'=>$moneyArr
            ];
            echo json_encode($monthData);
        }
    }

    public function phb(){
        $createDate = input('post.createDate');
        $pageSize = input('post.limit',20);
        $field = input('post.field','num');
        $order = input('post.order','desc');

        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];            
        }else{
            $endDate = date('Y-m-d');
            $starDate = date("Y-m-d",strtotime("-30 day"));
        }
        $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));

        $obj = db('OrderDetail');        
        $total = $obj->where($map)->group('goodsID')->count();

        $pages = ceil($total/$pageSize);
        $pageNum = input('post.page',1);
        $firstRow = $pageSize*($pageNum-1);

        $list = $obj->field('goodsID,name,sum(number) as num')->where($map)->group('goodsID')->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();

        $data = array(
                'code'=>0,
                'count'=>$total,
                'data'=>$list
            );
        echo json_encode($data);        
    }

    public function stock(){
        $pageSize = input('post.limit',20);
        $field = input('post.field','id');
        $order = input('post.order','desc');

        $obj = db('Goods');        
        $total = $obj->count();

        $pages = ceil($total/$pageSize);
        $pageNum = input('post.page',1);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $obj->where($map)->field('id,name,short,stock')->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        $data = array(
                'code'=>0,
                'count'=>$total,
                'data'=>$list
            );
        echo json_encode($data);
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