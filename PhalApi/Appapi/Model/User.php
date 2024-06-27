<?php

class Model_User extends PhalApi_Model_NotORM {
	/* 用户全部信息 */
	public function getBaseInfo($uid) {

		
		$info=DI()->notorm->user
				->select("id,user_nicename,avatar,avatar_thumb,sex,signature,province,city,area,birthday,age,mobile,coin,issuper,votestotal,consumption")
				->where('id=?  and user_type="2"',$uid)
				->fetchOne();

		if($info){

			if($info['age']==-1){
				$info['age']="年龄未填写";
			}else{
				$info['age'].="岁";
			}

			if($info['city']==""){
				$info['city']="城市未填写";
				$info['hometown']="";
			}else{
				$info['hometown']=$info['province'].$info['city'].$info['area'];
			}	

			$info['avatar']=get_upload_path($info['avatar']);
			$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);						
			$info['follows']=getFollows($uid);
			$info['fans']=getFans($uid);
			$info['praise']=getPraises($uid);
			$info['workVideos']=getWorks($uid);
			$info['likeVideos']=getLikes($uid);

			$info['signature']=ReplaceSensitiveWords($info['signature']); //个性签名过滤敏感词
			$info['user_nicename']=ReplaceSensitiveWords($info['user_nicename']); //昵称过滤敏感词
		}

		
		
					
		return $info;
	}
			
	/* 判断昵称是否重复 */
	public function checkName($uid,$name){
		$isexist=DI()->notorm->user
					->select('id')
					->where('id!=? and user_nicename=?',$uid,$name)
					->fetchOne();
		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	/* 判断手机号码是否重复 */
	public function checkMobile($uid,$mobile){
		$isexist=DI()->notorm->user
					->select('id')
					->where('id!=? and mobile=?',$uid,$mobile)
					->fetchOne();
		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	/* 修改信息 */
	public function userUpdate($uid,$fields){
		/* 清除缓存 */
		delCache("userinfo_".$uid);
		
		return DI()->notorm->user
					->where('id=?',$uid)
					->update($fields);
	}

	
	/* 关注 */
	public function setAttent($uid,$touid){

		//判断关注列表情况
		
		$isexist=DI()->notorm->user_attention
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->user_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;
		}else{
			DI()->notorm->user_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->user_attention
				->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));


			$isexist1=DI()->notorm->user_attention_messages
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();

			if($isexist1){
				DI()->notorm->user_attention_messages->where('uid=? and touid=?',$uid,$touid)->update(array("addtime"=>time()));
			}else{

				DI()->notorm->user_attention_messages
					->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));
				
				$msg_title=T("关注通知");
				
				jMessageIM($msg_title,$touid,"dsp_fans");
			}

			
			return 1;
		}			 
	}	
	
	/* 拉黑 */
	public function setBlack($uid,$touid){
		$isexist=DI()->notorm->user_black
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->user_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;
		}else{
			DI()->notorm->user_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->user_black
				->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));

			return 1;
		}			 
	}
	
	/* 关注列表 */
	public function getFollowsList($uid,$touid,$p,$key){
		$pnum=50;
		$start=($p-1)*$pnum;



		if($key!=0 &&!$key){
			$touids=DI()->notorm->user_attention
					->select("touid")
					->where('uid=?',$touid)
					->order("addtime desc")
					->limit($start,$pnum)
					->fetchAll();

		}else{

			

			$where.="a.uid='{$touid}' and u.user_nicename like '%".$key."%'";
		

			$prefix= DI()->config->get('dbs.tables.__default__.prefix');

			$touids=DI()->notorm->user_attention->queryAll("select a.touid,u.user_nicename from {$prefix}user_attention as a left join {$prefix}user as u on a.touid=u.id where ".$where." order by addtime desc limit {$start},{$pnum}");
		}


		//敏感词树        
        $tree=trieTreeBasic();

		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['touid'],$tree);
			if($userinfo){
				if($uid==$touid){
					$isattent=1;
				}else{
					$isattent=isAttention($uid,$v['touid']);
				}
				$userinfo['isattention']=$isattent;
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->user_attention->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}		
		$touids=array_values($touids);
		return $touids;
	}
	
	/* 粉丝列表 */
	public function getFansList($uid,$touid,$p){

		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->user_attention
					->select("uid,addtime")
					->where('touid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();

		//敏感词树        
        $tree=trieTreeBasic();
		
		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['uid'],$tree);
			if($userinfo){
				$userinfo['isattention']=isAttention($uid,$v['uid']);
				$touids[$k]=$userinfo;
				$touids[$k]['attentiontime']=datetime($v['addtime']);
			}else{
				DI()->notorm->user_attention->where('uid=? or touid=?',$v['uid'],$v['uid'])->delete();
				unset($touids[$k]);
			}
			
		}		
		$touids=array_values($touids);
		return $touids;
	}	

	/* 黑名单列表 */
	public function getBlackList($uid,$touid,$p){
		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->user_black
					->select("touid,addtime")
					->where('uid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();
		//敏感词树        
        $tree=trieTreeBasic();

		foreach($touids as $k=>$v){
			$userinfo=getUserInfo($v['touid'],$tree);
			if($userinfo){
				$userinfo['addtime']=datetime($v['addtime']); //拉黑时间
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->user_black->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}
		$touids=array_values($touids);
		return $touids;
	}

	
		/* 个人主页 */
	public function getUserHome($uid,$touid){

		$info=getUserInfo($touid);				

		$info['follows']=NumberFormat(getFollows($touid));
		$info['fans']=NumberFormat(getFans($touid));
		$info['isattention']=(string)isAttention($uid,$touid);
		/*$info['isblack']=(string)isBlack($uid,$touid);
		$info['isblack2']=(string)isBlack($touid,$uid);*/
		
		
		//获取对应用户的直播信息
        $liveinfo=getLiveInfo($touid);
        $info['liveinfo']=$liveinfo;

		return $info;
	}
	

	/*获取用户喜欢的视频列表*/
	public function getLikeVideos($uid,$touid,$p){


		$pnum=18; //数字必须为18

		$start=($p-1)*$pnum;

		$is_destroy=checkIsDestroyByUid($touid);
		if($is_destroy){
			return [];
		}

		//获取用户喜欢的视频列表
		$list=DI()->notorm->user_video_like->where("uid=? and status=1 ",$touid)->order("addtime desc")->limit($start,$pnum)->fetchAll();

		if(!$list){
			return 1001;
		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach ($list as $k => $v) {
			
			$videoinfo=DI()->notorm->user_video->where("id=? and status=1 and isdel=0 ",$v['videoid'])->fetchOne();

			if(!$videoinfo){
				//DI()->notorm->user_video_like->where("videoid=?",$v['videoid'])->delete();
				unset($list[$k]);
				continue;
			}
            $video=handleVideo($uid,$videoinfo,$tree);

			$video['addtime']=date('Y-m-d H:i:s', $v['addtime']);
			$video['datetime']=datetime($v['addtime']);

			$video['isdel']='0';  //暂时跟getAttentionVideo统一(包含下面的)
			$video['isdialect']='0';

			$lista[]=$video;  //因为unset掉某个数组后，k值连不起来了，所以重新赋值给新数组

		}

		if(empty($lista)){
			$lista=array();
		}

		return $lista;
	}



	/* 充值规则 */
	public function getChargeRules(){


		$rules= DI()->notorm->charge_rules
			->select('id,coin,coin_ios,money,product_id,give')
			->order('orderno asc')
			->fetchAll();


		return 	$rules;
	}

	/* 我的钻石 */
	public function getBalance($uid){
		return DI()->notorm->user
				->select("coin")
				->where('id=?',$uid)
				->fetchOne();
	}

	//获取vip充值规则
	public function getVipRules(){
		$rules= DI()->notorm->vip_charge_rules
            ->select('id,name,money,days,coin')
            ->order('orderno asc')
            ->fetchAll();

        foreach ($rules as $k => $v) {
        	$rules[$k]['money_ios']=$v['money'];
        }
            
        return 	$rules;
	}

	//获取用户是否可开播、是否可上传视频
	public function checkLiveVipStatus($uid){

		$configpri=getConfigPri();

		$auth_islimit=$configpri['auth_islimit'];
		$vip_switch=$configpri['vip_switch'];
		$nonvip_upload_nums=$configpri['nonvip_upload_nums'];

		$live_videos=$configpri['live_videos']; //开播需要达到的发布视频数
		$live_fans=$configpri['live_fans']; //开播需要达到的粉丝数

		$result['live_status']='1';
		$result['video_status']='1';
		$result['setvideo_charge']='0';

		//判断后台是否开启发布视频需要身份认证状态

		$isauth=isAuth($uid);

		if($auth_islimit && !$isauth){ //认证开启 且用户未认证
			return 1001;
		}

		//判断用户是否是vip
		$vipinfo=getUserVipInfo($uid);

		$today_start=strtotime(date("Y-m-d 00:00:00"));
		$today_end=strtotime(date("Y-m-d 23:59:59"));

		if($vip_switch && !$vipinfo['isvip'] && $nonvip_upload_nums){

			//判断用户今天发布视频总数
			$count=DI()->notorm->user_video->where("uid=? and addtime >=? and addtime<=?",$uid,$today_start,$today_end)->count();

			if($count>=$nonvip_upload_nums){
				$result['video_status']='0';
			}

		}



		//判断直播权限
		if($live_videos || $live_fans){

			//判断用户发布视频总数
			$count=DI()->notorm->user_video->where("uid=? and status=1 and isdel=0",$uid)->count();
			$fans_num=getFans($uid);

			//只判断发布视频数
			if($live_videos && !$live_fans){

				
				if($count<$live_videos){
					$result['live_status']='0';
				}


			}else if(!$live_videos && $live_fans){ //只判断粉丝数

				

				if($fans_num<$live_fans){
					$result['live_status']='0';
				}

			}else{

				if($count<$live_videos||$fans_num<$live_fans){
					$result['live_status']='0';
				}


			}


		}


		//获取后台配置的观看模式
		$watch_video_type=$configpri['watch_video_type'];
		if($watch_video_type){
			$result['setvideo_charge']='1';
		}

		return $result;
	}
	
	//获取每日任务
    public function seeDailyTasks($uid){
    	$configpri=getConfigPri();
    	$configpub=getConfigPub();
		$name_coin=$configpub['name_coin']; //钻石名称
		
		
		
		$list=[];
		
		//type 任务类型 1观看直播, 2观看视频, 3直播奖励, 4打赏奖励, 5分享奖励
		$type=['1'=>'观看直播','2'=>$Think.\lang('ADMIN_ADVERT_INDEX_GUANKANSHIPING'),'3'=>'直播奖励','4'=>'打赏奖励','5'=>'分享奖励'];
		
		// 当天时间
		$time=strtotime(date("Y-m-d 00:00:00",time()));
		foreach($type as $k=>$v){
			$data=[
				'id'=>'0',
				'type'=>(string)$k,
				'title'=>$v,
				'tip_m'=>'',
				'state'=>'0',
			];
			
			if($k==1){
				$target=$configpri['watch_live_term'];
				$reward=$configpri['watch_live_coin'];
			}else if($k==2){
				$target=$configpri['watch_video_term'];
				$reward=$configpri['watch_video_coin'];
			}else if($k==3){
				$target=$configpri['open_live_term']*60;
				$reward=$configpri['open_live_coin'];
				
			}else if($k==4){
				$target=$configpri['award_live_term'];
				$reward=$configpri['award_live_coin'];
			}else{
				$target=$configpri['share_live_term'];
				$reward=$configpri['share_live_coin'];
			}
			
			
			$save=[
				'uid'=>$uid,
				'type'=>$k,
				'target'=>$target,
				'schedule'=>'0',
				'reward'=>$reward,
				'addtime'=>$time,
				'state'=>'0',
			];
			
			$where="uid={$uid} and type={$k}";	
			//每日任务
			$info=DI()->notorm->user_daily_tasks
    			->where($where)
    			->select("*")
    			->fetchOne();
			
			if(!$info){
				$info=DI()->notorm->user_daily_tasks->insert($save);
				
				
			}else if($info['addtime']!=$time){
				$save['uptime']=time(); //更新时间
				DI()->notorm->user_daily_tasks->where("id={$info['id']}")->update($save);
			}else{
				$target=$info['target'];
				$reward=$info['reward'];
				$data['state']=$info['state'];
			}
			
			//提示标语
			if($k==1){
				$tip_m="观看直播时长达到{$target}分钟，奖励{$reward}".$name_coin;
			}else if($k==2){
				$tip_m="观看视频时长达到{$target}分钟，奖励{$reward}".$name_coin;
			}else if($k==3){
				$tip_m="每天开播满足{$target}分钟可获得奖励{$reward}".$name_coin;
			}else if($k==4){
				$tip_m="打赏Ta人超过{$target}{$name_coin}，奖励{$reward}".$name_coin;
			}else{
				$tip_m="直播间每日分享{$target}次可获得奖励{$reward}".$name_coin;
			}
			$data['id']=$info['id'];
			$data['tip_m']=$tip_m;
			$list[]=$data;	
		}
    	return $list;
    }
	
	
	public function receiveTaskReward($uid,$taskid){
		$rs = array('code' => 0, 'msg' => '领取成功!', 'info' => array());
		$where="id={$taskid} and uid={$uid}";	
		//每日任务
		$info=DI()->notorm->user_daily_tasks
			->where($where)
			->select("*")
			->fetchOne();
			
		if(!info){
			$rs['code']='1001';
			$rs['msg']='系统繁忙,请稍后操作~';
			return $rs;
		}
		if($info['state']==0){
			$rs['code']='1001';
			$rs['msg']='任务未达标,请继续加油~';
		}else if($info['state']==2){
			$rs['code']='1001';
			$rs['msg']='奖励已送达,不能重复领取!';
		}else{
			$rs['msg']='奖励已送放,明天继续加油哦~';
			
			
			//更新任务状态
			$issave=DI()->notorm->user_daily_tasks
				->where("id={$info['id']}")
				->update(['state'=>2,'uptime'=>time()]);
				
			if($issave){
				$coin=$info['reward'];
				/* 增加用户钻石 */
				$isprofit =DI()->notorm->user
							->where('id = ?', $uid)
							->update( array('coin' => new NotORM_Literal("coin + {$coin}") ));
				if($isprofit){  //生成记录
					$insert=array(
						"type"=>'income',
						"action"=>'daily_tasks',
						"uid"=>$uid,
						"touid"=>$uid,
						"giftid"=>'0',
						"giftcount"=>'0',
						"totalcoin"=>$coin,
						"addtime"=>time() 
					);
					DI()->notorm->user_coinrecord->insert($insert);
				}
				
				//删除用户每日任务数据
				$key="seeDailyTasks_".$uid;
				delcache($key);
			}
			
			

		}
	
		return $rs;
	}


	//用户设置美颜参数
	public function setBeautyParams($uid,$params){
		$info=DI()->notorm->user_beauty_params
		->where("uid=?",$uid)
		->fetchOne();

		$data=array(
			'params'=>$params
		);

		if($info){
			$res=DI()->notorm->user_beauty_params
			->where("uid=?",$uid)
			->update($data);

		}else{
			
			$data['uid']=$uid;
			$res=DI()->notorm->user_beauty_params
			->insert($data);
		}

		if($res===false){
			return 0;
		}

		return 1;

	}

	//获取用户设置的美颜参数
	public function getBeautyParams($uid){
		$info=DI()->notorm->user_beauty_params
		->where("uid=?",$uid)
		->fetchOne();

		$params=[];

		if(!$info){
			$configpub=getConfigPub();
			$params=array(
				'skin_whiting'=>$configpub['skin_whiting'],
				'skin_smooth'=>$configpub['skin_smooth'],
				'skin_tenderness'=>$configpub['skin_tenderness'],
				'eye_brow'=>$configpub['eye_brow'],
				'big_eye'=>$configpub['big_eye'],
				'eye_length'=>$configpub['eye_length'],
				'eye_corner'=>$configpub['eye_corner'],
				'eye_alat'=>$configpub['eye_alat'],
				'face_lift'=>$configpub['face_lift'],
				'face_shave'=>$configpub['face_shave'],
				'mouse_lift'=>$configpub['mouse_lift'],
				'nose_lift'=>$configpub['nose_lift'],
				'chin_lift'=>$configpub['chin_lift'],
				'forehead_lift'=>$configpub['forehead_lift'],
				'lengthen_noseLift'=>$configpub['lengthen_noseLift'],
				'brightness'=>$configpub['brightness'],
			);
		}else{
			$params=json_decode($info['params'],true);
		}

		return $params;
	}

	//用户店铺余额提现
    public function setShopCash($data){
        
        $nowtime=time();
        
        $uid=$data['uid'];
        $accountid=$data['accountid'];
        $money=$data['money'];
        
        $configpri=getConfigPri();
        $balance_cash_start=$configpri['balance_cash_start'];
        $balance_cash_end=$configpri['balance_cash_end'];
        $balance_cash_max_times=$configpri['balance_cash_max_times'];
        
        $day=(int)date("d",$nowtime);
        
        if($day < $balance_cash_start || $day > $balance_cash_end){
            return 1005;
        }
        
        //本月第一天
        $month=date('Y-m-d',strtotime(date("Ym",$nowtime).'01'));
        $month_start=strtotime(date("Ym",$nowtime).'01');

        //本月最后一天
        $month_end=strtotime("{$month} +1 month");
        
        if($balance_cash_max_times){
            $count=DI()->notorm->user_balance_cashrecord
                    ->where('uid=? and addtime > ? and addtime < ?',$uid,$month_start,$month_end)
                    ->count();
            if($count >= $balance_cash_max_times){
                return 1006;
            }
        }
        
        
        /* 钱包信息 */
        $accountinfo=DI()->notorm->user_cash_account
                ->select("*")
                ->where('id=? and uid=?',$accountid,$uid)
                ->fetchOne();

        if(!$accountinfo){
            return 1007;
        }
        

        /* 最低额度 */
        $balance_cash_min=$configpri['balance_cash_min'];
        
        if($money < $balance_cash_min){
            return 1004;
        }
        

        $ifok=DI()->notorm->user
            ->where('id = ? and balance>=?', $uid,$money)
            ->update(array('balance' => new NotORM_Literal("balance - {$money}")) );

        if(!$ifok){
            return 1001;
        }
        
        
        
        $data=array(
            "uid"=>$uid,
            "money"=>$money,
            "orderno"=>$uid.'_'.$nowtime.rand(100,999),
            "status"=>0,
            "addtime"=>$nowtime,
            "type"=>$accountinfo['type'],
            "account_bank"=>$accountinfo['account_bank'],
            "account"=>$accountinfo['account'],
            "name"=>$accountinfo['name'],
        );
        
        $rs=DI()->notorm->user_balance_cashrecord->insert($data);
        if(!$rs){
            return 1002;
        }           
            
        return $rs;
    }

    //判断用户是否第一次关注对方
    public function isFirstAttent($uid,$touid){
    	$isexist=DI()->notorm->user_attention_messages
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if(!$isexist){
			return 1;
		}

		return 0;
    }


    //BrainTree支付回调
	public function BraintreeCallback($uid,$orderno,$ordertype,$nonce,$money){

		$now=time();
		
		if($ordertype=='coin_charge'){ //钻石充值

			//查询钻石充值订单信息
			$charge_info=DI()->notorm->user_charge
				->where("uid=? and orderno=? and type=6 and money=?",$uid,$orderno,$money)
				->fetchOne();

			if(!$charge_info){
				return 1001;
			}

			if($charge_info['status']!=0){
				return 1002;
			}

			//更新用户钻石
			$coin=$charge_info['coin']+$charge_info['coin_give'];
			DI()->notorm->user
				->where("id=?",$uid)
				->update(array('coin' => new NotORM_Literal("coin + {$coin}")));

			$configpri=getConfigPri();

			$data['trade_no']=$nonce;
			$data['status']=1;
			$data['ambient']=1;

			if(!$configpri['braintree_paypal_environment']){
				$data['ambient']=0;
			}

			DI()->notorm->user_charge
				->where("id=?",$charge_info['id'])
				->update($data);
			

		}else if($ordertype=='order_pay'){ //订单支付

			//查询商城订单
			$order_info=DI()->notorm->shop_order
						->select("id,status,total,goodsid,nums,shop_uid,goods_name,orderno")
						->where("uid=? and total=? and orderno=?",$uid,$money,$orderno)
						->fetchOne();

			if(!$order_info){
				return 1001;
			}

			if($order_info['status']!=0){
				return 1002;
			}

			//更新订单状态
			$data['status']=1;
			$data['paytime']=$now;
			$data['type']=6;
			$data['trade_no']=$nonce;

			DI()->notorm->shop_order
				->where("id=?",$order_info['id'])
				->update($data);

			//增加用户的商城累计消费
			DI()->notorm->user
				->where("id=?",$uid)
				->update(array('balance_consumption' => new NotORM_Literal("balance_consumption + {$order_info['total']}")));

			//增加商品销量
			changeShopGoodsSaleNums($order_info['goodsid'],1,$order_info['nums']);
			//增加店铺销量
			changeShopSaleNums($order_info['shop_uid'],1,$order_info['nums']);

			//写入订单信息
	        $title="你的商品“".$order_info['goods_name']."”收到一笔新订单,订单编号:".$order_info['orderno'];

	        $data1=array(
	            'uid'=>$order_info['shop_uid'],
	            'orderid'=>$order_info['id'],
	            'title'=>$title,
	            'addtime'=>$now,
	            'type'=>'1'

	        );

	        addShopGoodsOrderMessage($data1);

	        jMessageIM($title,$order_info['shop_uid'],'goodsorder_admin');

		}else if($ordertype=='vip_pay'){ //购买vip
			$orderinfo=DI()->notorm->user_vip_charge
						->where("uid=? and orderno=? and money=? and status='0' and type='7'",$uid,$orderno,$money)
						->fetchOne();

			if(!$orderinfo){
				return 1001;
			}

			if($orderinfo['status']!=0){
				return 1002;
			}

			$now=time();
			$vipinfo=getUserVipInfo($orderinfo['touid']);
			$days=$orderinfo['days']*24*60*60;
			if($vipinfo['isvip']==0){ //用户不是vip
				$endtime=$now+$days;
			}else{
				$endtime=DI()->notorm->user
						->where("id=?",$orderinfo['touid'])
						->fetchOne("vip_endtime");
				$endtime=$endtime+$days;
			}

			//更新用户vip信息
			DI()->notorm->user
				->where("id=?",$orderinfo['touid'])
				->update(
					array("vip_endtime"=>$endtime)
				);

			// 更新订单状态
			DI()->notorm->user_vip_charge
				->where("id=?",$orderinfo['id'])
				->update(
					array("status"=>1,"trade_no"=>$nonce)
				);
		}
	}
	
}
