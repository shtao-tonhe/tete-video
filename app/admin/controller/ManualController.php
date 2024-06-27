<?php

/**
 * 管理员手动充值记录
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;


class ManualController extends AdminbaseController {
	
	//列表
    public function index(){

			
    	$lists = Db::name("user_charge_admin")
			->where(function (Query $query){
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
                    $query->where('touid', 'like', "%$keyword%");
                }

				
			})
			->order('id desc')
			->paginate(20);
			
		$coin = 0;
		
		foreach($lists as $k=>$v){
			$userinfo=Db::name("user")
				->field("user_login,user_nicename")
				->where("id='$v[touid]'")
				->find();
			$v['user_login']=m_s($userinfo['user_login']);
			$v['user_nicename']=$userinfo['user_nicename'];
			
			$coin+=$v['coin'];
			
			
			$lists[$k]=$v;
			
		};
		
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
		
        // 获取分页显示
        $page = $lists->render();


    	$this->assign('lists', $lists);
    	$this->assign('coin', $coin);
		$this->assign('page', $page);

 
    	
    	return $this->fetch();
    }
		
	public function add(){	  
		return $this->fetch();				
	}		
    
    public function add_post() { 
		if($this->request->isPost()) {
			$data = $this->request->param();
			$user_login = (int)$data['user_login'];
			$coin = $data['coin'];
			
			if($user_login=='' || $coin==''){
				$this->error($Think.\lang('INCOMPLETE_INFORMATION'));
			}
			
			if(!is_numeric($coin)){
                $this->error($Think.\lang('RECHARGE_POINTS_MUST_BE_NUM'));
            }

            if(floor($coin)!=$coin){
                $this->error($Think.\lang('RECHARGE_POINT_NUST_BE_INTEGER'));
            }


			$user_info=Db::name("user")
				->field("id,coin")
				->where(["id"=>$user_login])
				->find();
			if(!$user_info){
				$this->error($Think.\lang('MEMBER_NOT_EXIST'));
			}
			
            $total=$user_info['coin']+$coin;
            if($total<0){
                $total=0;
            }
		

			$id=get_current_admin_id();
    		$user=Db::name("user")->where(["id"=>$id])->find();	
			
			$inster=[
				'touid'=>$user_info['id'],
				'coin'=>$coin,
				'addtime'=>time(),
				'admin'=>$user['user_login'],
				'ip'=>get_client_ip(0,true),
			];
			
			$result=Db::name("user_charge_admin")->insert($inster);
			if ($result) {
				Db::name("user")->where(["id"=>$user_login])->update(['coin'=>$total]);
				$this->success($Think.\lang('RECHARGE_SUCCESSFULLY'));
			} else {
				$this->error($Think.\lang('RECHARGE_FAILED'));
			}
			
		}

    }
    

}
