<?php
session_start();
class Model_Live extends PhalApi_Model_NotORM {

	//获取直播推荐列表
	public function getRecommendLists($p){
		if($p<1){
            $p=1;
        }

        if($p>1){
        	return [];
        }

        $pnum=1000;

        $where=" islive= '1'";

		/*if($p>1){
			$endtime=$_SESSION['recom_livelists_starttime'];
            if($endtime){
                $where.=" and recommend_time > {$endtime}";
            }
			
		}*/

		/*var_dump($where);
		die;*/

		$result=DI()->notorm->user_live
            ->select("uid,title,city,stream,pull,thumb,isshop,isvideo,starttime")
            ->where($where)
            ->order('isrecommend desc,recommend_time asc')
            ->limit(0,$pnum)
            ->fetchAll();


        //敏感词树        
        $tree=trieTreeBasic();

        foreach($result as $k=>$v){

			$v=$this->handleLive($v,$tree);
			
            
            $result[$k]=$v;
			
		}


		/*if($result){
			$last=end($result);
			$_SESSION['recom_livelists_starttime']=$last['starttime'];
		}*/

		return $result;

	}

	// 礼物列表
	public function getGiftList(){

		$rs=DI()->notorm->gift
			->select("id,type,mark,giftname,needcoin,gifticon,swftype,swf,swftime")
			->order("orderno asc,addtime desc")
			->fetchAll();

		return $rs;
	}

	//检测用户是否被禁播
	public function checkBan($uid){
		$isexist=DI()->notorm->user_live_ban
                ->where('uid=? ',$uid)
                ->fetchOne();
        if($isexist){
            return 1;
        }
		return 0;
	}

	//创建房间
 	public function createRoom($uid,$data){

 		$isexist=DI()->notorm->user_live
			->select("uid")
			->where('uid=?',$uid)
			->fetchOne();
		$now=time();

		//判断当前用户是否为推荐主播
		$userinfo=getUserInfo($uid);
		$data['isrecommend']=$userinfo['isrecommend'];
		if($userinfo['recommend_time']=='0'){
			$userinfo['recommend_time']=$now;
		}
		$data['recommend_time']=$userinfo['recommend_time'];

		if($isexist){

			//判断存在的记录是否为直播状态
			if($isexist['isvideo']==0 && $isexist['islive']==1 ){
				//若存在未关闭的直播，关闭直播
				$this->stopRoom($uid,$isexist['stream']);

				//写入直播记录
				$res=DI()->notorm->user_live->insert($data);
				
				
				/*开播直播计时---用于每日任务--记录主播开播*/
				$key='open_live_daily_tasks_'.$uid;
				$Room_time=$now;
				setcaches($key,$Room_time);
				
				
			}else{
				//更新直播记录
				$res=DI()->notorm->user_live->where("uid=?",$uid)->update($data);
			}
		}else{

			//写入直播记录
			$res=DI()->notorm->user_live->insert($data);
			
			
			/*开播直播计时---用于每日任务--记录主播开播*/
			$key='open_live_daily_tasks_'.$uid;
			$Room_time=$now;
			setcaches($key,$Room_time);
			
		}

		if(!$res){
			return 0;
		}

		return 1;
 	}


 	// 修改直播状态
	public function changeLive($uid,$stream,$status){

		if($status==1){
            $info=DI()->notorm->user_live
                    ->select("*")
					->where('uid=? and stream=?',$uid,$stream)
                    ->fetchOne();
            if($info){
                DI()->notorm->user_live
					->where('uid=? and stream=?',$uid,$stream)
					->update(array("islive"=>1));
            }
			return $info;
		}else{
			$this->stopRoom($uid,$stream);
			return 1;
		}
	}

