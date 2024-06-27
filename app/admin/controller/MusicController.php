<?php

/**
 * 背景音乐管理
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class MusicController extends AdminbaseController {	
	/*分类列表*/
	public function classify(){

		$lists = Db::name('user_music_classify')
            ->where(function (Query $query) {

                $data = $this->request->param();
  				$keyword=isset($data['keyword']) ? $data['keyword']: '';
                if (!empty($keyword)) {
                    $query->where('title', 'like', "%$keyword%");
                }

            })
            ->order("orderno,addtime DESC")
            ->paginate(20);

		$lists->each(function($v,$k){
			
			$v['img_url']=get_upload_path($v['img_url']);
	
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

	/*分类添加*/
	public function classify_add(){

		return $this->fetch();
	}

	/*分类添加提交*/
	public function classify_add_post(){

		if($this->request->isPost()) {
			
			$data = $this->request->param();
			$classify=Db::name("user_music_classify");
			
			$title=trim($data['title']);
			$url=$data['img_url'];
			$orderno=$data['orderno'];

			if($title==""){
				$this->error($Think.\lang('ADMIN_REPORT_CLASSIFY_ADD_WRITE_CLASSIFT_NAME'));
			}

			if($url==""){
				$this->error($Think.\lang('UPLOAD_CATEGORY_ICON'));
			}

			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}
			
			
			$isexit=$classify
				->where("title='{$title}'")
				->find();	
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}
			
			$data['title']=$title;
			$data['orderno']=$orderno;
			$data['img_url']=$url;
			$data['addtime']=time();
			
			$result=$classify->insert($data);

			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'),'admin/Music/classify',3);
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}
		}


	}

	/*分类删除*/
	public function classify_del(){
		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_music_classify")->where("id={$id}")->update(array("isdel"=>1));				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
	}

	/*分类取消删除*/
	public function classify_canceldel(){

		$id = $this->request->param('id');
		if($id){
			$result=Db::name("user_music_classify")->where("id={$id}")->update(array("isdel"=>0));				
			if($result){
				$this->success($Think.\lang('CANCELLATION_SUCCEEDED'));
			}else{
				$this->error($Think.\lang('CANCEL_FAILED'));
			}			
		}else{				
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
	}

	/*分类编辑*/
	public function classify_edit(){
	
		
		$id = $this->request->param('id');
		if($id){
			$info=Db::name("user_music_classify")->where("id={$id}")->find();

			$this->assign("classify_info",$info);
		}else{
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
		}
		
		return $this->fetch();
	}

	/*分类编辑提交*/
	public function classify_edit_post(){
		
		if($this->request->isPost()) {
			
			$data = $this->request->param();	

			$id=$data["id"];
			$title=$data["title"];
			$url=$data['img_url'];
			$orderno=$data["orderno"];

			if(!trim($title)){
				$this->error($Think.\lang('CATEGORY_TITLE_NOT_EMPTY'));
			}
			
			if($url==""){
				$this->error($Think.\lang('UPLOAD_CATEGORY_ICON'));
			}


			if(!is_numeric($orderno)){
				$this->error($Think.\lang('FILL_IN_THE_NUM_OF_SORTING_NUM'));
			}

			if($orderno<0){
				$this->error($Think.\lang('THE_SORT_MUST_GREATER_ZERO'));
			}
		
			$isexit=Db::name("user_music_classify")
				->where("id!={$id} and title='{$title}'")
				->find();
			if($isexit){
				$this->error($Think.\lang('THE_CLASSIFICATION_ALREADY_EXISTS'));
			}

			
			$result=Db::name("user_music_classify")
				->update($data);
				
			
			if($result!==false){
				$this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			}else{
				$this->error($Think.\lang('UPDATE_FAILED'));
			}
		}

	}
	
	//分类排序
    public function classify_listorders(){
		$ids = $this->request->param('listorders');
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("user_music_classify")->where(array('id' => $key))->update($data);
        }
				
        $status = true;
        if ($status) {
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }


	/*背景音乐*/
    public function index(){


		$lists = Db::name('user_music')
            ->where(function (Query $query) {

                $data = $this->request->param();
				$classify_id=isset($data['classify_id']) ? $data['classify_id']: '';
				if (!empty($classify_id)) {
                    $query->where('classify_id', $classify_id);
                }
				$upload_type=isset($data['upload_type']) ? $data['upload_type']: '';
				if (!empty($upload_type)) {
                    $query->where('upload_type', $upload_type);
                }
  				$keyword=isset($data['keyword']) ? $data['keyword']: '';
                if (!empty($keyword)) {
                    $query->where('title', 'like', "%$keyword%");
                }

            })
            ->order("use_nums DESC")
            ->paginate(20);

		$lists->each(function($v,$k){
			$v['img_url']=get_upload_path($v['img_url']);
            $v['file_url']=get_upload_path($v['file_url']);
			$classify_title=Db::name("user_music_classify")->where("id={$v['classify_id']}")->find();
			
			$v['classify_title']=$classify_title['title'];
			$userinfo=Db::name("user")
				->field("user_nicename")
				->where("id='$v[uploader]'")
				->find();
			if(!$userinfo){
				$userinfo=['user_nicename'=>$Think.\lang('USER_NOT_EXIST')];
			}
			$v['uploader_nicename']=$userinfo['user_nicename'];

			return $v;
			
		});
			
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$lists->appends($data);	
			
        // 获取分页显示
        $page = $lists->render();
		
		//分类列表
		$classify=Db::name("user_music_classify")
			->order("orderno")
			->select();

    	$this->assign('lists', $lists);
    	$this->assign('classify', $classify);
    	$this->assign("page", $page);
    	
    	return $this->fetch();
    }

    /*背景音乐添加*/
    public function music_add(){
		
    	$classify=Db::name("user_music_classify")
			->order("orderno")
			->select();
			
    	$this->assign('classify', $classify);
		
    	return $this->fetch();
    }

    /*背景音乐添加保存*/
    public function music_add_post(){
    	if($this->request->isPost()) {
			
			$data = $this->request->param();
			
   
			$data['addtime']=time();
			$data['upload_type']=1;
			$data['uploader']=get_current_admin_id(); //当前管理员id
			
			
			$img_url=$data['img_url'];
			$title=$data['title'];
			$author=$data['author'];
			$length=$data['length'];
			$use_nums=$data['use_nums'];

			if($title==""){
				$this->error($Think.\lang('FILL_IN_THE_MUSIC_NAME'));
			}

			// 判断该音乐是否存在
			$isexist=Db::name("user_music")
				->where(["title"=>$title])
				->find();

			if($isexist){
				$this->error($Think.\lang('MUSIC_ALREADY_EXISTS'));
			}

			if($author==""){
				$this->error($Think.\lang('FILL_IN_THE_SIGER'));
			}

			if($img_url==""){
				$this->error($Think.\lang('UPLOAD_MUSIC_COVER'));
			}

			if($length==""){
				$this->error($Think.\lang('FILL_IN_THE_MUSIC_DURATION'));
			}

			if(!strpos($length,":")){
				$this->error($Think.\lang('FILL_IN_MUSIC_DURATION_ACCORDING_TO_FORMAT'));
			}

			if(!is_numeric($use_nums)||$use_nums<0){
				$this->error($Think.\lang('USE_TIMES_INTEGER'));
			}

			$files["file"]=$_FILES["file"];
            $type='mp3';

            $uploadSetting = cmf_get_upload_setting();
            $extensions=$uploadSetting['file_types']['audio']['extensions'];
            $allow=explode(",",$extensions);

            if (!get_file_suffix($files['file']['name'],$allow)){
                $this->error($Think.\lang('UPLOAD_THE_AUDIO_IN_THE_CORRECT_FORMAT'));
            }
            
            $rs=adminUploadFiles($files,$type);
            if($rs['code']!=0){
                $this->error($rs['msg']);
            }


			$data['file_url']=$rs['filepath'];
			
			unset($data['file']);

			$result=Db::name("user_music")->insert($data);

			if($result){
				$this->success($Think.\lang('ADD_SUCCESS'),'admin/music/music_add',3);
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}

    	}

    }



    /*音乐试听*/
    public function music_listen(){
		
		$id = $this->request->param('id');
    	if(!$id||$id==""||!is_numeric($id)){
    		$this->error($Think.\lang('LOADING_FAILED'));
    	}else{
    		//获取音乐信息
    		$info=Db::name("user_music")->where("id={$id}")->find();
    		$this->assign("info",$info);
    	}

    	return $this->fetch();
    }

    /*音乐删除*/
    public function music_del(){
    	$id = $this->request->param('id');
    	if(!$id||$id==""||!is_numeric($id)){
    		$this->error($Think.\lang('OPERATION_FAILED'));
    	}else{
    		$count=Db::name("user_video")->where("music_id={$id}")->count();
    		if($count>0){
    			$result=Db::name("user_music")->where("id={$id}")->update(array("isdel"=>1));
    		}else{
    			$result=Db::name("user_music")->where("id={$id}")->delete();
    		}				
			if($result){
				$this->success($Think.\lang('DELETE_SUCCESS'));
			}else{
				$this->error($Think.\lang('DELETE_FAILED'));
			}
    	}
    }


    /*取消删除*/
    public function music_canceldel(){
    	$id = $this->request->param('id');
    	if(!$id||$id==""||!is_numeric($id)){
    		$this->error($Think.\lang('OPERATION_FAILED'));
    	}else{
    		$result=Db::name("user_music")->where("id={$id}")->update(array("isdel"=>0));				
			if($result){
				$this->success($Think.\lang('CANCELLATION_SUCCEEDED'));
			}else{
				$this->error($Think.\lang('CANCEL_FAILED'));
			}
    	}
    }

    /*音乐编辑*/
    public function music_edit(){

    	$id = $this->request->param('id');
		
    	if($id==""){
    		$this->error($Think.\lang('OPERATION_FAILED'));
    	}else{

    		$music=Db::name("user_music");
    		$info=$music->where("id={$id}")->find();
    		$this->assign("info",$info);

    		$classify=Db::name("user_music_classify")->order("orderno")->select();
    		$this->assign("classify",$classify);
    	}
    	return $this->fetch();
    }


    public function music_edit_post(){

    	if($this->request->isPost()) {
			
			$data = $this->request->param();

    		$music=Db::name("user_music");
			$data['updatetime']=time();
			
			

			$id=$data['id'];
			$img_url=$data['img_url'];
			$title=$data['title'];
			$author=$data['author'];
			$length=$data['length'];
			$use_nums=$data['use_nums'];
	

			if($title==""){
				$this->error($Think.\lang('FILL_IN_THE_MUSIC_NAME'));
			}

			//判断该音乐是否存在
			$isexist=Db::name("user_music")
				->where([['title','=',$title],['id','<>',$id]])
				->find();

			if($isexist){
				$this->error($Think.\lang('MUSIC_ALREADY_EXISTS'));
			}

			if($author==""){
				$this->error($Think.\lang('FILL_IN_THE_SIGER'));
			}

			if($img_url==""){
				$this->error($Think.\lang('UPLOAD_MUSIC_COVER'));
			}

			if($length==""){
				$this->error($Think.\lang('FILL_IN_THE_MUSIC_DURATION'));
			}

			if(!strpos($length,":")){
				$this->error($Think.\lang('FILL_IN_MUSIC_DURATION_ACCORDING_TO_FORMAT'));
			}

			if(!is_numeric($use_nums)||$use_nums<0){
				$this->error($Think.\lang('USE_TIMES_INTEGER'));
			}


			if($_FILES){
                $files["file"]=$_FILES["file"];
				$type='mp3';

				$uploadSetting = cmf_get_upload_setting();
	            $extensions=$uploadSetting['file_types']['audio']['extensions'];
	            $allow=explode(",",$extensions);

	            if (!get_file_suffix($files['file']['name'],$allow)){
	                $this->error($Think.\lang('UPLOAD_THE_AUDIO_IN_THE_CORRECT_FORMAT'));
	            }

				$rs=adminUploadFiles($files,$type);
                if($rs['code']!=0){
                    $this->error($rs['msg']);
                }
                $data['file_url']=$rs['filepath'];
				
			}

			unset($data['file']);
			
			$result=$music->update($data);

			if($result!==false){
				  $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
			 }else{
				  $this->error($Think.\lang('UPDATE_FAILED'));
			 }

    	}

    }


	

}
