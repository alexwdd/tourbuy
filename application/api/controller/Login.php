<?php
namespace app\api\controller;
use think\Request;

class Login extends Common {

    public function _initialize(){
        parent::_initialize();
        header('Access-Control-Allow-Origin:*');
        if (config('site.isClose')==1) {
            returnJson(0,'SUCCESS','系统关闭');
        }
    }

    public function wechat(){        
        if (request()->isPost()) {            
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');

            if ($config['isReg'] == 0) {
                returnJson(0,'注册暂未开放！');
            }    

            $data['openid'] = input('post.openid');
            $data['nickname'] = input('post.nickname');
            $data['headimg'] = input('post.headimg');

            if($data['openid']==''){
                returnJson(0,'缺少openid');
            }
            if($data['nickname']==''){
                returnJson(0,'缺少昵称');
            }
            if($data['headimg']==''){
                returnJson(0,'缺少头像');
            }

            $user = db("Member")->field('id,nickname,headimg,openid')->where('openid',$data['openid'])->find();
            if($user){
                $request= Request::instance();
                $log = array(
                    'memberID' => $user['id'],
                    'account' => $data['openid'],
                    'loginTime' => time(),
                    'loginIP' => $request->ip()
                );
                db('LoginLog')->insert($log);

                //生成token
                $str = md5(uniqid(md5(microtime(true)),true)); 
                $token = sha1($str);
                $userData = array(
                    'token' => $token,
                    'token_out' => time()+3600*config('TOKEN_HOUR')
                );
                $r = db('Member')->where(array("openid" => $data['openid']))->update($userData);
                if($r){
                    $user['token'] = $token;
                    returnJson(1,'success',$user);
                }else{
                    returnJson(0,'登录失败');
                }                
            }else{
                $result = model('Member')->wechat($data);
                if ($result['code']==1) { 
                    $user = db("Member")->field('id,nickname,headimg,token')->where('id',$result['msg'])->find();
                    $this->createCoupon($user);
                    returnJson(1,'success',$user);
                }else{
                    returnJson(0,$result['msg']);
                }
            }
        }
    }


    public function mobile(){        
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $mobile = input('post.mobile');
            $password = input('post.password');            

            if (empty($mobile)) {      
                returnJson(0,'手机号不能为空！');
            } elseif (!check_mobile($mobile)) {
                returnJson(0,'手机号格式错误！');
            }

            if (empty($password)) {
                returnJson(0,'密码不能为空！');
            }

            $user = db('Member');
            $rs = $user->where(array("mobile" => $mobile))->find();
            if ($rs) {
                if ($rs['disable']==1) {     
                    returnJson(0,'账号禁用'); 
                }
              
                if($rs['password']!=md5($password)){
                    returnJson(0,'账号或密码不正确！');
                }

                $request= Request::instance();
                $data = array(
                    'memberID' => $rs['id'],
                    'mobile' => $mobile,
                    'loginTime' => time(),
                    'loginIP' => $request->ip()
                );
                db('LoginLog')->insert($data);

                //生成token
                $str = md5(uniqid(md5(microtime(true)),true)); 
                $token = sha1($str);
                $userData = array(
                    'token' => $token,
                    'token_out' => time()+3600*config('TOKEN_HOUR')
                );
                $r = $user->where(array("mobile" => $mobile))->update($userData);
                if ($r) {
                    returnJson(1,config("SUCCESS_RETURN"),array('token'=>$token));
                }else{
                    returnJson(0,'登录失败！');
                }
            } else {
                returnJson(0,'账号不存在！');
            }
        }
    }

    public function register(){        
        if (request()->isPost()) {            
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');

            if ($config['isReg'] == 0) {
                returnJson(0,'注册暂未开放！');
            }    

            $data = input('post.');
            if (!check_mobile($data['mobile'])) {
                returnJson(0,'手机号格式错误！');
            }

            $result = model('Member')->add($data,$father);
            if ($result['code']==1) { 
                #returnJson(1,'注册成功，等待审核',array('token'=>$result['token']));
                returnJson(1,'注册成功，等待审核');
            }else{
                returnJson(0,$result['msg']);
            }
        }
    }

    public function reset(){
        if (request()->isPost()) {            
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');
  
            $data = input('post.');
            if (!check_mobile($data['mobile'])) {
                returnJson(0,'手机号格式错误！');
            }

            if ($data['safepassword']=='') {
                returnJson(0,'请输入安全密码');
            }

            $map['mobile'] = $data['mobile'];
            $list = db("Member")->where($map)->find();
            if (!$list) {
                returnJson(0,'手机号码不存在');
            }

            if ($list['payPassword']!=md5($data['safepassword'])) {
                returnJson(0,'安全密码错误');
            }

            /*$res = $this->getCodeStatus($data['code'],$data['mobile']);
            if ($res['code']==0) {
                returnJson(0,$res['msg']);
            }*/
  
            $result = db('Member')->where('id',$list['id'])->setField('password',md5($data['password']));
            if ($result) {
                unset($map);
                $map['account'] = $data['mobile'];
                $map['regcode'] = $data['code'];
                db('MemberCode')->where($map)->order('id desc')->setField('status',1);
                returnJson(1,'密码已重置');
            }else{
                returnJson(0,'操作失败');
            }
        }
    }

    //找回密码发送短信验证码
    public function sendSms1(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('sms');
            $mobile = input('post.mobile');

            if (!check_mobile($mobile)) {
                returnJson(0,'手机号格式错误！');
            }

            $map['mobile'] = $mobile;
            $list = db("Member")->where($map)->find();
            if (!$list) {
                returnJson(0,'手机号码不存在');
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
            $content = '【'.$config['sms_sign'].'】您的验证码为'.$verify.'，在'.$config['out_time'].'分钟内有效。';            
            $res = send_sms($mobile,$content);
            if ($res==0) {
                $data = array(
                    'account'=>$mobile,
                    'regcode'=>$verify,
                    'status'=>0,
                    'createTime'=>time(),
                    );
                $list = db('MemberCode')->insert($data);
                returnJson(1,'验证码已发送');
            }else{
                returnJson(0,'短信验证码发送失败');
            }
        }
    }

    //发送短信验证码
    public function sendSms(){
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
            $content = '【'.$config['sms_sign'].'】您的验证码为'.$verify.'，在'.$config['out_time'].'分钟内有效。';            
            $res = send_sms($mobile,$content);
            if ($res==0) {
                $data = array(
                    'account'=>$mobile,
                    'regcode'=>$verify,
                    'status'=>0,
                    'createTime'=>time(),
                    );
                $list = db('MemberCode')->insert($data);
                returnJson(1,'验证码已发送');
            }else{
                returnJson(0,'短信验证码发送失败');
            }
        }
    }

    public function download(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            returnJson(1,'success',['ios'=>'http://' . $_SERVER['HTTP_HOST'].config('site.ios'),'android'=>'http://' . $_SERVER['HTTP_HOST'].config('site.android')]);
        }
    }
}
