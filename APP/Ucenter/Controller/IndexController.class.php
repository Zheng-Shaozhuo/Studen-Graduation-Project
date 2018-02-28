<?php
namespace Ucenter\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('login');
    }

    public function doLogin(){
    	$flag = I("post.flag");
    	$usrname = I("post.usrname");
    	$usrpwd = I("post.usrpwd");
        session(null);

    	if($flag == "s"){
    	//学生登录
            $obj = M('student');
            $where['stuCard'] = $usrname;
            $where['stuPwd'] = md5($usrpwd);

            $usrInfo = $obj->field('stuId, stuRealName, stuSex')->where($where)->find();

    		if(isset($usrInfo) && is_array($usrInfo)){
    			session("FLAG", "student");
                session("NAME", $usrInfo['stuRealName']);
                session("ID", $usrInfo['stuId']);
                session("SEX", $usrInfo['stuSex']);

    			$this->redirect("Student/index");
    		}else{
    			$this->error("用户名或密码不正确");
    		}

    	}else if($flag == "t"){
    	//教师登录
            $obj = M('teacher');
            $where['thrName'] = $usrname;
            $where['thrPwd'] = md5($usrpwd);

            $usrInfo = $obj->field('thrId, thrRealName, thrSex')->where($where)->find();

    		if(isset($usrInfo) && is_array($usrInfo)){
                session("FLAG", "teacher");
                session("NAME", $usrInfo['thrRealName']);
                session("ID", $usrInfo['thrId']);
                session("SEX", $usrInfo['thrSex']);
    			
    			$this->redirect("Teacher/index");
    		}else{
    			$this->error("用户名或密码不正确");
    		}
    	}

    }
}