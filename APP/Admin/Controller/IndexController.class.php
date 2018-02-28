<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->display("login");
    }

    /*
     *  2015年3月8日22:10:47
     *  用户登录处理
     */
    public function doLogin(){
    	$usrname = I("post.usrname");
    	$usrpwd = I("post.usrpwd");

    	if($usrname == "admin" && $usrpwd == "admin"){
    		session("flag", true);

    		$this->redirect("Admin/index");
    	}else{
    		$this->error("账号或密码错误");
    	}
    }
}