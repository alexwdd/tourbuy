<?php
namespace app\api\controller;
use think\Request;

class Wechat extends Common {

    public function _initialize(){
        parent::_initialize();
        header('Access-Control-Allow-Origin:*');
        if (config('site.isClose')==1) {
            returnJson(0,'SUCCESS','系统关闭');
        }
    }

    public function login(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $code = input('post.code');
            $shareUser = input('post.shareUser');
            if($code){
                $config = tpCache('weixin');
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$config['APP_ID'].'&secret='.$config['APP_SECRET'].'&code='.$code.'&grant_type=authorization_code';
                $con = $this->https_post($url);
                $con = json_decode($con,true);
                if ($con['errcode']) {
                    returnJson(0,$con['errmsg']);
                }else{
                    $openid = $con['openid'];
                    $access_token = $con['access_token'];
                    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';            
                    $con = $this->https_post($url);
                    $con = json_decode($con);
                    $wxface = (string)$con->headimgurl;
                    $wxname = (string)$con->nickname;

                    $request = Request::instance();
                    $data['nickname'] = $wxname;
                    $data['openid'] = $openid;    
                    $data['headimg'] = $wxface;
                    $data['disable'] = 0;
                    $data['createTime'] = time();
                    $data['createIP'] = $request->ip();
                    $user = db('Member')->where("openid",$openid)->find();
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
                            returnJson(1,'success',['token'=>$token]);
                        }else{
                            returnJson(0,'登录失败');
                        }
                    }else{
                        if($shareUser!='' && is_numeric($shareUser)){
                            $father = db("Member")->where('id',$shareUser)->find();
                            if($father){
                                $data['tjID'] = $father['id'];
                                $data['tjName'] = $father['nickname'];
                            }
                        }
                        $result = model('Member')->wechat($data);
                        if ($result['code']==1) { 
                            $user = db("Member")->field('id,nickname,headimg,token')->where('id',$result['msg'])->find();
                            $this->autoCoupon($user);
                            returnJson(1,'success',['token'=>$user['token']]);
                        }else{
                            returnJson(0,$result['msg']);
                        }
                    } 
                }
            }
        }
    }
}