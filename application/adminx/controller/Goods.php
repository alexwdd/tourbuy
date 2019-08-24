<?php
namespace app\adminx\controller;

class Goods extends Admin
{
    #列表
    public function index()
    {
        if (request()->isPost()) {
            $result       = model('Goods')->getList();
            $cateArr      = db('GoodsCate')->column('id,name');
            $brandArr      = db('Brand')->column('id,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cid']])) {
                    $result['data'][$key]['cate'] = $cateArr[$value['cid']];
                }
                if (isset($brandArr[$value['brandID']])) {
                    $result['data'][$key]['brand'] = $brandArr[$value['brandID']];
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
            return view();
        }
    }

    #添加
    public function pub() {
        if(request()->isPost()){
            $data = input('post.');
            $goods = model('Goods');
            $result = $goods->saveData( $data );
            if ($result['code']==1) {
                if($data['id']!=''){
                    $goods_id = $data['id'];
                }else{
                    $goods_id = $goods->getLastInsID();
                }                
                $goods->afterSave($goods_id);
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
                if($list['linkIds']!=''){
                    $ids = explode(",",$list['linkIds']);
                    $linkGoods = db('Goods')->where('id','in',$ids)->select();                    
                }
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

            $shop = db("Shop")->field('id,name')->select();
            $this->assign('shop', $shop);

            $this->assign('list', $list);
            $this->assign('linkGoods',json_encode($linkGoods));

            return view();
        }
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
            $this->success("操作成功");
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
        $str = $this->getSpecInput($goods_id ,input('post.spec_arr/a',[[]]),$defPrice);
        exit($str);   
    }

    public function getSpecInput($goods_id, $spec_arr, $defPrice)
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
        $keyGoodsSpecPrice = db('GoodsSpecPrice')->where('goods_id = '.$goods_id)->column('key,key_name,price,cutPrice,store_count,bar_code,weight,spec_img');//规格项                          
        $str = "<table class='layui-table' lay-size='sm' id='spec_input_tab'>";
        $str .="<thead><tr>";       
        // 显示第一行的数据
        foreach ($clo_name as $k => $v) 
        {
            $str .=" <td>{$spec[$v]}</td>";
        }    
        $str .="<td>价格</td>
               <td>砍价</td>
               <td>库存</td>
               <td>重量(kg)</td>
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
            $keyGoodsSpecPrice[$item_key]['store_count'] ? false : $keyGoodsSpecPrice[$item_key]['store_count'] = 0; //库存默认为0

            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][price]' value='{$keyGoodsSpecPrice[$item_key][price]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][cutPrice]' value='{$keyGoodsSpecPrice[$item_key][cutPrice]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][store_count]' value='{$keyGoodsSpecPrice[$item_key][store_count]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";            
            $str .="<td><input class='layui-input spec-ipt' name='item[$item_key][weight]' value='{$keyGoodsSpecPrice[$item_key][weight]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/><input type='hidden' name='item[$item_key][key_name]' value='$item_name' /></td>";
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
            
            $file = input('post.file');
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
                if ($goodsID!='' && $goodsID>0) {
                    $res = db("Goods")->where('id',$goodsID)->find();
                }

                if ($res) {
                    unset($data);               
                    $data['name'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                    $data['short'] = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                    $data['intr'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
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
                    $data['brandID'] = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());
                    $data['typeID'] = trim($sheet->getCellByColumnAndRow(7, $i)->getValue());
                    $data['price'] = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                    $data['price1'] = trim($sheet->getCellByColumnAndRow(9, $i)->getValue());
                    $data['weight'] = trim($sheet->getCellByColumnAndRow(10, $i)->getValue());
                    $data['wuliuWeight'] = trim($sheet->getCellByColumnAndRow(11, $i)->getValue());
                    $data['endDate'] = trim($sheet->getCellByColumnAndRow(12, $i)->getValue());
                    $data['number'] = trim($sheet->getCellByColumnAndRow(13, $i)->getValue());
                    $data['max'] = trim($sheet->getCellByColumnAndRow(14, $i)->getValue());
                    $data['show'] = trim($sheet->getCellByColumnAndRow(15, $i)->getValue());
                    $data['comm'] = trim($sheet->getCellByColumnAndRow(16, $i)->getValue());
                    $data['empty'] = trim($sheet->getCellByColumnAndRow(17, $i)->getValue());
                    $data['gst'] = trim($sheet->getCellByColumnAndRow(18, $i)->getValue());
                    $data['sellNumber'] = trim($sheet->getCellByColumnAndRow(19, $i)->getValue());
                    $data['keyword'] = trim($sheet->getCellByColumnAndRow(20, $i)->getValue());
                    $data['inprice'] = trim($sheet->getCellByColumnAndRow(21, $i)->getValue());
                    $data['say'] = trim($sheet->getCellByColumnAndRow(22, $i)->getValue());
                    $data['wuliu'] = trim($sheet->getCellByColumnAndRow(23, $i)->getValue());
                    $data['en'] = trim($sheet->getCellByColumnAndRow(24, $i)->getValue());
                    $obj->where('id',$goodsID)->update($data);

                    unset($map);
                    $map['goodsID'] = $goodsID;
                    $map['base'] = 1;

                    unset($data['sellNumber']);
                    unset($data['inprice']);
                    db('GoodsIndex')->where($map)->update($data);
                }else{
                    unset($data);               
                    $data['name'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                    $data['short'] = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                    $data['intr'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
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
                    $data['brandID'] = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());
                    $data['typeID'] = trim($sheet->getCellByColumnAndRow(7, $i)->getValue());
                    $data['price'] = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                    $data['price1'] = trim($sheet->getCellByColumnAndRow(9, $i)->getValue());
                    $data['weight'] = trim($sheet->getCellByColumnAndRow(10, $i)->getValue());
                    $data['wuliuWeight'] = trim($sheet->getCellByColumnAndRow(11, $i)->getValue());
                    $data['endDate'] = trim($sheet->getCellByColumnAndRow(12, $i)->getValue());
                    $data['number'] = trim($sheet->getCellByColumnAndRow(13, $i)->getValue());
                    $data['max'] = trim($sheet->getCellByColumnAndRow(14, $i)->getValue());
                    $data['show'] = trim($sheet->getCellByColumnAndRow(15, $i)->getValue());
                    $data['comm'] = trim($sheet->getCellByColumnAndRow(16, $i)->getValue());
                    $data['empty'] = trim($sheet->getCellByColumnAndRow(17, $i)->getValue());
                    $data['gst'] = trim($sheet->getCellByColumnAndRow(18, $i)->getValue());
                    $data['sellNumber'] = trim($sheet->getCellByColumnAndRow(19, $i)->getValue());
                    $data['keyword'] = trim($sheet->getCellByColumnAndRow(20, $i)->getValue());
                    $data['inprice'] = trim($sheet->getCellByColumnAndRow(21, $i)->getValue());
                    $data['say'] = trim($sheet->getCellByColumnAndRow(22, $i)->getValue());
                    $data['wuliu'] = trim($sheet->getCellByColumnAndRow(23, $i)->getValue());
                    $data['en'] = trim($sheet->getCellByColumnAndRow(24, $i)->getValue());
                    $data['sort'] = 50;
                    $data['updateTime'] = time();
                    $data['createTime'] = time();
                    $goodsID = $obj->insertGetId($data);
                    $data['goodsID'] = $goodsID;
                    $data['base'] = 1;
                    unset($data['updateTime']);
                    unset($data['createTime']);
                    unset($data['sellNumber']);
                    unset($data['inprice']);
                    db('GoodsIndex')->insert($data);
                }
            }
            
            $msg = '共'.($highestRow-1).'条数据，成功导入'.$total.'条，错误信息'.$error;
            return info($msg,1);
        }else{
            return view();
        }
    }

    public function export(){
        $list = db('Goods')->order('id desc')->select();
        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '短名称')
            ->setCellValue('D1', '描述')
            ->setCellValue('E1', '分类1(数字)')
            ->setCellValue('F1', '分类2(数字)')
            ->setCellValue('G1', '品牌(数字)')
            ->setCellValue('H1', '包裹类型(数字)')
            ->setCellValue('I1', '价格')
            ->setCellValue('J1', '会员价')
            ->setCellValue('K1', '商品重量(kg)')
            ->setCellValue('L1', '物流重量(kg)')
            ->setCellValue('M1', '保质期')
            ->setCellValue('N1', '单品数量')
            ->setCellValue('O1', '每日限购')
            ->setCellValue('P1', '状态(0隐藏1显示)')
            ->setCellValue('Q1', '本周特价(0否1是)')
            ->setCellValue('R1', '售罄(0否1是)')
            ->setCellValue('S1', '含税(0否1是)')
            ->setCellValue('T1', '初始销量')
            ->setCellValue('U1', '关键词')
            ->setCellValue('V1', '进货价')
            ->setCellValue('W1', '特色描述')
            ->setCellValue('X1', '快递')
            ->setCellValue('Y1', '英文');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, $v['name'])                
                ->setCellValue('C'.$num, $v['short'])
                ->setCellValue('D'.$num, $v['intr'])                 
                ->setCellValue('E'.$num, $v['cid'])
                ->setCellValue('F'.$num, $v['cid1'])
                ->setCellValue('G'.$num, $v['brandID'])
                ->setCellValue('H'.$num, $v['typeID'])
                ->setCellValue('I'.$num, $v['price'])
                ->setCellValue('J'.$num, $v['price1'])
                ->setCellValue('K'.$num, $v['weight'])
                ->setCellValue('L'.$num, $v['wuliuWeight'])
                ->setCellValue('M'.$num, $v['endDate'])
                ->setCellValue('N'.$num, $v['number'])
                ->setCellValue('O'.$num, $v['max'])
                ->setCellValue('P'.$num, $v['show'])
                ->setCellValue('Q'.$num, $v['comm'])
                ->setCellValue('R'.$num, $v['empty'])
                ->setCellValue('S'.$num, $v['gst'])
                ->setCellValue('T'.$num, $v['sellNumber'])
                ->setCellValue('U'.$num, $v['keyword'])
                ->setCellValue('V'.$num, $v['inprice'])
                ->setCellValue('W'.$num, $v['say'])
                ->setCellValue('X'.$num, $v['wuliu'])
                ->setCellValue('Y'.$num, $v['en']);
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
