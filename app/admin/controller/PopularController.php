<?php

/**
 * 上热门
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class PopularController extends AdminbaseController {
    var $type=[
        '0'=>'余额',
        '1'=>'支付宝',
        '2'=>'微信',
        '3'=>'苹果',
    ];
    
    var $status=[
        '0'=>'未支付',
        '1'=>'已支付',
    ];
    public function index(){
		
    	$lists=Db::name("popular_orders")
			->where(function(Query $query){
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
                    $query->where('uid|videoid', 'like', "%$keyword%");
                }

			})
			->order("id DESC")
            ->paginate(20);
			
			
			$lists->each(function($v,$k){
				$userinfo=Db::name("user")
					->field("user_nicename")
					->where("id='{$v['uid']}'")
					->find();
				if(!$userinfo){
					$userinfo=['user_nicename'=>$Think.\lang('USER_NOT_EXIST')];
				}

				$video_userinfo=Db::name("user")
					->field("user_nicename")
					->where("id='{$v['touid']}'")
					->find();

				if(!$video_userinfo){
					$video_userinfo=['user_nicename'=>$Think.\lang('USER_NOT_EXIST')];
				}

			   $v['userinfo']= $userinfo;
			   $v['video_userinfo']= $video_userinfo;
			   
			   $v['type_name']= $this->type[$v['type']];
			   $v['status_name']= $this->status[$v['status']];
			   
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
    
}
