<?php
    namespace Ucenter\Controller;
    use Think\Controller;
    class StudentController extends BaseController {
    	function __construct(){
    		parent::__construct();

    		layout('Public/tplSLayout');
	    	$this->assign('state', strtolower(ACTION_NAME));
    	}

    	/*
    	 *	2015年3月8日19:12:29
    	 *	学生管理中心首页
    	 */
        public function index(){
            $Data = array();
            $Data['os']         = php_uname('s');
            $Data['web_os'] = $_SERVER["SERVER_NAME"];
            $Data['php_version']    = PHP_VERSION;
            $Data['ip']     = GetHostByName($_SERVER['SERVER_NAME']);
            $Data['language']   = 'PHP';
            $Data['db_os']      = 'MYSQL';
            $Data['db_version'] = mysql_get_server_info();
            $Data['domainname'] = $_SERVER["HTTP_HOST"];
            $this->assign('system', $Data);
            
        	$this->assign("title", "登录信息");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:22
    	 *	学生管理中心个人信息
    	 */
        public function person(){
        	$this->assign("title", "个人管理");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:36
    	 *	学生管理中心毕设列表
    	 */
        public function bslist(){
        	$this->assign("title", "毕设列表");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:54
    	 *	学生管理中心毕设详情
    	 */
        public function detail(){
        	$this->assign("title", "毕设详情");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:16:18
    	 *	学生管理中心消息管理
    	 */
        public function msg(){
        	$this->assign("title", "消息管理");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:14:05
    	 *	学生管理中心毕设进度
    	 */
        public function plan(){
        	$this->assign("title", "毕设进度");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:14:19
    	 *	学生管理中心毕设选择
    	 */
        public function choose(){
        	layout(false);
        	$this->assign("title", "毕设选题");
            $this->display();
        }

        /*
         *  2015年3月8日20:28:12
         *  学生管理中心注销
         */
        public function loginout(){
            session(null);
            $this->redirect("Index/index");
        }

    }