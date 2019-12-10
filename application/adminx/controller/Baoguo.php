<?php
namespace app\adminx\controller;
use think\Cache;

class Baoguo extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$map['status'] = 1;
            $map['type'] = array('gt',0);
            if($this->admin['administrator']==0){
                $map['cityID'] = $this->admin['cityID'];
            }
			$result = model('OrderBaoguo')->getList($map);			
			echo json_encode($result);
    	}else{
            if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);

            $express = db("Express")->select();
            $this->assign('express', $express);
	    	return view();
    	}
	}

    public function getShop(){
        $cityID = input('post.cityID');
        $list = db("Shop")->where('cityID',$cityID)->select();
        echo json_encode(['data'=>$list]);
    }

    #编辑
    public function image() {
        $id = input('get.id');
        if ($id=='' || !is_numeric($id)) {
            $this->error('参数错误');
        }
        $list = model('OrderBaoguo')->find($id);
        if (!$list) {
            $this->error('信息不存在');
        } else {
            $list['image'] = explode(",",$list['image']);
            $this->assign('list', $list); 
            return view();
        }
    }

    public function export(){
    	$cityID = input('get.cityID');
    	$shopID = input('get.shopID');
        $createDate = input('get.date');
    	$ids = input('get.ids');
    	if ($shopID!='') {
            $map['shopID'] = $shopID;
        }

        if($this->admin['administrator']==0){
            $map['cityID'] = $this->admin['cityID'];
        }else{
            if ($cityID!='') {
                $map['cityID'] = $cityID;
            }
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
                if ($val['spec']!='') {
                    //$spec = db("GoodsSpecPrice")->where('item_id',$val['specID'])->value("key_name");
                    $goodsName .= '('.$val['spec'].')'; 
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
            ->setCellValue('L1', '发件人');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, ' '.$v['order_no'])                
                ->setCellValue('C'.$num, $v['kdNo'])
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

        $objPHPExcel->getActiveSheet()->setTitle('直邮包裹');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="直邮包裹'.date("Y-m-d",time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }	

    public function import(){
        if (request()->isPost()) {
            set_time_limit(0);
            ini_set("memory_limit", "512M"); 
            
            $file = input('post.excel');
            $objReader = \PHPExcel_IOFactory::createReader ( 'Excel5' );
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load('.'.$file);
            $sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            //$highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
            $obj = db('OrderBaoguo');
            $total = 0;
            $error = '';
            for ( $i = 2; $i <= $highestRow; $i++) {
                $orderID = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());              
                $kdNo = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                $data['kdNo'] = str_replace("，",",",$kdNo);
                $obj->where('id',$orderID)->update($data);
                $total++;
            }
            
            $msg = '共'.($highestRow-1).'条数据，成功导入'.$total.'条，错误信息'.$error;
            return info($msg,1);
        }else{
            return view();
        }
    }	

    public function upload(){
        $path = '.'.config('UPLOAD_PATH');
        if(!is_dir($path)){
            mkdir($path);
        }

        $file = request()->file('file');
        $info = $file->validate(['size'=>config('image_size')*1000*1000,'ext'=>config('image_exts')])->move($path);
        if($info){
            $fileName = $info->getInfo()['name'];
            $fileName = explode(".",$fileName);
            $fileName = $fileName[0]; //文件原名
            $fname=str_replace('\\','/',$info->getSaveName());
            $fname = config('UPLOAD_PATH').$fname;

            $image = \think\Image::open('.'.$fname);
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
            $image->thumb(600, 1000)->save('.'.$fname);
            
            $map['kdNo'] = strtoupper(trim($fileName));
            if($this->admin['administrator']==0){
                $map['cityID'] = $this->admin['cityID'];
            }
            $list = db("OrderBaoguo")->where($map)->find();
            if ($list) {
                if ($list['image']=='') {
                    $data['image'] = $fname;
                }else{
                    $data['image'] = $list['image'].','.$fname;
                }
                $data['flag'] = 1;
                $res = db("OrderBaoguo")->where($map)->update($data);
                /*if ($res) {
                    $orderID = db("OrderBaoguo")->where($map)->value('orderID');
                    $where['orderID'] = $orderID;
                    $where['flag'] = 0;
                    $count = db("OrderBaoguo")->where($where)->count();
                    if ($count==0) {
                        unset($map);
                        $map['id'] = $orderID;
                        $map['payStatus'] = array('in',[2,3]);
                        db("Order")->where($map)->setField("payStatus",4);
                    }
                }*/
            }
            return array('code'=>0,'msg'=>$fname);
        }else{
            //是专门来获取上传的错误信息的
            return array('code'=>0,'msg'=>$file->getError());
        }       
    }

    public function exportExpress(){
        $cityID = input('get.cityID');
        $shopID = input('get.shopID');
        $createDate = input('get.date');
        $ids = input('get.ids');
        if ($shopID!='') {
            $map['shopID'] = $shopID;
        }

        if($this->admin['administrator']==0){
            $map['cityID'] = $this->admin['cityID'];
        }else{
            if ($cityID!='') {
                $map['cityID'] = $cityID;
            }
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
                if ($val['spec']!='') {
                    //$spec = db("GoodsSpecPrice")->where('item_id',$val['specID'])->value("key_name");
                    $goodsName .= '('.$val['spec'].')'; 
                }                               
                if ($k==0) {
                    $content .= $goodsName.'*'.$val['number'].'*'.$val['jiesuan'];
                }else{
                    $content .= ";".$goodsName.'*'.$val['number'].'*'.$val['jiesuan'];
                }               
            }
            $list[$key]['goods'] = $content;
        }

        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '订单号')
            ->setCellValue('B1', '快递号')
            ->setCellValue('C1', '收件人')
            ->setCellValue('D1', '电话')
            ->setCellValue('E1', '地址')
            ->setCellValue('F1', '快递')
            ->setCellValue('G1', '商品')
            ->setCellValue('H1', '运费')
            ->setCellValue('I1', '发件人');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, ' '.$v['order_no'])                
                ->setCellValue('B'.$num, $v['kdNo'])                
                ->setCellValue('C'.$num, $v['name'])
                ->setCellValue('D'.$num, $v['tel'])                 
                ->setCellValue('E'.$num,  $v['province'].'/'.$v['city'].'/'.$v['county'].'/'.$v['addressDetail'])                 
                ->setCellValue('F'.$num, $v['express'])                 
                ->setCellValue('G'.$num, $v['goods'])                 
                ->setCellValue('H'.$num, $v['wuliuInprice'])    
                ->setCellValue('I'.$num, $v['sender'].'/'.$v['senderTel']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('物流直邮包裹');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="物流直邮包裹'.date("Y-m-d",time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }

    public function mprint(){
		$ids = input('get.ids');
		$ids = explode("-",$ids);

		$map['eimg'] = array('neq','');
		$map['id'] = array('in',$ids);
		db("OrderBaoguo")->where($map)->setField('print',1);

		$list = db("OrderBaoguo")->where($map)->select();
		$this->assign('list',$list);

		unset($map);
		$map['id'] = array('in',$ids);
		$map['eimg'] = array('neq','');
		$map['type'] = array('in',[1,2,3]);
		db("OrderBaoguo")->where($map)->update(['flag'=>1,'updateTime'=>time()]);

		foreach ($list as $key => $value) {
			unset($where);
			$where['orderID'] = $value['orderID'];
        	$where['print'] = 0;
        	$printNumber = db("OrderBaoguo")->where($where)->count();//未打印总数

        	unset($where);
			$where['orderID'] = $value['orderID'];
        	$where['flag'] = 0;
        	$flagNumber = db("OrderBaoguo")->where($where)->count();//未发货总数

        	unset($map);
    		$map['id'] = $value['orderID'];
    		$map['status'] = array('in',[1,2]);
        	if ($flagNumber==0 && $printNumber==0) {
        		db("Order")->where($map)->setField("payStatus",3);
        	}elseif($printNumber==0){
	        	db("Order")->where($map)->setField("payStatus",2);
        	}
		}
		return view();
	}
}
?>