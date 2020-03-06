<?php
namespace app\adminx\controller;

class Brand extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Brand')->getList();
			$map['cate']=4;	
            $cateArr = db('OptionItem')->where($map)->column('id,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cid']])) {
                    $result['data'][$key]['cate'] = $cateArr[$value['cid']];
                }                
            }
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Brand')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Brand')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
			}
			$this->assign('list', $list);

			$map['cate']=4;
            $cate = db('OptionItem')->field("id,name")->where($map)->order('value asc')->select();
            $this->assign('cate', $cate);
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Brand')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
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
            $obj = db('Brand');
            $total = 0;
            $error = '';
            for ( $i = 2; $i <= $highestRow; $i++) {
                $brandID = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
                
                unset($data);               
                $data['cid'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                $data['name'] = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
                $data['desc'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
                $data['url'] = trim($sheet->getCellByColumnAndRow(4, $i)->getValue());               
                $data['sort'] = trim($sheet->getCellByColumnAndRow(5, $i)->getValue());               
                $data['comm'] = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());               
                $data['py'] = strtoupper(trim($sheet->getCellByColumnAndRow(7, $i)->getValue()));
                
                if ($brandID!='' && $brandID>0) {
                    $where['id'] = $brandID;
                    $res = db("Brand")->where($where)->find();
                }

                if ($res) {
                    $obj->where('id',$brandID)->update($data);
                }else{
                    $data['top'] = 0;
                    $data['updateTime'] = time();
                    $data['createTime'] = time();
                    $brandID = $obj->insertGetId($data);
                }
            }
            
            $msg = '共'.($highestRow-1).'条数据，成功导入';
            return info($msg,1);
        }else{
            return view();
        }
    }

    public function export(){
        $list = db('Brand')->order('id desc')->select();
        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '分类(数字)')
            ->setCellValue('C1', '名称')
            ->setCellValue('D1', '描述')
            ->setCellValue('E1', '品牌官网')
            ->setCellValue('F1', '排序')
            ->setCellValue('G1', '推荐(0否1是)')
            ->setCellValue('H1', '首字母');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, $v['cid'])                
                ->setCellValue('C'.$num, $v['name'])                
                ->setCellValue('D'.$num, $v['desc'])
                ->setCellValue('E'.$num, $v['url'])                 
                ->setCellValue('F'.$num, $v['sort'])
                ->setCellValue('G'.$num, $v['comm'])
                ->setCellValue('H'.$num, $v['py']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('商品');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="品牌.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }
}
?>