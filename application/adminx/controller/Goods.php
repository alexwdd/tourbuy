<?php
namespace app\adminx\controller;

class Goods extends Admin
{
    #列表
    public function index()
    {
        if (request()->isPost()) {
            $result       = model('Goods')->getList($this->admin);
            $cateArr      = db('GoodsCate')->column('id,name');
            $brandArr      = db('Brand')->column('id,name');
            $modelArr      = db('GoodsModel')->column('id,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cid']])) {
                    $result['data'][$key]['cate'] = $cateArr[$value['cid']];
                }
                if (isset($brandArr[$value['brandID']])) {
                    $result['data'][$key]['brand'] = $brandArr[$value['brandID']];
                }
                if (isset($modelArr[$value['modelID']])) {
                    $result['data'][$key]['model'] = $modelArr[$value['modelID']];
                }
            }
            echo json_encode($result);
        } else {   
            $cate = model("GoodsCate")->getCate($this->modelID);
            foreach ($cate as $key => $value) {
                $count               = count(explode('-', $value['path'])) - 3;
                $cate[$key]['count'] = $count;
            }
            $this->assign('cate', $cate);

            if($this->admin['administrator']==1){
                $shop = db('Shop')->field('id,name')->order("py asc")->select();
            }else{
                $shop = db('Shop')->field('id,name')->where('cityID',$this->admin['cityID'])->order("py asc")->select();
            }            
            $this->assign('shop', $shop);
            return view();
        }
    }

    #添加
    public function pub() {
        if(request()->isPost()){
            $data = input('post.');
            if($data['ziti']==0 && $data['typeID']==''){
                $this->error('请选择包裹类型');
            }

            if($data['tehui']==1 && $data['marketPrice']==''){
                $this->error('请输入商品原价');
            }

            if($data['expressID']==5 && $data['typeID']==1 && $data['specification']==''){
                $this->error('红酒类商品必须填写货物规格');
            }

            $goods = model('Goods');
            $result = $goods->saveData( $data );
            if ($result['code']==1) {
                if($data['id']!=''){
                    $goods_id = $data['id'];
                }else{
                    $goods_id = $goods->getLastInsID();
                }                
                $goods->afterSave($goods_id);

                //删除抢购
                if($data['show']==0){
                    db("Flash")->where('goodsID',$goods_id)->delete();
                    cache('flash', NULL);
                }
            }
            return $result;
        }else{
            $cate = model("GoodsCate")->getCate();
            foreach ($cate as $key => $value) {
                $count               = count(explode('-', $value['path'])) - 3;
                $cate[$key]['count'] = $count;
            }
            $this->assign('cate', $cate);

            $linkGoods = [];
            $id = input('get.id');
            if ($id!='' || is_numeric($id)) {
                $list = model('Goods')->find($id);
                if (!$list) {
                    $this->error('信息不存在');
                }

                $expressID = db("CityExpress")->where('cityID',$list['cityID'])->column("expressID");
                $express = db("Express")->where('id','in',$expressID)->select();
                $this->assign('express',$express);

                $model = db("Express")->where('id',$list['expressID'])->value("model");
                $this->assign('type',config(strtoupper($model).'_BAOGUO_TYPE'));

                if ($list['image']) {
                    $image = explode(",", $list['image']);
                } else {
                    $image = [];
                }
                $this->assign('image', $image);

                //套餐
                $pack = db("Goods")->where('fid',$id)->select();
                $this->assign('pack',$pack);

                //是否在抢购中
                unset($map);
                $map['endDate'] = array('gt',time());
                $map['goodsID'] = $list['id'];
                $res = db("Flash")->where($map)->find();
                if ($res) {
                    $flag = 1;
                }else{
                    $flag = 0;
                }
            }else{
                $list['show'] = 1;
                $flag = 0;
            }
            $this->assign('flag', $flag);

            $brand = db("Brand")->order("py asc , sort asc")->select();
            $this->assign('brand', $brand);

            $model = db("GoodsModel")->field('id,name')->select();
            $this->assign('model', $model);

            if($this->admin['administrator']==1){
                $shop = db('Shop')->field('id,name,cityID')->order("py asc")->select();
            }else{
                $shop = db('Shop')->field('id,name,cityID')->where('cityID',$this->admin['cityID'])->order("py asc")->select();
            } 
            $this->assign('shop', $shop);
            $this->assign('list', $list);
            return view();
        }
    }

    public function getExpress(){
        $cityID = input('post.cityID');
        $expressID = db("CityExpress")->where('cityID',$cityID)->column("expressID");
        $list = db("Express")->where('id','in',$expressID)->select();
        echo json_encode(['data'=>$list]);
    }

    public function getGoodsType(){
        $model = input('post.model');        
        $list = config(strtoupper($model).'_BAOGUO_TYPE');
        echo json_encode(['data'=>$list]);
    }

    public function getPack(){
        $res = $this->fetch();        
        echo $res;
    }

    public function delPack()
    {
        if (request()->isPost()) {
            db("Goods")->where('id',input('post.id'))->delete();
        }
    }

    #删除
    public function del()
    {
        $id = explode(",", input('post.id'));
        if (count($id) == 0) {
            $this->error('请选择要删除的数据');
        } else {
            // 删除此商品
            db("Goods")->whereIn('fid', $id)->delete(); //套餐表
            db("Goods")->whereIn('id', $id)->delete(); //商品表
            db("GoodsSpecPrice")->whereIn('goods_id', $id)->delete(); //商品属性
            db('GoodsPush')->whereIn('goodsID', $id)->delete();
            db('Flash')->whereIn('goodsID', $id)->delete();
            $this->success("操作成功");
        }
    }

    public function comment(){
        if (request()->isPost()) {
            $goodsID = input('param.goodsID');
            $map['goodsID'] = $goodsID;
            $result = model('GoodsComment')->getList($map);
            echo json_encode($result);
        }else{
            $goodsID = input('param.id');
            $this->assign('goodsID',$goodsID);
            return view();
        }
    }

    public function write(){
        if(request()->isPost()){
            $data = input('post.');
            $data['createTime'] = time();
            $data['status'] = 1;
            $data['memberID'] = 0;
            unset($data['image']);
            unset($data['file']);
            $image = input('post.image/a');
            if ($image) {
                $data['images'] = implode("|", input('post.image/a'));
            }else{
                $data['images'] = '';
            }
            $res = db("GoodsComment")->insert($data);
            if($res){
                return info('操作成功',1);
            }else{
                return info('操作失败',0);
            }            
        }else{
            $goodsID = input('param.goodsID');
            $this->assign('goodsID', $goodsID);
            return view();
        }
    }

    public function checkComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要取消的数据');
        }else{
            $map['id'] = array('in',$id);
            db("GoodsComment")->where($map)->setField('status',1);
            $this->success("操作成功");
        }
    }

    public function delComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('GoodsComment')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }

    //商品推送
    public function push(){
        if (request()->isPost()) {
            $id=input('post.id');
            $cateID=input('post.cateID');
            $id = explode("-", $id);
            if($id==''){
                $this->error('您没有选择任何信息！');
            }else{
                foreach ($id as $v) {
                    $db = db('GoodsPush');
                    $where['goodsID'] = $v;
                    $where['cateID'] = $cateID;
                    $res = $db->where($where)->find();
                    if($res){
                        $db->where($where)->update('updateTime',time());
                    }else{
                        $goods = db("Goods")->where('id',$v)->find();
                        $temp = explode("-",$goods['path']);
                        $data = [
                            'goodsID'=>$v,
                            'goodsName'=>$goods['name'],
                            'cateID'=>$cateID,
                            'cid'=>$temp[1],
                            'updateTime'=>time()
                        ];
                        $db->insert($data);
                    }                    
                }
                $url = "reload";
                $this->success('操作成功');
            }
        }else{
            $id=input('get.id');
            $this->assign('id',$id);
            unset($map);
            $map['cate']=1;
            $cate = db('OptionItem')->field("id,name,value")->where($map)->order('value asc')->select();
            $this->assign('cate', $cate);
            return view();
        }       
    }

    /*public function getSpec(){
        $wuliu = db("Wuliu")->order("sort asc")->select();
        $this->assign("wuliu",$wuliu);

        $cate = model("GoodsCate")->getCate($this->modelID);
        foreach ($cate as $key => $value) {
            $count = count(explode('-', $value['path'])) - 3;
            $cate[$key]['count'] = $count;
        }
        $this->assign('cate', $cate);

        $this->assign('tag',config('GOODS_TAG'));
        $res = $this->fetch();        
        echo $res;
    }

    public function delspec()
    {
        if (request()->isPost()) {
            db("GoodsIndex")->where('id',input('post.id'))->delete();
        }
    }*/

    public function ajaxGetSpecSelect(){
        $goods_id = input('get.goods_id/d') ? input('get.goods_id/d') : 0;        
        $spec_type = input('get.spec_type/d') ? input('get.spec_type/d') : 0;        
        $specList = db('ModelSpec')->where("mID = ".$spec_type)->order('sort asc')->select();
        foreach($specList as $k => $v){
            $specList[$k]['spec_item'] = db('ModelSpecItem')->field('id,item')->where("specID = ".$v['id'])->select(); // 获取规格项  
        }                
        
        $items_id = db('GoodsSpecPrice')->where('goods_id = '.$goods_id)->column("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
        $items_ids = explode('_', $items_id[0]);
        $this->assign('items_ids',$items_ids);
        $this->assign('specList',$specList);
        return $this->fetch();        
    }

    public function ajaxGetSpecInput(){
        $goods_id = input('goods_id/d') ? input('goods_id/d') : 0;
        $defPrice = input('price/f') ? input('price/f') : 0;
        $defStock = input('stock/d') ? input('stock/d') : 0;
        $defJiesuan = input('jiesuan/f') ? input('jiesuan/f') : 0;
        $defWeight = input('weight/f') ? input('weight/f') : 0;
        $defWuliuWeight = input('wuliuWeight/f') ? input('wuliuWeight/f') : 0;
        $defServePrice = input('servePrice/f') ? input('servePrice/f') : 0;
        $defZtServePrice = input('ztServePrice/f') ? input('ztServePrice/f') : 0;
        $str = $this->getSpecInput($goods_id ,input('post.spec_arr/a',[[]]),$defPrice,$defJiesuan,$defStock,$defWeight,$defWuliuWeight,$defZtServePrice,$defServePrice);
        exit($str);   
    }

    public function getSpecInput($goods_id, $spec_arr, $defPrice,$defJiesuan,$defStock,$defWeight,$defWuliuWeight,$defZtServePrice,$defServePrice)
    {      
        // 排序
        foreach ($spec_arr as $k => $v)
        {
            $spec_arr_sort[$k] = count($v);
        }
        asort($spec_arr_sort);        
        foreach ($spec_arr_sort as $key =>$val)
        {
            $spec_arr2[$key] = $spec_arr[$key];
        }     
        
        $clo_name = array_keys($spec_arr2);         
        $spec_arr2 = combineDika($spec_arr2); //  获取 规格的 笛卡尔积                 
                   
        $spec = db('ModelSpec')->column('id,name'); // 规格表
        $specItem = db('ModelSpecItem')->column('id,item,specID');//规格项
        $keyGoodsSpecPrice = db('GoodsSpecPrice')->where('goods_id = '.$goods_id)->column('key,key_name,price,jiesuan,store_count,bar_code,weight,wuliuWeight,spec_img,servePrice,ztServePrice,ztInprice,inprice');//规格项
        $str = "<table class='layui-table' lay-size='sm' id='spec_input_tab'>";
        $str .="<thead><tr>";       
        // 显示第一行的数据
        foreach ($clo_name as $k => $v) 
        {
            $str .=" <td>{$spec[$v]}</td>";
        }    
        $str .="<td>平台售价</td>
               <td>门店价</td>
               <td>直邮服务费%</td>
               <td>直邮进价</td>
               <td>自提服务费%</td>
               <td>自提进价</td>
               <td>库存</td>
               <td>重量(kg)</td>
               <td>物流重量(kg)</td>
               <td>规格图片</td>
             </tr></thead>";
        // 显示第二行开始 
        foreach ($spec_arr2 as $k => $v) 
        {
            $str .="<tr>";
            $item_key_name = array();
            foreach($v as $k2 => $v2)
            {
                $str .="<td>{$specItem[$v2]['item']}</td>";
                $item_key_name[$v2] = $spec[$specItem[$v2]['specID']].':'.$specItem[$v2]['item'];
            }   
            ksort($item_key_name);            
            $item_key = implode('_', array_keys($item_key_name));
            $item_name = implode(' ', $item_key_name);
            
            $keyGoodsSpecPrice[$item_key]['price'] ? false : $keyGoodsSpecPrice[$item_key]['price'] = $defPrice; // 价格默认为商品价格
            $keyGoodsSpecPrice[$item_key]['jiesuan'] ? false : $keyGoodsSpecPrice[$item_key]['jiesuan'] = $defJiesuan;
            $keyGoodsSpecPrice[$item_key]['ztServePrice'] ? false : $keyGoodsSpecPrice[$item_key]['ztServePrice'] = $defZtServePrice;
            $keyGoodsSpecPrice[$item_key]['servePrice'] ? false : $keyGoodsSpecPrice[$item_key]['servePrice'] = $defServePrice;
            $keyGoodsSpecPrice[$item_key]['weight'] ? false : $keyGoodsSpecPrice[$item_key]['weight'] = $defWeight;
            $keyGoodsSpecPrice[$item_key]['wuliuWeight'] ? false : $keyGoodsSpecPrice[$item_key]['wuliuWeight'] = $defWuliuWeight;
            $keyGoodsSpecPrice[$item_key]['store_count'] ? false : $keyGoodsSpecPrice[$item_key]['store_count'] = $defStock; //库存默认为0

            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][price]' value='{$keyGoodsSpecPrice[$item_key][price]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /><input type='hidden' name='item[$item_key][key_name]' value='$item_name' /></td>";

            $str .="<td><input class='layui-input spec-ipt spec-js' name='item[$item_key][jiesuan]' value='{$keyGoodsSpecPrice[$item_key][jiesuan]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";

            $str .="<td><input class='layui-input spec-ipt serBtn' name='item[$item_key][servePrice]' value='{$keyGoodsSpecPrice[$item_key][servePrice]}' onkeyup='getInprice(this)' onpaste='getInprice(this)' /></td>";

            $str .="<td><input class='layui-input spec-ipt inprice' name='item[$item_key][inprice]' value='{$keyGoodsSpecPrice[$item_key][inprice]}' readonly/></td>";

            $str .="<td><input class='layui-input spec-ipt ztBtn' name='item[$item_key][ztServePrice]' value='{$keyGoodsSpecPrice[$item_key][ztServePrice]}' onkeyup='getInprice(this)' onpaste='getInprice(this)' /></td>";

            $str .="<td><input class='layui-input spec-ipt ztInprice' name='item[$item_key][ztInprice]' value='{$keyGoodsSpecPrice[$item_key][ztInprice]}' readonly/></td>";

            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][store_count]' value='{$keyGoodsSpecPrice[$item_key][store_count]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";   

            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][weight]' value='{$keyGoodsSpecPrice[$item_key][weight]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";

            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][wuliuWeight]' value='{$keyGoodsSpecPrice[$item_key][wuliuWeight]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";

            $str .="<td><div class='layui-inline' style='width:300px'><input class='layui-input' name='item[$item_key][spec_img]' value='{$keyGoodsSpecPrice[$item_key][spec_img]}'/></div> <div class='layui-inline'><button type='button' class='layui-btn upBtn'>上传</button></div></td>";
            $str .="</tr>";
        }
        $str .= "</table>";
        return $str;   
    }

    /*public function saveGoodsAttr($data)
    {
        $db = db('GoodsAttr');
        //清除原来的属性
        $db->where('goodsID = ' . $data['id'])->delete();

        $attr = db("goodsAttribute")->select();
        $attr_data = array();
        foreach ($data as $key => $value) {
            if ($value[0]!='') {
                $attr_id = str_replace('attr_', '', $key);
                foreach ($attr as $k => $v) {
                    if ($v['id']==$attr_id) {
                        array_push($attr_data,array('goodsID'=>$data['id'],'attr_id'=>$attr_id,'attr_name'=>$v['name'],'attr_value'=>$value[0]));
                    }
                }
            }
        }
        $db->insertAll($attr_data);
    }*/

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
            $obj = db('Goods');
            $total = 0;
            $error = '';
            for ( $i = 2; $i <= $highestRow; $i++) {
                $goodsID = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
                
                unset($data);               
                $data['name'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                $data['en'] = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                $data['short'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
                $data['intr'] = trim($sheet->getCellByColumnAndRow(4, $i)->getValue());
                $cid = trim($sheet->getCellByColumnAndRow(5, $i)->getValue());
                $cid1 = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());
                if ($cid>0 && $cid!='') {
                    $path = db('GoodsCate')->where('id',$cid)->value("path");
                    if ($path) {
                        $data['cid'] = $cid;
                        $data['path'] = $path;
                    }else{
                        $data['cid'] = 0;
                        $data['path'] = '';
                    }                    
                }else{
                    $data['cid'] = 0;
                    $data['path'] = '';
                }
                
                if ($cid1>0 && $cid1!='') {
                    $path1 = db('GoodsCate')->where('id',$cid1)->value("path");
                    if ($path1) {
                        $data['cid1'] = $cid1;
                        $data['path1'] = $path1;
                    }else{
                        $data['cid1'] = 0;
                        $data['path1'] = '';
                    }                    
                }else{
                    $data['cid1'] = 0;
                    $data['path1'] = '';
                }                 
                $data['brandID'] = trim($sheet->getCellByColumnAndRow(7, $i)->getValue());

                $typeArr = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                $typeArr = explode("-",$typeArr);
                $data['expressID'] = $typeArr[0];
                $data['typeID'] = $typeArr[1];

                $data['price'] = trim($sheet->getCellByColumnAndRow(9, $i)->getValue());
                $data['jiesuan'] = trim($sheet->getCellByColumnAndRow(10, $i)->getValue());
                $data['servePrice'] = trim($sheet->getCellByColumnAndRow(11, $i)->getValue());
                $data['ztServePrice'] = trim($sheet->getCellByColumnAndRow(12, $i)->getValue());
                $data['weight'] = trim($sheet->getCellByColumnAndRow(13, $i)->getValue());
                $data['wuliuWeight'] = trim($sheet->getCellByColumnAndRow(14, $i)->getValue());
                $data['endDate'] = trim($sheet->getCellByColumnAndRow(15, $i)->getValue());
                $data['number'] = trim($sheet->getCellByColumnAndRow(16, $i)->getValue());

                $data['baoyou'] = trim($sheet->getCellByColumnAndRow(17, $i)->getValue());
                $data['show'] = trim($sheet->getCellByColumnAndRow(18, $i)->getValue());
                $data['ziti'] = trim($sheet->getCellByColumnAndRow(19, $i)->getValue());
                $data['stock'] = trim($sheet->getCellByColumnAndRow(20, $i)->getValue());
                $data['gst'] = trim($sheet->getCellByColumnAndRow(21, $i)->getValue());
                $data['sellNumber'] = trim($sheet->getCellByColumnAndRow(22, $i)->getValue());
                $data['keyword'] = trim($sheet->getCellByColumnAndRow(23, $i)->getValue());
                $data['say'] = trim($sheet->getCellByColumnAndRow(24, $i)->getValue());
                $data['shopID'] = trim($sheet->getCellByColumnAndRow(25, $i)->getValue());

                if ($goodsID!='' && $goodsID>0) {
                    $where['id'] = $goodsID;
                    $map['fid'] = 0;
                    $res = db("Goods")->where($where)->find();
                }

                $shop = db("Shop")->field('name,group,cityID')->where('id',$data['shopID'])->find();
                if($shop){
                    $data['shopName'] = $shop['name'];
                    $data['cityID'] = $shop['cityID'];
                    $data['group'] = $shop['group'];
                }else{
                    $data['shopID'] = 0;
                    $data['shopName'] = '';
                    $data['cityID'] = 0;
                    $data['group'] = 0;
                }                

                if ($res) {
                    $data['inprice'] = ($data['jiesuan'] * (100-$res['servePrice']))/100;
                    $data['ztInprice'] = ($data['jiesuan'] * (100-$res['ztServePrice']))/100;
                    $obj->where('id',$goodsID)->update($data);
                }else{
                    $data['sort'] = 50;
                    $data['updateTime'] = time();
                    $data['createTime'] = time();
                    $goodsID = $obj->insertGetId($data);
                }
            }
            
            $msg = '共'.($highestRow-1).'条数据，成功导入';
            return info($msg,1);
        }else{
            return view();
        }
    }

    public function export(){
        $list = db('Goods')->where('fid',0)->order('id desc')->select();
        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '英文')
            ->setCellValue('D1', '短名称')
            ->setCellValue('E1', '描述')
            ->setCellValue('F1', '分类1(数字)')
            ->setCellValue('G1', '分类2(数字)')
            ->setCellValue('H1', '品牌(数字)')
            ->setCellValue('I1', '包裹类型(快递ID-包裹类型ID)')
            ->setCellValue('J1', '平台售价')
            ->setCellValue('K1', '门店价')
            ->setCellValue('L1', '直邮服务费%')
            ->setCellValue('M1', '自提服务费%')
            ->setCellValue('N1', '商品重量(kg)')
            ->setCellValue('O1', '物流重量(kg)')
            ->setCellValue('P1', '保质期')
            ->setCellValue('Q1', '单品数量')
            ->setCellValue('R1', '包邮')
            ->setCellValue('S1', '状态(0隐藏1显示)')
            ->setCellValue('T1', '强制自提(0否1是)')
            ->setCellValue('U1', '库存(数字)')
            ->setCellValue('V1', '税率(%)')
            ->setCellValue('W1', '初始销量')
            ->setCellValue('X1', '关键词')
            ->setCellValue('Y1', '特色描述')
            ->setCellValue('Z1', '商铺ID');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, $v['name'])                
                ->setCellValue('C'.$num, $v['en'])                
                ->setCellValue('D'.$num, $v['short'])
                ->setCellValue('E'.$num, $v['intr'])                 
                ->setCellValue('F'.$num, $v['cid'])
                ->setCellValue('G'.$num, $v['cid1'])
                ->setCellValue('H'.$num, $v['brandID'])
                ->setCellValue('I'.$num, $v['expressID'].'-'.$v['typeID'])
                ->setCellValue('J'.$num, $v['price'])
                ->setCellValue('K'.$num, $v['jiesuan'])
                ->setCellValue('L'.$num, $v['servePrice'])
                ->setCellValue('M'.$num, $v['ztServePrice'])
                ->setCellValue('N'.$num, $v['weight'])
                ->setCellValue('O'.$num, $v['wuliuWeight'])
                ->setCellValue('P'.$num, $v['endDate'])
                ->setCellValue('Q'.$num, $v['number'])
                ->setCellValue('R'.$num, $v['baoyou'])
                ->setCellValue('S'.$num, $v['show'])
                ->setCellValue('T'.$num, $v['ziti'])
                ->setCellValue('U'.$num, $v['stock'])
                ->setCellValue('V'.$num, $v['gst'])
                ->setCellValue('W'.$num, $v['sellNumber'])
                ->setCellValue('X'.$num, $v['keyword'])
                ->setCellValue('Y'.$num, $v['say'])
                ->setCellValue('Z'.$num, $v['shopID']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('商品');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="商品.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }

    public function import1(){
        if (request()->isPost()) {
            set_time_limit(0);
            ini_set("memory_limit", "512M"); 
            
            $file = input('post.file');
            $objReader = \PHPExcel_IOFactory::createReader ( 'Excel5' );
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load('.'.$file);
            $sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            //$highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
            $obj = db('GoodsIndex');
            $total = 0;
            $error = '';
            for ( $i = 2; $i <= $highestRow; $i++) {
                $goodsID = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
    
                if ($goodsID>0) {
                    unset($data);               
                    $data['name'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                    $data['short'] = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                    $data['en'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
                    $cid = trim($sheet->getCellByColumnAndRow(4, $i)->getValue());
                    $cid1 = trim($sheet->getCellByColumnAndRow(5, $i)->getValue());
                    if ($cid>0 && $cid!='') {
                        $path = db('GoodsCate')->where('id',$cid)->value("path");
                        if ($path) {
                            $data['cid'] = $cid;
                            $data['path'] = $path;
                        }else{
                            $data['cid'] = 0;
                            $data['path'] = '';
                        }                    
                    }else{
                        $data['cid'] = 0;
                        $data['path'] = '';
                    }
                    
                    if ($cid1>0 && $cid1!='') {
                        $path1 = db('GoodsCate')->where('id',$cid1)->value("path");
                        if ($path1) {
                            $data['cid1'] = $cid1;
                            $data['path1'] = $path1;
                        }else{
                            $data['cid1'] = 0;
                            $data['path1'] = '';
                        }                    
                    }else{
                        $data['cid1'] = 0;
                        $data['path1'] = '';
                    }
                    $data['price'] = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());
                    $data['price1'] = trim($sheet->getCellByColumnAndRow(7, $i)->getValue());
                    $data['number'] = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                    $data['wuliu'] = trim($sheet->getCellByColumnAndRow(9, $i)->getValue());

                    $obj->where('id',$goodsID)->update($data);

                    unset($map);
                    $map['id'] = $goodsID;
                    db('GoodsIndex')->where($map)->update($data);
                    $total++;
                }                    
            }
            
            $msg = '共'.($highestRow-1).'条数据，成功导入'.$total.'条，错误信息'.$error;
            return info($msg,1);
        }else{
            return view();
        }
    }

    public function export1(){
        $map['base']=0;
        $list = db('GoodsIndex')->where($map)->order('id desc')->select();
        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '短名称')
            ->setCellValue('D1', '英文')
            ->setCellValue('E1', '分类1(数字)')
            ->setCellValue('F1', '分类2(数字)')
            ->setCellValue('G1', '价格')
            ->setCellValue('H1', '会员价')
            ->setCellValue('I1', '单品数量')   
            ->setCellValue('J1', '快递');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, $v['name'])                
                ->setCellValue('C'.$num, $v['short'])
                ->setCellValue('D'.$num, $v['en'])                 
                ->setCellValue('E'.$num, $v['cid'])
                ->setCellValue('F'.$num, $v['cid1'])
                ->setCellValue('G'.$num, $v['price'])
                ->setCellValue('H'.$num, $v['price1'])
                ->setCellValue('I'.$num, $v['number'])
                ->setCellValue('J'.$num, $v['wuliu']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('套餐');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="套餐.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }
}
