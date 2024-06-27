<?php

/**
 * 大转盘 价格配置
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class TurntableconController extends AdminbaseController {
    
    function index(){
        
    	$lists = Db::name("turntable_con")
			->order("list_order asc,id asc")
			->paginate(20);
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }
    
    //排序
    public function listOrder() { 
		
        $model = DB::name('turntable_con');
        parent::listOrders($model);
        
        $this->resetcache();
        $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        
    }

    function edit(){
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('turntable_con')
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
            
			$times=$data['times'];

			if($times<1){
				$this->error($Think.\lang('ENTER_CORRECT_NUM_OF_TIMES'));
			}
			$coin=$data['coin'];
			if($coin<1){
				$this->error($Think.\lang('ENTER_CORRECT_NUM_OF_PRICE'));
			}

            
			$rs = DB::name('turntable_con')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
            
            $this->resetcache();
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
		}
    }
        
    function resetcache(){
        $key='turntable_con';
        $list=DB::name('turntable_con')
                ->field("id,times,coin")
                ->order('list_order asc,id asc')
                ->select();
        if($list){
            setcaches($key,$list);
        }else{
			delcache($key);
		}
        return 1;
    }
}
