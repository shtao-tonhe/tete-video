<?php

/**
 * 举报
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class ReportController extends AdminbaseController {

	//列表
    public function classify(){
		$lists=Db::name("user_report_classify")
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
	public function classify_add(){

		return $this->fetch();
	}


	/*分类添加提交*/
	public function classify_add_post(){

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
			
			
			$isexit=Db::name("user_report_classify")
				->where("title='{$title}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}
			
			$data['title']=$title;
			$data['orderno']=$orderno;
			$data['addtime']=time();
			
			$result=Db::name("user_report_classify")->insert($data);

			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'),'admin/Report/classify',3);
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}

	}

	//分类排序
    public function classify_listorders() { 

		$ids = $this->request->param('listorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("user_report_classify")
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
	public function classify_del(){

		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_report_classify")
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
	public function classify_edit(){
		$id = $this->request->param('id');
		if($id){
			$info=Db::name("user_report_classify")
				->where("id={$id}")
				->find();

			$this->assign("classify_info",$info);
		}else{
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		
		return $this->fetch();
	}

	/*分类编辑提交*/
	public function classify_edit_post(){

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
		
			$isexit=Db::name("user_report_classify")
				->where("id!={$id} and title='{$title}'")
				->find();
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}
			
			$data["updatetime"]=time();
			
			$result=Db::name("user_report_classify")
				->update($data);
				
			
			if($result!==false){
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			}else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			}
		}

	}

    public function index(){
		
		$lists = Db::name('user_report')
            ->where(function (Query $query) {
                $data = $this->request->param();

                $status=isset($data['status']) ? $data['status']: '';
                $start_time=isset($data['start_time']) ? $data['start_time']: '';
                $end_time=isset($data['end_time']) ? $data['end_time']: '';


                if ($status!='') {
                    $query->where('status','eq', intval($status));
                }
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
                    $query->where('uid', 'like', "%$keyword%");
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
			
			$userinfo=Db::name("user")
				->field("user_nicename,user_status")
				->where("id='$v[touid]'")
				->find();
			
			$v['touserinfo']= $userinfo;
			
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
	
	//标记处理
	public function setstatus(){
		$id = $this->request->param('id');
		if($id){
			 $data['status']=1;
			 $data['uptime']=time();
			 $result=Db::name("user_report")
				->where("id='{$id}'")
				->update($data);
			if($result){

				$reportInfo=Db::name("user_report")
					->where("id={$id}")
					->find();
				
				$reportedUserInfo=Db::name("user")
					->where("id={$reportInfo['touid']}")
					->field("id,user_nicename")
					->find();
				
				
				//发送极光IM
				$id=$_SESSION['ADMIN_ID'];
				$user=Db::name("user")->where("id='{$id}'")->find();

				//向系统通知表中写入数据
				$sysInfo=array(
					'title'=>$Think.\lang('USER_REPORT_HANDLING_REMINDER'),
					'addtime'=>time(),
					'admin'=>$user['user_login'],
					'ip'=>$_SERVER['REMOTE_ADDR'],
					'uid'=>$reportInfo['uid']

				);

				$baseMsg='您于'.date("Y-m-d H:i:s",$reportInfo['addtime']).'对'.$reportedUserInfo['user_nicename'].'的举报已被管理员于'.date("Y-m-d H:i:s",time()).'进行处理';

				$sysInfo['content']=$baseMsg;

				$result1=Db::name("system_push")->insert($sysInfo);

				if($result1!==false){
					$test=$Think.\lang('USER_REPORT_HANDLING_REMINDER');
					$uid=$reportInfo['uid'];
					jMessageIM($test,$uid);
				}

				$this->success($Think.\lang('MARK_SUCCESSFUL'));
			}else{
				$this->error($Think.\lang('MARKING_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  		
	}		
	
	//拉黑用户
	public function ban(){
    	$id = $this->request->param('id');
    	if ($id) {
    		$rst = Db::name("user")
				->where(array("id"=>$id,"user_type"=>2))
				->setField('user_status','0'); 
    		if ($rst!==false) {
				
    			$this->success($Think.\lang('MENBER_BLACKMAIL_SUCCESS'));
    		} else {
    			$this->error($Think.\lang('MENBER_BLACKMAIL_FAILED'));
    		}
    	} else {
    		$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
    	}
    }
	
	//下架视频
    public function ban_video(){
    	$id = $this->request->param('id');
    	if($id){
    		$rst = Db::name("user_video")
				->where(array("uid"=>$id))
				->setField('isdel','1');
    		if ($rst!==false) {
				
    			$this->success($Think.\lang('ALL_VIDEOS_REMOVED_SUCCESSFULLY'));
    		} else {
    			$this->error($Think.\lang('VIDEO_OFF_SHELF_FAILED'));
    		}
    	}else {
    		$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
    	}
    }

	//标记处理+禁用用户+下架视频
    public function ban_all(){
    	$id = $this->request->param('id');
    	if($id){

    		$data['status']=1;
			$data['uptime']=time();
			
			//标记处理
			$result=Db::name("user_report")
				->where("id='{$id}'")
				->update($data);

				
			//获取该举报信息对应的用户
			$info=Db::name("user_report")
				->where("id='{$id}'")
				->find();

			 //用户禁用
    		Db::name("user")
				->where(array("id"=>$info['touid'],"user_type"=>2))
				->setField('user_status','0'); 
				
				
				
    		 //下架视频
    		Db::name("user_video")
				->where(array("uid"=>$info['touid']))
				->setField('isdel','1');
    		
				
    		$this->success($Think.\lang('OPERATION_SUCCESSFUL'));
    		
    	}else {
    		$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
    	}
    }
	
	
	
	/*删除举报*/
	public function del(){

		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_report")
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
    
}
