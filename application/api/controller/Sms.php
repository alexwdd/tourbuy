<?php
namespace app\api\controller;
use app\common\controller\Base;

class Sms extends Base {

    public function _initialize(){
        parent::_initialize();
        header('Access-Control-Allow-Origin:*');
        if (config('site.isClose')==1) {
            returnJson(0,'SUCCESS','系统关闭');
        }
    }

    //发送短信验证码
    public function send(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('sms');
            $mobile = input('post.mobile');

            if (!check_mobile($mobile)) {
                returnJson(0,'手机号格式错误！');
            }

            $count = $this->getDayNumber($mobile);

            if ($count>=$config['dayNumber']) {
                returnJson(0,'每天最多发送'.$config['dayNumber'].'条');
            }

            $info = db('MemberCode')->where(array('account'=>$this->user['mobile']))->find();
            if ($info) {
                if (time()-$info['createTime']<=$config['diffTime']*60) {
                    returnJson(0,'请在'.$config['diffTime'].'分钟后再试');
                }
            }

            $verify = rand(1000, 9999);//获取随机验证码
            if (strlen($mobile)==11) {         
                ali_sms($mobile,$verify,'SMS_179225756');
            }else{
                $content = 'Your verification code is '.$verify.'.';
                au_sms($mobile,$content);                
            }          
            $data = array(
                'account'=>$mobile,
                'regcode'=>$verify,
                'status'=>0,
                'createTime'=>time(),
                );
            $list = db('MemberCode')->insert($data);
            returnJson(1,'验证码已发送');            
        }
    }

    //检查短信验证码是否正确
    public function checkSmsCode(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $code = input('post.code');
            $mobile = input('post.mobile');     

            if ($code=='' || !is_numeric($code)) {
                returnJson(0,'请输入验证码');
            }

            if (!check_mobile($mobile)) {
                returnJson(0,'手机号格式错误！');
            }
            $res = $this->getCodeStatus($code,$mobile);
            if ($res['code']==0) {
                returnJson(0,$res['msg']);
            }else{
                returnJson(1,$res['msg']);
            }            
        }
    }

    public function getDayNumber($account){        
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $map['createTime'] = array('between',array($beginToday,$endToday));
        $map['account'] = $account;
        $count = db('MemberCode')->where($map)->count();
        return $count;
    }
}