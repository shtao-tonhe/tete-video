<?php

/**
 * 短视频
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;



class VideoController extends AdminbaseController {

	protected function initialize(){
        parent::initialize();
        $site_info=cmf_get_option('site_info');
		$name_coin=$site_info['name_coin'];
		$this->name_coin=$name_coin;
    }

    protected function getVideoClass($k=''){
    	$key="video_class";
    	$list=getcaches($key);
    	if(!$list){
    		$list=Db::name("user_video_class")->where("status=1")->order("orderno")->column('title','id');
    		setcaches($key,$list,10);
    	}
    	
        if($k==''){
            return $list;
        }
        
        return isset($list[$k]) ? $list[$k]: '';
    }


	/*待审核视频列表*/
    public function index(){

		$ordertype = $this->request->param('ordertype');
		
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
		$lists = Db::name('user_video')
            ->where(function (Query $query) {
                $data = $this->request->param();
				$query->where('isdel', 0);
				$query->where('status', 0);
				$query->where('is_ad', 0);

				$classid=isset($data['classid']) ? $data['classid']: '';
				$keyword=isset($data['keyword']) ? $data['keyword']: '';
				$keyword1=isset($data['keyword1']) ? $data['keyword1']: '';
				$keyword2=isset($data['keyword2']) ? $data['keyword2']: '';
				
				if (!empty($classid)) {
                    $query->where('classid', 'eq', $classid);
                }

                if (!empty($keyword)) {
                    $query->where('uid|id', 'like', "%$keyword%");
                }
				
				if (!empty($keyword1)) {
                    $query->where('title', 'like', "%$keyword1%");
                }
				
				if (!empty($keyword2)) {
					
					$userlist =Db::name("user")
						->field("id")
						->where("user_nicename like '%".$keyword2."%'")
						->select();

					$strids="";
					$userlist->all();

					foreach ($userlist as $k => $vu) {
						if($strids==""){
							$strids=$vu['id'];
						}else{
							$strids.=",".$vu['id'];
						}
					}


                    $query->where('uid', 'in', $strids);
                }
            })
            ->order($orderstr)
            ->paginate(20);
		
			$lists->each(function($v,$k){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('SYSTEM_ADMINISTRATOR')
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				
			}
			
			$v['userinfo']=$userinfo;
            $v['thumb']=get_upload_path($v['thumb']);			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$v['hasurgemoney']=$hasurgemoney;
			if($v['classid']){
				$v['class_name']=$this->getVideoClass($v['classid']);
			}

			return $v;
			
		});


		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);	
		// 获取分页显示
        $page = $lists->render();
		
		$p= $this->request->param('page');
		if(!$p){
			$p=1;
		}

		
		
		$classify=$this->getClassLists();	
		$this->assign("classify",$classify);
		$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign("p",$p);
    	$this->assign("time",time());
    	$this->assign("name_coin",$this->name_coin);
    	return $this->fetch();

    }


    /*未通过视频列表*/
    public function nopassindex(){
		
		$ordertype = $this->request->param('ordertype');
		
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}

		$lists = Db::name('user_video')
            ->where(function (Query $query) {
                $data = $this->request->param();
				$query->where('isdel', 0);
				$query->where('status', 2);
				$query->where('is_ad', 0);

				$classid=isset($data['classid']) ? $data['classid']: '';
				$keyword=isset($data['keyword']) ? $data['keyword']: '';
				$keyword1=isset($data['keyword1']) ? $data['keyword1']: '';
				$keyword2=isset($data['keyword2']) ? $data['keyword2']: '';
				
				if (!empty($classid)) {
                    $query->where('classid', 'eq', $classid);
                }

                if (!empty($keyword)) {
                    $query->where('uid|id', 'like', "%$keyword%");
                }
				
				if (!empty($keyword1)) {
                    $query->where('title', 'like', "%$keyword1%");
                }
				
				if (!empty($keyword2)) {
					
					$userlist =Db::name("user")
						->field("id")
						->where("user_nicename like '%".$keyword2."%'")
						->select();
					$strids="";
					$userlist->all();

					foreach ($userlist as $k => $vu) {
						if($strids==""){
							$strids=$vu['id'];
						}else{
							$strids.=",".$vu['id'];
						}
					}
					
                    $query->where('uid', 'in', $strids);
                }
            })
            ->order($orderstr)
            ->paginate(20);
			
			
		$lists->each(function($v,$k){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('SYSTEM_ADMINISTRATOR')
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				
			}
			
			$v['userinfo']=$userinfo;
            $v['thumb']=get_upload_path($v['thumb']);			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$v['hasurgemoney']=$hasurgemoney;

			if($v['classid']){
				$v['class_name']=$this->getVideoClass($v['classid']);
			}

			return $v;
			
		});
			
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);	
		
		// 获取分页显示
        $page = $lists->render();
		
		$p= $this->request->param('page');
		if(!$p){
			$p=1;
		}
	
		$classify=$this->getClassLists();	
		$this->assign("classify",$classify);
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign("p",$p);
    	$this->assign("time",time());
    	$this->assign("name_coin",$this->name_coin);
    	return $this->fetch();
    }


    /*审核通过视频列表*/
    public function passindex(){
		
		$ordertype = $this->request->param('ordertype');
		
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
		
		
		$lists = Db::name('user_video')
            ->where(function (Query $query) {
				$data = $this->request->param();

				$query->where('isdel', 0);
				$query->where('status', 1);
				$query->where('is_ad', 0);

				$classid=isset($data['classid']) ? $data['classid']: '';
				$keyword=isset($data['keyword']) ? $data['keyword']: '';
				$keyword1=isset($data['keyword1']) ? $data['keyword1']: '';
				$keyword2=isset($data['keyword2']) ? $data['keyword2']: '';

				if (!empty($classid)) {
                    $query->where('classid', 'eq', $classid);
                }
				
                if (!empty($keyword)) {
                    $query->where('uid|id', 'like', "%$keyword%");
                }
				if (!empty($keyword1)) {
                    $query->where('title', 'like', "%$keyword1%");
                }
				
				if (!empty($keyword2)) {
					
					$userlist =Db::name("user")
						->field("id")
						->where("user_nicename like '%".$keyword2."%'")
						->select();

					$strids="";
					$userlist->all();

					foreach ($userlist as $k => $vu) {
						if($strids==""){
							$strids=$vu['id'];
						}else{
							$strids.=",".$vu['id'];
						}
					}


                    $query->where('uid', 'in', $strids);
                }
            })
            ->order($orderstr)
            ->paginate(20);
			
			
		$lists->each(function($v,$k){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('SYSTEM_ADMINISTRATOR')
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				
			}
			
			$v['userinfo']=$userinfo;
            $v['thumb']=get_upload_path($v['thumb']);			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$v['hasurgemoney']=$hasurgemoney;

			if($v['classid']){
				$v['class_name']=$this->getVideoClass($v['classid']);
			}

			return $v;
			
		});
		
		
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
			
		// 获取分页显示
        $page = $lists->render();
		
		
		
		
		$p= $this->request->param('page');
		if(!$p){
			$p=1;
		}

		$classify=$this->getClassLists();	
		$this->assign("classify",$classify);
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign("p",$p);
    	$this->assign("time",time());
    	$this->assign("name_coin",$this->name_coin);
    	return $this->fetch();
    }

	//删除视频	
	public function del(){

		$res=array("code"=>0,"msg"=>$Think.\lang('DELETE_SUCCESS'),"info"=>array());
		
		$data = $this->request->param();

    	$id=$data['id'];
    	$reason=$data["reason"];
		if(!$id){

			$res['code']=1001;
			$res['msg']=$Think.\lang('FAILED_TO_VIDEO');
			echo json_encode($res);
			exit;
		}	

		//获取视频信息
		$videoInfo=Db::name("user_video")->where("id={$id}")->find();

		$result=Db::name("user_video")->where("id={$id}")->delete();
		
		//$result=Db::name("user_video")->where("id={$id}")->setField("isdel","1");

		if($result!==false){

			Db::name("user_video_comments_at_messages")->where("videoid={$id}")->delete(); //删除视频评论@信息列表
			Db::name("user_video_comments_messages")->where("videoid={$id}")->delete(); //删除视频评论信息列表
			Db::name("praise_messages")->where("videoid={$id}")->delete(); //删除赞通知列表
			Db::name("user_video_comments")->where("videoid={$id}")->delete();	 //删除视频评论
			Db::name("user_video_like")->where("videoid={$id}")->delete();	 //删除视频喜欢
			Db::name("user_video_report")->where("videoid={$id}")->delete();	 //删除视频举报
			Db::name("user_video_view")->where("videoid={$id}")->delete();	 //删除视频观看
			
			
			/*//获取该视频的评论id
			$commentlists=Db::name("user_video_comments")->field("id")->where("videoid={$id}")->select();
			$commentids="";
			foreach($commentlists as $k=>$v){
				if($commentids==""){
					$commentids=$v['id'];
				}else{
					$commentids.=",".$v['id'];
				}
			}

			//删除视频评论喜欢
			$map['commentid']=array("in",$commentids);*/


			Db::name("user_video_comments_like")->where("videoid={$id}")->delete(); //删除视频评论喜欢

			//如果该视频有正在上热门记录，需要将用户的钻石退回并将上热门记录删除
			$popinfo=Db::name("popular_orders")->where("videoid={$id} and status=1 and refund_status=0")->find();
			if($popinfo){
				$coin=$videoInfo['p_nums']/$popinfo['nums']*$popinfo['money'];
				$coin=floor($coin);

				if($coin>=1){
					$isok=changeUserCoin($popinfo['uid'],$coin,1);
					if($isok){

						$data=array(
							'type'=>'income',
							'action'=>'pop_refund',
							'uid'=>$popinfo['uid'],
							'touid'=>$popinfo['uid'],
							'totalcoin'=>$coin,
							'videoid'=>$id,
							'addtime'=>time()

						);
						//写入钻石消费记录
						setCoinRecord($data);

						//将上热门记录删除
						Db::name("popular_orders")->where("videoid={$id}")->delete();
					}
				}





			}
			

			if($videoInfo['isdel']==0){ //视频上架情况下被删除发送通知
				//极光IM
				$admin_id=$_SESSION['ADMIN_ID'];
				$user=Db::name("user")->where("id='{$admin_id}'")->find();

	    		//向系统通知表中写入数据
	    		$sysInfo=array(
	    			'title'=>$Think.\lang('VIDEO_DELETION_REMINDER'),
	    			'addtime'=>time(),
	    			'admin'=>$user['user_login'],
	    			'ip'=>$_SERVER['REMOTE_ADDR'],
	    			'uid'=>$videoInfo['uid']

	    		);

	    		if($videoInfo['title']!=''){
	    			$videoTitle='上传的《'.$videoInfo['title'].'》';
	    		}else{
	    			$videoTitle=$Think.\lang('UPLOADED');
	    		}

	    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$videoTitle.$Think.\lang('ADMIN_LIVEBAN_LIVELIST_DELETE');

	    		if(!$reason){
	    			$sysInfo['content']=$baseMsg;
	    		}else{
	    			$sysInfo['content']=$baseMsg.$Think.\lang('THE_REASON_DELETION').$reason;
	    		}

	    		$result1=Db::name("system_push")->insert($sysInfo);

	    		if($result1!==false){

	    			$text=$Think.\lang('VIDEO_DELETION_REMINDER');
	    			$uid=$videoInfo['uid'];
	    			jMessageIM($text,$uid);
	    		}
			}


			$this->changeVideoPopular($id,$videoInfo['p_nums'],0);
			

			$res['msg']=$Think.\lang('VIDEO_DELETED_SUCCESSFULLY');
			echo json_encode($res);
			exit;
			
		}else{

			$res['code']=1002;
			$res['msg']=$Think.\lang('VIDEO_DELETED_FAILED');
			echo json_encode($res);
			exit;
		}			
										  			
	}		
    //排序
    public function listsorders() { 
		
        $ids=$this->request->param('listsorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("user_video")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }	
    
	//添加
	public function add(){

		$classify=$this->getClassLists();
		
		$this->assign("classify",$classify);
		$this->assign("name_coin",$this->name_coin);
		$this->assign("time",time());

		return $this->fetch();				
	}

	public function add_post(){
		if($this->request->isPost()) {
			$data=$this->request->param();

			
			$video=Db::name("user_video");
		
			$data['addtime']=time();
			$data['uid']=0;
			
			$owner=$data['owner'];
			$owner_uid=$data['owner_uid'];

			if($owner==1){

				if($owner_uid==""||!is_numeric($owner_uid)){
					$this->error($Think.\lang('FILL_IN_VIDEO_UD'));
					return;
				}

				//判断用户是否存在
				$ownerInfo=Db::name("user")->where("user_type=2 and id={$owner_uid}")->find();
				if(!$ownerInfo){
					$this->error($Think.\lang('VIDEO_OWNER_NOT_EXIST'));
					return;
				}

				$data['uid']=$owner_uid;

			}



			$url=$data['href'];
			$url_w=$data['href_w'];
			
			$title=$data['title'];
			$coin=$data['coin'];
			$thumb=$data['thumb'];

			if($title==""){
				$this->error($Think.\lang('FILL_IN_VIDEO_TITLE'));
			}

			if($coin<0){
				$this->error($Think.\lang('ADMIN_VIDEO_AD_COST').$this->name_coin.$Think.\lang('CANNOT_LESS_THAN_ZERO'));
			}

			if(floor($coin)!=$coin){
				$this->error($Think.\lang('ADMIN_VIDEO_AD_COST').$this->name_coin.$Think.\lang('MUST_BE_AN_INTEGER'));
			}

			if($thumb==""){
				$this->error($Think.\lang('UPLOAD_VIDEO_COVER'));
			}
			
			
			$data['thumb_s']=$thumb;


            //获取后台上传配置
            $configpri=getConfigPri();
            $show_val=$configpri['show_val'];
            $data['show_val']=$show_val;

            $uploadSetting = cmf_get_upload_setting();
            $extensions=$uploadSetting['file_types']['video']['extensions'];
            $allow=explode(",",$extensions);
            
			if($url!=""){

				//判断链接地址的正确性
				if(strpos($url,'http')===false){
                    $this->error($Think.\lang('FILL_IN_THE_CORRECT_VIDEO_ADDRESS'));
                }

                $video_type=substr(strrchr($url, '.'), 1);

                if(!in_array(strtolower($video_type), $allow)){
                	$this->error($Think.\lang('FILL_IN_THE_CORRECT_VIDEO_SUFFIX_CORRECTLY'));
                }
             
				$data['href']=$url;
                
                //判断链接地址的正确性
				if(strpos($url_w,'http')===false){
                    $this->error($Think.\lang('fiLL_IN_CORRECT_WATERMARK_VIDEO_ADDRESS'));
                }

                $video_type_w=substr(strrchr($url_w, '.'), 1);

                if(!in_array(strtolower($video_type_w), $allow)){
                	$this->error($Think.\lang('FILL_IN_WATERMARK_VIDEO_CORRECT_SUFFIX'));
                }

				$data['href_w']=$url_w;
               

			}else{
                
                if(!$_FILES["file"]){
                    $this->error($Think.\lang('UPLOAD_VIDEO'));
                }
                if(!$_FILES["file_w"]){
                    $this->error($Think.\lang('UPLOAD_WATERMARK_VIDEO'));
                }
                
                $files["file"]=$_FILES["file"];
				$type='video';


				if (!get_file_suffix($files['file']['name'],$allow)){
                    $this->error($Think.\lang('UPLOAD_VIDEO_CORRECT_FORMAT'));
                }


				$rs=adminUploadFiles($files,$type);
                if($rs['code']!=0){
                    $this->error($rs['msg']);
                }
				
				
				$files_w["file"]=$_FILES["file_w"];
				$type_w='video';

				if (!get_file_suffix($files_w['file']['name'],$allow)){
                    $this->error($Think.\lang('UPLOAD_THE_WATERMARK_VIDEO_INCORRECT_OR_CHECK'));
                }

				$rs_w=adminUploadFiles($files_w,$type_w);
                
  
                if($rs_w['code']!=0){
                   $this->error($rs_w['msg']);
                }
                $data['href']=$rs['filepath'];
                $data['href_w']=$rs_w['filepath'];
	
			}
			
			
			//计算封面尺寸比例
			$configpub=getConfigPub(); //获取公共配置信息
			$anyway='1.1';
			//if($configpri['cloudtype']!=1){
				$thumb_url=get_upload_path($thumb);
				
				$refer=$configpub['site'];
				$option=array(
					'http'=>array('header'=>"Referer: {$refer}"),
					"ssl" => [
				        "verify_peer"=>false,
				        "verify_peer_name"=>false,
				    ]
				);
		            $context=stream_context_create($option);//创建资源流上下文
	            $file_contents = file_get_contents($thumb_url,false, $context);//将整个文件读入一个字符串
	            $thumb_size = getimagesizefromstring($file_contents);//从字符串中获取图像尺寸信息

				if($thumb_size){
					$thumb_width=$thumb_size[0];  //封面-宽
					$thumb_height=$thumb_size[1];  //封面-高

					$anyway=round($thumb_height/$thumb_width); 
				}
			//}
			$data['anyway']=$anyway;

			unset($data['file']);
			unset($data['file_w']);
			unset($data['owner']);
			unset($data['owner_uid']);
			unset($data['video_upload_type']);
			
			$result=$video->insert($data); 

			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'),'admin/video/passindex',3);
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}			
	}
	
	//编辑
	public function edit(){
		$data = $this->request->param();
		$id=intval($data['id']);
		$from=$data["from"];
		
		if($id){
			$video=Db::name("user_video")->where("id={$id}")->find();
			if($video['uid']==0){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('SYSTEM_ADMINISTRATOR')
				);
			}else{
				$userinfo=getUserInfo($video['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
			}
			
			$video['userinfo']=$userinfo;
            $video['thumb']=get_upload_path($video['thumb']);
            $video['href']=get_upload_path($video['href']);
            $video['href_w']=get_upload_path($video['href_w']);

            //获取视频绑定的商品
            $goodsinfo=[];
            $goodsinfo=Db::name("shop_goods")->where("id={$video['goodsid']}")->find();
            if($goodsinfo){
            	$thumb_arr=explode(',',$goodsinfo['thumbs']);
				$goodsinfo['thumb']=get_upload_path($thumb_arr[0]);
            	if($goodsinfo['type']==0){
            		$goodsinfo['type_name']=$Think.\lang('GOODS_IN_STATION');
            	}else if($goodsinfo['type']==1){
            		$goodsinfo['type_name']=$Think.\lang('GOODS_OUTSIDE_STATION');
            	}else{
            		$goodsinfo['type_name']=$Think.\lang('ADMIN_REFUNDLIST_INDEX_ZIYT');
            	}
            	$goodsinfo['old_price']=$goodsinfo['original_price'];
				$goodsinfo['price']=$goodsinfo['present_price'];
				$goodsinfo['des']=$goodsinfo['goods_desc'];
            }


			$this->assign('video', $video);
			$this->assign('goodsinfo', $goodsinfo);
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		$this->assign("from",$from);							  
		return $this->fetch();				
	}
	
	public function edit_post(){
		if($this->request->isPost()) {
			$data = $this->request->param();

			$video=Db::name("user_video");

			$id=$data['id'];
			$title=$data['title'];
			$thumb=$data['thumb'];
			$type=$data['video_upload_type'];
			$url=$data['href_e'];
			$url_w=$data['href_e_w'];
			$status=$data['status'];
			$isdel=$data['isdel'];
			$nopasstime=$data['nopasstime'];
			$coin=$data['coin'];
			


			/*if($title==""){
				$this->error("请填写视频标题");
			}*/

			if($coin<0){
				$this->error($Think.\lang('ADMIN_VIDEO_AD_COST').$this->name_coin.$Think.\lang('CANNOT_LESS_THAN_ZERO'));
			}

			if(floor($coin)!=$coin){
				$this->error($Think.\lang('ADMIN_VIDEO_AD_COST').$this->name_coin.$Think.\lang('MUST_BE_AN_INTEGER'));
			}

			if($thumb==""){
				$this->error($Think.\lang('UPLOAD_VIDEO_COVER'));
			}

			$data['thumb_s']=$thumb;

			if($type!=''){

				$uploadSetting = cmf_get_upload_setting();
            	$extensions=$uploadSetting['file_types']['video']['extensions'];
            	$allow=explode(",",$extensions);

				if($type==0){ //视频链接型式
					if($url==''){
						$this->error($Think.\lang('FILL_IN_VIDEO_LINK_ADDRESS'));
					}
                    
                    if($url_w==''){
						$this->error($Think.\lang('FILL_IN_WATERMARK_VIDEO_LINK_ADDRESS'));
					}

					//判断链接地址的正确性
                    if(strpos($url,'http')===false){
                        $this->error($Think.\lang('FILL_IN_THE_CORRECT_VIDEO_ADDRESS'));
                    }

                    $video_type=substr(strrchr($url, '.'), 1);

                    if(!in_array(strtolower($video_type), $allow)){
	                	$this->error($Think.\lang('FILL_IN_THE_CORRECT_VIDEO_SUFFIX_CORRECTLY'));
	                }
					
                    $data['href']=$url;
                    
                    //判断链接地址的正确性
                    if(strpos($url_w,'http')===false){
                        $this->error($Think.\lang('fiLL_IN_CORRECT_WATERMARK_VIDEO_ADDRESS'));
                    }

                    $video_type_w=substr(strrchr($url_w, '.'), 1);

                    if(!in_array(strtolower($video_type_w), $allow)){
	                	$this->error($Think.\lang('FILL_IN_WATERMARK_VIDEO_CORRECT_SUFFIX'));
	                }
					
					$data['href_w']=$url_w;


				}else if($type==1){ //文件上传型式

                    if(!$_FILES["file"]){
                        $this->error($Think.\lang('UPLOAD_VIDEO'));
                    }
                    if(!$_FILES["file_w"]){
                        $this->error($Think.\lang('UPLOAD_WATERMARK_VIDEO'));
                    }
                    
					$files["file"]=$_FILES["file"];
                    $type='video';

                    if (!get_file_suffix($files['file']['name'],$allow)){
	                    $this->error($Think.\lang('UPLOAD_THE_WATERMARK_VIDEO_INCORRECT_OR_CHECK'));
	                }
                    
                    $rs=adminUploadFiles($files,$type);
                    if($rs['code']!=0){
                        $this->error($rs['msg']);
                    }
                    
            
					$file_w["file"]=$_FILES["file_w"];
                    $type_w='video';

                    if (!get_file_suffix($file_w['file']['name'],$allow)){
	                    $this->error($Think.\lang('UPLOAD_THE_WATERMARK_VIDEO_INCORRECT_OR_CHECK'));
	                }
                    
                    $rs_w=adminUploadFiles($file_w,$type_w);
                    if($rs_w['code']!=0){
                       $this->error($rs_w['msg']);
                    }
					
					$data['href']=$rs['filepath'];
					$data['href_w']=$rs_w['filepath'];


				}
			}

			if($status==2){
				
				$data['nopass_time']=time();
			}

			//审核通过给该视频添加曝光值（改为接口添加视频时直接添加曝光值）
			// if($status==1){
				// $data['show_val']=100;
			// }
			
			
			//计算封面尺寸比例
			$configpub=getConfigPub(); //获取公共配置信息
            //$configpri=getConfigPri();  //获取后台上传配置
			$anyway='1.1';

			//if($configpri['cloudtype']!=1){

			$thumb_url=get_upload_path($thumb);

			$refer=$configpub['site'];
			$option=array(
				'http'=>array('header'=>"Referer: {$refer}"),
				"ssl" => [
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			    ]
			);
            $context=stream_context_create($option);//创建资源流上下文
            $file_contents = file_get_contents($thumb_url,false, $context);//将整个文件读入一个字符串
            $thumb_size = getimagesizefromstring($file_contents);//从字符串中获取图像尺寸信息


			if($thumb_size){
				$thumb_width=$thumb_size[0];  //封面-宽
				$thumb_height=$thumb_size[1];  //封面-高

				$anyway=round($thumb_height/$thumb_width); 
			}
			//}

			$data['anyway']=$anyway;
			
			
			unset($data['file']);
			unset($data['file_w']);
			unset($data['href_e']);
			unset($data['href_e_w']);
			unset($data['video_upload_type']);
			unset($data['owner_uid']);
			unset($data['nopasstime']);
			unset($data['ckplayer_playerzmblbkjP']);
			
			

			$result=$video->update($data);

			if($result!==false){

				if($status==2||$isdel==1){  //如果该视频下架或视频状态改为不通过，需要将视频喜欢列表的状态更改
					Db::name("user_video_like")->where("videoid={$id}")->setField('status',0);
					Db::name("user_video_view")->where("videoid={$id}")->setField('status',0);
				}

				if($status==2&&$nopasstime==0){  //视频状态为审核不通过且为第一次审核为不通过，发送极光IM

					$videoInfo=Db::name("user_video")->where("id={$id}")->find();

					$id=$_SESSION['ADMIN_ID'];
					$user=Db::name("user")->where("id='{$id}'")->find();

		    		//向系统通知表中写入数据
		    		$sysInfo=array(
		    			'title'=>$Think.\lang('VIDEO_FAILED_PASS_REVIEW_REMINDER'),
		    			'addtime'=>time(),
		    			'admin'=>$user['user_login'],
		    			'ip'=>$_SERVER['REMOTE_ADDR'],
		    			'uid'=>$videoInfo['uid']

		    		);

		    		if($videoInfo['title']!=''){
		    			$videoTitle='上传的《'.$videoInfo['title'].'》';
		    		}else{
		    			$videoTitle=$Think.\lang('UPLOADED');
		    		}

		    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$Think.\lang('TAPPROVAL_NOT_PASSED');;

		    		
		    		$sysInfo['content']=$baseMsg;
		    		

		    		$result1=Db::name("system_push")->insert($sysInfo);

		    		if($result1!==false){

		    			$text=$Think.\lang('VIDEO_FAILED_PASS_REVIEW_REMINDER');
		    			$uid=$videoInfo['uid'];
		    			jMessageIM($text,$uid);

		    		}

				}

				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			 }else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			 }
		}			
	}
	
    public function reportlist(){

			
    	$lists=Db::name("user_video_report")
			->where(function (Query $query) {
                $data = $this->request->param();
				
				if ($data['status']!='') {
                    $query->where('status', $data['status']);
                }
				
				if (!empty($data['start_time'])) {
                    $query->where('addtime', 'gt' , strtotime($data['start_time']));
                }
			
                if (!empty($data['end_time'])) {
                    $query->where('addtime', 'lt' ,strtotime($data['end_time']));
                }
				
				if (!empty($data['start_time']) && !empty($data['end_time'])) {
                    $query->where('addtime', 'between' , [strtotime($data['start_time']),strtotime($data['end_time'])]);
                }
				
				
				if (!empty($data['keyword'])) {
                    $keyword = $data['keyword'];
                    $query->where('uid', 'like', "%$keyword%");
                }
				
				
			})
			->order("addtime DESC")
            ->paginate(20);
			
			
			$lists->all();
			
			$lists->each(function($v,$k){
				$userinfo=Db::name("user")
					->field("user_nicename")
					->where("id='{$v['uid']}'")
					->find();
					
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				$v['userinfo']= $userinfo;
				
				
				$touserinfo=Db::name("user")
					->field("user_nicename")
					->where("id='{$v['touid']}'")
					->find();
					
				if(!$touserinfo){
					$touserinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				$v['touserinfo']= $touserinfo;

				//判断视频是否下架
				$video_isdel=Db::name("user_video")->where("id={$v['videoid']}")->value("isdel");
				$v['video_isdel']=$video_isdel;

				return $v;
			
		});
		
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
		
		
		
		// 获取分页显示
        $page = $lists->render();

		$p= $this->request->param('page');
		if(!$p){
			$p=1;
		}	
		
			
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign("p",$p);
    	return $this->fetch();
    }
	
	//视频举报标记
	public function setstatus(){

		$id=$this->request->param('id');

		if($id){
			$data['status']=1;
			$data['uptime']=time();
			$result=Db::name("user_video_report")->where("id='{$id}'")->update($data);				
			if($result!==false){
				$this->success($Think.\lang('MARK_SUCCESSFUL'));
			}else{
				$this->error($Think.\lang('MARKING_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  		
	}

	//删除用户举报列表
	public function report_del(){
		$id=$this->request->param("id");
		if($id){
			$result=Db::name("user_video_report")->delete($id);				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  
	}

	//举报内容设置**************start******************
	
	//举报类型列表
	
	public function reportset(){
		$lists = Db::name("user_video_report_classify")
			->order("orderno ASC")
			->select();
			
		$this->assign('lists', $lists);
		return $this->fetch();
	}

	//添加举报理由
	public function add_report(){
		
		return $this->fetch();
	}

	public function add_reportpost(){
		
		if($this->request->isPost()) {
			$data=$this->request->param();
			
			$report=Db::name("user_video_report_classify");
			
			$name=$data['name'];//举报类型名称
			if(!trim($name)){
				$this->error($Think.\lang('REPORT_TYPE_NAME_CANNOT_EMPTY'));
			}
			$isexit=Db::name("user_video_report_classify")
				->where("name='{$name}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('REPORT_TYPE_NAME_ALREADY_EXISTS'));
			}
	
			$data['addtime']=time();
			$result=$report->insert($data); 
			
			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'));
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}	
	}

	//编辑举报类型名称
	public function edit_report(){
		$id   = $this->request->param('id', 0, 'intval');
		if($id){
			$reportinfo=Db::name("user_video_report_classify")
				->where("id={$id}")
				->find();
			
			$this->assign('reportinfo', $reportinfo);						
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  
		return $this->fetch();				
	}
	
	public function edit_reportpost(){
		if($this->request->isPost()) {
			$data=$this->request->param();
			
			$report=Db::name("user_video_report_classify");
			
	
			$id=$data['id'];
			$name=$data['name'];//举报类型名称
			
			if(!trim($name)){
				$this->error($Think.\lang('REPORT_TYPE_NAME_CANNOT_EMPTY'));
			}
		
			$isexit=Db::name("user_video_report_classify")
				->where("id!={$id} and name='{$name}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('REPORT_TYPE_NAME_ALREADY_EXISTS'));
			}
			
		
			$result=$report->update($data); 
			if($result!==false){
				  $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			 }else{
				  $this->error($Think.\lang('UPDATE_FAILED'));
			 }
		}			
	}


	//删除举报类型名称
	public function del_report(){
		$id   = $this->request->param('id', 0, 'intval');
		if($id){
			$result=Db::name("user_video_report_classify")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}								  
		return $this->fetch();		
	}
	
	//举报内容排序
    public function listordersset() { 
		
     
		$ids=$this->request->param('listorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("user_video_report_classify")
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

	//举报内容设置**************end******************	
    //设置下架
    public function setXiajia(){
    	$res=array("code"=>0,"msg"=>$Think.\lang('ADMIN_VIDEO_REPORTLIST_LOWER_SUCCEED'),"info"=>array());
    	$data = $this->request->param();
		
		
    	$id=$data['id'];
    	$reason=$data["reason"];
    	if(!$id){
    		$res['code']=1001;
    		$res['msg']=$Think.\lang('CONFIRM_VIDEO_INFORMATION');
    		echo json_encode($res);
    		exit;
    	}

    	//判断此视频是否存在
    	$videoInfo=Db::name("user_video")->where("id={$id}")->find();
    	if(!$videoInfo){
    		$res['code']=1001;
    		$res['msg']=$Think.\lang('CONFIRM_VIDEO_INFORMATION');
    		echo json_encode($res);
    		exit;
    	}

    	//更新视频状态
    	$data=array("isdel"=>1,"xiajia_reason"=>$reason);

    	$result=Db::name("user_video")->where("id={$id}")->update($data);

    	if($result!==false){

    		//将视频喜欢列表的状态更改
    		Db::name("user_video_like")->where("videoid={$id}")->setField('status',0);
            //将视频喜欢列表的状态更改
    		Db::name("user_video_view")->where("videoid={$id}")->setField('status',0);

    		//将点赞信息列表里的状态修改
    		Db::name("praise_messages")->where("videoid={$id}")->setField('status',0);

    		//将评论@信息列表的状态更改
    		Db::name("user_video_comments_at_messages")->where("videoid={$id}")->setField('status',0);

    		//将评论信息列表的状态更改
    		Db::name("user_video_comments_messages")->where("videoid={$id}")->setField('status',0);

    		//更新此视频的举报信息
    		$data1=array(
    			'status'=>1,
    			'uptime'=>time()
    		);

    		Db::name("user_video_report")->where("videoid={$id}")->update($data1);

    		


    		$admin_id=get_current_admin_id();
			
			$user=Db::name("user")->where("id='{$admin_id}'")->find();

    		//向系统通知表中写入数据
    		$sysInfo=array(
    			'title'=>$Think.\lang('VIDEO_OFF_SHELF_REMINDER'),
    			'addtime'=>time(),
    			'admin'=>$user['user_login'],
    			'ip'=>$_SERVER['REMOTE_ADDR'],
    			'uid'=>$videoInfo['uid']

    		);


    		if($videoInfo['title']!=''){
    			$videoTitle='上传的《'.$videoInfo['title'].'》';
    		}else{
    			$videoTitle=$Think.\lang('UPLOADED');
    		}

    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$Think.\lang('LOWER');;

    		if(!$reason){
    			$sysInfo['content']=$baseMsg;
    		}else{
    			$sysInfo['content']=$baseMsg.$Think.\lang('RESPN_FOR_GETTING_OFF_SHELF').$reason;
    		}

    		$result1=Db::name("system_push")->insert($sysInfo);


    		if($result1!==false){

    			$text=$Think.\lang('VIDEO_OFF_SHELF_REMINDER');
    			$uid=$videoInfo['uid'];
    			jMessageIM($text,$uid);

    		}

    		//处理视频热门
    		$this->changeVideoPopular($id,$videoInfo['p_nums'],1);

    		echo json_encode($res);
    		exit;
    	}else{
    		$res['code']=1002;
    		$res['msg']=$Think.\lang('VIDEO_OFF_SHELF_FAILED');
    		echo json_encode($res);
    		exit;
    	}
    	
    }

    /*下架视频列表*/
    public  function lowervideo(){
		
		$ordertype = $this->request->param('ordertype');
		
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}

		$lists = Db::name('user_video')
            ->where(function (Query $query) {
                $data = $this->request->param();
				$query->where('isdel', 1);
				$query->where('is_ad', 0);

				$classid=isset($data['classid']) ? $data['classid']: '';
				$keyword=isset($data['keyword']) ? $data['keyword']: '';
				$keyword1=isset($data['keyword1']) ? $data['keyword1']: '';
				$keyword2=isset($data['keyword2']) ? $data['keyword2']: '';
				
				if (!empty($classid)) {
                    $query->where('classid', 'eq', $classid);
                }

                if (!empty($keyword)) {
                    $query->where('uid|id', 'like', "%$keyword%");
                }
				
				if (!empty($keyword1)) {
                    $query->where('title', 'like', "%$keyword1%");
                }
				
				if (!empty($keyword2)) {
					
					$userlist =Db::name("user")
						->field("id")
						->where("user_nicename like '%".$keyword2."%'")
						->select();
					$strids="";
					$userlist->all();

					foreach ($userlist as $k => $vu) {
						if($strids==""){
							$strids=$vu['id'];
						}else{
							$strids.=",".$vu['id'];
						}
					}
                    $query->where('uid', 'in', $strids);
                }
            })
            ->order($orderstr)
            ->paginate(20);
			
			
		$lists->each(function($v,$k){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('SYSTEM_ADMINISTRATOR')
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>$Think.\lang('DELETED')
					);
				}
				
			}
			
			$v['userinfo']=$userinfo;
            $v['thumb']=get_upload_path($v['thumb']);			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$v['hasurgemoney']=$hasurgemoney;
			if($v['classid']){
				$v['class_name']=$this->getVideoClass($v['classid']);
			}
			return $v;
			
		});
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);
			
		// 获取分页显示
        $page = $lists->render();
		
		$p= $this->request->param('page');
		if(!$p){
			$p=1;
		}

		$classify=$this->getClassLists();	
		$this->assign("classify",$classify);
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign("p",$p);
    	$this->assign("time",time());
    	$this->assign("name_coin",$this->name_coin);
    	return $this->fetch();
    }


    public function video_listen(){
    	$id   = $this->request->param('id', 0, 'intval');
    	if(!$id||$id==""||!is_numeric($id)){
    		$this->error($Think.\lang('LOADING_FAILED'));
    	}else{
    		//获取音乐信息
    		$info=Db::name("user_video")->where("id={$id}")->find();
            $info['thumb']=get_upload_path($info['thumb']);
            $info['href']=get_upload_path($info['href']);
    		$this->assign("info",$info);
    	}

    	return $this->fetch();
    }


    /*视频上架*/
    public function set_shangjia(){
    	$id   = $this->request->param('id', 0, 'intval');
    	if(!$id){
    		$this->error($Think.\lang('FAILED_TO_VIDEO'));
    	}

    	//获取视频信息
    	$info=Db::name("user_video")->where("id={$id}")->find();
    	if(!$info){
    		$this->error($Think.\lang('FAILED_TO_VIDEO'));
    	}

    	$data=array(
    		'xiajia_reason'=>'',
    		'isdel'=>0
    	);
    	$result=Db::name("user_video")->where("id={$id}")->update($data);
    	if($result!==false){

    		//将视频喜欢列表的状态更改
    		Db::name("user_video_like")->where("videoid={$id}")->setField('status',1);
            
            //将视频观看列表的状态更改
    		Db::name("user_video_view")->where("videoid={$id}")->setField('status',1);

    		//将点赞信息列表里的状态修改
    		Db::name("praise_messages")->where("videoid={$id}")->setField('status',1);

    		//将评论@信息列表的状态更改
    		Db::name("user_video_comments_at_messages")->where("videoid={$id}")->setField('status',1);
    		//将评论信息列表的状态更改
    		Db::name("user_video_comments_messages")->where("videoid={$id}")->setField('status',1);

    		


    		$this->success($Think.\lang('SUCCESSFUL_LAUNCH'));
    	}
    	return $this->fetch();
    }
	
	
	//评论列表
    public function commentlists(){
    	
    	$data = $this->request->param();
    	$videoid=$data['videoid'];
		
		$lists = Db::name('user_video_comments')
            ->where("videoid={$videoid}")
            ->order("addtime DESC")
            ->paginate(20);
		
	
		$lists->each(function($v,$k){
		
			$userinfo=getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>$Think.\lang('DELETED')
				);
			}

			$v['user_nicename']=$userinfo['user_nicename'];
            if($v['voice']){
            	$v['voice']=get_upload_path($v['voice']);
            }
	
			return $v;
			
		});

		
		//分页-->筛选条件参数
		$lists->appends($data);

        // 获取分页显示
        $page = $lists->render();

    	$this->assign("lists",$lists);
    	$this->assign("page", $page);
    	return $this->fetch();

    }

    private function getClassLists(){
    	//获取视频分类
		$classify=Db::name("user_video_class")->where("status=1")->order("orderno")->select();
		return $classify;
    }

    //批量下架
    public function setBatchXiajia(){
    	$data = $this->request->param();
        $status=$data['status'];
        $ids = $data['ids'];
        if(empty($ids)){
        	$this->error($Think.\lang('SELECT_LIST'));
        }

        foreach ($ids as $k => $v) {
        	
        	$id=$v;
        	

        	//获取视频详情
        	$videoInfo=Db::name("user_video")->where("id={$id}")->find();

        	if(!$videoInfo){
        		continue;
        	}

        	$update_data=array(
        		'isdel'=>$status
        	);

        	$result=Db::name("user_video")->where("id={$id}")->update($update_data);

	    	if($result!==false){

	    		//将视频喜欢列表的状态更改
	    		Db::name("user_video_like")->where("videoid={$id}")->setField('status',0);
	            //将视频喜欢列表的状态更改
	    		Db::name("user_video_view")->where("videoid={$id}")->setField('status',0);

	    		//将点赞信息列表里的状态修改
	    		Db::name("praise_messages")->where("videoid={$id}")->setField('status',0);

	    		//将评论@信息列表的状态更改
	    		Db::name("user_video_comments_at_messages")->where("videoid={$id}")->setField('status',0);

	    		//将评论信息列表的状态更改
	    		Db::name("user_video_comments_messages")->where("videoid={$id}")->setField('status',0);

	    		//更新此视频的举报信息
	    		$data1=array(
	    			'status'=>1,
	    			'uptime'=>time()
	    		);

	    		Db::name("user_video_report")->where("videoid={$id}")->update($data1);

	    		$admin_id=get_current_admin_id();
				
				$user=Db::name("user")->where("id='{$admin_id}'")->find();

	    		//向系统通知表中写入数据
	    		$sysInfo=array(
	    			'title'=>$Think.\lang('VIDEO_OFF_SHELF_REMINDER'),
	    			'addtime'=>time(),
	    			'admin'=>$user['user_login'],
	    			'ip'=>$_SERVER['REMOTE_ADDR'],
	    			'uid'=>$videoInfo['uid']

	    		);


	    		if($videoInfo['title']!=''){
	    			$videoTitle='上传的《'.$videoInfo['title'].'》';
	    		}else{
	    			$videoTitle=$Think.\lang('UPLOADED');
	    		}

	    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$Think.\lang('LOWER');

	    		$result1=Db::name("system_push")->insert($sysInfo);

	    		if($result1!==false){

	    			$text=$Think.\lang('VIDEO_OFF_SHELF_REMINDER');
	    			$uid=$videoInfo['uid'];
	    			jMessageIM($text,$uid);

	    		}

	    		//处理视频热门
    			$this->changeVideoPopular($id,$videoInfo['p_nums'],1);

	    	}

        }

        $this->success($Think.\lang('OPERATION_SUCCESSFUL'));
    }

    //批量删除
    public function setBatchDel(){
    	$data = $this->request->param();
        $ids = $data['ids'];
        if(empty($ids)){
        	$this->error($Think.\lang('SELECT_LIST'));
        }
        foreach ($ids as $k => $v) {

        	$id=$v;
        	//获取视频信息
			$videoInfo=Db::name("user_video")->where("id={$id}")->find();
			$result=Db::name("user_video")->where("id={$id}")->delete();
			
			if($result!==false){

				Db::name("user_video_comments_at_messages")->where("videoid={$id}")->delete(); //删除视频评论@信息列表
				Db::name("user_video_comments_messages")->where("videoid={$id}")->delete(); //删除视频评论信息列表
				Db::name("praise_messages")->where("videoid={$id}")->delete(); //删除赞通知列表
				Db::name("user_video_comments")->where("videoid={$id}")->delete();	 //删除视频评论
				Db::name("user_video_like")->where("videoid={$id}")->delete();	 //删除视频喜欢
				Db::name("user_video_report")->where("videoid={$id}")->delete();	 //删除视频举报
				Db::name("user_video_view")->where("videoid={$id}")->delete();	 //删除视频观看
				Db::name("user_video_comments_like")->where("videoid={$id}")->delete(); //删除视频评论喜欢

				if($videoInfo['isdel']==0){ //视频上架情况下被删除发送通知
					//极光IM
					$admin_id=$_SESSION['ADMIN_ID'];
					$user=Db::name("user")->where("id='{$admin_id}'")->find();

		    		//向系统通知表中写入数据
		    		$sysInfo=array(
		    			'title'=>$Think.\lang('VIDEO_DELETION_REMINDER'),
		    			'addtime'=>time(),
		    			'admin'=>$user['user_login'],
		    			'ip'=>$_SERVER['REMOTE_ADDR'],
		    			'uid'=>$videoInfo['uid']

		    		);

		    		if($videoInfo['title']!=''){
		    			$videoTitle='上传的《'.$videoInfo['title'].'》';
		    		}else{
		    			$videoTitle=$Think.\lang('UPLOADED');
		    		}

		    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$videoTitle.$Think.\lang('ADMIN_LIVEBAN_LIVELIST_DELETE');

		    		$result1=Db::name("system_push")->insert($sysInfo);

		    		if($result1!==false){

		    			$text=$Think.\lang('VIDEO_DELETION_REMINDER');
		    			$uid=$videoInfo['uid'];
		    			jMessageIM($text,$uid);
		    		}
				}

				//处理视频热门
    			$this->changeVideoPopular($id,$videoInfo['p_nums'],0);
				
			}
        }

        $this->success($Think.\lang('OPERATION_SUCCESSFUL'));
    }

    //批量审核 通过/不通过
    public function setBatchStatus(){
    	$data=$this->request->param();
    	$status=$data['status'];
    	$ids=$data['ids'];

    	if(empty($ids)){
        	$this->error($Think.\lang('SELECT_LIST'));
        }

        foreach ($ids as $k => $v) {
        	$post_data=array(
        		'status'=>$status
        	);
        	if($status==2){
        		$post_data['nopass_time']=time();
        	}

        	$id=$v;

        	$videoInfo=Db::name("user_video")->where("id={$id}")->find();

        	
        	$result=Db::name("user_video")->where("id={$id}")->update($post_data);

			if($result!==false){

				if($status==2){  //如果该视频状态改为不通过，需要将视频喜欢列表的状态更改
					Db::name("user_video_like")->where("videoid={$id}")->setField('status',0);
					Db::name("user_video_view")->where("videoid={$id}")->setField('status',0);
				}

				

				if($status==2&&$videoInfo['nopasstime']==0){  //视频状态为审核不通过且为第一次审核为不通过，发送极光IM

					

					$admin_id=$_SESSION['ADMIN_ID'];
					$user=Db::name("user")->where("id='{$admin_id}'")->find();

		    		//向系统通知表中写入数据
		    		$sysInfo=array(
		    			'title'=>$Think.\lang('VIDEO_FAILED_PASS_REVIEW_REMINDER'),
		    			'addtime'=>time(),
		    			'admin'=>$user['user_login'],
		    			'ip'=>$_SERVER['REMOTE_ADDR'],
		    			'uid'=>$videoInfo['uid']

		    		);

		    		if($videoInfo['title']!=''){
		    			$videoTitle='上传的《'.$videoInfo['title'].'》';
		    		}else{
		    			$videoTitle=$Think.\lang('UPLOADED');
		    		}

		    		$baseMsg=$Think.\lang('YOU_IN').date("Y-m-d H:i:s",$videoInfo['addtime']).$videoTitle.$videoTitle.$Think.\lang('VIDEO_ASVED_BY_ADMINISTRATOR').date("Y-m-d H:i:s",time()).$Think.\lang('TAPPROVAL_NOT_PASSED');;

		    		
		    		$sysInfo['content']=$baseMsg;
		    		

		    		$result1=Db::name("system_push")->insert($sysInfo);

		    		if($result1!==false){

		    			$text=$Think.\lang('VIDEO_FAILED_PASS_REVIEW_REMINDER');
		    			$uid=$videoInfo['uid'];
		    			jMessageIM($text,$uid);

		    		}

				}

				
			 }
        }

        $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
    }


    //处理热门视频 type 处理类型 1要更新视频的热门信息 0不处理
    public function changeVideoPopular($videoid,$pnums,$type){


    	//如果该视频正在上热门，将上热门剩余的钻石退回
		$popinfo=Db::name("popular_orders")->where("videoid={$videoid} and status=1 and refund_status=0")->field("id,uid,touid,money,nums")->find();


		if($popinfo['nums']){

			$coin=$pnums/$popinfo['nums']*$popinfo['money'];
			$coin=floor($coin);

			if($coin>=1){
				$isok=changeUserCoin($popinfo['uid'],$coin,1);
				if($isok){

					$data=array(
						'type'=>'income',
						'action'=>'pop_refund',
						'uid'=>$popinfo['touid'], //视频发布者
						'touid'=>$popinfo['uid'], //花钱帮助上热门用户的id
						'totalcoin'=>$coin,
						'videoid'=>$videoid,
						'addtime'=>time()

					);
					//写入钻石消费记录
					setCoinRecord($data);

					if($type){
						//更新视频的热门信息
						$data1=array(
							'p_expire'=>0,
							'p_nums'=>0,
							'p_add'=>0
						);
						Db::name("user_video")->where("id={$videoid}")->update($data1);
					}

					
				}
			}

			//将上热门记录的退款状态修改一下
			Db::name("popular_orders")->where("id={$popinfo['id']}")->update(["refund_status"=>1]);

		}

    }
}
