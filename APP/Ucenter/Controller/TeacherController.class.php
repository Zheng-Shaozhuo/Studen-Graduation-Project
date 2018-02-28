<?php
    namespace Ucenter\Controller;
    use Think\Controller;
    class TeacherController extends BaseController {
    	function __construct(){
    		parent::__construct();

    		layout('Public/tplTLayout');
	    	$this->assign('state', strtolower(ACTION_NAME));
    	}

    	/*
    	 *	2015年3月8日19:43:41
    	 *	教师管理中心首页
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

            $this->assign('usrSex', session('SEX'));
            
        	$this->assign("title", "登录信息");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:43:51
    	 *	教师管理中心个人信息
    	 */
        public function person(){
        	$this->assign("title", "个人管理");

            $obj = M('teacher');
            $usrDetail = $obj->where(array('thrId' => session('ID')))->find();
            $this->assign('usrDetail', $usrDetail);

            $this->display();
        }

        /*
    	 *	2015年3月8日19:44:04
    	 *	教师管理中心毕设列表
    	 */
        public function bslist(){
        	$this->assign("title", "毕设列表");

            $obj = M("gproject");
            $bsList = $obj->field("gpId, gpTitle, gpContent, gpMust")->select();
            $this->assign('bsList', $bslist);

            $this->display();
        }

        /*
    	 *	2015年3月8日19:45:05
    	 *	教师管理中心新增毕设
    	 */
        public function add(){
        	$this->assign("title", "新增毕设");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:44:21
    	 *	教师管理中心消息管理
    	 */
        public function msg(){
        	$this->assign("title", "消息管理");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:44:33
    	 *	教师管理中心毕设进度
    	 */
        public function plan(){
        	$this->assign("title", "毕设进度");
            $this->display();
        }   

        /*
         *  2015年3月11日12:17:57 
         *  教师管理中心个人信息修改
         */
        public function modifyInfo(){
            if(IS_POST){
                $oldPwd = I("post.oldpwd");
                $newPwd = I("post.newpwd");
                $data['thrRealName'] = I("post.realName");
                $data['thrAge'] = I("post.age");
                $data['thrSex'] = I("post.sex");
                $data['thrPhone'] = I("post.Phone");
                $data['thrEmail'] = I("post.Email");
                $data['thrAddress'] = I("post.Address");
                $data['thrStudy'] = I("post.Study");
                $data['updateTime'] = time();

                $tPhone = I("post.chkPhone");
                $tEmail = I("post.chkEmail");
                $tAddress = I("post.chkAddress");
                $tStudy = I("post.chkStudy");
                $data['showState'] = ($tPhone != "" ? $tPhone : "0") . ($tEmail != "" ? $tEmail : "0") . ($tAddress != "" ? $tAddress : "0") . ($tStudy != "" ? $tStudy : "0");

                $where['thrId'] = I('post.usrId');
                if($oldpwd != $newpwd){
                    $data['thrPwd'] = md5($newpwd);
                    $where['thrPwd'] = $oldpwd;
                }
                // var_dump($data);var_dump($where);exit;
                $obj = M("teacher");
                if($obj->where($where)->save($data)){
                    $this->success("用户信息修改成功");
                }else{
                    $this->error("用户信息修改失败，请检查");
                }
            }
        }

        /*
         *  2015年3月11日15:05:24
         *  教师管理中心新增毕设
         */
        public function addDesign(){
            if(IS_POST){
                $data['gpThrId'] = session('ID');
                $data['gpTitle'] = I("post.title");
                $data['gpContent'] = I("post.content");
                $data['gpAim'] = I("post.aim");
                $data['gpRequest'] = I("post.request");
                $data['gpMust'] = I("post.must");
                $data['gpFormal'] = I("post.formal");
                $data['gpOthers'] = I("post.other");
                $data['state'] = 1;

                $time = time();
                $data['updateTime'] = $time;
                $data['createTime'] = $time;

                $obj = M("gproject");
                if($obj->add($data)){
                    $this->success("课题添加成功");
                }else{
                    $this->error("课题添加失败，请检查");
                }
            }
        }

    }