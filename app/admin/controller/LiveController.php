<?php

/**
 * 直播记录
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class LiveController extends AdminbaseController {
    function index(){

        $lists = Db::name('user_liverecord')
            ->where(function (Query $query) {
                $data = $this->request->param();

                $start_time=isset($data['start_time']) ? $data['start_time']: '';
                $end_time=isset($data['end_time']) ? $data['end_time']: '';
                
                if (!empty($start_time)) {
                    $query->where('starttime', 'gt' , strtotime($start_time));
                }
                if (!empty($end_time)) {
                    $query->where('starttime', 'lt' ,strtotime($end_time));
                }
                
                if (!empty($start_time) && !empty($end_time)) {
                    $query->where('starttime', 'between' , [strtotime($start_time),strtotime($end_time)]);
                }

                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                
                if (!empty($keyword)) {
                    $query->where('uid', 'like', "%$keyword%");
                }
            })
            ->order("id DESC")
            ->paginate(20);


        $lists->each(function($v,$k){
            $userinfo=getUserInfo($v['uid']);
            
            $v['userinfo']= $userinfo;
            
            return $v;
            
        });

        //分页-->筛选条件参数
        $data = $this->request->param();
        $lists->appends($data); 
            
        // 获取分页显示
        $page = $lists->render();
			
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }
		
	public function del(){

        $id=$this->request->param('id',0,'intval');
        if($id){
            $result=Db::name("user_liverecord")->where(["uid"=>$id])->delete();				
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
