<?php

/**
 * 分销
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class AgentController extends AdminbaseController {
    public function index(){

		$lists=Db::name("agent")
			->where(function(Query $query){
				
				$data = $this->request->param();

				$uid=isset($data['uid']) ? $data['uid'] :'';
				$one=isset($data['one']) ? $data['one'] :'';
				
				if (!empty($uid)) {
                    $query->where('uid', $uid);
                }
				if (!empty($one)) {
                    $query->where('one', $one);
                }
			})
			->order("addtime DESC")
			->paginate(20);
		
		$lists->each(function($v,$k){
			$userinfo=Db::name("user")
				->field("user_nicename")
				->where("id='$v[uid]'")
				->find();
			$v['userinfo']= $userinfo;
			
			if($v['one']){
				$oneuserinfo=Db::name("user")
					->field("user_nicename")
					->where("id='{$v['one']}'")
					->find();
			}else{
				$oneuserinfo['user_nicename']=$Think.\lang('NOT_SET');
			}
			$v['oneuserinfo']=$oneuserinfo;
			
			
			return $v;
		});
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
	
		// 获取分页显示
        $page = $lists->render();	
   
		$this->assign('lists', $lists);
        $this->assign('page', $page);
    	
    	return $this->fetch();
    }

    public function index2(){

		$lists=Db::name("agent_profit")
			->where(function(Query $query){
				
				$data = $this->request->param();

				$uid=isset($data['uid']) ? $data['uid'] :'';
				
				if (!empty($uid)) {
                    $query->where('uid', $uid);
                }
			
			})
			->order("uid DESC")
			->paginate(20);
		
		$lists->each(function($v,$k){
			$userinfo=Db::name("user")
				->field("user_nicename")
				->where("id='$v[uid]'")
				->find();
			$v['userinfo']= $userinfo;

			return $v;
		});
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
	
		// 获取分页显示
        $page = $lists->render();	
   
		$this->assign('lists', $lists);
        $this->assign('page', $page);
		
		return $this->fetch();
    }
	
	
	public function del(){
		$id = $this->request->param('id');
		if($id){
			$result=Db::name("agent")->where(['uid'=>$id])->delete();				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  	
	}
		
}
