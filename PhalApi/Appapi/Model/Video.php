<?php
session_start();
class Model_Video extends PhalApi_Model_NotORM {
	/* 发布视频 */
	public function setVideo($data,$music_id) {
		$uid=$data['uid'];

		//获取后台配置的初始曝光值
		$configPri=getConfigPri();
		$data['show_val']=$configPri['show_val'];

		if($configPri['video_audit_switch']==0){
			$data['status']=1;
		}

		$data['thumb']=setCloudType($data['thumb']);
		$data['thumb_s']=setCloudType($data['thumb_s']);
		$data['href']=setCloudType($data['href']);
		$data['href_w']=setCloudType($data['href_w']);

		$result= DI()->notorm->user_video->insert($data);

		if($music_id>0){ //更新背景音乐被使用次数
			DI()->notorm->user_music
            ->where("id = '{$music_id}'")
		 	->update( array('use_nums' => new NotORM_Literal("use_nums + 1") ) );
		}
		
		return $result;
	}

	/* 评论/回复 */
    public function setComment($data) {
    	$videoid=$data['videoid'];

		

		if($data['voice']){
			$data['voice']=setCloudType($data['voice']);
		}
		
        $res=DI()->notorm->user_video_comments->insert($data);


        if(!$res){
        	return 1001;
        }

        /* 更新 视频 */
		DI()->notorm->user_video
            ->where("id = '{$videoid}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );

			
		$videoinfo=DI()->notorm->user_video
					->select("comments")
					->where('id=?',$videoid)
					->fetchOne();
					
		$count=DI()->notorm->user_video_comments
					->where("commentid='{$data['commentid']}'")
					->count();

		

		$rs=array(
			'comments'=>$videoinfo['comments'],
			'replys'=>$count,
		);
		
		//如果有人发评论@了其他人，写入评论@记录
		$arr=json_decode($data['at_info'],true); //将json串转为数组


		if(!empty($arr)){

			$data1=array("videoid"=>$data['videoid'],"addtime"=>time(),"uid"=>$data['uid']);
			foreach ($arr as $k => $v) {
				$data1['touid']=$v['uid'];
				DI()->notorm->user_video_comments_at_messages->insert($data1);
				jMessageIM("@通知",$v['uid'],"dsp_at");
			}
		}

		//他人直接对视频进行的评论，向评论信息表中写入记录

		if($data['uid']!=$data['touid']){

			$data2=array("uid"=>$data['uid'],"touid"=>$data['touid'],"videoid"=>$data['videoid'],"content"=>$data['content'],"addtime"=>time());
			DI()->notorm->user_video_comments_messages->insert($data2);
				
			if($data['uid']!=$data['touid']){

				jMessageIM("评论通知",$data['touid'],"dsp_comment"); //$data['touid']为视频发布者ID或评论者ID
			}
			
		}

		
		

		return $rs;	
    }			

	/* 阅读 */
	public function addView($uid,$videoid){
        /* 观看记录 */
        $nowtime=time();
        $ifok=DI()->notorm->user_video_view
				->where('uid=? and videoid=?',$uid,$videoid)
				->update(['addtime'=>$nowtime]);


        if(!$ifok){
            DI()->notorm->user_video_view
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>$nowtime ));
        }
        
        /* 减少上热门 */
        DI()->notorm->user_video
				->where("id = ? and p_expire>? and p_nums>=1",$videoid,$nowtime)
				->update( array('p_nums' => new NotORM_Literal("p_nums - 1") ) );

				
		
		/*$view=DI()->notorm->user_video_view
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();

		if(!$view){
			DI()->notorm->user_video_view
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
						
			DI()->notorm->user_video
				->where("id = '{$videoid}'")
				->update( array('view' => new NotORM_Literal("view + 1") ) );
		}*/

		/*//用户看过的视频存入redis中
		$readLists=DI()->redis -> Get('readvideo_'.$uid);
		$readArr=array();
		if($readLists){
			$readArr=json_decode($readLists,true);
			if(!in_array($videoid,$readArr)){
				$readArr[]=$videoid;
			}
		}else{
			$readArr[]=$videoid;
		}

		DI()->redis -> Set('readvideo_'.$uid,json_encode($readArr));*/

		DI()->notorm->user_video
				->where("id = '{$videoid}'")
				->update( array('views' => new NotORM_Literal("views + 1") ) );

