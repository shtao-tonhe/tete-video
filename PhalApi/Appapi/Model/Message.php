<?php

class Model_Message extends PhalApi_Model_NotORM {
	

	

	/*获取粉丝关注信息列表*/
	public function fansLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;


		$list=DI()->notorm->user_attention_messages->select("*")->where("touid=?",$uid)->order("addtime desc")->limit($start,$nums)->fetchAll();


		if(!$list){
			return 0;
		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($list as $k => $v) {
			$list[$k]['addtime']=datetime($v['addtime']);
			unset($list[$k]['touid']);
			$userinfo=getUserInfo($v['uid'],$tree);
			$list[$k]['userinfo']=$userinfo;
			$list[$k]['isattention']=isAttention($uid,$v['uid']);
		}

		return $list;
	}

	public function praiseLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$list=DI()->notorm->praise_messages->select("*")->where("touid='{$uid}' and status=1")->order("addtime desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return 0;
		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($list as $k => $v) {
			$list[$k]['addtime']=datetime($v['addtime']);
			unset($list[$k]['touid']);
			$userinfo=getUserInfo($v['uid'],$tree);
			if(!$userinfo){
				$userinfo['user_nicename']="已重置";
				$userinfo['avatar']="/default.png";
			}
			
			$list[$k]['user_nicename']=$userinfo['user_nicename'];
			$list[$k]['avatar']=get_upload_path($userinfo['avatar']);
			$videoinfo=DI()->notorm->user_video->select("uid")->where("id=?",$v['videoid'])->fetchOne();
			$list[$k]['videouid']=$videoinfo['uid'];
            
            $list[$k]['video_thumb']=get_upload_path($v['video_thumb']);           
		}

		return $list;
	}

	public function atLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$list=DI()->notorm->user_video_comments_at_messages->select("*")->where("touid='{$uid}' and status=1")->order("addtime desc")->limit($start,$nums)->fetchAll();
		if(!$list){
			return 0;
		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($list as $k => $v) {
			$userinfo=getUserInfo($v['uid'],$tree);
			$list[$k]['avatar']=get_upload_path($userinfo['avatar']);
			$list[$k]['user_nicename']=$userinfo['user_nicename'];
			$videoinfo=DI()->notorm->user_video->select("title,thumb,uid")->where("id='{$v['videoid']}'")->fetchOne();
			$list[$k]['video_title']=$videoinfo['title'];
			$list[$k]['video_thumb']=get_upload_path($videoinfo['thumb']);
			$list[$k]['addtime']=datetime($v['addtime']);
			$list[$k]['videouid']=$videoinfo['uid'];
		}

		return $list;

	}

	public function commentLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$list=DI()->notorm->user_video_comments_messages->select("*")->where("touid='{$uid}' and status=1")->order("addtime desc")->limit($start,$nums)->fetchAll();
		if(!$list){
			return 0;
		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($list as $k => $v) {
			$videoinfo=DI()->notorm->user_video->select("title,thumb,uid")->where("id='{$v['videoid']}'")->fetchOne();
			$list[$k]['addtime']=datetime($v['addtime']);
			$list[$k]['video_thumb']=get_upload_path($videoinfo['thumb']);   
			$userinfo=getUserInfo($v['uid'],$tree);
			$list[$k]['user_nicename']=$userinfo['user_nicename'];
			$list[$k]['avatar']=get_upload_path($userinfo['avatar']);
			$list[$k]['videouid']=$videoinfo['uid'];
            
                     
		}

		return $list;
	}

	/*官方通知列表*/
	public function officialLists($p){
		$nums=20;
		$start=($p-1)*$nums;

		$list=DI()->notorm->admin_push->select("id,title,synopsis,type,url,addtime")->order("addtime desc")->limit($start,$nums)->fetchAll();


		if(!$list){
			return 0;
		}


		//获取公共配置
		$config=getConfigPub();
		foreach ($list as $k => $v) {
			if($v['type']==1){
				$list[$k]['url']=$config['site'].'/Appapi/Message/msginfo?id='.$v['id'];	
			}

			$list[$k]['addtime']=datetime($v['addtime']);

			unset($list[$k]['type']);
		}

		return $list;
	}

	/*用户的系统通知列表*/
	public function systemnotifyLists($uid,$p){

		$nums=20;
		$start=($p-1)*$nums;
		$list=DI()->notorm->system_push->select("id,title,content,addtime")->where("uid=?",$uid)->order("addtime desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return 0;
		}

		foreach ($list as $k => $v) {
			$list[$k]['unix_addtime']=$v['addtime'];
			$list[$k]['addtime']=datetime($v['addtime']);
		}

		return $list;
	}

	/*获取用户系统消息最新时间*/
	public function getLastTime($uid){

		$res=array();

		$sysInfo=DI()->notorm->system_push->where("uid=?",$uid)->order("addtime desc")->fetchOne();
		$officeInfo=DI()->notorm->admin_push->order("addtime desc")->fetchOne();

		$goodsorderInfo=DI()->notorm->shop_order_message->where("uid=?",$uid)->order("addtime desc")->fetchOne();

		if($sysInfo){
			$res['sysInfo']	=$sysInfo;
		}

		if($officeInfo){
			$res['officeInfo']	=$officeInfo;
		}
		
		if($goodsorderInfo){
			$res['goodsorderInfo']	=$goodsorderInfo;
		}

		return $res;
	}

	//店铺订单信息列表
    public function getShopOrderList($uid,$p){
        if($p<1){
            $p=1;
        }
        $pnum=50;
        $start=($p-1)*$pnum;

        $list=DI()->notorm->shop_order_message
                ->select("title,orderid,addtime,type,is_commission")
                ->where("uid=?",$uid)
                ->order("addtime desc")
                ->limit($start,$pnum)
                ->fetchAll();

        foreach ($list as $k => $v) {
            $list[$k]['addtime']=date("Y-m-d H:i",$v['addtime']);
            $list[$k]['avatar']=get_upload_path('/goodsorderMsg.png');

            $where['id']=$v['orderid'];
            $order_info=getShopOrderInfo($where,'status');
            $list[$k]['status']=$order_info['status'];
        }

        return $list;
    }	
	
}
