<?php

/**
 * 退款理由
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class RefundreasonController extends AdminbaseController {
    protected function getStatus($k=''){
        $status=array(
            '0'=>$Think.\lang('HIDE'),
            '1'=>$Think.\lang('DISPLAY'),
        );
        if($k==''){
            return $status;
        }
        return isset($status[$k])?$status[$k]:'';
    }
    
    /*分类列表*/
	function index(){
        $data = $this->request->param();
        $map=[];
        
        
        $keyword=isset($data['keyword']) ? $data['keyword']: '';
        if($keyword!=''){
            $map[]=['name','like','%'.$keyword.'%'];
        }
			

    	$lists = Db::name("shop_refund_reason")
                ->where($map)
                ->order("list_order asc,id DESC")
                ->paginate(20);
        
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("status", $this->getStatus());
    	
    	return $this->fetch();
	}
    
    //分类排序
    function listOrder() { 
        $model = DB::name('shop_refund_reason');
        parent::listOrders($model);
        
        $this->resetcache();
		
		
		$action=$Think.\lang('UPDATE_BUYER_REFUND_RESON_SORTING');
        setAdminLog($action);

        $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
    }


	/*分类删除*/
	function del(){
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('shop_refund_reason')->where("id={$id}")->delete();
        if(!$rs){
            $this->error($Think.\lang('DELETE_FAILED'));
        }

        $this->resetcache();
		
		$action="删除买家退款原因列表ID: ".$id;
        setAdminLog($action);
        
        $this->success($Think.\lang('DELETE_SUCCESS'));
	}


	/*分类添加*/
	function add(){
        $this->assign("status", $this->getStatus());
		return $this->fetch();
	}

	/*分类添加提交*/
	function add_post(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();
            
			$name=$data['name'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_RESON_FOR_REFUND'));
			}
            
            $isexist=DB::name('shop_refund_reason')->where(['name'=>$name])->find();
            if($isexist){
                $this->error($Think.\lang('REFUND_RESON_ALREADY_EXISTS'));
            }
            
            $data['addtime']=time();
            
			$id = DB::name('shop_refund_reason')->insertGetId($data);
            if(!$id){
                $this->error($Think.\lang('ADD_FAILED'));
            }
			
			
			$action="添加买家退款原因列表ID: ".$id;
			setAdminLog($action);

            $this->resetcache();
            
            $this->success($Think.\lang('ADD_SUCCESS'));
            
		}

	}

	/*分类编辑*/
	function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('shop_refund_reason')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        $this->assign('status',$this->getStatus());
        $this->assign('data', $data);
        return $this->fetch();
	}

	/*分类编辑提交*/
	function edit_post(){
        if ($this->request->isPost()){
            
            $data = $this->request->param();
            
			$name=$data['name'];
			$id=$data['id'];

			if($name==""){
				$this->error($Think.\lang('FILL_IN_RESON_FOR_REFUND'));
			}
            
            $isexist=DB::name('shop_refund_reason')->where([['id','<>',$id],['name','=',$name]])->find();
            if($isexist){
                $this->error($Think.\lang('REFUND_RESON_ALREADY_EXISTS'));
            }
            
            if(mb_strlen($name)>30){
                $this->error($Think.\lang('NO_MORE_THAN_WORDS'));
            }

            $data['edittime']=time();
            
			$rs = DB::name('shop_refund_reason')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }

			$action="修改买家退款原因列表ID: ".$id;
			setAdminLog($action);
			
            $this->resetcache();
            
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
            
		}

	}


    // 写入退款原因缓存
    function resetcache(){
        $key='getRefundReason';
        
        $rs=DB::name('shop_refund_reason')
            ->field("id,name")
            ->where('status=1')
            ->order("list_order asc,id desc")
            ->select();
        if($rs){
            setcaches($key,$rs);
        }   
        return 1;
    }

}
