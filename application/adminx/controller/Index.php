<?php
namespace app\adminx\controller;

class Index extends Admin {

    public function index(){
        $menu = $this->getMenu();
        $this->assign('menu',$menu);        
    	return view();
    }

    public function console(){
        return view();
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