<?php
namespace app\shop\model;
use think\Session;

class Shop extends Admin
{
    protected $auto = ['updateTime'];
    protected $insert = ['createTime'];  

    public function setUpdateTimeAttr(){
        return time();
    }

    public function setCreateTimeAttr(){
        return time();
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
    
    public function getUpdateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function setImageAttr(){       
        $image = input('post.image/a');
        if ($image) {
            return implode(",", input('post.image/a'));
        }else{
            return '';
        } 
    }

    /**
     *  用户登录
     */
    public function login(array $data)
    {
        $msg = '';
        $userValidate = validate('Shop');
        if(!$userValidate->scene('login')->check($data)) {
            return info($userValidate->getError(),0);
        }
        if($data['account']=='') {
            return info('请输入用户名',0);
        }
        $map = [
            'account' => $data['account'],
            'password' => md5($data['password'])
        ];
        $userRow = $this->where($map)->find();
        if( empty($userRow) ) {
            return info(lang('loginError'),0);
        }
        return info('success', 1, '', $userRow);
    }

    public function password($data){

        $userValidate = validate('Shop');
        if(!$userValidate->scene('password')->check($data)) {
            return info($userValidate->getError(),0);
        }

        $user = Session::get('shopinfo', 'shop');
        $oldpwd = db('Shop')->where(array('id'=>$user['id']))->value('password');
        if($oldpwd!=md5($data['oldpwd'])){
            return info('原始密码错误', 0);
        }else{          
            $res = $this->allowField(true)->save(['password'=>md5($data['password'])],['id'=>$user['id']]);
            if ($res) {
                return info('操作成功', 1);
            }else{
                return info('操作失败', 0);
            }
        }
    }

    //添加更新数据
    public function saveData( $data )
    {
        $cate = input('post.cate/a');
        if ($cate) {
            $data['cate'] = implode(",", input('post.cate/a'));
        }else{
            $data['cate'] = '';
        }

        $data['py'] = getfirstchar($data['name']);

        $result = $this->edit( $data );

        return $result;
    }

    //更新
    public function edit(array $data = [])
    {
        $validate = validate('Shop');
        if(!$validate->scene('edit')->check($data)) {
            return info($validate->getError());
        }
        if($data['password']!=''){
            $data['password'] = MD5($data['password']);
        }else{
            unset($data['password']);
        }
        $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($this->id > 0){
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }
}
