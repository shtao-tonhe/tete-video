<?php

/**
 * 话题标签
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class LabelController extends AdminbaseController {

	//列表
    public function index(){

		$lists = Db::name('label')
            ->where(function (Query $query) {
                $data = $this->request->param();
                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                if (!empty($keyword)) {
                    $query->where('name', 'like', "%$keyword%");
                }
            })
            ->order("orderno asc, id desc")
            ->paginate(20);

		$lists->each(function($v,$k){
			$v['img_url']=get_upload_path($v['img_url']);
			return $v;
		});

        // 获取分页显示
        $page = $lists->render();
		
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);

    	return $this->fetch();
    }
	
	//删除
    public function del(){
        $id=$this->request->param('id');
        if($id){
            $result=Db::name('label')->delete($id);				
                if($result){
					
					//清除视频标签
					Db::name('user_video')->where("labelid={$id}")->update(['labelid'=>0]);
					$this->resetcache($id);
                    $this->success($Think.\lang('DELETE_SUCCESS'));
                 }else{
                    $this->error($Think.\lang('DELETE_FAILED'));
                 }			
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  
        return $this->fetch();				
    }	
    //排序
    public function listsorders() { 

		$ids=$this->request->param('listsorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name('label')->where(array('id' => $key))->update($data);
        }
				
        $status = true;
        if ($status) {
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }	
    
	
	//添加
    public function add(){ 
        return $this->fetch();				
    }
   
    public function add_post(){
        if($this->request->isPost()) {
			$data=$this->request->param();
			
            $name=$data['name'];
            if($name==''){
                $this->error($Think.\lang('FILL_IN_THE_NAME'));
            }
             
           
            $isexist=Db::name('label')
				->where("name='{$name}'")
				->find();
            if($isexist){
                $this->error($Think.\lang('NAME_ALREADY_EXISTS'));
            }
            
            if($data['thumb']==''){
                $this->error($Think.\lang('UPLOAD_THE_COVER'));
            }
             
            if($data['des']==''){
                $this->error($Think.\lang('FILL_IN_THE_DESCRIPTION'));
            }

            $result=Db::name('label')->insert($data); 
            if($result){
                $this->success($Think.\lang('ADD_SUCCESS'));
            }else{
                $this->error($Think.\lang('ADD_FAILED'));
            }
        }			
    }		
	
	
	//编辑
    public function edit(){
        $id=$this->request->param('id');
        if($id){
            $data=Db::name('label')->find($id);
            $this->assign('data', $data);						
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  
        return $this->fetch();				
    }
    
    public function edit_post(){
		
		 if($this->request->isPost()) {
			$data=$this->request->param();	
            $name=$data['name'];
            if($name==''){
                $this->error($Think.\lang('FILL_IN_THE_NAME'));
            }
            
            $isexist=Db::name('label')
				->where("name='{$name}' and id!={$data['id']}")
				->find();
            if($isexist){
                $this->error($Think.\lang('NAME_ALREADY_EXISTS'));
            }
            
            if($data['thumb']==''){
                $this->error($Think.\lang('UPLOAD_THE_COVER'));
            }
             
            if($data['des']==''){
                $this->error($Think.\lang('FILL_IN_THE_DESCRIPTION'));
            }

            $result=Db::name('label')->update($data); 
            if($result){
				
				$this->resetcache($data['id']);
				
                $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
            }else{
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
        }			
			
    }
	
	
	/*更新缓存*/
	public function resetcache($labelid){
        $key='LabelInfo_'.$labelid;
        $rs=Db::name('label')
            ->field("id,name,des,thumb")
            ->where("id={$labelid}")
            ->find();

        if($rs){
            setcaches($key,$rs);
        }   
        return 1;
    }
        

}
