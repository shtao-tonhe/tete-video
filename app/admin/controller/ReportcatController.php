<?php

/**
 * 直播间举报类型
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class ReportcatController extends AdminbaseController {

	//列表
    public function index(){
		$lists=Db::name("user_live_report_classify")
            ->where(function (Query $query) {
                $data = $this->request->param();
                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                if (!empty($keyword)) {
                    $query->where('title', 'like', "%$keyword%");
                }

            })
            ->order("orderno asc")
            ->paginate(20);
			
			
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);	

    	 // 获取分页显示
        $page = $lists->render();
		
        $this->assign('lists', $lists);
        $this->assign('page', $page);
		
		
		return $this->fetch();
	}

	/*分类添加*/
	public function add(){

		return $this->fetch();
	}


	/*分类添加提交*/
	public function add_post(){

		if($this->request->isPost()) {
			
			$data = $this->request->param();
			
			$title=trim($data['title']);
			$orderno=$data['orderno'];

			if($title==""){
				$this->error($Think.\lang('ADMIN_REPORT_CLASSIFY_ADD_WRITE_CLASSIFT_NAME'));
			}


			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}
			
			
			$isexit=Db::name("user_live_report_classify")
				->where("title='{$title}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}
			
			$data['title']=$title;
			$data['orderno']=$orderno;
			$data['addtime']=time();
			
			$result=Db::name("user_live_report_classify")->insert($data);

			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'),'admin/Reportcat/index',3);
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}

	}

	//分类排序
    public function listorderset() { 

		$ids = $this->request->param('listorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("user_live_report_classify")
				->where(array('id' => $key))
				->update($data);
        }
				
        $status = true;
        if ($status) {
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }

    /*分类删除*/
	public function del(){

		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_live_report_classify")
				->where("id={$id}")
				->delete();				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
	}

	/*分类编辑*/
	public function edit(){
		$id = $this->request->param('id');
		if($id){
			$info=Db::name("user_live_report_classify")
				->where("id={$id}")
				->find();

			$this->assign("info",$info);
		}else{
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		
		return $this->fetch();
	}

	/*分类编辑提交*/
	public function edit_post(){

		if($this->request->isPost()) {
			
			$data = $this->request->param();	

			$id=$data["id"];
			$title=$data["title"];
			$orderno=$data["orderno"];

			if(!trim($title)){
				$this->error($Think.\lang('CATEGORY_TITLE_NOT_EMPTY'));
			}

			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}
		
			$isexit=Db::name("user_live_report_classify")
				->where("id!={$id} and title='{$title}'")
				->find();
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}
			
			$data["updatetime"]=time();

			unset($data['id']);
			
			$result=Db::name("user_live_report_classify")->where("id={$id}")->update($data);
			
			
			if($result!==false){
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			}else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			}
		}

	}
	

    
}
