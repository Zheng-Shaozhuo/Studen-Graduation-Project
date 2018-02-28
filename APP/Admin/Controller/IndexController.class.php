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
    	$where['adminName'] = I("post.usrname");
    	$where['adminPwd'] = md5(I("post.usrpwd"));

        $obj = M("admin");
        $info = $obj->field("adminRealName, state")->where($where)->find();
    	if(is_array($info) && isset($info)){
    		session("flag", true);
            session("NAME", $info['adminRealName']);
            session("state", $info['state']);

    		$this->redirect("Admin/index");
    	}else{
    		$this->error("账号或密码错误");
    	}
    }
}