 	//直播关播
 	public function stopRoom($uid,$stream){

 		$info=DI()->notorm->user_live
				->select("uid,showid,starttime,title,province,city,stream,thumb")
				->where('uid=? and stream=? and islive="1"',$uid,$stream)
				->fetchOne();

        file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 info:'.json_encode($info)."\r\n",FILE_APPEND);

        if($info){
        	$isdel=DI()->notorm->user_live
				->where('uid=?',$uid)
				->delete();
            if(!$isdel){
                return 0;
            }

            $nowtime=time();
			$info['endtime']=$nowtime;
			$info['time']=date("Y-m-d",$info['showid']);

			$votes=DI()->notorm->user_coinrecord
				->where('uid !=? and touid=? and showid=?',$uid,$uid,$info['showid'])
				->sum('totalcoin');

			$info['votes']=0;
			if($votes){
				$info['votes']=$votes;
			}

			$nums=DI()->redis->zCard('user_'.$stream);
			DI()->redis->hDel("livelist",$uid);
			delcache('user_'.$stream);
			$info['nums']=$nums;

			$result=DI()->notorm->user_liverecord->insert($info);

			file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result['id'])."\r\n",FILE_APPEND);

			// 解除本场禁言
            $list2=DI()->notorm->user_live_shut
                ->select('uid')
                ->where('liveuid=? and showid!=0',$uid)
                ->fetchAll();

            DI()->notorm->user_live_shut->where('liveuid=? and showid!=0',$uid)->delete();

