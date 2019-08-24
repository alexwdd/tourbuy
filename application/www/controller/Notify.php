<?php
namespace app\www\controller;

use app\common\controller\Base;

class Notify extends Base {

	public function _initialize(){
        parent::_initialize();
    }

	public function ominotify(){
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        header('Content-type:text/json;charset="utf-8"');
        ini_set('date.timezone', 'Asia/Shanghai');

        require_once EXTEND_PATH.'omipay/OmiPayApi.php';
        require_once EXTEND_PATH.'omipay/OmiPayData.php';

        $response = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
        if($response){
        	$config = tpCache('omi');
            $input = new \OmiData();  
            $input -> setMerchantNo($config['OMI_ID']);
            $input -> setSercretKey($config['OMI_KEY']);
            $input->setTime($response['timestamp']);
            $input->setNonceStr($response['nonce_str']);
            $input->setSign();
            if ($input->getSign() == $response['sign']) {   //验证成功
                $content = json_encode($response)."\r\n";
                $file = date('Y-m-d') . '.log';
                file_put_contents($file, $content,FILE_APPEND);
                $order_no = $response['out_order_no'];
                $map['order_no'] = $order_no;
                $list = db('Order')->where($map)->find();
                if ($list) {
                    if ($list['payStatus'] > 0) {
                        exit('该订单已经支付完成，请不要重复操作');  
                    }else{
                        //更新订单状态
                        $data['payStatus'] = 1;
                        $data['status'] = 1;
                        $data['payType'] = 1;
                        db('Order')->where($map)->update($data);
                        db('OrderBaoguo')->where('orderID',$list['id'])->setField('status',1);

                        $detail = db("OrderCart")->where('orderID',$list['id'])->select();
                        foreach ($detail as $key => $value) {
                        	unset($map);
                        	if($value['fid']>0){
                        		$map['id'] = $value['fid'];
                        		$map['fid'] = $value['fid'];
                            	db("Goods")->whereOr($map)->setDec("stock",$value['trueNumber']);
                        	}else{
                        		$map['id'] = $value['goodsID'];
                            	db("Goods")->where($map)->setDec("stock",$value['trueNumber']);
                        	}                        	
                        }
                        $this->saveJiangjin($list);
                        echo 'success';               
                    }
                }else{
                    exit('订单不存在');  
                }

                $result = array('return_code' => 'SUCCESS');
                echo json_encode($result);exit;
            } else {//验证失败
                echo "fail";
            }
        }
	}
    
    public function saveJiangjin($order){
    	$fina = $this->getUserMoney($order['memberID']);
    	if($order['point']>0){
    		$jdata = array(
                'type' => 2,
                'money' => $order['point'],
                'memberID' => $order['memberID'],  
                'doID' => $order['memberID'],
                'oldMoney'=>$fina['point'],
                'newMoney'=>$fina['point']+$order['point'],
                'admin' => 2,
                'msg' => '购买商品，获得'.$order['point'].'积分',
                'extend1' => $order['id'],
                'createTime' => time()
            ); 
            db("Finance")->insert( $jdata );
    	}
    	if($order['fund']>0){
    		$fdata = array(
                'type' => 5,
                'money' => $order['fund'],
                'memberID' => $order['memberID'],  
                'doID' => $order['memberID'],
                'oldMoney'=>$fina['fund'],
                'newMoney'=>$fina['fund']+$order['fund'],
                'admin' => 2,
                'msg' => '购买商品，获得$'.$order['fund'].'返利基金',
                'extend1' => $order['id'],
                'createTime' => time()
            ); 
            db("Finance")->insert( $fdata );
    	}
    }
}