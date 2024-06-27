<?php

/**
 * 极光推送
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;



class PushmessageController extends AdminbaseController {

	/*推送发送*/
	public function add(){

		return $this->fetch();
	}

	public function add_post(){
		$rs=array("code"=>0,"msg"=>"","info"=>array());
		
		$data = $this->request->param();

		$title=$data["title"];
		$synopsis=$data["synopsis"];
		$msg_type=$data["msg_type"];
		$content=$data["content"];
		$url=$data["url"];

		if($title==""){
			$rs['code']=1001;
			$rs['msg']=$Think.\lang('ADMIN_PUSHMESSAGE_QINGTIANXIEBIAOTI');
			echo json_encode($rs);
			exit;
		}

		if($synopsis==""){
			$rs['code']=1001;
			$rs['msg']=$Think.\lang('ADMIN_PUSHMESSAGE_QINGTIANXIEJIANJIE');
			echo json_encode($rs);
			exit;
		}

		if($msg_type==2&&$url==""){
			$rs['code']=1002;
			$rs['msg']=$Think.\lang('ADMIN_PUSHMESSAGE_QINGTIANXIELIANJIEDIZHIE');
			echo json_encode($rs);
			exit;
		}

		$id=get_current_admin_id();
		$user=Db::name("user")
			->where("id='{$id}'")
			->find();
			
		$info=array("title"=>$title,"synopsis"=>$synopsis,"type"=>$msg_type,"content"=>htmlspecialchars_decode($content),"url"=>$url,"admin"=>$user['user_login'],"addtime"=>time(),"ip"=>$_SERVER['REMOTE_ADDR']);

		$result=Db::name("admin_push")
			->insert($info);

		if($result!==false){
			$rs['info']['id']=$result;
			$rs['info']['count']=Db::name("user")->where("user_type=2 and user_status=1")->count();

			echo json_encode($rs);
			exit;
		}else{
			$rs['code']=1002;
			$rs['msg']=$Think.\lang('PUSH_FAILED');
			echo json_encode($rs);

		}


	}

	/*推送记录*/
	public function index(){

		$lists=Db::name("admin_push")
            ->where(function (Query $query) {
                $data = $this->request->param();
                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                if (!empty($keyword)) {
                    $query->where('title', 'like', "%$keyword%");
                }

            })
            ->order("addtime desc")
            ->paginate(20);

		//var_dump($push->getLastSql());
		
		//获取私密配置信息
		/*$config=getConfigPri();
		$timestamp=time();
		$random_str="022cd9fd995849b58b3ef0e943421ed9";
		$signature = md5("appkey={$config['jpush_key']}&timestamp={timestamp}&random_str={$random_str}&key={$config['jpush_secret']}");
*/		
		//获取当前用户总数
		$count=Db::name("user")->where("user_type=2 and user_status=1")->count();
		
    	$this->assign('lists', $lists);
    	$this->assign('page', $page);
    	$this->assign("count", $count);
    	
    	/*$this->assign("jpush_key",$config['jpush_key']);
    	$this->assign("random_str",$random_str);
    	$this->assign("signature",$signature);
    	$this->assign("timestamp",$timestamp);*/
		
    	return $this->fetch();
	}

	/*将原来的信息重新获取一份新加入数据库*/
	public function push_add(){

		$res=array("code"=>0,"msg"=>"","info"=>array());

		$id=$this->request->param("id");
		if($id==""){
			$res['code']=1001;
			$res['msg']=$Think.\lang('DATA_TRANSFER_IN_FAILED');
			echo json_encode($res);
			exit;
		}

		//判断id信息是否存在
		$info=Db::name("admin_push")->where("id={$id}")->find();
		if(!$info){
			$res['code']=1001;
			$res['msg']=$Think.\lang('DATA_TRANSFER_IN_FAILED');
			echo json_encode($res);
			exit;
		}

		unset($info['id']);
		$info['addtime']=time();
		$result=Db::name("admin_push")->add($info);
		
		if($result==false){
			$res['code']=1001;
			$res['msg']=$Think.\lang('FAILED_TO_WRITE_DATA');
			echo json_encode($res);
			exit;
		}

		//获取当前用户的总数
		/*$count=Db::name("user")->where("user_type=2 and user_status=1")->count();
		$res['info']=$count;*/

		echo json_encode($res);

	}


	public function push(){
		
		$res=array("code"=>0,"msg"=>"","info"=>array());

		$data=$this->request->param();

		$id=$data['msgid'];
		$lastid=$data['lastid'];
		$num=$data['num'];

		if($id==""){
			$res['code']=1001;
			$res['msg']=$Think.\lang('DATA_TRANSFER_IN_FAILED')+"1";
			echo json_encode($res);
			exit;
		}

		//判断id信息是否存在
		$info=Db::name("admin_push")->where("id={$id}")->find();
		if(!$info){
			$res['code']=1001;
			$res['msg']=$Think.\lang('DATA_TRANSFER_IN_FAILED')+"2";
			echo json_encode($res);
			exit;
		}


		//获取后台配置的极光推送app_key和master_secret
		$configPri=getConfigPri();
		$appKey = $configPri['jpush_key'];
		$masterSecret =  $configPri['jpush_secret'];

		if($appKey&&$masterSecret){
			//极光IM			
			require_once CMF_ROOT.'sdk/jmessage/autoload.php'; //导入极光IM类库

			$jm = new \JMessage\JMessage($appKey, $masterSecret);
			//注册管理员
			$admin = new \JMessage\IM\Admin($jm);

			
			$regInfo = [
			    'username' => 'dsp_admin_1',
			    'password' => 'dsp_admin_1',
			    'nickname'=>'视频官方'
			];

			$response = $admin->register($regInfo);
			//var_dump($response['body']);
			if($response['body']==""||$response['body']['error']['code']==899001){ //新管理员注册成功或管理员已经存在
				//发布消息
				$message = new \JMessage\IM\Message($jm);
				$user = new \JMessage\IM\User($jm);
				$before=userSendBefore(); //获取极光用户账号前缀

				$from = [
				    'id'   => 'dsp_admin_1', //短视频系统规定视频官方必须是该账号（与APP保持一致）
				    'type' => 'admin'
				];

				$msg = [
				   'text' => $info['title']
				];

				$notification =[
					'notifiable'=>false  //是否在通知栏展示
				];

				$prefix=config('database.prefix');

				//查找系统所有用户
				$userlists=Db::name("user")
					->query("select id from ".$prefix."user where user_type=2 and user_status=1 and id>{$lastid} order by id asc limit {$num}");

				//file_put_contents("userend.txt", "时间：".date("Y-m-d:H:i:s",time()).PHP_EOL,FILE_APPEND);//换行追加

				foreach ($userlists as $k => $v) {
					/*$target=[];
					$userinfo=$user->show($v['id']); //获取用户信息

					if($userinfo['body']['error']['code']==899002){  //极光用户不存在
						continue;
					}*/

					$target = [
					    'id'   => $before.$v['id'],
					    'type' => 'single'
					];

					$response = $message->sendText(1, $from, $target, $msg,$notification,[]);  //最后一个参数代表其他选项数组，主要是配置消息是否离线存储，默认为true

					//file_put_contents("userend.txt", "时间：".date("Y-m-d:H:i:s",time()).PHP_EOL,FILE_APPEND);//换行追加

					$lastid=$v["id"];
				}

				//file_put_contents("userend.txt", "时间：".date("Y-m-d:H:i:s",time()).PHP_EOL.PHP_EOL,FILE_APPEND);//换行追加

				/*$target = [
				    'id'   => '12220',
				    'type' => 'single'
				];

				$response = $message->sendText(1, $from, $target, $msg,$notification,[]);  //最后一个参数代表其他选项数组，主要是配置消息是否离线存储，默认为true*/
				
				//file_put_contents("a.txt", $lastid."时间：".date("Y-m-d:H:i:s",time()).PHP_EOL,FILE_APPEND);//换行追加

				$res['msg']=$Think.\lang('MESSAGE_PUSH_SUCCEEDED');
				$res['info']=$lastid;

			}else{
				$res['code']=1001;
				$res['msg']=$Think.\lang('ADMIN_PUSHMESSAGE_XIAOXITUISONGSHIBAI');
			}

			echo json_encode($res);
			exit;
				
		}else{

			$res['code']=1001;
			$res['msg']=$Think.\lang('ADMIN_PUSHMESSAGE_XIAOXITUISONGSHIBAI');
			echo json_encode($res);
			exit;
		}

		
	}

	public function del(){

		$id=$this->request->param("id");
		if($id==""){
			$this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
			exit;
		}

		$result=Db::name("admin_push")->where("id={$id}")->delete();
		if($result!==false){
			$this->success($Think.\lang('DELETE_SUCCESS'));
		}else{
			$this->error($Think.\lang('DELETE_FAILED'));
		}
	}

	public function push_return(){
		$res['code']=0;
		$res['msg']="";
		echo json_encode($res);
	}

	

}
