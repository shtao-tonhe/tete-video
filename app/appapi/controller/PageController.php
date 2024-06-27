<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;


class PageController extends HomebaseController{


	//文章
	public function news() {
		$id = $this->request->param('id',0,'intval');
		$news=Db::name("posts")
			->field("post_title,post_content")
			->where("id='{$id}'")->find();

		$this->assign("news",$news);
		 
		return $this->fetch();
	}

	//读取常见问题下的文章列表
	public function questions(){

		$questionList=Db::name("posts")
			->field("id,post_title")
			->where("termid=13")
			->select();
		$questionList->all();	
		
		$time=time();

		$this->assign("questionList",$questionList);
		$this->assign("time",$time);
		return $this->fetch();
	}		
	
}