		return 0;
	}
	/* 点赞 */
	public function addLike($uid,$videoid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$video=DI()->notorm->user_video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();

		if(!$video){
			return 1001;
		}
		if($video['uid']==$uid){
			return 1002;//不能给自己点赞
		}
		$like=DI()->notorm->user_video_like
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->user_video_like
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			
			DI()->notorm->user_video
				->where("id = '{$videoid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';
		}else{
			DI()->notorm->user_video_like
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->user_video
				->where("id = '{$videoid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$video=DI()->notorm->user_video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();
				
		$rs['likes']=NumberFormat($video['likes']);
		
		//获取视频点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$videoid}' and type=1")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$video['uid'],"obj_id"=>$videoid,"videoid"=>$videoid,"addtime"=>time(),"type"=>1,"video_thumb"=>$video['thumb']));
			
			jMessageIM("点赞通知",$video['uid'],"dsp_like");
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=1 and obj_id='{$videoid}'")->update(array("addtime"=>time()));
		}
		
		return $rs; 		
	}
 


	/* 分享 */
	public function addShare($uid,$videoid){

		
		$rs=array(
			'isshare'=>'0',
			'shares'=>'0',
		);
		DI()->notorm->user_video
			->where("id = '{$videoid}'")
			->update( array('shares' => new NotORM_Literal("shares + 1") ) );
		$rs['isshare']='1';

		
		$video=DI()->notorm->user_video
				->select("shares")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['shares']=NumberFormat($video['shares']);
		
		return $rs; 		
	}




	/* 评论/回复 点赞 */
	public function addCommentLike($uid,$commentid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);

		//根据commentid获取对应的评论信息
		$commentinfo=DI()->notorm->user_video_comments
			->where("id='{$commentid}'")
			->fetchOne();

		if(!$commentinfo){
			return 1001;
		}

		$like=DI()->notorm->user_video_comments_like
			->select("id")
			->where("uid='{$uid}' and commentid='{$commentid}'")
			->fetchOne();

		if($like){
			DI()->notorm->user_video_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->user_video_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';

		}else{
			DI()->notorm->user_video_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time(),"touid"=>$commentinfo['uid'],"videoid"=>$commentinfo['videoid'] ));
			
			DI()->notorm->user_video_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$video=DI()->notorm->user_video_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();

		//获取视频信息
		$videoinfo=DI()->notorm->user_video->select("thumb")->where("id='{$commentinfo['videoid']}'")->fetchOne();

		$rs['likes']=$video['likes'];


		//获取评论点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$commentid}' and type=0")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$commentinfo['uid'],"obj_id"=>$commentid,"videoid"=>$commentinfo['videoid'],"addtime"=>time(),"type"=>0,"video_thumb"=>$videoinfo['thumb']));
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=0 and obj_id='{$commentid}'")->update(array("addtime"=>time()));
		}
		return $rs; 		
	}
	
	/* 热门视频 */
	public function getVideoList($uid,$p){


		$nums=20;
		$start=($p-1)*$nums;

		$videoids_s='';
		$where="isdel=0 and status=1 and is_ad=0";  //上架且审核通过
		
		$video=DI()->notorm->user_video
				->select("*")
				->where($where)
				->order("RAND()")
				->limit($start,$nums)
				->fetchAll();

		//敏感词树        
        $tree=trieTreeBasic();

        foreach($video as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            
            $video[$k]=$v;
        }
        

		return $video;
	}


	/* 关注人视频 */
	public function getAttentionVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=array();
		$attention=DI()->notorm->user_attention
				->select("touid")
				->where("uid='{$uid}'")
				->fetchAll();
		
		if($attention){
			
			$uids=array_column($attention,'touid');
			$touids=implode(",",$uids);
			
			
			$where="uid in ({$touids})  and isdel=0 and status=1";
			
			$video=DI()->notorm->user_video
					->select("*")
					->where($where)
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


			if(!$video){
				return 0;
			}

			//敏感词树        
        	$tree=trieTreeBasic();
			
            foreach($video as $k=>$v){
                $v=handleVideo($uid,$v,$tree);
                
                $video[$k]=$v;
            }				
			
		}
		

		return $video;		
	} 			
	
	/* 视频详情 */
	public function getVideo($uid,$videoid){
		$video=DI()->notorm->user_video
					->select("*")
					->where("id = {$videoid} and isdel=0 and status=1 ")
					->fetchOne();
		if(!$video){
			return 1000;
		}

		//敏感词树        
        $tree=trieTreeBasic();		
		
        $video=handleVideo($uid,$video,$tree);
        
		return 	$video;
	}
	
	/* 评论列表 */
	public function getComments($uid,$videoid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->user_video_comments
					->select("*")
					->where("videoid='{$videoid}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();

		//敏感词树        
        $tree=trieTreeBasic();

		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid'],$tree);				
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
			$comments[$k]['voice']=get_upload_path($v['voice']);	
			if($uid){
				$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);	
			}else{
				$comments[$k]['islike']='0';	
			}
			
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid'],$tree);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;

			$count=DI()->notorm->user_video_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
			$comments[$k]['content']=eachReplaceSensitiveWords($tree,$v['content']);

			/* 回复 */
            $reply=DI()->notorm->user_video_comments
					->select("*")
					->where("commentid='{$v['id']}'")
					->order("addtime desc")
					->limit(0,1)
					->fetchAll();

			foreach($reply as $k1=>$v1){
                
                $v1['userinfo']=getUserInfo($v1['uid'],$tree);				
                $v1['datetime']=datetime($v1['addtime']);	
                $v1['likes']=NumberFormat($v1['likes']);	
                $v1['voice']=get_upload_path($v1['voice']);
                $v1['islike']=(string)$this->ifCommentLike($uid,$v1['id']);
                if($v1['touid']>0){
                    $touserinfo=getUserInfo($v1['touid'],$tree);
                }
                if(!$touserinfo){
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                
                if($v1['parentid']>0 && $v1['parentid']!=$v['id']){
                    $tocommentinfo=DI()->notorm->user_video_comments
                        ->select("content,at_info")
                        ->where("id='{$v1['parentid']}'")
                        ->fetchOne();
                }else{
                    $tocommentinfo=(object)array();
                    $touserinfo=(object)array();
                    $v1['touid']='0';
                }
                $v1['touserinfo']=$touserinfo;
                $v1['tocommentinfo']=$tocommentinfo;
                $v1['content']=eachReplaceSensitiveWords($tree,$v1['content']);


                $reply[$k1]=$v1;
            }

			$comments[$k]['replylist']=$reply;
		}
		
		$commentnum=DI()->notorm->user_video_comments
					->where("videoid='{$videoid}'")
					->count();
		
		$rs=array(
			"comments"=>$commentnum,
			"commentlist"=>$comments,
		);
		
		return $rs;
	}

	/* 回复列表 */
	public function getReplys($uid,$commentid,$last_replyid,$p){

		if($last_replyid==0){ //获取回复列表里最新的一条

			$comment=DI()->notorm->user_video_comments
				->select("*")
				->where("commentid='{$commentid}'")
				->order("addtime desc")
				->fetchOne();

			$comments[]=$comment;

		}else{
			if($p<1){
				$p=1;
			}


			//第一页获取2条数据，从第二页开始每页获取10条数据
			$nums=10;
			if($p==1){
				$nums=2;
			}

			$start=0;
			$comments=DI()->notorm->user_video_comments
				->select("*")
				->where("commentid='{$commentid}' and id< '{$last_replyid}'")
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		}

		//敏感词树        
        $tree=trieTreeBasic();

		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=getUserInfo($v['uid'],$tree);				
			$comments[$k]['datetime']=datetime($v['addtime']);	
			$comments[$k]['likes']=NumberFormat($v['likes']);	
            $comments[$k]['voice']=get_upload_path($v['voice']);
			$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);
			if($v['touid']>0){
				$touserinfo=getUserInfo($v['touid'],$tree);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}


			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->user_video_comments
					->select("content,at_info")
					->where("id='{$v['parentid']}'")
					->fetchOne();
			}else{

				$tocommentinfo=(object)array();
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';

			}

			$comments[$k]['touserinfo']=$touserinfo;
			$comments[$k]['tocommentinfo']=$tocommentinfo;
			$comments[$k]['content']=eachReplaceSensitiveWords($tree,$v['content']);


		}

		//该评论下的总回复数
		$count=DI()->notorm->user_video_comments
			->where("commentid='{$commentid}'")
			->count();

		$res['lists']=$comments;
		$res['replys']=$count;
		
		return $res;


	}
	
	/*删除评论 删除子级评论*/
	public function delComments($uid,$videoid,$commentid,$commentuid) {
       $result=DI()->notorm->user_video
					->select("uid")
					->where("id='{$videoid}'")
					->fetchOne();	
					
		if(!$result){
			return 1001;
		}			
		
		
		if($uid!=$commentuid){
			if($uid!=$result['uid']){
				return 1002;
			}
		}
			
		
		
		// 删除 评论记录
		DI()->notorm->user_video_comments
					->where("id='{$commentid}'")
					->delete(); 
		//删除视频评论喜欢
		DI()->notorm->user_video_comments_like
					->where("commentid='{$commentid}'")
					->delete(); 
		/* 更新 视频 */
		DI()->notorm->user_video
            ->where("id = '{$videoid}' and comments>0")
		 	->update( array('comments' => new NotORM_Literal("comments - 1") ) );
		
		
		//删除相关的子级评论
		$lists=DI()->notorm->user_video_comments
				->select("*")
				->where("commentid='{$commentid}' or parentid='{$commentid}'")
				->fetchAll();
		foreach($lists as $k=>$v){
			//删除 评论记录
			DI()->notorm->user_video_comments
						->where("id='{$v['id']}'")
						->delete(); 
			//删除视频评论喜欢
			DI()->notorm->user_video_comments_like
						->where("commentid='{$v['id']}'")
						->delete(); 
						
			/* 更新 视频 */
			DI()->notorm->user_video
				->where("id = '{$v['videoid']}' and comments>0")
				->update( array('comments' => new NotORM_Literal("comments - 1") ) );
		}
			
		
						
		return 0;

    }

	
	
	
	/* 评论/回复 是否点赞 */
	public function ifCommentLike($uid,$commentid){
		$like=DI()->notorm->user_video_comments_like
				->select("id")
				->where("uid='{$uid}' and commentid='{$commentid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 我的视频 */
	public function getMyVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=DI()->notorm->user_video
				->select("*")
				->where('uid=?  and isdel=0 and status=1',$uid)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		
		//敏感词树        
        $tree=trieTreeBasic();

        foreach($video as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            
            $video[$k]=$v;
        }

		return $video;
	} 	
	/* 删除视频 */
	public function del($uid,$videoid){
		
		$result=DI()->notorm->user_video
					->select("*")
					->where("id='{$videoid}' and uid='{$uid}'")
					->fetchOne();	
		if($result){
			// 删除 评论记录
			 /*DI()->notorm->user_video_comments
						->where("videoid='{$videoid}'")
						->delete(); 
			// 删除  视频评论@信息
			 DI()->notorm->user_video_comments_at_messages
						->where("videoid='{$videoid}'")
						->delete(); 
			//删除视频评论消息
			DI()->notorm->user_video_comments_messages
						->where("videoid='{$videoid}'")
						->delete();
			//删除视频评论喜欢
			DI()->notorm->user_video_comments_like
						->where("videoid='{$videoid}'")
						->delete(); 
			
			// 删除  点赞
			 DI()->notorm->user_video_like
						->where("videoid='{$videoid}'")
						->delete(); 
			//删除视频举报
			DI()->notorm->user_video_report
						->where("videoid='{$videoid}'")
						->delete(); 
			// 删除视频 
			 DI()->notorm->user_video
						->where("id='{$videoid}'")
						->delete();	*/ 

			
			//将点赞信息列表里的状态修改
			DI()->notorm->praise_messages
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));

			//将视频评论@信息列表的状态更改
			DI()->notorm->user_video_comments_at_messages
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));

			//将评论信息列表的状态更改	

			DI()->notorm->user_video_comments_messages
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));

			//将喜欢的视频列表状态修改
			DI()->notorm->user_video_like
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));
                
            //将喜欢的视频列表状态修改
			DI()->notorm->user_video_view
				->where("videoid='{$videoid}'")
				->update(array("status"=>0));	

			DI()->notorm->user_video
				->where("id='{$videoid}'")
				->update( array( 'isdel'=>1 ) );

			changeVideoPopular($videoid,$result['p_nums'],1);

		}				
		return 0;
	}	

	/* 个人主页视频 */
	public function getHomeVideo($uid,$touid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		
		if($uid==$touid){  //自己的视频（需要返回视频的状态前台显示）
			$where=" uid={$uid} and isdel='0' and status=1  and is_ad=0";
		}else{  //访问其他人的主页视频
            
            //获取对方用户的状态
            $is_destroy=checkIsDestroyByUid($touid);
            if($is_destroy){
            	return [];
            }

			$where=" uid={$touid} and isdel='0' and status=1  and is_ad=0";
		}
		
		
		$video=DI()->notorm->user_video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		//敏感词树        
        $tree=trieTreeBasic();

		foreach($video as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            
            $video[$k]=$v;
        }

		return $video;
		
	}
	/* 举报 */
	public function report($data) {
		
		$video=DI()->notorm->user_video
					->select("uid")
					->where("id='{$data['videoid']}' and isdel=0 and status=1")
					->fetchOne();

		if(!$video){
			return 1000;
		}
		
		$data['touid']=$video['uid'];
					
		$result= DI()->notorm->user_video_report->insert($data);
		return 0;
	}


	public function getRecommendVideos($uid,$p,$isstart,$mobileid){
		$pnums=20;
		$start=($p-1)*$pnums;


		$nowtime=time();
        
		$configPri=getConfigPri();
		$video_showtype=$configPri['video_showtype'];

		if($video_showtype==0){ //随机

			if($p==1){
				if($uid>0){
					DI()->redis -> del('readvideo_'.$uid);
				}else{
					DI()->redis -> del('readvideo_'.$mobileid);
				}
				
			}

			//去除看过的视频
			$where=array();
			if($uid>0){
				$readLists=DI()->redis -> Get('readvideo_'.$uid);
			}else{
				$readLists=DI()->redis -> Get('readvideo_'.$mobileid);
			}
			
			if($readLists){
				$where=json_decode($readLists,true);
			}

			$info=DI()->notorm->user_video
			->where("isdel=0 and status=1 and is_ad=0")
			->where('not id',$where)
			->where('p_expire<? or p_nums=0',$nowtime)
			->order("rand()")
			->limit($pnums)
			->fetchAll();
			$where1=array();
			foreach ($info as $k => $v) {
				if(!in_array($v['id'],$where)){
					$where1[]=$v['id'];
				}
			}

			//将两数组合并
			$where2=array_merge($where,$where1);

			if($uid>0){
				DI()->redis -> set('readvideo_'.$uid,json_encode($where2));
			}else{
				DI()->redis -> set('readvideo_'.$mobileid,json_encode($where2));
			}
			



		}else{

			//获取私密配置里的评论权重和点赞权重
			$comment_weight=$configPri['comment_weight'];
			$like_weight=$configPri['like_weight'];
			$share_weight=$configPri['share_weight'];

			//热度值 = 点赞数*点赞权重+评论数*评论权重+分享数*分享权重
			//转化率 = 完整观看次数/总观看次数
			//排序规则：（曝光值+热度值）*转化率
			//曝光值从视频发布开始，每小时递减1，直到0为止

			$info=DI()->notorm->user_video
            ->select("*,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend")
            ->where("isdel=0 and status=1 and is_ad=0")
            ->where('p_expire<? or p_nums=0',$nowtime)
            // ->where('not id',$where)
            ->order("recomend desc,addtime desc")
            ->limit($start,$pnums)
            ->fetchAll();
		}


		if(!$info){
			return 1001;
		}


		$videoCount=count($info);
        $configPri=getConfigPri();
        
        /* 上热门视频 */
        $popular_interval=$configPri['popular_interval'];
        if($popular_interval>0){
            $p_pnums=floor($pnums/$popular_interval);;
            $p_start=($p-1)*$p_pnums;
            $popularlist=DI()->notorm->user_video
                            ->where("isdel=0 and status=1 and is_ad=0")
                            ->where('p_nums>0 and p_expire>?',$nowtime)
                            ->limit($p_start,$p_pnums)
                            ->order('p_add desc')
                            ->fetchAll();
            foreach($popularlist as $k=>$v){
                $p_setnum=($k+1)*$popular_interval;
                if($videoCount>=$p_setnum){
                    array_splice($info, ($k+1)*$popular_interval+$k, 0, array($v)); 
                }
            }
        }
        
        
		$videoCount=count($info);

		//获取私密配置里的视频间隔数
		
		//广告视频开关
		$ad_video_switch=$configPri['ad_video_switch'];
		$ad_video_loop=$configPri['ad_video_loop'];
		$video_ad_num=$configPri['video_ad_num']; //广告视频间隔数
		$orderStr="orderno desc,addtime desc";

		if($ad_video_switch){ //广告开关打开

			//广告不轮循展示
			if(!$ad_video_loop){


				$ad_pnums=(int)($pnums/$video_ad_num);
				$start1=($p-1)*$ad_pnums;

				$Adwhere=array();

				/*if($uid>0){ //广告随机显示时使用

					if($isstart==1){ //将用户观看广告缓存删除
						DI()->redis -> del('readad_'.$uid);
					}

					//获取缓存的广告id组
					$adHasLists=DI()->redis -> Get('readad_'.$uid);
					if($adHasLists){
						$Adwhere=json_decode($adHasLists,true);
					}
				}*/



				

				/*if($uid>0){

					//获取广告位
					$adLists=DI()->notorm->user_video->where("isdel=0 and status=1 and is_ad=1 and (ad_endtime=0 or (ad_endtime>0 and ad_endtime>{$nowtime}))")->where("not id",$Adwhere)->limit(0,$ad_pnums)->order($orderStr)->fetchAll();
				}else{*/
					$adLists=DI()->notorm->user_video->where("isdel=0 and status=1 and is_ad=1 and (ad_endtime=0 or (ad_endtime>0 and ad_endtime>{$nowtime}))")->limit($start1,$ad_pnums)->order($orderStr)->fetchAll();
				//}



				if($adLists){
					foreach ($adLists as $k => $v) {
						if($v){

							$videoNum=($k+1)*$video_ad_num;

							if($videoCount>=$videoNum){
								
								array_splice($info, ($k+1)*$video_ad_num+$k, 0, array($v)); //向推荐视频列表中插入广告位视频

								//广告随机显示时使用
								/*if($uid>0){
									if($videoCount/$video_ad_num>=($k+1)){
										//用户看过的广告存入redis中
										$adHasLists=DI()->redis -> Get('readad_'.$uid);
										$readadArr=array();
										if($adHasLists){
											$readadArr=json_decode($adHasLists,true);
											if(!in_array($v['id'],$readadArr)){
												$readadArr[]=$v['id'];
											}
										}else{
											$readadArr[]=$v['id'];
										}

										DI()->redis -> Set('readad_'.$uid,json_encode($readadArr));
									}
								}*/
								
								
							}
							
						}
					}

				}



			}else{ //广告轮循展示

				//广告总量
				$adcount=DI()->notorm->user_video->where("isdel=0 and status=1 and is_ad=1 and (ad_endtime=0 or (ad_endtime>0 and ad_endtime>{$nowtime}))")->count();

				//视频总数
				$videocount=DI()->notorm->user_video->where("isdel=0 and status=1 and is_ad=0")->count();


				//需要广告总数
				if($adcount>0){
					$needcount=floor($videocount/$video_ad_num);

					$cha=ceil($needcount/$adcount);
					$key="ad_lists";
					$ad_lists=getcache($key);

					if(!$ad_lists){
						$ad_lists=DI()->notorm->user_video->where("isdel=0 and status=1 and is_ad=1 and (ad_endtime=0 or (ad_endtime>0 and ad_endtime>{$nowtime}))")->order($orderStr)->fetchAll();

						setcaches($key,$ad_lists,5);

					}
					
					$ad_list1=array();


					for($i=0;$i<$cha;$i++){
						$ad_list1=array_merge($ad_list1,$ad_lists);
					}


					array_values($ad_list1);



					$ad_pnums=(int)($pnums/$video_ad_num);

					$start1=($p-1)*$ad_pnums;

					$adLists=array_slice($ad_list1,$start1,$ad_pnums);

					if($adLists){

						foreach ($adLists as $k => $v) {
							if($v){

								$videoNum=($k+1)*$video_ad_num;

								if($videoCount>=$videoNum){
									
									array_splice($info, ($k+1)*$video_ad_num+$k, 0, array($v)); //向推荐视频列表中插入广告位视频
									
								}
								
							}
						}

					}

				}

			}

			
		}
		
		//敏感词树        
        $tree=trieTreeBasic();

        foreach($info as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            
            $info[$k]=$v;
        }

		return $info;
	}

	/*获取附近的视频*/
	public function getNearby($uid,$lng,$lat,$city,$p){
		$pnum=20;
		$start=($p-1)*$pnum;

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');


		$info=DI()->notorm->user_video->queryAll("select *, round(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ".$lat." * PI() / 180 - lat * PI() / 180) / 2),2) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((".$lng." * PI() / 180 - lng * PI() / 180) / 2),2))) * 1000) AS distance FROM ".$prefix."user_video  where  city  like '%{$city}%' and isdel=0 and status=1  and is_ad=0 order by distance asc,addtime desc limit ".$start.",".$pnum);

		if(!$info){
			return 1001;
		}

		//敏感词树        
        $tree=trieTreeBasic();

        foreach($info as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            $v['distance']=distanceFormat($v['distance']);
            
            $info[$k]=$v;
        }
		
		return $info;
	}
	
	

	/* 举报分类列表 */
	public function getReportContentlist() {
		
		$reportlist=DI()->notorm->user_video_report_classify
					->select("*")
					->order("orderno asc")
					->fetchAll();
		if(!$reportlist){
			return 1001;
		}

		//添加固定项 其他
		$reportlist[]=array(
			'id'=>'-1',
			'orderno'=>'1000',
			'name'=>'其他',
			'addtime'=>'1589872429'
		);

		
		return $reportlist;
		
	}

	/*更新视频看完次数*/
	public function setConversion($videoid){

		//更新视频看完次数
		$res=DI()->notorm->user_video
				->where("id = '{$videoid}' and isdel=0 and status=1")
				->update( array('watch_ok' => new NotORM_Literal("watch_ok + 1") ) );

		return 1;
	}
    
    /* 标签下视频列表 */
    public function getLabelVideoList($uid,$labelid,$p){
        
        if($p<1){
            $p=1;
        }
        
        $nums=50;
        $start=($p-1)*$nums;
        
        
        $list=DI()->notorm->user_video
                ->select("*")
                ->where('labelid=? and isdel=0 and status=1',$labelid)
                ->order("id desc")
                ->limit($start,$nums)
                ->fetchAll();

        //敏感词树        
        $tree=trieTreeBasic();
                
        foreach($list as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            $list[$k]=$v;
        }
                
        return $list;
    }

    /* 视频观看历史 */
    public function getViewRecord($uid,$p){
        
        if($p<1){
            $p=1;
        }
        
        $nums=50;
        $start=($p-1)*$nums;
        
        
        $list=DI()->notorm->user_video_view
                ->select("videoid")
                ->where('uid=? and status=1',$uid)
                ->order("addtime desc")
                ->limit($start,$nums)
                ->fetchAll();

        //敏感词树        
        $tree=trieTreeBasic();
                
        foreach($list as $k=>$v){
            
            $videoinfo=DI()->notorm->user_video
                ->select("*")
                ->where('id=?',$v['videoid'])
                ->fetchOne();
                
            $videoinfo=handleVideo($uid,$videoinfo,$tree);
            $list[$k]=$videoinfo;
        }
                
        return $list;
    }


    //获取视频分类
    public function getClassLists(){

    	$key='getVideoClass';

    	$rules= DI()->notorm->user_video_class
            ->select('id,title')
            ->where("status=1")
            ->order('orderno asc')
            ->fetchAll();

        setcaches($key,$rules);
        
        return $rules;

    }

    //关键词搜索视频分类
    public function searchClassLists($keywords){
    	$rules=DI()->notorm->user_video_class
            ->select('id,title')
            ->where("status=1 and title like '%".$keywords."%'")
            ->order('orderno asc')
            ->fetchAll();

        if(!$rules){
        	return 1001;
        }

        return $rules;
    }

    //根据视频分类id获取视频列表
    public function getVideoListByClass($classid,$p){
    	$pnums=50;
    	if($p<1){
    		$p=1;
    	}


    	$where="status=1 and isdel=0 and  is_ad=0 and classid=".$classid;

    	if($p>1){
    		$videoclass_endtime=$_SESSION['videoclass_endtime'];
    		if($videoclass_endtime){
    			$where.=" and addtime<".$videoclass_endtime;
    		}
    		
    	}



    	$list=DI()->notorm->user_video->where($where)->order("addtime desc")->limit(0,$pnums)->fetchAll();

    	//敏感词树        
        $tree=trieTreeBasic();

    	foreach($list as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            
            $list[$k]=$v;
        }

        $end=end($list);
        if($end){
        	$_SESSION['videoclass_endtime']=strtotime($end['addtime']);
        }

		return $list;

    }

    //用户观看视频扣除钻石
    public function setVideoPay($uid,$videoid){

    	$key="video_pay_".$uid;

    	//判断用户是否已经扣过钻石

    	$is_pay=DI()->redis->sIsMember($key,$videoid);

    	if($is_pay){

    		return 1005;

    	}else{

    		$payLists=getVideoPayLists($uid);
    		if(in_array($videoid, $payLists)){
    			return 1005;
    		}
    	}

    	//获取视频信息
    	$info=DI()->notorm->user_video->select("coin,uid")->where("id=? and is_ad=0 and status=1 and isdel=0",$videoid)->fetchOne();

    	if(!$info){
    		return 1001;
    	}

    	if($info['uid']==$uid){
    		return 1002;
    	}

    	if(!$info['coin']){
    		return 1003;
    	}

    	$coin=getUserCoin($uid);
    	if($coin<$info['coin']){
    		return 1004;
    	}

    	//扣除用户钻石
    	$res=changeUserCoin($uid,$info['coin']);
    	if(!$res){
    		return 0;
    	}

    	//向用户扣除视频redis里存入记录
    	
    	DI()->redis->sAdd($key,$videoid);

    	//向数据库中写入记录
    	setVideoPayLists($uid,$videoid);

    	//写入消费记录
    	$data=array(
    		'type'=>'expend',
    		'action'=>'watchvideo',
    		'uid'=>$uid,
    		'touid'=>$info['uid'],
    		'totalcoin'=>$info['coin'],
    		'addtime'=>time()

    	);
    	setCoinRecord($data);


    	//写入映票收入记录
    	$data=array(
    		'action'=>'3',
    		'uid'=>$info['uid'],
    		'votes'=>$info['coin'],
    		'addtime'=>time()
    	);

    	setVoteRecord($data);

    	//给用户添加映票
    	changeUserVotes($info['uid'],$info['coin'],1);

    	return 1;

    }

    //根据音乐id获取同类型视频列表
    public function getVideoListByMusic($uid,$musicid,$p){
    	if($p<1){
			$p=1;
		}

		$where="isdel=0 and status=1";

		$pnums=20;

		if($p!=1){
			$endtime=$_SESSION['music_video_endtime'];
			if($endtime){
				$where.=" and addtime<{$endtime}";
			}
		}

		/*var_dump($endtime);
		die;*/

		$list=DI()->notorm->user_video
			->where("music_id=?",$musicid)
			->where($where)
			->order("addtime desc")
			->limit($pnums)
			->fetchAll();


		//敏感词树        
        $tree=trieTreeBasic();
                
        foreach($list as $k=>$v){
            $v=handleVideo($uid,$v,$tree);
            $list[$k]=$v;
        }


        if($list){
			$end=end($list);
			$_SESSION['music_video_endtime']=$end['addtime_format'];
		}

		return $list;
    }


    //获取音乐信息
	public function getMusicInfo($musicid){
		$musicinfo=DI()->notorm->user_music->select("title,author,img_url,file_url,use_nums,length")->where("id=? and isdel=0",$musicid)->fetchOne();
		if(!$musicinfo){
			return 1001;
		}

		$musicinfo['img_url']=get_upload_path($musicinfo['img_url']);
		$musicinfo['file_url']=get_upload_path($musicinfo['file_url']);
		$musicinfo['use_nums']=$musicinfo['use_nums'].'人参与';

		return $musicinfo;
	}

	//视频送礼物
	public function videoSendGift($data,$gift_info,$ispack){

		$uid=$data['uid'];

		//获取视频信息
		$videoinfo=getVideoInfoById($data['videoid']);

		if($videoinfo['uid']==$uid){
			return 1001; //给自己视频送礼物
		}

		
		$total=$gift_info['needcoin']*$data['giftcount'];

		$now=time();
		$type='expend';
		$action='video_sendgift';

		if($ispack==1){
			
			/* 背包礼物 */
			$ifok =DI()->notorm->backpack
					->where('uid=? and giftid=? and nums>=?',$uid,$gift_info['id'],$data['giftcount'])
				->update(array('nums'=> new NotORM_Literal("nums - {$data['giftcount']} ")));
			if(!$ifok){
				/* 数量不足 */
				return 1003;
			}
		}else{
			
			//更新用户的余额消费
			$ifok=changeUserCoin($uid,$total);
			if(!$ifok){
				return 1002; //余额不足
			}
			
		}

		//非管理员发布者
		if($videoinfo['uid']){

			//给对方用户增加映票
			changeUserVotes($videoinfo['uid'],$total,1);


			//写入映票记录
			$data1=array(
				'action'=>4, //视频送礼物
				'uid'=>$videoinfo['uid'],
				'votes'=>$total,
				'addtime'=>$now
			);
			
		

			setVoteRecord($data1);
		}

		
		

		$data2=array(
			'uid'=>$uid,
			'touid'=>$videoinfo['uid'],
			'type'=>$type,
			'action'=>$action,
			'giftid'=>$gift_info['id'],
			'giftcount'=>$data['giftcount'],
			'totalcoin'=>$total,
			'addtime'=>$now,
			'videoid'=>$data['videoid']
		);

		setCoinRecord($data2);
		
		
		/*打赏礼物---每日任务---针对于用户*/
		$data=[
			'type'=>'4',
			'total'=>$total,
		];
		dailyTasks($uid,$data);
		

		return 1;

	}

	//删除视频访问记录
	public function deltViewRecord($uid,$videoid_arr){
		$res=DI()->notorm->user_video_view
		->where("uid={$uid}")
		->where("videoid",$videoid_arr)
		->delete();

		return 1;
	}
 
}
