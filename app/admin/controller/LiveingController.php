<?php

/**
 * 禁播列表
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class LiveingController extends AdminbaseController {

     protected function getLiveClass(){

        $liveclass=Db::name("live_class")->order('list_order asc, id desc')->column('id,name');

        return $liveclass;
    }

    function index(){

        $lists = Db::name('user_live')
            ->where(function (Query $query) {
                $data = $this->request->param();

                $query->where('islive','eq','1');
                
                $start_time=isset($data['start_time']) ? $data['start_time']: '';
                $end_time=isset($data['end_time']) ? $data['end_time']: '';

                if (!empty($start_time)) {
                    $query->where('starttime', 'gt' , strtotime($start_time));
                }
                if (!empty($end_time)) {
                    $query->where('starttime', 'lt' ,strtotime($end_time));
                }
                
                if (!empty($start_time) && !empty($end_time)) {
                    $query->where('starttime', 'between' , [strtotime($start_time),strtotime($end_time)]);
                }

                $keyword=isset($data['keyword']) ? $data['keyword']: '';
                
                if (!empty($keyword)) {
                    $query->where('uid', 'like', "%$keyword%");
                }
            })
            ->order("starttime DESC")
            ->paginate(20);

        $lists->each(function($v,$k){
            $userinfo=getUserInfo($v['uid']);
            $v['userinfo']=$userinfo;
            $where=[];
            $where['action']='sendgift';
            $where['touid']=$v['uid'];
            $where['showid']=$v['showid'];

            /* 本场总收益 */
            $totalcoin=Db::name("user_coinrecord")->where($where)->sum('totalcoin');
            if(!$totalcoin){
                $totalcoin=0;
            }

            /* 送礼物总人数 */
            $total_nums=Db::name("user_coinrecord")->where($where)->group("uid")->count();
            if(!$total_nums){
                $total_nums=0;
            }

            /* 人均 */
            $total_average=0;
            if($totalcoin && $total_nums){
                $total_average=round($totalcoin/$total_nums,2);
            }

            /* 人数 */
            $nums=zSize('user_'.$v['stream']);

            $v['totalcoin']=$totalcoin;
            $v['total_nums']=$total_nums;
            $v['total_average']=$total_average;
            $v['nums']=$nums;

            if($v['isvideo']==0){
                $v['pull']=PrivateKeyA('rtmp',$v['stream'],0);
            }

            return $v;
            
        });

        //分页-->筛选条件参数
        $data = $this->request->param();
        $lists->appends($data); 
            
        // 获取分页显示
        $page = $lists->render();

        $config=getConfigPub();

        $liveclass=$this->getLiveClass();
        $liveclass[0]=$Think.\lang('ADMIN_LIVEING_ADDSP_MORENG_FENLEI');
			
    	$this->assign('lists', $lists);
    	$this->assign("page", $page);
    	$this->assign('config', $config);
        $this->assign("liveclass", $liveclass);

    	return $this->fetch();
    }

    public function add(){
        $this->assign("liveclass", $this->getLiveClass());

        return $this->fetch();
    }

    //添加保存
    public function add_post(){
        if($this->request->ispost()){
            $data=$this->request->param();
            $uid=$data['uid'];
            $pull=urldecode($data['pull']);
            $liveclassid=$data['liveclassid'];

            $userinfo=getUserInfo($uid);
            if(!$userinfo){
                $this->error($Think.\lang('USER_NOT_EXIST'));
            }

            $nowtime=time();

            if($userinfo['recommend_time']==0){
                $userinfo['recommend_time']=$nowtime;
            }

            $liveinfo=Db::name("user_live")->field("uid,islive")->where(['uid'=>$uid])->find();
            if($liveinfo){
                $this->error($Think.\lang('USER_BROADCASTING_LIVE'));
            }

            
            $stream=$uid.'_'.$nowtime;
            $data=array(
                "uid"=>$uid,        
                "showid"=>$nowtime,
                "starttime"=>$nowtime,
                "title"=>'',
                "province"=>'',
                "city"=>$Think.\lang('LIKE_MARS'),
                "stream"=>$stream,
                "thumb"=>'',
                "pull"=>$pull,
                "isvideo"=>1,
                "islive"=>1,
                "isrecommend"=>$userinfo['isrecommend'],
                "recommend_time"=>$userinfo['recommend_time'],
                "liveclassid"=>$liveclassid,

            );

            if($liveinfo){
                $result=Db::name("user_live")->where(['uid'=>$uid])->update($data);
            }else{
                $result=Db::name("user_live")->insert($data);
            }

            if($result!==false){
                  $this->success($Think.\lang('ADD_SUCCESS'));
             }else{
                  $this->error($Think.\lang('ADD_FAILED'));
             }
        }
    }

    public function edit(){
        $uid=$this->request->param('uid');
        if($uid){
            $live=Db::name("user_live")->where("uid={$uid}")->find();
            $this->assign('live', $live);

        }else{
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }

        $this->assign("liveclass", $this->getLiveClass());

        return $this->fetch();
    }

    public function edit_post(){
       if($this->request->ispost()){
            $data=$this->request->param();
            $pull=$data['pull']; 
            if($pull==''){
                $this->error($Think.\lang('FILL_IN_THE_VIDEO_ADDRESS'));
            }

            $uid=$data['uid'];
            $result=Db::name("user_live")->where(['uid'=>$uid])->update($data);
            if($result!==false){
                  $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
             }else{
                  $this->error($Think.\lang('UPDATE_FAILED'));
             }
       } 
    }
		
	public function del(){

        $id=$this->request->param('id',0,'intval');
        if($id){
            $result=Db::name("user_live")->where(["uid"=>$id])->delete();				
            if($result){
                $this->success($Think.\lang('DELETE_SUCCESS'));
            }else{
                $this->error($Think.\lang('DELETE_FAILED'));
            }			
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  			
	}		


    
}
