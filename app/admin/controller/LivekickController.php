<?php

/**
 * 踢人列表
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class LivekickController extends AdminbaseController {
    function index(){

        $lists = Db::name('user_live_kick')
            ->where(function (Query $query) {
                $data = $this->request->param();

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
                    $query->where('uid', 'like', "%$keyword%");
                }
            })
            ->order("addtime DESC")
            ->paginate(20);


        $lists->each(function($v,$k){
            $uidinfo=getUserInfo($v['uid']);
            $liveinfo=getUserInfo($v['liveuid']);
            $actioninfo=getUserInfo($v['actionid']);
            
            $v['uidinfo']= $uidinfo;
            $v['liveinfo']= $liveinfo;
            $v['actioninfo']= $actioninfo;
            
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
            $result=Db::name("user_live_kick")->where(["id"=>$id])->delete();				
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