            foreach($list2 as $k=>$v){
                DI()->redis -> hDel($uid . 'shutup',$v['uid']);
            }
			
			
			/*主播直播奖励---每日任务*/
			$key='open_live_daily_tasks_'.$uid;
			$starttime=getcaches($key);
			if($starttime){ 
				$endtime=time();  //当前时间
				$data=[
					'type'=>'3',
					'starttime'=>$starttime,
					'endtime'=>$endtime,
				];
				dailyTasks($uid,$data);
				//删除当前存入的时间
				delcache($key);
			}
			
			
        }

        return 1;
 	}

 	// 关播信息
	public function stopInfo($stream){
		
		$rs=array(
			'nums'=>"0",
			'length'=>"0",
			'votes'=>"0",
		);
		
		$stream2=explode('_',$stream);
		$liveuid=$stream2[0];
		$starttime=$stream2[1];
		$liveinfo=DI()->notorm->user_liverecord
					->select("starttime,endtime,nums,votes")
					->where('uid=? and starttime=?',$liveuid,$starttime)
					->fetchOne();
		if($liveinfo){
            $cha=$liveinfo['endtime'] - $liveinfo['starttime'];
			$rs['length']=getSeconds($cha,1);
			$rs['nums']=(string)$liveinfo['nums'];
		}
		if($liveinfo['votes']){
			$rs['votes']=(string)$liveinfo['votes'];
		}
		return $rs;
	}

	// 直播状态
	public function checkLive($uid,$liveuid,$stream){
        
		$islive=DI()->notorm->user_live
					->select("uid,title,city,stream,pull,thumb,isvideo,starttime,isshop,islive")
					->where('uid=? and stream=?',$liveuid,$stream)
					->fetchOne();

					
		if(!$islive || $islive['islive']==0){
			return 1005;
		}

		/* 是否被踢出 */
        $isexist=DI()->notorm->user_live_kick
					->select("id")
					->where('uid=? and liveuid=?',$uid,$liveuid)
					->fetchOne();
					
        if($isexist){
            return 1008;
        }

        
        //敏感词树        
        $tree=trieTreeBasic();
        
        $liveinfo=$this->handleLive($islive,$tree);
        unset($liveinfo['islive']);

        return $liveinfo;
		
	}

	// 是否禁言
	public function checkShut($uid,$liveuid){
        
        $isexist=DI()->notorm->user_live_shut
                ->where('uid=? and liveuid=? ',$uid,$liveuid)
                ->fetchOne();

        if($isexist){

            DI()->redis -> hSet($liveuid . 'shutup',$uid,1);
            
        }else{

            DI()->redis -> hDel($liveuid . 'shutup',$uid);
        }
		return 1;			
	}

	// 获取用户本场贡献
    public function getContribut($uid,$liveuid,$showid){
        $sum=DI()->notorm->user_coinrecord
				->where('action="sendgift" and uid=? and touid=? and showid=? ',$uid,$liveuid,$showid)
				->sum('totalcoin');
        if(!$sum){
            $sum=0;
        }
        
        return (string)$sum;
    }

    //直播信息格式化处理
    public function handleLive($info,$tree){

    	$nums=DI()->redis->zCard('user_'.$info['stream']); //获取直播间人数

		$info['nums']=(string)$nums;
		
        $userinfo=getUserInfo($info['uid'],$tree);
		$info['avatar']=$userinfo['avatar'];
		$info['avatar_thumb']=$userinfo['avatar_thumb'];
		$info['user_nicename']=$userinfo['user_nicename'];
		$info['sex']=$userinfo['sex'];

		if(!$info['thumb']){
			$info['thumb']=$info['avatar'];
		}

		$info['thumb']=get_upload_path($info['thumb']);
		if($info['isvideo']==0){
			$info['pull']=PrivateKeyA('rtmp',$info['stream'],0); //获取播流地址
		}

		if($info['title']){
			if($tree){
				$info['title']=eachReplaceSensitiveWords($tree,$info['title']);
			}else{
				$info['title']=ReplaceSensitiveWords($info['title']);
			}
		}

		

		

		return $info;

    }

    //直播间送礼物
    public function sendGift($uid,$liveuid,$stream,$giftid,$giftcount,$ispack){
    	// 礼物信息
		$giftinfo=DI()->notorm->gift
			->select("type,giftname,gifticon,needcoin,swftype,swf,swftime")
			->where('id=?',$giftid)
			->fetchOne();

		if(!$giftinfo){
			return 1002;
		}

		$total=$giftinfo['needcoin']*$giftcount;
		$now=time();
		$stream_arr=explode('_',$stream);
        $showid=$stream_arr[1];
        $action='sendgift';

	
		if($ispack==1){
			/* 背包礼物 */
            $ifok =DI()->notorm->backpack
                    ->where('uid=? and giftid=? and nums>=?',$uid,$giftid,$giftcount)
                ->update(array('nums'=> new NotORM_Literal("nums - {$giftcount} ")));
            if(!$ifok){
                /* 数量不足 */
                return 1003;
            }

            $action="backpack_sendgift";

		}else{
			//更新用户的余额
			$ifok=changeUserCoin($uid,$total);
			if(!$ifok){
				return 1001;
			}
		
		}

		//写入消费记录
        $data=array(
			'type'=>'expend',
			'action'=>$action,
			'uid'=>$uid,
			'touid'=>$liveuid,
			'giftid'=>$giftid,
			'giftcount'=>$giftcount,
			'totalcoin'=>$total,
			'showid'=>$showid,
			'addtime'=>$now

		);
		setCoinRecord($data);

        //更新主播映票
        changeUserVotes($liveuid,$total,1);


        //写入映票收入记录
        $data=array(
        	'action'=>5, //直播间送礼物
			'uid'=>$liveuid,
			'votes'=>$total,
			'addtime'=>$now
        );

        setVoteRecord($data);

        DI()->redis->zIncrBy('user_'.$stream,$total,$uid);
		
		/* PK处理 */
        $key1='LivePK';
        $key2='LivePK_gift';
        
        $ispk='0';
        $pkuid1='0';
        $pkuid2='0';
        $pktotal1='0';
        $pktotal2='0';
        
        $pkuid=DI()->redis -> hGet($key1,$liveuid);
        if($pkuid){
            $ispk='1';
            DI()->redis -> hIncrBy($key2,$liveuid,$total);
            
            $gift_uid=DI()->redis -> hGet($key2,$liveuid);
            $gift_pkuid=DI()->redis -> hGet($key2,$pkuid);
            
            $pktotal1=$gift_uid;
            $pktotal2=$gift_pkuid;
            
            $pkuid1=$liveuid;
            $pkuid2=$pkuid;
            
        }

        // 清除缓存
		delCache("userinfo_".$uid); 
		delCache("userinfo_".$liveuid);

		$votestotal=getUserVotesTotal($liveuid);

		$gifttoken=md5(md5($action.$uid.$liveuid.$giftid.$giftcount.$total.$showid.$now.rand(100,999)));
		$swf=$giftinfo['swf'] ? get_upload_path($giftinfo['swf']):'';
		$coin=getUserCoin($uid);

		$result=array(
			'uid'=>$uid,
			'giftid'=>$giftid,
			'type'=>$giftinfo['type'],
			'giftcount'=>$giftcount,
			'totalcoin'=>$total,
			'giftname'=>$giftinfo['giftname'],
			'gifticon'=>get_upload_path($giftinfo['gifticon']),
			'swftime'=>$giftinfo['swftime'],
			'swftype'=>$giftinfo['swftype'],
			'swf'=>get_upload_path($giftinfo['swf']),
			'coin'=>$coin,
			'votestotal'=>$votestotal,
			'gifttoken'=>$gifttoken,
			
			
			"ispk"=>$ispk,
            "pkuid"=>$pkuid,
            "pkuid1"=>$pkuid1,
            "pkuid2"=>$pkuid2,
            "pktotal1"=>$pktotal1,
            "pktotal2"=>$pktotal2,

		);
		
		
		/*打赏礼物---每日任务---针对于用户*/
		$data=[
			'type'=>'4',
			'total'=>$total,
		];
		dailyTasks($uid,$data);

		return $result;

    }

    // 设置/取消 管理员
	public function setAdmin($liveuid,$touid){

		//判断用户是否存在
		$isexist=checkUserIsExist($touid);
		if(!$isexist){
			return 1005;
		}	
					
		$isexist=DI()->notorm->user_livemanager
			->select("*")
			->where('uid=? and  liveuid=?',$touid,$liveuid)
			->fetchOne();

		if(!$isexist){
			$count =DI()->notorm->user_livemanager
				->where('liveuid=?',$liveuid)
				->count();

			if($count>=5){
				return 1004;
			}

			$rs=DI()->notorm->user_livemanager
					->insert(array("uid"=>$touid,"liveuid"=>$liveuid) );

			if($rs!==false){
				return 1;
			}else{
				return 1003;
			}				
			
		}else{

			$rs=DI()->notorm->user_livemanager
				->where('uid=? and liveuid=?',$touid,$liveuid)
				->delete();

			if($rs!==false){
				return 0; //需要返回给前台告知是否为管理员
			}else{
				return 1003;
			}						
		}


	}

	// 管理员列表
	public function getAdminList($liveuid){
		$rs=DI()->notorm->user_livemanager
			->select("uid")
			->where('liveuid=?',$liveuid)
			->fetchAll();

		foreach($rs as $k=>$v){
			$rs[$k]=getUserInfo($v['uid']);
		}	

        $info['list']=$rs;
        $info['nums']=(string)count($rs);
        $info['total']='5';
		return $info;
	}

	// 直播举报类型列表
	public function getReportClass(){
		return  DI()->notorm->user_live_report_classify
            ->select("id,title")
            ->where("isdel=0")
			->order("orderno asc")
			->fetchAll();
	}

	//直播举报
	public function setReport($uid,$touid,$content){
		$res=DI()->notorm->user_live_report
				->insert(
					array(
						"uid"=>$uid,
						"touid"=>$touid,
						'content'=>$content,
						'addtime'=>time()
					)
				);

		return $res;
	}

	//直播间禁言
	public function setShutUp($uid,$liveuid,$touid,$showid){
        
        $isexist=DI()->notorm->user_live_shut
                ->where('uid=? and liveuid=? ',$touid,$liveuid)
                ->fetchOne();

        if($isexist){
            if($isexist['showid']==$showid){
                return 1002;
            }
            
            if($isexist['showid']==0 && $showid!=0){
                return 1002;
            }
            
            $rs=DI()->notorm->user_live_shut->where('id=?',$isexist['id'])->update([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'showid'=>$showid,'addtime'=>time() ]);
            
        }else{
            $rs=DI()->notorm->user_live_shut->insert([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'showid'=>$showid,'addtime'=>time() ]);
        }
        
        
        
		return $rs;			
	}

	/* 踢人 */
	public function kicking($uid,$liveuid,$touid){
        
        $isexist=DI()->notorm->user_live_kick
            ->where('uid=? and liveuid=? ',$touid,$liveuid)
            ->fetchOne();

        if($isexist){
            return 1002;
        }
        
        $rs=DI()->notorm->user_live_kick->insert([ 'uid'=>$touid,'liveuid'=>$liveuid,'actionid'=>$uid,'addtime'=>time() ]);
        
        
		return $rs;
	}

	// 超管关闭直播间
	public function superStopRoom($uid,$token,$liveuid,$type){
		
		//禁播
		if($type==1){
			
            // 禁播列表
            $isexist=DI()->notorm->user_live_ban->where('uid=? ',$liveuid)->fetchOne();
            if($isexist){
                return 1002;
            }
            DI()->notorm->user_live_ban->insert([ 'uid'=>$liveuid,'superid'=>$uid,'addtime'=>time() ]);
		}
        
        //封禁账号
        if($type==2){
            //禁用
			DI()->notorm->user->where('id=? ',$liveuid)->update(array('user_status'=>0));
        }
		
	
		$info=DI()->notorm->user_live
				->select("stream")
				->where('uid=? and islive="1"',$liveuid)
				->fetchOne();
		if($info){
            $this->stopRoom($liveuid,$info['stream']);
		}

		
		return 0;
		
	}

	/* 检测房间状态 */
    public function checkLiveing($uid,$stream){
        $info=DI()->notorm->user_live
                ->select('uid')
				->where('uid=? and stream=? ',$uid,$stream)
				->fetchOne();
        if($info){
            return '1';
        }
        
        return '0';
    }
	
	//获取直播间在售商品中正在展示的商品
    public function getLiveShowGoods($liveuid){

    	$res=array('goodsid'=>'0','goods_name'=>'','goods_thumb'=>'','goods_price'=>'','goods_old_price'=>'','goods_type'=>'0');

    	//判断直播间是否开启购物车
    	$isshop=DI()->notorm->user_live->where("uid=?",$liveuid)->fetchOne('isshop');
    	if(!$isshop){
    		return $res;
    	}

    	$where=array(
    		'uid'=>$liveuid,
    		'status'=>1,
    		'issale'=>1,
    		'live_isshow'=>1,
    	);
    	$model_shop=new Model_Shop();
    	$goods_info=$model_shop->getGoods($where);
		
		
    	if($goods_info){
    		$goods_info=handleGoods($goods_info);
    		$res['goodsid']=$goods_info['id'];
    		$res['goods_name']=$goods_info['name'];
    		$res['goods_thumb']=$goods_info['thumbs_format'][0];;
    		if($goods_info['type']==1){
				$res['goods_price']=$goods_info['present_price'];
			}else{
				$res['goods_price']=$goods_info['specs_format'][0]['price'];
			}
    		$res['goods_old_price']=$goods_info['original_price'];
    		$res['goods_type']=$goods_info['type'];
    		
    	}else{ //代售平台商品
    		$where1=array(
    			'uid'=>$liveuid,
    			'status'=>1,
    			'issale'=>1,
    			'live_isshow'=>1
    		);
    		$onsale_platfrom_goods=getOnsalePlatformInfo($where1);

    		if($onsale_platfrom_goods){
    			$where2=array(
    				'id'=>$onsale_platfrom_goods['goodsid'],
    				'status'=>1
    			);
    			$goods_info=$model_shop->getGoods($where2);

    			if($goods_info){
		    		$goods_info=handleGoods($goods_info);
		    		$res['goodsid']=$goods_info['id'];
		    		$res['goods_name']=$goods_info['name'];
		    		$res['goods_thumb']=$goods_info['thumbs_format'][0];
		    		if($goods_info['type']==1){ //外链商品
		    			$res['goods_price']=$goods_info['present_price'];
		    		}else{
		    			$res['goods_price']=$goods_info['specs_format'][0]['price'];
		    		}
		    		
		    		$res['goods_type']=$goods_info['type'];

		    	}


    		}

    	}

    	return $res;

    }

    //直播间在售商品列表是否正在展示状态
    public function setLiveGoodsIsShow($uid,$goodsid){

    	$rs=array('status'=>'0'); //商品展示状态 0不显示 1 展示

    	//获取商品信息
    	$model_shop=new Model_Shop();
    	$where=array('uid'=>$uid,'id'=>$goodsid);
    	$goods_info=$model_shop->getGoods($where);

    	if(!$goods_info){ //非本人发布的商品
    		
    		//判断是否为该用户代售的商品
    		
    		$where1=[];
    		$where1['uid']=$uid;
    		$where1['goodsid']=$goodsid;
    		$where1['status']=1;

    		$is_sale=checkUserSalePlatformGoods($where1);

    		if(!$is_sale){
    			return 1001;
    		}

    		$sale_info=getOnsalePlatformInfo($where1);

    		if($sale_info['live_isshow']){ //在售
    			setOnsalePlatformInfo($where1,['live_isshow'=>0]);
    		}else{
    			setOnsalePlatformInfo($where1,['live_isshow'=>1,'issale'=>1]);
    			$rs['status']='1';

    			//将自己发布的商品在售状态改为0
    			DI()->notorm->shop_goods->where("uid={$uid} and status=1 and live_isshow=1")->update(array("live_isshow"=>0));

    			//将其他代售商品的在售状态改为0
    			$where2="uid={$uid} and goodsid !={$goodsid}";
    			setOnsalePlatformInfo($where2,['live_isshow'=>0]);
    		}



    		
    	}else{ //自己发布的商品

    		if($goods_info['status']!=1){
	    		return 1002;
	    	}

	    	if($goods_info['live_isshow']==1){ //取消展示
	    		$data=array(
	    			'live_isshow'=>0
	    		);

	    		$res=$model_shop->upGoods($where,$data);
	    		if(!$res){
	    			return 1003;
	    		}


	    	}else{ //设置展示

	    		
	    		$data=array(
	    			'live_isshow'=>1
	    		);

	    		$res=$model_shop->upGoods($where,$data);
	    		if(!$res){
	    			return 1004;
	    		}
	    		//将其他展示状态的商品改为非展示状态
	    		$where1="uid={$uid} and id !={$goodsid} and live_isshow=1";
	    		$data1=array(
	    			'live_isshow'=>0
	    		);

	    		$model_shop->upGoods($where1,$data1);

	    		$rs['status']='1';

	    		//将其他代售商品的在售状态改为0
    			$where2="uid={$uid} and goodsid !={$goodsid}";
    			setOnsalePlatformInfo($where2,['live_isshow'=>0]);

	    	}


    	}


    	return $rs;
    }

    // 根据直播分类获取直播列表
    public function getClassLive($liveclassid,$p) {
        if($p<1){
            $p=1;
        }
		$pnum=50;
		//$start=($p-1)*$pnum;
		$start=0;
		$where=" islive='1' and liveclassid={$liveclassid}";
        
		if($p!=1){
			$endtime=$_SESSION['getClassLive_starttime'];
            if($endtime){
                $where.=" and starttime < {$endtime}";
            }
			
		}
		$last_starttime=0;
		$result=DI()->notorm->user_live
				->where($where)
				->order("starttime desc")
				->limit(0,$pnum)
				->fetchAll();

		//敏感词树        
        $tree=trieTreeBasic();

		foreach($result as $k=>$v){
			$v=$this->handleLive($v,$tree);
            $result[$k]=$v;
		}		
		if($result){
            $last=end($result);
			$_SESSION['getClassLive_starttime']=$last['starttime'];
		}

		return $result;
    }

}
