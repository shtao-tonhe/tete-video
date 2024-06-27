<?php

/**
 * 引导图
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class GuideController extends AdminBaseController{


    public function set(){
		$this->assign('config', cmf_get_option('guide'));
    	return $this->fetch();
    }
    
    public function set_post(){
		 if($this->request->isPost()){
            $options = $this->request->param('post/a');
            cmf_set_option('guide', $options);
            $this->success($Think.\lang('EDIT_SUCCESS'), '');
        }
    }
    
	//列表
    public function index(){
        $config = cmf_get_option('guide');
		
        $type=$config['type'];
        $map['type']=$type;
        
    	$guide=Db::name("guide");
    	$lists = $guide
            ->where($map)
            ->order("orderno asc, id desc")
            ->paginate(20);
			
		
		$page = $lists->render();
        $this->assign("page", $page);
		
    	$this->assign('lists', $lists);
    	$this->assign('type', $type);
    	return $this->fetch();
    }
	
	//添加
    public function add(){
        $config = cmf_get_option('guide');
        
        $type=$config['type'];


        if($type==1){
            $map['type']=$type;
        
            $guide=Db::name("guide");
            $count=$guide->where($map)->count();

            if($count>=1){
                $this->error($Think.\lang('ONLY_ONE_BOOT_PAGE_VIDEO'));
            }
        }
        
        $this->assign('type', $type);
        
        return $this->fetch();				
    }
    
    public function add_post(){
       if ($this->request->isPost()) {
			$data = $this->request->param();
			$type=$data['type'];


			if($type==1){

				$count=Db::name("guide")->where("type=1")->count();
				if($count>=1){
					$this->error($Think.\lang('ONLY_ONE_BOOT_PAGE_VIDEO'));
				}

				if($_FILES){

					$files["file"]=$_FILES["file"];
					$type='video';

					$uploadSetting = cmf_get_upload_setting();
		            $extensions=$uploadSetting['file_types']['video']['extensions'];
		            $allow=explode(",",$extensions);

		            if (!get_file_suffix($files['file']['name'],$allow)){
	                    $this->error($Think.\lang('UPLOAD_VIDEO_CORRECT_FORMAT'));
	                }

					$rs=adminUploadFiles($files,$type);

					if($rs['code']!=0){

						$this->error($rs['msg']);
					}

					$data['thumb']=$rs['filepath'];

				}else{

					$this->error($Think.\lang('UPLOAD_VIDEO'));
				}
			}
			
			if(!$data['thumb']){
				$this->error($Think.\lang('UPLOAD_PICTURES'));
			}
			
			unset($data['file']);
			$data['addtime']=time();
			$data['uptime']=time();
          
             
			$result=Db::name("guide")->insert($data); 
			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'));
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
        }			
    }
	
	//删除
	public function del(){
		$id = $this->request->param('id');
        if($id){
            $result=Db::name("guide")->delete($id);				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'), '');
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  
        return $this->fetch();				
    }

	//编辑
    public function edit(){
        $id = $this->request->param('id');
        if($id){
            $data=Db::name("guide")->find($id);
            $this->assign('data', $data);						
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
		
        return $this->fetch();				
    }
    
    public function edit_post(){

		if ($this->request->isPost()) {
			$data = $this->request->param();
			
			$type=$data['type'];

			if($type==1){

				if($_FILES){

					$files["file"]=$_FILES["file"];
					$type='video';

					$uploadSetting = cmf_get_upload_setting();
		            $extensions=$uploadSetting['file_types']['video']['extensions'];
		            $allow=explode(",",$extensions);

		            if (!get_file_suffix($files['file']['name'],$allow)){
	                    $this->error($Think.\lang('UPLOAD_VIDEO_CORRECT_FORMAT'));
	                }

					$rs=adminUploadFiles($files,$type);
					if($rs['code']!=0){
						$this->error($rs['msg']);
					}
					$data['thumb']=$rs['filepath'];
					
					
				}else{
					$this->error($Think.\lang('UPLOAD_VIDEO'));
				}
			}
			if(!$data['thumb']){
				$this->error($Think.\lang('UPLOAD_PICTURES'));
			}
			unset($data['file']);
			
			$data['uptime']=time();
			$result=Db::name("guide")->update($data); 
			if($result){
				$this->success($Think.\lang('EDIT_SUCCESSLY'));
			}else{
				$this->error($Think.\lang('EDIT_FAILEDLY'));
			}
        }	
    }

    //排序
    public function listsorders() { 
		$ids = $this->request->param('listsorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("guide")
				->where(array('id' => $key))
				->update($data);
        }
				
        $status = true;
        if($status){
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'), '');
        }else{
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }	
	
        

}
