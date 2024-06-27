<?php

/**
 * 直播分类
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class LiveclassController extends AdminbaseController {
    function index(){
			
    	$lists = Db::name("live_class")
            //->where()
            ->order("list_order asc, id desc")
            ->paginate(20);
            
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }
		
    function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('live_class')->where("id={$id}")->delete();
        if(!$rs){
            $this->error($Think.\lang('DELETE_FAILED'));
        }
        
        $action="删除直播分类：{$id}";
        setAdminLog($action);
                    
        $this->resetcache();
        $this->success($Think.\lang('DELETE_SUCCESS'));
    }		
    //排序
    public function listOrder() { 
		
        $model = DB::name('live_class');
        parent::listOrders($model);
        
        $action=$Think.\lang('UPDATE_BROADCAST_CLASSIFICATION_SORT');
        setAdminLog($action);
        
        $this->resetcache();
        $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
    }	
    

    function add(){        
        return $this->fetch();
    }	
    function addPost(){
        if ($this->request->isPost()) {
            
            $data = $this->request->param();
            
			$name=$data['name'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_THE_NAME'));
			}

            $des=$data['des'];
            if($des==''){
                $this->error($Think.\lang('FILL_IN_LIVE_BROADCAST_CLASSIFCATION_DECRIPTION'));
            }

            if(mb_strlen($des)>200){
                $this->error($Think.\lang('THE_CLASSIFICATION_DECRIPTION'));
            }
            
			$id = DB::name('live_class')->insertGetId($data);
            if(!$id){
                $this->error($Think.\lang('ADD_FAILED'));
            }
            
            $action="添加直播分类：{$id}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success($Think.\lang('ADD_SUCCESS'));
            
		}
    }		
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('live_class')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        $this->assign('data', $data);
        return $this->fetch(); 			
    }
    
    function editPost(){
        if ($this->request->isPost()) {
            
            $data      = $this->request->param();
            
			$name=$data['name'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_THE_NAME'));
			}

			$des=$data['des'];
            if($des==''){
                $this->error($Think.\lang('FILL_IN_LIVE_BROADCAST_CLASSIFCATION_DECRIPTION'));
            }

            if(mb_strlen($des)>200){
                $this->error($Think.\lang('THE_CLASSIFICATION_DECRIPTION'));
            }
            
			$id = DB::name('live_class')->update($data);
            if($id===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
            
            $action="修改直播分类：{$data['id']}";
            setAdminLog($action);
            
            $this->resetcache();
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
		}	
    }
    
    function resetcache(){
        $key='getLiveClass';
        $rules= DB::name('live_class')
                ->order('list_order asc,id desc')
                ->select();

        if($rules){
            setcaches($key,$rules);
        }else{
			delcache($key);
		}
        
        return 1;
    }
}
