<?php
namespace app\adminx\controller;

class Report extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Report')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}	

	//查看
    public function view(){
        $id = input('get.id');
        if ($id!='' || is_numeric($id)) {
            $list = db('Report')->where('id',$id)->find();
            if (!$list) {
                $this->error('信息不存在');
            }
            $list['item'] = db("ReportItem")->where("reportID",$id)->select();
            foreach ($list['item'] as $key => $value) {
                $list['item'][$key]['info'] = db("ReportDetail")->where("itemID",$value['id'])->select();
            }
            $this->assign('list', $list);
        	return view();
        }        
    }

    public function addItem(){
    	if(request()->isPost()){
	        $reportID = input('post.reportID');
	        $item = input('post.item');
	        if($item==''){
	        	$this->error("请选择分类");
	        }
	        if($reportID=='' || !is_numeric($reportID)){
	        	$this->error("参数错误");
	        }

	        $report = db("Report")->where("id",$reportID)->find();
	        if (!$report) {
	        	$this->error("体检报告已删除");
	        }
	        $map['reportID'] = $reportID;
	        $map['name'] = $item;
	        $res = db("ReportItem")->where($map)->find();
	        if ($res) {
	        	$this->error("分类已存在，不要重复添加");
	        }
	        $data['name'] = $item;
	        $data['reportID'] = $reportID;
	        $data['memberID'] = $report['memberID'];
	        $res = db("ReportItem")->insert($data);
	        if ($res) {
	        	$this->success("操作成功","reload");
	        }else{
	        	$this->error("操作失败");
	        }
		}else{
			$item = db("OptionCate")->order('sort asc,id asc')->select();
			$this->assign('item', $item);
			$this->assign('reportID', input('param.reportID'));
			return view();
		}
    }

    public function delItem(){
    	if(request()->isPost()){
	    	$itemID = input('post.id');
	    	if ($itemID=='' || !is_numeric($itemID)) {
	    		$this->error("参数错误");
	    	}
	        $map['id'] = $itemID;
	        $res = db("ReportItem")->where($map)->delete();
	        if ($res) {
	        	unset($map);
	        	$map['itemID'] = $itemID;
	        	db("ReportDetail")->where($map)->delete();
	        	$this->success("操作成功","reload");
	        }else{
	        	$this->error("操作失败");
	        }
        }
    }

    public function addInfo(){
    	if(request()->isPost()){
    		$data = input('post.');
	        if ($data['name']=='') {
	        	$this->error("请输入检查项目");
	        }
	        if ($data['value']=='') {
	        	$this->error("请输入检查结果");
	        }
	        if ($data['reportID']=='' || $data['itemID']=='' || $data['memberID']=='') {
	        	$this->error("参数错误");
	        }
	        $res = db("ReportDetail")->insert($data);
	        if ($res) {
	        	$this->success("操作成功","reload");
	        }else{
	        	$this->error("操作失败");
	        }
		}else{
			$this->assign('reportID', input('param.reportID'));
			$this->assign('itemID', input('param.itemID'));
			$this->assign('memberID', input('param.memberID'));
			return view();
		}
    }

    public function delInfo(){
    	if(request()->isPost()){
	    	$detailID = input('post.id');
	    	if ($detailID=='' || !is_numeric($detailID)) {
	    		$this->error("参数错误");
	    	}
	        $map['id'] = $detailID;
	        $res = db("ReportDetail")->where($map)->delete();
	        if ($res) {
	        	$this->success("操作成功","reload");
	        }else{
	        	$this->error("操作失败");
	        }
        }
    }

    public function import(){
    	if(request()->isPost()){
	    	set_time_limit(0);
            ini_set("memory_limit", "512M"); 
            
            $file = input('post.excel');
            $objReader = \PHPExcel_IOFactory::createReader ( 'Excel5' );
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load('..'.$file);
            $sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            //$highestColumm= PHPExcel_Cell::columnIndexFromString($highestColumm); //字母列转换为数字列 如:AA变为27
            $total = 0;
            $error = '';
            for ( $i = 2; $i <= $highestRow; $i++) {
            	$data['reportID'] = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
                $data['memberID'] = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
                $data['itemID'] = trim($sheet->getCellByColumnAndRow(3, $i)->getValue());
                $data['name'] = trim($sheet->getCellByColumnAndRow(5, $i)->getValue());
                $data['value'] = trim($sheet->getCellByColumnAndRow(6, $i)->getValue());
                $data['abnormal'] = trim($sheet->getCellByColumnAndRow(7, $i)->getValue());
                $data['remark'] = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                if ($data['name']!='' && $data['value']!='') {
                	db("ReportDetail")->insert($data);
                }
            }
            $this->success("操作成功","reload");
        }else{
        	return view();
        }
    }

	#删除报告
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Report')->del($id)){
				$map['reportID'] = array('in',$id);
				db("ReportItem")->where($map)->delete();
				db("ReportDetail")->where($map)->delete();
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>