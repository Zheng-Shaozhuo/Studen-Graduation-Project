<?php
    namespace Admin\Controller;
    use Think\Controller;
    class AdminController extends BaseController {
        function __construct(){
            parent::__construct();
            layout("Public/tplLayout");

                $flag = array();
                $flag['prt'] = strtolower(CONTROLLER_NAME);
                $flag['son'] = strtolower(CONTROLLER_NAME) . '_' . strtolower(ACTION_NAME);
                $this->assign('flag', $flag);
        }

        /*
         *  2015年3月8日22:13:36
         *  后台管理中心-首页
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
            
            $titles = array();
            $titles['prt'] = null;
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "登录信息";
            $this->assign("titles", $titles);

            $countList['usr'] = M('admin')->Count();
            $countList['student'] = M('student')->Count();
            $countList['teacher'] = M('teacher')->Count();
            $countList['message'] = M('message')->Count();
            $countList['gp'] = M('gproject')->Count();
            $this->assign("CLists", $countList);

            $this->display();
        }

        /*
         *  2015年3月8日22:13:43
         *  后台管理中心-注销
         */
        public function loginout(){
            session(null);

            $this->redirect("Index/index");
        }
    }