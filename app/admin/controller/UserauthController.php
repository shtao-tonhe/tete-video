<?php

/**
 * 用户认证
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class UserauthController extends AdminbaseController {
	
	
	//列表
    public function index(){
		$lists = Db::name('user_auth')
            ->where(function (Query $query) {

                $data = $this->request->param();

                $status=isset($data['status']) ? $data['status']: '';
				
                if ($status!='') {
                    $query->where('status', $status);
                }
				
				$start_time=isset($data['start_time']) ? $data['start_time']: '';
                $end_time=isset($data['end_time']) ? $data['end_time']: '';
			
                if (!empty($start_time)) {
                    $query->where('addtime', 'gt' , strtotime($start_time));
                }
			
                if (!empty($end_time)) {
                    $query->where('addtime', 'lt' ,strtotime($end_time));
                }
				
				if (!empty($start_time) && !empty($end_time)) {
                    $query->where('addtime', 'between' , [strtotime($start_time),strtotime($end_time)]);
                }
				
				$keyword=isset($data['keyword']) ? $data['keyword']: '';

                if (!empty($keyword)) {
                    $query->where('uid|real_name|mobile', 'like', "%$keyword%");
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

			$v['mobile']=m_s($v['mobile']);
			$v['cer_no']=m_s($v['cer_no']);
			
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

	//删除
	public function del(){
		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_auth")->where("uid='{$id}'")->delete();				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
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
			$auth=Db::name("user_auth")->where("uid='{$id}'")->find();
			$auth['mobile']=m_s($auth['mobile']);
			$auth['cer_no']=m_s($auth['cer_no']);

			$auth['front_view']= get_upload_path($auth['front_view']);
			$auth['back_view']= get_upload_path($auth['back_view']);
			$auth['handset_view']= get_upload_path($auth['handset_view']);
			
			
			$userinfo=Db::name("user")
				->field("user_nicename")
				->where("id='$auth[uid]'")
				->find();
			if(!$userinfo){
				$userinfo=['user_nicename'=>$Think.\lang('USER_NOT_EXIST')];
			}
			
			$auth['userinfo']=$userinfo;
			$this->assign('auth', $auth);						
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}			
		
		return $this->fetch();				
	}
		
	public function edit_post(){
		if($this->request->isPost()) {
			$data = $this->request->param();
			$data['uptime']=time();
			
			
			$result=Db::name("user_auth")->update($data); 
			if($result){
				
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'),url('userauth/index'));
			}else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			}
		}			
	}		
    
}
