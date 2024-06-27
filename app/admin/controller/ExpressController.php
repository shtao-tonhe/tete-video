<?php

/**
 * 店铺物流公司管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class ExpressController extends AdminbaseController {
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
            $map[]=['express_name','like','%'.$keyword.'%'];
        }
			

    	$lists = Db::name("shop_express")
                ->where($map)
                ->order("list_order asc,id DESC")
                ->paginate(20);
        
        $lists->each(function($v,$k){
			$v['express_thumb']=get_upload_path($v['express_thumb']);
            return $v;           
        });
        
        $lists->appends($data);
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("status", $this->getStatus());
    	
    	return $this->fetch();
	}
    
    //分类排序
    function listOrder() { 
        $model = DB::name('shop_express');
        parent::listOrders($model);
        
        $this->resetcache();
		
		
		$action="更新物流公司列表顺序";
        setAdminLog($action);

        $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
    }


	/*分类删除*/
	function del(){
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('shop_express')->where("id={$id}")->delete();
        if(!$rs){
            $this->error($Think.\lang('DELETE_FAILED'));
        }

        $this->resetcache();
        
		
		$action="删除物流公司ID: ".$id;
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
            
            $data      = $this->request->param();
            
			$express_name=$data['express_name'];

			if($express_name==""){
				$this->error($Think.\lang('FILL_IN_NAME_EXPRESS_COMPANY'));
			}
            
            $isexist=DB::name('shop_express')->where(['express_name'=>$express_name])->find();
            if($isexist){
                $this->error($Think.\lang('EXPRESS_COMPANY_NAME_ALREADY_EXOSTS'));
            }
            
			$express_thumb=$data['express_thumb'];
			if($express_thumb==""){
				$this->error($Think.\lang('UPLOAD_THE_COMPANY_ICON'));
			}

            $express_phone=$data['express_phone'];
            if($express_phone==""){
                $this->error($Think.\lang('FILL_IN_NUM_OF_EXPRESS_COMPANY'));
            }
			if(!preg_match("/^\d*$/",$express_phone)){
				$this->error($Think.\lang('CORRECT_TELEPHONE_NUM_OF_COMPANY'));
			}
            $express_code=$data['express_code'];
            if($express_code==""){
                $this->error($Think.\lang('FILL_IN_EXPRESS_COMPANY_CODE'));
            }
            
            $data['addtime']=time();
            
			$id = DB::name('shop_express')->insertGetId($data);
            if(!$id){
                $this->error($Think.\lang('ADD_FAILED'));
            }
			
			
			$action="添加物流公司ID: ".$id;
			setAdminLog($action);

            $this->resetcache();
            
            $this->success($Think.\lang('ADD_SUCCESS'));
            
		}

	}

	/*分类编辑*/
	function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('shop_express')
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
            
			$express_name=$data['express_name'];
			$id=$data['id'];

			if($express_name==""){
				$this->error($Think.\lang('FILL_IN_NAME_OF_LOGISTICS_COMPANY'));
			}
            
            $isexist=DB::name('shop_express')->where([['id','<>',$id],['express_name','=',$express_name]])->find();
            if($isexist){
                $this->error($Think.\lang('LOGISTICS_NAME_ALREADY_EXISTS'));
            }
            
			$express_thumb=$data['express_thumb'];
			if($express_thumb==""){
				$this->error($Think.\lang('UPLOAD_THE_LOGISTICS_COMPANY_ICON'));
			}
            
            $express_phone=$data['express_phone'];

            if($express_phone==""){
                $this->error($Think.\lang('FILL_IN_NUM_OF_LOGISTICS_COMPANY'));
            }

            $express_code=$data['express_code'];
            if($express_code==""){
                $this->error($Think.\lang('FILL_IN_EXPRESS_COMPANY_CODE'));
            }

            $data['edittime']=time();
            
			$rs = DB::name('shop_express')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }

            $this->resetcache();
			
			
			$action="编辑物流公司ID: ".$data['id'];
			setAdminLog($action);
            
            $this->success($Think.\lang('EDIT_SUCCESSLY'));
            
		}

	}

    //获取物流公司编码列表
    function expresslist(){

        $json_string=file_get_contents(CMF_ROOT."/public/static/express.json");
        $expresslist = json_decode($json_string, true);
        $lists=$expresslist['data'];
        $keyword=$this->request->param("keyword");
        if($keyword){
            $newlist=[];
            foreach ($lists as $k => $v) {
                if(strpos($v['name'],$keyword)!==false){
                   $newlist[]=$v; 
                }
            }

            

          $lists=$newlist;  
        }
        

        $this->assign('lists',$lists);

        return $this->fetch();
    }

    // 写入物流信息缓存
    function resetcache(){
        $key='getExpressList';
        
        $rs=DB::name('shop_express')
            ->field("id,express_name,express_phone,express_thumb")
            ->where('express_status=1')
            ->order("list_order asc,id desc")
            ->select();
        if($rs){
            setcaches($key,$rs);
        }   
        return 1;
    }

}
