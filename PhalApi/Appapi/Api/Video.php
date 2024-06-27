<?php

class Api_Video extends PhalApi_Api {

	public function getRules() {
		return array(

            'setVideo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'title' => array('name' => 'title', 'type' => 'string',  'desc' => '标题'),
				'thumb' => array('name' => 'thumb', 'type' => 'string',  'require' => true, 'desc' => '封面图'),
				'href' => array('name' => 'href', 'type' => 'string',  'require' => true, 'desc' => '视频链接'),
				'href_w' => array('name' => 'href_w', 'type' => 'string', 'desc' => '水印视频链接'),
				'lat' => array('name' => 'lat', 'type' => 'string',  'desc' => '维度'),
				'lng' => array('name' => 'lng', 'type' => 'string',  'desc' => '经度'),
				'city' => array('name' => 'city', 'type' => 'string',  'desc' => '城市'),
				'music_id' => array('name' => 'music_id', 'type' => 'int','default'=>0, 'desc' => '背景音乐id'),
				'labelid' => array('name' => 'labelid', 'type' => 'int','default'=>0, 'desc' => '标签ID'),
				'goodsid' => array('name' => 'goodsid', 'type' => 'int','default'=>0, 'desc' => '商品id'),
                'classid' => array('name' => 'classid', 'type' => 'int', 'desc' => '视频分类id'),
                'coin' => array('name' => 'coin', 'type' => 'int', 'desc' => '观看该视频需要花费钻石数'),
                'anyway' => array('name' => 'anyway', 'type' => 'string', 'default'=>'1.1','desc' => '横竖屏(封面-高/宽)，大于1表示竖屏,小于1表示横屏'),

			),
            'setComment' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'default'=>0, 'desc' => '回复的评论UID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论commentid'),
                'parentid' => array('name' => 'parentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论ID'),
                'content' => array('name' => 'content', 'type' => 'string',  'default'=>'', 'desc' => '内容'),
                'at_info'=>array('name'=>'at_info','type'=>'string','desc'=>'被@的用户json信息'),
                'type'=>array('name'=>'type','type'=>'int','default'=>'0','desc'=>'类型，0文字，1语音'),
                'voice'=>array('name'=>'voice','type'=>'string','desc'=>'语音'),
                'length'=>array('name'=>'length','type'=>'int','desc'=>'时长'),
            ),
            'addView' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),

            ),
            'addLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			'addStep' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			
			'addShare' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
			
			
			'addCommentLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论/回复 ID'),
            ),
            'getVideoList' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getAttentionVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'mobileid' => array('name' => 'mobileid', 'type' => 'string', 'desc' => '手机唯一识别码'),
            ),
            'getComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
            
			
			'getReplys' => array(
				'uid' => array('name' => 'uid', 'type' => 'int',  'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'last_replyid' => array('name' => 'last_replyid', 'type' => 'int', 'min' => 0, 'default'=>0, 'require' => true, 'desc' => '上一次请求时最小的回复id，第一次请求时为评论列表里的回复id'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'delComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => false, 'desc' => '用户Token'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'commentuid' => array('name' => 'commentuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论者用户ID'),
                
            ),
			
			'getMyVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

            'del' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
			
			'report' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => 'token'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
            ),
			
			'getHomeVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
            'getRecommendVideos'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            	'isstart' => array('name' => 'isstart', 'type' => 'int', 'default'=>0, 'desc' => '是否启动App'),
                'mobileid' => array('name' => 'mobileid', 'type' => 'string', 'desc' => '手机唯一识别码'),
            ),

            'getNearby'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'city' => array('name' => 'city', 'type' => 'string', 'default'=>'', 'desc' => '城市'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'setConversion'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),

                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
            
            'getViewRecord'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'desc' => 'token'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1', 'desc' => '页码'),
            ),

            'searchClassLists'=>array(
                'keywords' => array('name' => 'keywords', 'type' => 'string', 'desc' => '分类搜索关键词'),
            ),

            'getVideoListByClass'=>array(
                'classid' => array('name' => 'classid', 'type' => 'int', 'desc' => '视频分类id'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1', 'desc' => '页码'),
            ),

            'setVideoPay'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => 'token'), 
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),

            'getVideoListByMusic'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'musicid' => array('name' => 'musicid', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '音乐ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

            'videoSendGift'=>array(

                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'desc' => 'token'), 
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID'),
                'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
				'ispack' => array('name' => 'ispack', 'type' => 'int', 'default'=>'0', 'desc' => '是否背包 1是 0否'),
            ),

            'checkPayVideoIsShare'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'mobileid' => array('name' => 'mobileid', 'type' => 'string', 'desc' => '手机唯一识别码'),
            ),
			
			'startWatchVideo'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),
			
			'endWatchVideo'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),
			
			'getCitys'=>array(
            	/* 'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '关键词'), */
            ),
			
            'deltViewRecord'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'videoids' => array('name' => 'videoids', 'type' => 'string', 'require' => true, 'desc' => '视频ID列表'),
            ),
            
		);
	}
	
		

	
	/**
	 * 发布短视频
	 * @desc 用于发布短视频
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 视频记录ID
	 * @return string msg 提示信息
	 */
	public function setVideo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$title=checkNull($this->title);
		$thumb=checkNull($this->thumb);
		$href=checkNull($this->href);
		$href_w=checkNull($this->href_w);
		$lat=checkNull($this->lat);
		$lng=checkNull($this->lng);
		$city=checkNull($this->city);
		$music_id=checkNull($this->music_id);
		$labelid=checkNull($this->labelid);
		$goodsid=$this->goodsid;
        $classid=checkNull($this->classid);
        $coin=checkNull($this->coin);
        $anyway=checkNull($this->anyway);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        if(!$coin){
            $coin=0;
        }

		/*if($title){

			//检测敏感词
			$keywordsIsExist=checkSensitiveWords($title);
			if($keywordsIsExist){
				$rs['code'] = 1001;
                $rs['msg'] = '输入非法，请重新输入';
                return $rs;
			}
		}*/


        if($classid){
            $where= array('id'=>$classid);
            $class_isexist=checkVideoClass($where);
            if(!$class_isexist){
                $rs['code'] = 1001;
                $rs['msg'] = '视频分类不存在';
                return $rs;
            }
        }

        $configpri=getConfigPri();
        $watch_video_type=$configpri['watch_video_type'];

        if(!$watch_video_type){ //次数限制模式

            $coin=0;

        }else{ //内容限制模式【用户可设置收费金额】

            if($coin<0){
               $rs['code'] = 1002;
                $rs['msg'] = '收费金额不能为负数';
                return $rs; 
            }

            if($coin&&(floor($coin)!=$coin)){
                $rs['code'] = 1003;
                $rs['msg'] = '收费金额不能为小数';
                return $rs;
            }

        }
        
        $isgoods=0;
        $goods=[];
        $uploadGoods=[];
		if($goodsid>0){
			$isgoods=1;
		}

        if(!$thumb){
            $rs['code'] = 1004;
            $rs['msg'] = '请选择视频封面';
            return $rs;
        }
		
        if($configpri['cloudtype']==1){ //七牛
            $thumb_s=$thumb.'?imageView2/2/w/200/h/200';
        }else{
            $thumb_s=$thumb;
        }

        if(!$href){
            $rs['code'] = 1004;
            $rs['msg'] = $Think.\lang('UPLOAD_VIDEO');
            return $rs;
        }

        $configpub=getConfigPub();
        $watermark=$configpub['watermark'];

        if($watermark!=""){
            if($href_w==""){
                $rs['code'] = 1004;
                $rs['msg'] = $Think.\lang('UPLOAD_VIDEO');
                return $rs;
            }
            
        }else{
            $href_w=$href;
        }
	
		$data=array(
			"uid"=>$uid,
			"title"=>$title,
			"thumb"=>$thumb,
			"thumb_s"=>$thumb_s,
			"href"=>$href,
			"href_w"=>$href_w,
			"lat"=>$lat,
			"lng"=>$lng,
			"city"=>$city,
			"likes"=>0,
			"views"=>1, //因为涉及到推荐排序问题，所以初始值要为1
			"comments"=>0,
			"addtime"=>time(),
			"music_id"=>$music_id,
			"labelid"=>$labelid,
			"isgoods"=>$isgoods,
			"goodsid"=>$goodsid,
            'coin'=>$coin,
            'classid'=>$classid,
            'anyway'=>$anyway,

		);
		
		$domain = new Domain_Video();
		$info = $domain->setVideo($data,$music_id);
		if(!$info){
			$rs['code']=1001;
			$rs['msg']='发布失败';
            return $rs;
		}

        

        //粉丝推送通知

        $app_key = $configpri['jpush_key'];
		$master_secret = $configpri['jpush_secret'];
        $video_audit_switch=$configpri['video_audit_switch'];
        $jpush_switch=$configpri['jpush_switch'];

        //审核开关关闭情况下,推送开关打开的情况下

		if($app_key && $master_secret && !$video_audit_switch && $jpush_switch){
			require API_ROOT.'/public/JPush/autoload.php';
			// 初始化
			$client = new \JPush\Client($app_key, $master_secret,null);

			$userinfo=getUserInfo($uid);

			$videoinfo=array(
				'videoid'=>$info['id']
			);

			$open_title='你的好友：'.$userinfo['user_nicename'].'发布了新视频，快来看看吧';

			$apns_production=false;

			if($configpri['jpush_sandbox']){
				$apns_production=true;
			}

			$pushids=getFansIds($uid);

            //file_put_contents("34.txt", json_encode($pushids));

	        $nums=count($pushids);
	        for($i=0;$i<$nums;){
	        	$alias=array_slice($pushids,$i,900);
	        	$i+=900;

	        	try{	
					$result = $client->push()
							->setPlatform('all')
							->addRegistrationId($alias)
							->setNotificationAlert($open_title)
							->iosNotification($open_title, array(
								'sound' => 'sound.caf',
								'category' => 'jiguang',
								'extras' => array(
									'type' => '1',
									'videoinfo' => $videoinfo
								),
							))
							->androidNotification('', array(
								'extras' => array(
									'title' => $open_title,
									'type' => '1',
									'videoinfo' => $videoinfo
								),
							))
							->options(array(
								'sendno' => 100,
								'time_to_live' => 0,
								'apns_production' =>  $apns_production,
							))
							->send();
				} catch (Exception $e) {   
					file_put_contents('./setvideo_jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
					file_put_contents('./setvideo_jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
				}


	        }

		}


        


		$rs['info'][0]['id']=$info['id'];
		$rs['info'][0]['thumb_s']=$thumb_s;
        $rs['info'][0]['title']=$title;
		return $rs;
	}		
	
   	/**
     * 用户评论视频/回复别人评论
     * @desc 用于用户评论视频/回复别人评论
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return int info[0].isattent 对方是否关注我
     * @return int info[0].u2t 我是否拉黑对方
     * @return int info[0].t2u 对方是否拉黑我
     * @return int info[0].comments 评论总数
     * @return int info[0].replys 回复总数
     * @return string msg 提示信息
     */
	public function setComment() {
        $rs = array('code' => 0, 'msg' => '评论成功', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$touid=$this->touid;
		$videoid=$this->videoid;
		$commentid=$this->commentid;
		$parentid=$this->parentid;
		$content=checkNull($this->content);
		$type=checkNull($this->type);
		$voice=checkNull($this->voice);
		$length=checkNull($this->length);
		$at_info=$this->at_info;


		if(!$at_info){
			$at_info='';
		}
        
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($touid>0){
			$isattent=isAttention($touid,$uid);
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
			if($t2u==1){
				$rs['code'] = 1000;
				$rs['msg'] = '对方暂时拒绝接收您的消息';
				return $rs;
			}
		
		}


        
        if($type==1){ //语音
            if($voice==''){
                $rs['code'] = 1001;
				$rs['msg'] = '请输入内容';
				return $rs;
            }
        }else{

            if($content==''){
                $rs['code'] = 1002;
				$rs['msg'] = '请输入内容';
				return $rs;
            }

            //检测敏感词
			$keywordsIsExist=checkSensitiveWords($content);
			if($keywordsIsExist){
				$rs['code'] = 1001;
                $rs['msg'] = '输入非法，请重新输入';
                return $rs;
			}


        }
		
		if($commentid==0 && $commentid!=$parentid){
			$commentid=$parentid;
		}
		
		$data=array(
			'uid'=>$uid,
			'touid'=>$touid,
			'videoid'=>$videoid,
			'commentid'=>$commentid,
			'parentid'=>$parentid,
			'content'=>$content,
			'addtime'=>time(),
			'at_info'=>urldecode($at_info),
			'type'=>$type,
			'voice'=>$voice,
			'length'=>$length,
		);


        $domain = new Domain_Video();
        $result = $domain->setComment($data);
		
        if($result==1001){
            $rs['code']=1001;
            $rs['msg']="评论失败";
            return $rs;
        }
		
		$info=array(
			'isattent'=>'0',
			'u2t'=>'0',
			't2u'=>'0',
			'comments'=>$result['comments'],
			'replys'=>$result['replys'],
			//'current'=>$result['current'],
		);
		if($touid>0){
			$isattent=isAttention($touid,$uid);
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
			
			$info['isattent']=(string)$isattent;
			$info['u2t']=(string)$u2t;
			$info['t2u']=(string)$t2u;
		}
		
		$rs['info'][0]=$info;
		
		if($parentid!=0){
			 $rs['msg']='回复成功';			
		}
        return $rs;
    }	
	
   	/**
     * 更新视频阅读次数
     * @desc 用于更新视频阅读次数
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function addView() {
        $rs = array('code' => 0, 'msg' => '更新视频阅读次数成功', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);

		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		/*if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = '更新视频阅读次数失败';
			return $rs;
		}*/

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}


        $domain = new Domain_Video();
        $res = $domain->addView($uid,$videoid);

        return $rs;
    }	
   	/**
     * 视频点赞数累计
     * @desc 用于视频点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);
		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
        $domain = new Domain_Video();
        $result = $domain->addLike($uid,$videoid);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = "视频已删除";
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = "不能给自己点赞";
			return $rs;
		}
		$rs['info'][0]=$result;
        return $rs;
    }	


   	/**
     * 视频分享数累计
     * @desc 用于视频分享数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isshare 是否分享
     * @return string info[0].shares 分享数量
     * @return string msg 提示信息
     */
	public function addShare() {
        $rs = array('code' => 0, 'msg' => '分享成功', 'info' => array());

        $uid=checkNull($this->uid);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);


		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = '视频分享数修改失败';
			return $rs;
		}
		
        $domain = new Domain_Video();
        $rs['info'][0] = $domain->addShare($uid,$videoid);

        return $rs;
    }	
	
	
   	/**
     * 评论/回复 点赞数累计
     * @desc 用于评论/回复 点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addCommentLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $commentid=checkNull($this->commentid);

        $isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Video();
         $res= $domain->addCommentLike($uid,$commentid);
         if($res==1001){
         	$rs['code']=1001;
         	$rs['msg']='评论信息不存在';
         	return $rs;
         }
         $rs['info'][0]=$res;

        return $rs;
    }	
	/**
     * 获取热门视频
     * @desc 用于获取热门视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return object info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string info[].isattent 是否关注
     * @return string info[].thumb_s 封面小图，分享用
     * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getVideoList() {

		
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $p=checkNull($this->p);
		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		

		$key='videoHot_'.$p;

		$info=getcache($key);

		if(!$info){
			$domain = new Domain_Video();
			$info= $domain->getVideoList($uid,$p);

			if($info==10010){
				$rs['code'] = 0;
				$rs['msg'] = "暂无视频列表";
				return $rs;
			}
			
			setcaches($key,$info,2);
		}

        
		$rs['info'] =$info;
        return $rs;
    }	
	/**
     * 获取关注视频
     * @desc 用于获取关注视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
	 * @return string info[].islike 是否点赞 
	 * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getAttentionVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		

        $uid=checkNull($this->uid);
		
		//未登录状态下访问接口
		if($uid<0){
			$rs['code']=0;
			$rs['msg']="暂无视频列表";
			return $rs;
		}
		
		
		
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$key='attention_vidseoLists_'.$uid.'_'.$p;
        $info=getcache($key);

        if(!$info){
        	$domain = new Domain_Video();
        	$info=$domain->getAttentionVideo($uid,$p);
        	if($info==0){
        		$rs['code']=0;
                $rs['msg']="暂无视频列表";
                return $rs;
        	}
        }
        
        $rs['info'] = $info;

        return $rs;
    }		
	/**
     * 获取视频详情
     * @desc 用于获取视频详情
     * @return int code 操作码，0表示成功，1000表示视频不存在  1001 需要登录  1002 购买vip  1003 支付钻石
     * @return array info[0] 视频详情
     * @return object info[0].userinfo 用户信息
     * @return string info[0].datetime 格式后的时间差
     * @return string info[0].isattent 是否关注
     * @return string info[0].likes 点赞数
     * @return string info[0].comments 评论数
     * @return string info[0].views 阅读数
     * @return string info[0].steps 踩一踩数量
     * @return string info[0].shares 分享数量
     * @return string info[0].islike 是否点赞
     * @return string info[0].isstep 是否踩
     * @return string msg 提示信息
     */
	public function getVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);
        $mobileid=checkNull($this->mobileid);

        if($uid<0&&!$mobileid){
            $rs['code'] = 999;
            $rs['msg'] = "手机识别码获取失败";
            return $rs;
        }

        $domain = new Domain_Video();
        $result = $domain->getVideo($uid,$videoid);
		if($result==1000){
			$rs['code'] = 999;
			$rs['msg'] = "视频已删除";
			return $rs;	
		}


        //判断视频是否为广告视频【广告视频直接返回数据】
        $is_ad=checkVideoIsAd($videoid);


        if($is_ad){
            $rs['info'][0]=$result;
            return $rs;
        }
        

        $configpri=getConfigPri();
        $watch_video_type=$configpri['watch_video_type']; //观看模式
        $nonvip_watch_nums=$configpri['nonvip_watch_nums']; //非vip会员免费观看次数
        $vip_switch=$configpri['vip_switch']; //vip开关

        $today_start=strtotime(date("Y-m-d 00:00:00"));
        $today_end=strtotime(date("Y-m-d 23:59:59"));

        if($uid<0){ //游客

            if($watch_video_type && $result['coin']){ //内容限制模式且视频需要付费

                $rs['code'] = 1003;
                $rs['msg'] = "该视频需要付费观看\n请登录后观看视频";
                return $rs;
                
            }

            if($watch_video_type==0 && $nonvip_watch_nums){ //次数限制模式

                $res=$this->numberLimitFormat(0,$mobileid,$videoid);

                if($res['code']!=0){
                    return $res;
                }

            }


        }else{ // 用户登录

            if($result['uid'] !=$uid){ //非自我视频

                if($vip_switch){ //vip启用

                    //判断用户是否是vip
                    $vipinfo=getUserVipInfo($uid);

                    if(!$vipinfo['isvip']){

                        if($watch_video_type==0 && $nonvip_watch_nums){ //次数限制模式
                            
                            $res=$this->numberLimitFormat(1,$uid,$videoid,1);

                            if($res['code']!=0){
                                return $res;
                            }

                        }else if($watch_video_type==1){ //内容限制模式


                            if($result['coin']&& ($result['uid'] !=$uid)){

                                $res=$this->payLimitFormat($uid,$videoid,$result['coin']);

                                if($res['code']!=0){
                                    return $res;
                                }
                            }

                        }
                    }

                    
                }else{ //vip开关没打开


                    if($watch_video_type==0 && $nonvip_watch_nums){ //次数限制模式
                            
                        $res=$this->numberLimitFormat(1,$uid,$videoid,0);

                        if($res['code']!=0){
                            return $res;
                        }

                    }else if($watch_video_type==1){ //内容限制模式

                        if($result['coin'] && ($result['uid'] !=$uid)){

                            $res=$this->payLimitFormat($uid,$videoid,$result['coin']);

                            if($res['code']!=0){
                                return $res;
                            }
                        }
                        
                    }

                }

            }

        }

		$rs['info'][0]=$result;

        return $rs;
    }

    /**
     * 次数限制模式处理模块
     * @desc 用于次数限制模式处理模块
     */

    private function numberLimitFormat($type,$uid,$videoid,$vip_switch=0){  //type:0 游客模式，1登录模式

        $rs=array('code'=>0,'msg'=>'','info'=>array());

        //获取最新观看时间
        $watchtimekey="video_watchtime_".$uid;
        $watch_lists_key="video_watch_".$uid;

        $watchtime=DI()->redis->get($watchtimekey);

        if(!$watchtime){
            if($type==0){ //游客模式
                $watchtime=getTouristWatchTime($uid);
            }else{
                $watchtime=getUserWatchTime($uid);
            }
            
        }

        $today_start=strtotime(date("Y-m-d 00:00:00"));
        $today_end=strtotime(date("Y-m-d 23:59:59"));

        $configpri=getConfigPri();
        $nonvip_watch_nums=$configpri['nonvip_watch_nums']; //非vip会员免费观看次数


        if(!$watchtime || $watchtime<$today_start){ //第一次看 或 最新记录为昨天


            //更新最新观看时间
            DI()->redis->set($watchtimekey,time());

            //更新数据库观看时间
            if($type==0){
                setTouristWatchTime($uid);
            }else{
                setUserWatchTime($uid);
            }

            //清空观看记录
            DI()->redis->delete($watch_lists_key); 

            //记录观看视频的记录
            DI()->redis->sAdd($watch_lists_key,$videoid);

            //更新数据库观看视频记录
            if($type==0){
                setTouristWatchLists($uid,$videoid,1);
            }else{
                setUserWatchLists($uid,$videoid,1);
            }


        }else{

            //判断是否看过该视频

            $watchLists=DI()->redis->sMembers($watch_lists_key);

            if($watchLists){

                $is_watch=DI()->redis->sIsMember($watch_lists_key,$videoid);


                if(!$is_watch){

                    //判断观看次数是否达到限制上限
                    $watch_nums=DI()->redis->sCard($watch_lists_key);


                    if($watch_nums>=$nonvip_watch_nums){


                        if($type==0 || ($type==1 && $vip_switch==0 )){ //游客||登录状态但vip开关未打开

                            $rs['code'] = 1000; //此值要按照不同情况 固定写死，不然app端弹窗会错乱
                            $rs['msg'] = "您今天免费观看次数已用尽\n欢迎明天再来继续观看";

                        }else{

                            $rs['code'] = 1002; //此值要按照不同情况 固定写死，不然app端弹窗会错乱

                            $rs['msg'] = "您今天免费观看次数已用尽\n开通会员可无限观看视频";

                        }

                        
                        return $rs;
                        
                    }else{

                        //更新redis观看时间
                        DI()->redis->set($watchtimekey,time());

                        //更新数据库观看时间
                        if($type==0){
                            setTouristWatchTime($uid);
                        }else{
                            setUserWatchTime($uid);
                        }

                        //记录观看视频的记录
                        DI()->redis->sAdd($watch_lists_key,$videoid);

                        //更新数据库观看视频记录
                        if($type==0){
                            setTouristWatchLists($uid,$videoid,0);
                        }else{
                            setUserWatchLists($uid,$videoid,0);
                        }

                    }

                }


            }else{


                //获取观看视频记录
                if($type==0){ //游客
                    $watchLists=getTouristWatchLists($uid);
                }else{
                    $watchLists=getUserWatchLists($uid);
                }

                

                $is_watch=in_array($videoid, $watchLists);


                if(!$is_watch){

                    //判断观看次数是否达到限制上限
                    $watch_nums=count($watchLists);

                    if($watch_nums>=$nonvip_watch_nums){

                        if($type==0 || ($type==1 && $vip_switch==0 )){ //游客||登录状态但vip开关未打开

                            $rs['code'] = 1000; //此值要按照不同情况 固定写死，不然app端弹窗会错乱
                            $rs['msg'] = "您今天免费观看次数已用尽\n欢迎明天再来继续观看";

                        }else{

                            $rs['code'] = 1002; //此值要按照不同情况 固定写死，不然app端弹窗会错乱
                            $rs['msg'] = "您今天免费观看次数已用尽\n开通会员可无限观看视频";

                        }

                        return $rs;

                    }else{

                        //更新数据库观看时间
                        if($type==0){
                            setTouristWatchTime($uid);
                        }else{
                            setUserWatchTime($uid);
                        }

                        //更新redis观看时间
                        DI()->redis->set($watchtimekey,time());

                        //更新数据库观看视频记录
                        if($type==0){
                            setTouristWatchLists($uid,$videoid,0);
                        }else{
                            setUserWatchLists($uid,$videoid,0);
                        }

                        //redis记录观看视频记录
                        foreach ($watchLists as $k => $v) {
                            DI()->redis->sAdd($watch_lists_key,$v);
                        }

                        DI()->redis->sAdd($watch_lists_key,$videoid);

                    }




                }

            }

        }

        return $rs;


    }


    /**
     * 内容限制模式处理模块
     * @desc 用于内容限制模式处理模块
     */
    private function payLimitFormat($uid,$videoid,$needcoin){

        $rs=array('code'=>0,'msg'=>'','info'=>array());

        $configpub=getConfigPub();

        //判断用户是否付过费
        $pay_lists_key="video_pay_".$uid;

        $payLists=DI()->redis->sMembers($pay_lists_key);

        if($payLists){
            $is_pay=DI()->redis->sIsMember($pay_lists_key,$videoid);

            if(!$is_pay){
                //获取用户的钻石数
                //$usercoin=getUserCoin($uid);
 
                $rs['code']=1003; //此值要按照不同情况 固定写死，不然app端弹窗会错乱
                $rs['msg']="观看该视频需要支付".$needcoin.$configpub['name_coin']."\n"."是否观看?";              
            }

        }else{

            //获取用户的付费视频记录
            $payLists=getVideoPayLists($uid);
            $is_pay=in_array($videoid, $payLists);

            if(!$is_pay){

                //获取用户的钻石数
                //$usercoin=getUserCoin($uid);

                
                $rs['code']=1003; //此值要按照不同情况 固定写死，不然app端弹窗会错乱

                $rs['msg']="观看该视频需要支付".$needcoin.$configpub['name_coin']."\n"."是否观看?";
            }

            //将付费记录存入redis
            if($payLists){
                foreach ($payLists as $k => $v) {
                    DI()->redis->sAdd($pay_lists_key,$v);
                }
            }   
            

        }

        return $rs;

        
    }






	/**
     * 获取视频评论列表
     * @desc 用于获取视频评论列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].comments 评论总数
     * @return array info[0].commentlist 评论列表
     * @return object info[0].commentlist[].userinfo 用户信息
	 * @return string info[0].commentlist[].datetime 格式后的时间差
	 * @return string info[0].commentlist[].replys 回复总数
	 * @return string info[0].commentlist[].likes 点赞数
	 * @return string info[0].commentlist[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getComments() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);
        $p=checkNull($this->p);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'][0] = $domain->getComments($uid,$videoid,$p);

        return $rs;
    }	
	
	/**
     * 获取视频评论回复列表
     * @desc 用于获取视频评论回复列表
     * @return int code 操作码，0表示成功
     * @return array info 评论列表
     * @return object info[].userinfo 用户信息
	 * @return string info[].datetime 格式后的时间差
	 * @return object info[].tocommentinfo 回复的评论的信息
	 * @return object info[].tocommentinfo.content 评论内容
	 * @return string info[].likes 点赞数
	 * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getReplys() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $commentid=checkNull($this->commentid);
        $last_replyid=checkNull($this->last_replyid);
        $p=checkNull($this->p);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Video();
        $res = $domain->getReplys($uid,$commentid,$last_replyid,$p);

        $rs['info'][0]['lists']=$res['lists'];
        $rs['info'][0]['replys']=$res['replys'];
        return $rs;
    }	
	
	
	/**
     * 删除评论以及子级评论
     * @desc 用于删除评论以及子级评论
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function delComments() {
        $rs = array('code' => 0, 'msg' => $Think.\lang('DELETE_SUCCESS'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$commentid=checkNull($this->commentid);
		$commentuid=checkNull($this->commentuid);


		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
        $domain = new Domain_Video();
        $info = $domain->delComments($uid,$videoid,$commentid,$commentuid);
		
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '视频信息错误,请稍后操作~';
		}else if($info==1002){
			$rs['code'] = 1002;
			$rs['msg'] = '您无权进行删除操作~';
		}

        return $rs;
    }
	
	
	/**
     * 获取用户发布的视频
     * @desc 用于获取我发布的视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getMyVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=$this->p;
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Video();
        $rs['info'] = $domain->getMyVideo($uid,$p);

        return $rs;
    }	
	
	/**
     * 下架视频以及相关信息
     * @desc 用于下架视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function del() {
        $rs = array('code' => 0, 'msg' => $Think.\lang('DELETE_SUCCESS'), 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
        $domain = new Domain_Video();
        $info = $domain->del($uid,$videoid);

        return $rs;
    }	

	/**
     * 举报视频
     * @desc 用于举报视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function report() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$videoid=checkNull($this->videoid);
		$content=checkNull($this->content);
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$data=array(
			'uid'=>$uid,
			'videoid'=>$videoid,
			'content'=>$content,
			'addtime'=>time(),
		);
        $domain = new Domain_Video();
        $info = $domain->report($data);
		
		if($info==1000){
			$rs['code'] = 1001;
			$rs['msg'] = '视频不存在';
			return $rs;
		}

        return $rs;
    }	


	/**
     * 获取个人主页视频
     * @desc 用于获取个人主页视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);
        $p=checkNull($this->p);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Video();
        $info = $domain->getHomeVideo($uid,$touid,$p);
		
		
		$rs['info']=$info;

        return $rs;
    }	
	
	/**
     * 检测文件后缀
     * @desc 用于检测文件后缀
     * @return int code 操作码，0表示成功
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function checkExt($filename){
		$config=array("jpg","png","jpeg");
		$ext   =   pathinfo(strip_tags($filename), PATHINFO_EXTENSION);
		 
		return empty($config) ? true : in_array(strtolower($ext), $config);
	}	
	
	/**
     * 获取七牛上传Token
     * @desc 用于获取七牛上传Token
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
	private function getQiniuToken(){
	
	   	//获取后台配置的七牛云存储信息
		$configPri=getConfigPri();
		 
		$token = DI()->qiniu->getQiniuToken1($configPri['qiniu_accesskey'],$configPri['qiniu_secretkey'],$configPri['qiniu_bucket']);
		
		return $token; 
		
	}


	
    /**
     * 获取推荐视频
     * @desc 用户获取推荐视频
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return string info[0].id 视频id
     * @return string info[0].uid 视频发布者id
     * @return string info[0].title 视频标题
     * @return string info[0].thumbs 视频封面
     * @return string info[0].thumbs 视频小封面
     * @return string info[0].href 视频链接
     * @return string info[0].likes 视频被喜欢总数
     * @return string info[0].views 视频被观看总数
     * @return string info[0].comments 视频评论总数
     * @return string info[0].steps 视频被踩总数
     * @return string info[0].shares 视频分享总数
     * @return string info[0].addtime 视频发布时间
     * @return string info[0].lat 纬度
     * @return string info[0].lng 经度
     * @return string info[0].city 城市
     * @return string info[0].isdel 是否删除
     * @return string info[0].datetime 视频发布时间格式化
     * @return string info[0].islike 是否喜欢了该视频
     * @return string info[0].isattent 是否关注
     * @return string info[0].isstep 是否踩了该视频
     * @return array info[0].userinfo 视频发布者信息
     * @return string info[0].userinfo.id 视频发布者id
     * @return string info[0].userinfo.user_nicename 视频发布者昵称
     * @return string info[0].userinfo.avatar 视频发布者头像
     * @return string info[0].userinfo.avatar_thumb 视频发布者小头像
     * @return string info[0].userinfo.sex 视频发布者性别
     * @return string info[0].userinfo.signature 视频发布者签名
     * @return string info[0].userinfo.privince 视频发布者省份
     * @return string info[0].userinfo.city 视频发布者市
     * @return string info[0].userinfo.birthday 视频发布者生日
     * @return string info[0].userinfo.age 视频发布者年龄
     * @return string info[0].userinfo.praise 视频发布者被赞总数
     * @return string info[0].userinfo.fans 视频发布者粉丝数
     * @return string info[0].userinfo.follows 视频发布者关注数
     * @return array info[0].musicinfo 背景音乐信息
     * @return array info[0].musicinfo.id 背景音乐id
     * @return array info[0].musicinfo.title 背景音乐标题
     * @return array info[0].musicinfo.author 背景音乐作者
     * @return array info[0].musicinfo.img_url 背景音乐封面地址
     * @return array info[0].musicinfo.length 背景音乐长度
     * @return array info[0].musicinfo.file_url 背景音乐地址
     * @return array info[0].musicinfo.use_nums 背景音乐使用次数
     */
    public function getRecommendVideos(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$uid=checkNull($this->uid);
        $p=checkNull($this->p);
        $isstart=checkNull($this->isstart);
        $mobileid=checkNull($this->mobileid);

    	if($uid>0){ //非游客
    		$isBlackUser=isBlackUser($uid);
			if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
    	}
		
		
		$key='videoRecommend_'.$p;

		$info=getcache($key);
		
		if(!$info){

			$domain=new Domain_Video();
			$info=$domain->getRecommendVideos($uid,$p,$isstart,$mobileid);

			if($info==1001 || !$info){
				$rs['code']=0;
				$rs['msg']="暂无视频列表";
				return $rs;
			}

			setcaches($key,$info,2);

		}

		
		


		$rs['info']=$info;

		return $rs;
    }
	
	
	
	/**
	 * 获取附近的视频列表
	 * @desc 用于获取附近的视频列表
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function getNearby(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$lng=checkNull($this->lng);
		$lat=checkNull($this->lat);
		$city=checkNull($this->city);
		$p=checkNull($this->p);

		if($lng=='' || $lat=='' || $city==''){
			return $rs;
		}
		

		if(!$p){
			$p=1;
		}

		/* $key='videoNearby_'.$lng.'_'.$lat.'_'.$city.'_'.$p; */
		$key='videoNearby_'.$city.'_'.$p;

		$info=getcache($key);

		if(!$info){
			$domain = new Domain_Video();
			$info = $domain->getNearby($uid,$lng,$lat,$city,$p);

			if($info==1001){
				return $rs;
			}
			
			setcaches($key,$info,2);
		}

		$rs['info'] = $info;
        return $rs;
	}

	/**
     * 获取视频举报分类列表
     * @desc 获取视频举报分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
	public function getReportContentlist() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Video();
        $res = $domain->getReportContentlist();

        if($res==1001){
        	$rs['code']=1001;
        	$rs['msg']='暂无举报分类列表';
        	return $rs;
        }
        $rs['info']=$res;
        return $rs;
    }

    /**
     * 更新视频看完次数
     * @desc 更新视频看完次数
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setConversion(){

    	$rs = array('code' => 0, 'msg' => '视频完整观看次数更新成功', 'info' => array());
    	$uid=checkNull($this->uid);
		$videoid=checkNull($this->videoid);
		$random_str=checkNull($this->random_str);

		//md5加密验证字符串
		$str=md5($uid.'-'.$videoid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = '视频完整观看次数更新失败';
			return $rs;
		}

		$domain = new Domain_Video();
        $res = $domain->setConversion($videoid);
        

        return $rs;

    }

	/**
	 * 获取视频观看历史
	 * @desc 用于获取视频观看历史
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].orderid 订单号
	 * @return string msg 提示信息
	 */
    public function getViewRecord() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$p=checkNull($this->p);
		
        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        } 
        
        $domain = new Domain_Video();
        
		$list = $domain->getViewRecord($uid,$p);

		$rs['info']=$list;
		return $rs;
	}

    /**
     * 获取视频分类
     * @desc 用于获取视频分类
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].id 视频分类id
     * @return string info[0].title 视频分类名称
     * @return string msg 提示信息
     */
    public function getClassLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $key="getVideoClass";
        $rules=getcaches($key);

        if(!$rules){
            $domain=new Domain_Video();
            $rules=$domain->getClassLists();

        }

        $rs['info']=$rules;

        return $rs;
    }


    /**
     * 视频分类通过关键词搜索
     * @desc 用于视频分类通过关键词搜索
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].id 视频分类id
     * @return string info[0].title 视频分类名称
     * @return string msg 提示信息
     */
    public function searchClassLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $keywords=checkNull($this->keywords);

        if($keywords==""){
            $rs['code']=1001;
            $rs['msg']="请输入搜索关键词";
            return $rs;
        }

        $domain=new Domain_Video();
        $res=$domain->searchClassLists($keywords);
        if($res==1001){
            $rs['code']=0;
            $rs['msg']="未搜索到相符的分类";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }

    /**
     * 根据视频分类获取视频列表
     * @desc 用于根据视频分类获取视频列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
    public function getVideoListByClass(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $classid=checkNull($this->classid);
        $p=checkNull($this->p);
        
        $where=array("id"=>$classid);
        $isexist=checkVideoClass($where);
        if(!$isexist){
            $rs['code']=1001;
            $rs['msg']="视频分类不存在";
            return $rs;
        }

        $domain=new Domain_Video();
        $result=$domain->getVideoListByClass($classid,$p);


        $rs['info']=$result;

        return $rs;
    }

    /**
     * 付费视频扣除钻石
     * @desc 用于付费视频扣除钻石
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */

    public function setVideoPay(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $configpub=getConfigPub();
        $name_coin=$configpub['name_coin'];

        $domain=new Domain_Video();
        $res=$domain->setVideoPay($uid,$videoid);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']="视频已删除";
            return $rs;
        }

        if($res==1002){
            $rs['code']=1002;
            $rs['msg']="自己的视频无需扣费";
            return $rs;
        }

        if($res==1003){
            $rs['code']=1003;
            $rs['msg']="该视频免费观看";
            return $rs;
        }

        if($res==1004){
            $rs['code']=1004;
            $rs['msg']="余额不足";
            return $rs;
        }

        if($res==1005){
            $rs['code']=1005;
            $rs['msg']="已经扣除了".$name_coin;
            return $rs;
        }

        if(!$res){
            $rs['code']=1006;
            $rs['msg']=$name_coin."扣除失败";
            return $rs;
        }

        $rs['msg']=$name_coin."扣除成功";

        return $rs;

    }


    /**
     * 根据音乐id获取视频列表
     * @desc 根据音乐id获取视频列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0]['videolist'] 返回视频列表
     * @return array info[0]['musicinfo']['title'] 音乐名称
     * @return array info[0]['musicinfo']['author'] 音乐作者
     * @return array info[0]['musicinfo']['img_url'] 音乐封面地址
     * @return array info[0]['musicinfo']['file_url'] 音乐地址
     * @return array info[0]['musicinfo']['use_nums'] 音乐使用人数
     * @return array info[0]['musicinfo']['length'] 音乐长度
     */
    public function getVideoListByMusic(){

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $musicid=checkNull($this->musicid);
        $p=checkNull($this->p);

        if(!$uid){ //保险起见，防止app用户被顶替登录时，点击拍同款app提示错误
            $uid=-999;
        }

        $domain=new Domain_Video();
        
        $musicinfo=$domain->getMusicInfo($musicid);

        if($musicinfo==1001){
            $rs['code']=1001;
            $rs['msg']="音乐已下架";
            return $rs;
        }

        $res=$domain->getVideoListByMusic($uid,$musicid,$p);

        $rs['info'][0]['videolist']=$res;
        $rs['info'][0]['musicinfo']=$musicinfo;

        return $rs;
    }

    /**
     * 视频送礼物
     * @desc 视频送礼物
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function videoSendGift(){
        $rs = array('code' => 0, 'msg' => '赠送成功', 'info' => array());

        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoid=checkNull($this->videoid);
        $giftid=checkNull($this->giftid);
        $giftcount=checkNull($this->giftcount);
		$ispack=$this->ispack;

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $is_exist=checkVideoIsExist($videoid);
        if(!$is_exist){
            $rs['code'] = 1001;
            $rs['msg'] = '视频不存在';
            return $rs;
        }

        $domain = new Domain_Live();
        $giftlist=$domain->getGiftList();

        $gift_info=array();
        foreach($giftlist as $k=>$v){
            if($giftid == $v['id']){
               $gift_info=$v; 
            }
        }

        if(!$gift_info){
            $rs['code']=1002;
            $rs['msg']='礼物信息不存在';
            return $rs;
        }
		
		if($gift_info['mark']==2){
            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info=$domain_guard->getUserGuard($uid,$liveuid);
            if($guard_info['type']!=2){
               $rs['code']=1002;
                $rs['msg']='该礼物是年守护专属礼物奥~';
                return $rs; 
            }
        }
		

        $domain = new Domain_Video();
        $data=array(
            'uid'=>$uid,
            'videoid'=>$videoid,
            'giftid'=>$giftid,
            'giftcount'=>$giftcount
        );

        $result=$domain->videoSendGift($data,$gift_info,$ispack);

        if($result==1001){
            $rs['code']=1001;
            $rs['msg']="不能给自己视频送礼物";
            return $rs;
        }

        if($result==1002){
            $rs['code']=1002;
            $rs['msg']="余额不足";
            return $rs;
        }else if($result==1003){
			$rs['code']=1003;
			$rs['msg']='背包中数量不足';
			return $rs;
		}

        //获取用户的钻石
        $coin=getUserCoin($uid);


        //为了保证与直播送礼物统一

        $gift_info['giftcount']=$giftcount;
        $gift_info['giftid']=$gift_info['id'];

        unset($gift_info['id']);
        unset($gift_info['needcoin']);

        $rs['info'][0]['coin']=$coin;
        $rs['info'][0]['giftinfo']=$gift_info;

        return $rs;

    }


	 /**
    * 获取腾讯云存储联合身份临时访问凭证
    * @desc 用户app端使用腾讯云存储进行文件上传前进行身份验证，验证通过才可上传文件
    * 参考文档：https://cloud.tencent.com/document/product/598/13896
    * 参考github项目：https://github.com/tencentyun/qcloud-cos-sts-sdk/tree/master/php
    * @return int code 操作码，0表示成功
    * @return string msg 提示信息
    * @return array info 返回信息
    * @return string info[0].sessionToken 返回验证token
    * @return string info[0].tmpSecretId 返回临时secretid
    * @return string info[0].tmpSecretKey 返回临时secretkey
    * @return string info[0].requestId 返回requestId
    * @return string info[0].expiredTime 返回expiredTime 有效日期截止时间
    */
    public function getTxCosFederationToken(){

        $rs=array('code'=>0,"msg"=>"","info"=>array());

        require_once(API_ROOT.'/../sdk/tencentSts/sts/sts.php');
        $sts = new STS();
        $configpri=getConfigPri();

        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => '',
            'secretId' => $configpri['txcloud_secret_id'], // 腾讯云存储secretid密钥
            'secretKey' => $configpri['txcloud_secret_key'], // 腾讯云存储secretkey
            'bucket' => $configpri['txcloud_bucket'].'-'.$configpri['txcloud_appid'], // bucket-appid
            'region' => $configpri['txcloud_region'], // 换成 bucket 所在地区 如ap-shanghai
            'durationSeconds' => 1800, // 密钥有效期
            'allowPrefix' => '*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录状态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 简单上传
                'name/cos:PutObject',
                'name/cos:PostObject',
                // 分片上传
                'name/cos:InitiateMultipartUpload',
                'name/cos:ListMultipartUploads',
                'name/cos:ListParts',
                'name/cos:UploadPart',
                'name/cos:CompleteMultipartUpload'
            )
        );

        // 获取临时密钥，计算签名
        $tempKeys = $sts->getTempKeys($config);

        $info['sessionToken']=$tempKeys['credentials']['sessionToken'];
        $info['tmpSecretId']=$tempKeys['credentials']['tmpSecretId'];
        $info['tmpSecretKey']=$tempKeys['credentials']['tmpSecretKey'];
        $info['requestId']=$tempKeys['requestId'];
        $info['expiredTime']=(string)$tempKeys['expiredTime'];

        $rs['info'][0]=$info;

        return $rs;
    }
   

    /**
     * 获取云存储方式、获取七牛上传验证token字符串、获取腾讯云存储相关配置信息、获取亚马逊存储相关配置信息
     * @desc 用于获取云存储方式、获取七牛上传验证token字符串、获取腾讯云存储相关配置信息、获取亚马逊存储相关配置信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */

    public function getCosInfo(){

        $rs=array("code"=>0,"msg"=>"","info"=>array());


        //获取七牛信息
        $qiniuToken=$this->getQiniuToken();

        //获取腾讯云存储配置信息
        $configpri=getConfigPri();

        if(!$configpri['cloudtype']){
            $rs['code']=1001;
            $rs['msg']="无指定存储方式";
            return $rs;
        }
		
		$qiniu_domain_url=$configpri['qiniu_protocol']."://".$configpri['qiniu_domain']."/";
        $qiniuInfo=array(
            'qiniuToken'=>$qiniuToken,
            'qiniu_domain'=>$qiniu_domain_url,
            'qiniu_zone'=>'qiniu_hd'  //华东:qiniu_hd 华北:qiniu_hb  华南:qiniu_hn  北美:qiniu_bm   新加坡:qiniu_xjp 不可随意更改，app已固定好规则
        );

        $txCloudInfo=array(
            'region'=>$configpri['txcloud_region'],
            'bucket'=>$configpri['txcloud_bucket'],
            'appid'=>$configpri['txcloud_appid'],
        );

        $awsInfo=array(
            'aws_bucket'=>$configpri['aws_bucket'],
            'aws_region'=>$configpri['aws_region'],
            'aws_identitypoolid'=>$configpri['aws_identitypoolid'],
        );
        
        $rs['info'][0]['qiniuInfo']=$qiniuInfo;
        $rs['info'][0]['txCloudInfo']=$txCloudInfo;
        $rs['info'][0]['awsInfo']=$awsInfo;

        $cloudtype="";
        switch ($configpri['cloudtype']) {
            case '1':
                $cloudtype="qiniu";
                break;

            case '2':
                $cloudtype="tx";
                break;
            case '3':
                $cloudtype="aws";
                break;
         } 

        $rs['info'][0]['cloudtype']=$cloudtype;
        
        return $rs;
        
    }

    /**
     * 判断付费视频是否可以合拍、分享、下载
     * @desc 用于判断付费视频是否可以合拍、分享、下载
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0].video_status 是否可以发布视频 0 否 1 是
     * @return string info[0].video_msg 不可以发布视频时的提示语
     * @return int info[0].limit_status 用户观看视频是否受限 0 受限 1 不受限
     */
    public function checkPayVideoIsShare(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $videoid=checkNull($this->videoid);
        $mobileid=checkNull($this->mobileid);

        if(!$uid||!$videoid||!$mobileid){
            $rs['code']=1001;
            $rs['msg']='分享参数错误';
            return $rs;
        }

        $video_status=0;

        if($uid>0){
            $domain=new Domain_User();
            $res=$domain->checkLiveVipStatus($uid);
            $video_status=$res['video_status'];
        }

        $video_msg="您发布作品的次数已用尽\n开通会员可无限发布作品";

        $limit_status=1;  //可以合拍、分享、下载、复制链接


        $result=$this->getVideo($uid,$videoid,$mobileid);


        if($result['code']!=0){
            $limit_status=0;
        }


        $data['video_status']=$video_status;
        $data['video_msg']=$video_msg;
        $data['limit_status']=$limit_status;

        $rs['info'][0]=$data;

        return $rs;

    }
	
	
	/**
	 * 用户开始观看视频
	 * @desc 用于每日任务统计用户观看时长
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function startWatchVideo(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);


        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

		/*观看视频计时---每日任务*/
		$key='watch_video_daily_tasks_'.$uid;
		$time=time();
		setcaches($key,$time);

		return $rs;	
	}
	
	
	/**
	 * 用户结束观看视频
	 * @desc 用于每日任务统计用户观看时长
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function endWatchVideo(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);


        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

	
		/*观看视频计时---每日任务--取出用户起始时间*/
		$key='watch_video_daily_tasks_'.$uid;
		$starttime=getcaches($key);
		if($starttime){ 
			$endtime=time();  //当前时间
			$data=[
				'type'=>'2',
				'starttime'=>$starttime,
				'endtime'=>$endtime,
			];
			dailyTasks($uid,$data);
			//删除当前存入的时间
			delcache($key);

		}

		return $rs;	
	}


	/**
	 * 获取城市列表
	 * @desc 用于获取城市列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 视频记录ID
	 * @return string msg 提示信息
	 */
	public function getCitys() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$key='getCitys';
		$info=getcaches($key);
		if(!$info){
			$city=API_ROOT.'/../PhalApi/Config/city.json';
			// 从文件中读取数据到PHP变量 
			$json_string = file_get_contents($city); 
			 // 用参数true把JSON字符串强制转成PHP数组 
			$data = json_decode($json_string, true);

			$info=$data['city']; //城市
			
			setcaches($key,$info);
		}
		
	 
		$rs['info']=$info;
		return $rs;
	}

    /**
     * 删除视频访问记录
     * @desc 用于删除视频访问记录
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function deltViewRecord(){
        $rs=array('code'=>0,'msg'=>$Think.\lang('DELETE_SUCCESS'),'info'=>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $videoids=checkNull($this->videoids);

        $checkToken=checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        if(!$videoids){
            $rs['code'] = 1001;
            $rs['msg'] = '请选择访问记录';
            return $rs;
        }

        $new_videoid_arr=[];
        $videoid_arr=explode(',',$videoids);
        foreach ($videoid_arr as $k => $v) {
            if($v){
                $new_videoid_arr[]=$v;
            }
        }

        if(empty($new_videoid_arr)){
            $rs['code'] = 1001;
            $rs['msg'] = '请选择访问记录';
            return $rs;
        }

        $domain_video=new Domain_Video();
        $res=$domain_video->deltViewRecord($uid,$new_videoid_arr);

        return $rs;

    }
    
}
