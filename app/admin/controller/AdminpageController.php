<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class AdminpageController extends AdminBaseController{
	
	protected function initialize()
    {
        parent::initialize();
        $adminId = cmf_get_current_admin_id(); //获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign('admin_id', $adminId);
        }
    }
	
	// 后台页面列表
    public function index(){		


		//页面
		$posts=Db::name('posts')
			->where(function (Query $query) {
				$data = $this->request->param();
				
				$query->where('post_type', 1);
				
                if ($data['id']!='') {
                    $query->where('id', $data['id']);
                }
				
				if ($data['termid']!='') {
                    $query->where('termid', $data['termid']);
                }
				
                if (!empty($data['keyword'])) {
                    $keyword = $data['keyword'];
                    $query->where('post_title|post_keywords', 'like', "%$keyword%");
                }
			})
			->order("orderno DESC")
            ->paginate(20);
			
		$posts->each(function($v,$k){
			$userinfo=Db::name("user")
				->field("user_nicename")
				->where("id='$v[post_author]'")
				->find();
				
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('DELETED'),
				);
			}
				
			$v['userinfo']= $userinfo;
			
			return $v;
		});	
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$posts->appends($data);
		
    	// 获取分页显示
        $page = $posts->render();
	
        $this->assign('posts', $posts);
        $this->assign('page', $page);

        $configpub=getConfigPub();
		$this->assign("site",$configpub['site']);
	
		
        return $this->fetch();
    }
	
	//页面添加
	public function add(){
		
		//页面分类
        
		
		return $this->fetch();
	}
	
	public function add_post(){
			$data = input('post.post');
			if($data['post_type']==''){
				$this->error($Think.\lang('ARTICLE_CLASSIFICATION'));
			}else if(empty($data['post_title'])){
				$this->error($Think.\lang('ADMIN_ADMINPOST_ADD_SHURUTITLE'));
			}
			$data['post_author']=cmf_get_current_admin_id(); //获取后台管理员id
            $data['post_content']=html_entity_decode($data['post_content']);
        
            $data['post_status']='1';
            $data['post_type']='1';
            $data['post_date']=time();
            
			$add=Db::name('posts')->insert($data);
			if($add){
				$this->success($Think.\lang('ADD_SUCCESS'));
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}

	}
	
	//页面分类编辑
	public function edit(){
		$id = input('param.id');
		if($id){
		
			
			$info=Db::name('posts')->where('id',$id)->find();
            $info['post_content']=str_replace('../../','/upload/',$info['post_content']);
			$this->assign('info',$info);
		}else{
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		
		return $this->fetch();
	}
	
	public function edit_post(){
		$data = input('post.post');
		if($data['post_type']==''){
			$this->error($Think.\lang('ARTICLE_CLASSIFICATION'));
		}else if(empty($data['post_title'])){
			$this->error($Think.\lang('ADMIN_ADMINPOST_ADD_SHURUTITLE'));
		}
		
		$data['post_content']=html_entity_decode($data['post_content']);
		$data['post_type']='1';

		$save=Db::name('posts')->where('id',$data['id'])->update($data);	
		
		if($save){
			$this->success($Think.\lang('EDIT_SUCCESS'));
		}else{
			$this->error($Think.\lang('EDIT_FAILED'));
		}
	}
	
	
	// 页面分类删除
	public function del(){
        $id = input('param.id');
        if($id){
            $result=Db::name('posts')->delete($id);				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			 }else{
				$this->error($Think.\lang('DELETE_FAILED'));
			 }			
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  			
    }
	
	

	
	// 页面批量删除
	public function deletes(){
		$data = input();
		foreach ($data['ids'] as $k => $r) {
            Db::name('posts')->where(['id'=>$r])->delete();
        }
		$status = true;
		if ($status) {
			$this->success($Think.\lang('OPERATION_SUCCESSFUL'));
		} else {
			$this->error($Think.\lang('OPERATION_FAILED'));
		}
	}
	
	// 页面排序
	public function listordersset(){

		$ids=$this->request->param('listordersset');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("posts")
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
	
	
	
}