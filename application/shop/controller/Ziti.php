<?php
namespace app\shop\controller;
use think\Cache;

class Ziti extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			//$map['status'] = 1;
            $map['type'] = 0;
            $map['shopID'] = $this->admin['id'];
			$result = model('OrderBaoguo')->getList($map);			
			echo json_encode($result);
    	}else{
            $this->assign('type',config('BAOGUO_TYPE'));
	    	return view();
    	}
	}

	public function hexiao(){
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            foreach ($id as $key => $value) {
            	unset($map);
            	$map['id'] = $value;
            	$map['status'] = 1;
            	$map['shopID'] = $this->admin['id'];
            	$map['type'] = 0;
            	$list = db("OrderBaoguo")->where($map)->find(); 
            	if($list && $list['hexiao']==0){
            		db("OrderBaoguo")->where('id',$value)->update(['hexiao'=>1,'updateTime'=>time()]);
            		
            		$count = db("OrderBaoguo")->where(['orderID'=>$list['orderID'],'hexiao'=>1])->count();
            		$count1 = db("OrderBaoguo")->where(['orderID'=>$list['orderID']])->count();
            		if($count==$count1){
            			db("Order")->where('id',$list['orderID'])->setField("status",3);
            		}
            	}
            }
			$this->success("操作成功");
		}
	}

    public function export(){
        $createDate = input('get.date');
        $ids = input('get.ids');
        $hexiao = input('get.hexiao');
        if ($hexiao!='') {
            $map['hexiao'] = $hexiao;
        }
        if ($ids!='') {
            $map['id'] = array('in',$ids);;
        }        
        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }
        $map['status'] = 1;
        $map['type'] = 0;
        $map['shopID'] = $this->admin['id'];
        $list = db('OrderBaoguo')->where($map)->order('id desc')->select();
        foreach ($list as $key => $value) {
            //db("OrderBaoguo")->where('id',$value['id'])->setField('flag',1);           
            $order = db('Order')->field("total,status,comment")->where('id',$value['orderID'])->find();

            $list[$key]['total'] = $order['total'];
            $list[$key]['status'] = getOrderStatus($order);
            $goods = db("OrderDetail")->where("baoguoID",$value['id'])->select();
            $content = '';
            foreach ($goods as $k => $val) {
                $goodsName = $val['short'];
                if ($val['specID']>0) {
                    $spec = db("GoodsSpecPrice")->where('item_id',$val['specID'])->value("key_name");
                    $goodsName .= '('.$spec.')'; 
                }                               
                if ($k==0) {
                    $content .= $goodsName.'*'.$val['number'];
                }else{
                    $content .= ";".$goodsName.'*'.$val['number'];
                }               
            }
            $list[$key]['goods'] = $content;
        }

        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '订单号')
            ->setCellValue('C1', '快递号')
            ->setCellValue('D1', '订单金额')
            ->setCellValue('E1', '支付状态')
            ->setCellValue('F1', '会员ID')
            ->setCellValue('G1', '收件人')
            ->setCellValue('H1', '电话')
            ->setCellValue('I1', '地址')
            ->setCellValue('J1', '快递')
            ->setCellValue('K1', '商品')
            ->setCellValue('L1', '店铺');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, ' '.$v['order_no'])                
                ->setCellValue('C'.$num, '无')
                ->setCellValue('D'.$num, $v['total'])                 
                ->setCellValue('E'.$num, $v['status'])                 
                ->setCellValue('F'.$num, $v['memberID'])                 
                ->setCellValue('G'.$num, $v['name'])                 
                ->setCellValue('H'.$num, $v['tel'])
                ->setCellValue('I'.$num, $v['province'].'/'.$v['city'].'/'.$v['county'].'/'.$v['addressDetail'])
                ->setCellValue('J'.$num, $v['express'])
                ->setCellValue('K'.$num, $v['goods'])
                ->setCellValue('L'.$num, $v['sender'].'/'.$v['senderTel']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('自提包裹');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="自提包裹'.date("Y-m-d",time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }
}
?>