<?php
    namespace Ucenter\Controller;
    use Think\Controller;
    class StudentController extends BaseController {
    	function __construct(){
    		parent::__construct();

    		layout('Public/tplSLayout');
	    	$this->assign('state', strtolower(ACTION_NAME));
    	}

        private function chkUsrInfo(){
            $where['stuId'] = session("ID");
            $info = M('student')->where($where)->find();

            if($info['stuRealName'] != null && $info['stuSex'] != null && $info['stuPhone'] != null && $info['stuEmail'] != null && $info['stuMajor'] != null){
                return true;
            }else{
                return false;
            }
        }

    	/*
    	 *	2015年3月8日19:12:29
    	 *	学生管理中心首页
    	 */
        public function index(){

            $where['showStu'] = 1;
            $id = session("ID");
            $where['_string'] = '(msgFromId = ' . $id . ' and state = 1) OR (msgAccessId = ' . $id . ' and state = -1) ';
            $this->assign("msgCount", M('message')->where($where)->Count() + M('message')->where(array('state' => 2))->Count());

            $this->assign('usrSex', session('SEX'));
            
        	$this->assign("title", "登录信息");
            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:22
    	 *	学生管理中心个人信息
    	 */
        public function person(){
        	$this->assign("title", "个人管理");

            $obj = M("student");
            $usrInfo = $obj->where(array("stuId" => session("ID")))->find();
            $this->assign("usrInfo", $usrInfo);

            $obj = M("major");
            $majorList = $obj->select();
            $this->assign("majorList", $majorList);

            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:36
    	 *	学生管理中心毕设列表
    	 */
        public function bslist(){
            if(!$this->chkUsrInfo()){
                $this->error("请先去完善个人信息，再进行其他操作", U('Student/person'));
                return false;
            }

        	$this->assign("title", "毕设列表");

            $obj = M("stlinks");
            $where['stlStuId'] = session("ID");
            $meGpList = $obj->join("left join gproject on stlinks.stlSpId = gproject.gpId")->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("stlinks.state as sstate, gproject.*, teacher.thrRealName")->where($where)->select();
            $this->assign("meGpList", $meGpList);

            $this->display();
        }

        /*
    	 *	2015年3月8日19:13:54
    	 *	学生管理中心毕设详情
    	 */
        public function detail(){
            if(!$this->chkUsrInfo()){
                $this->error("请先去完善个人信息，再进行其他操作", U('Student/person'));
                return false;
            }


        	$this->assign("title", "毕设详情");

            $obj = M('stlinks');
            $where['stlStuId'] = session("ID");
            $where['stlinks.state'] = 2;
            
            $GpDetail = $obj->join("left join gproject on stlinks.stlSpId = gproject.gpId")->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("gproject.*, thrRealName, thrPhone, thrEmail, thrAddress, thrStudy")->where($where)->find();
            if(!isset($GpDetail)){
                $this->error("当前未有课题被选定。。。", U('Student/bslist'));
            }
            $this->assign("meDetail", $GpDetail);

            $this->display();
        }

        /*
    	 *	2015年3月8日19:16:18
    	 *	学生管理中心消息管理
    	 */
        public function msg(){
            if(!$this->chkUsrInfo()){
                $this->error("请先去完善个人信息，再进行其他操作", U('Student/person'));
                return false;
            }

        	$this->assign("title", "消息管理");

            $obj = M('stlinks');
            $data = array();
            $data = $obj->join("left join teacher on stlinks.stlThrId = teacher.thrId")->field("thrId, thrRealName")->where(array('stlStuId' => session("ID"), 'stlinks.state' => 2))->find();
            $this->assign("ff", $data);

            unset($obj);
            $where['showStu'] = 1;
            $id = session("ID");
            $where['_string'] = '(msgFromId = ' . $id . ' and state = 1) OR (msgAccessId = ' . $id . ' and state = -1) ';

            $obj    = M('message'); // 实例化User对象
            $count  = $obj->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $List = $obj->field('msgId, msgFromId, msgAccessId, msgContent, createTime, state')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();

            $this->assign('List', $List);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            unset($where);
            $where['state'] = 2;
            $this->assign("adminList", $obj->field('msgId, msgFromId, msgAccessId, msgContent, createTime, state')->where($where)->select());

            $this->display();
        }

        /*
    	 *	2015年3月8日19:14:05
    	 *	学生管理中心毕设进度
    	 */
        public function plan(){
            if(!$this->chkUsrInfo()){
                $this->error("请先去完善个人信息，再进行其他操作", U('Student/person'));
                return false;
            }


        	$this->assign("title", "毕设进度");

            $obj = M('stlinks');
            $where['stlStuId'] = session("ID");
            $where['stlinks.state'] = 2;
            $count = $obj->join("left join gproject on stlinks.stlSpId = gproject.gpId")->join("left join teacher on gproject.gpThrId = teacher.thrId")->where($where)->Count();
            if($count == 0){
                $this->error("当前未有课题被选定。。。", U('Student/bslist'));
            }

            unset($where);
            $where['plnStuId'] = session("ID");
            $this->assign("info", M("plan")->where($where)->find());

            $this->display();
        }

        /*
    	 *	2015年3月8日19:14:19
    	 *	学生管理中心毕设选择
    	 */
        public function choose($GPName = null, $GPKey = null, $GPThrName = null, $GPState = null, $GPSH = null){
            if(!$this->chkUsrInfo()){
                $this->error("请先去完善个人信息，再进行其他操作", U('Student/person'));
                return false;
            }


            layout(false);
            $this->assign("title", "毕设选题");

            $where = array();
            $seachData['GPName'] = $GPName;
            if(isset($GPName) && !empty($GPName)){
                $where['gproject.gpTitle'] = array("like", "%{$GPName}%");
            }
            $seachData['GPKey'] = $GPKey;
            if(isset($GPKey) && !empty($GPKey)){
                $where['gproject.gpContent'] = array("like", "%{$GPKey}%");
            }
            $seachData['GPThrName'] = $GPThrName;
            if(isset($GPThrName) && !empty($GPThrName)){
                $where['teacher.thrId'] = $GPThrName;
            }

            $where['gproject.state'] = array("in", array(1, 2));
            $seachData['GPState'] = $GPState;
            if(isset($GPState) && !empty($GPState)){
                $where['gproject.state'] = $GPState == 1 ? array("in", array(1, 2)) : 3;
            }
            $seachData['GPSH'] = $GPSH;
            if(isset($GPSH) && !empty($GPSH)){
                $where['gproject.gpSHState'] = $GPSH;
            }
            $this->assign("seachData", $seachData);
            
            $obj    = M('gproject'); // 实例化User对象
            $count  = $obj->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $gpList = $obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("gproject.*, teacher.thrRealName")->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('gpList', $gpList);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
        	
            unset($where);
            $obj = M("stlinks");
            $where['stlStuId'] = session("ID");
            $choseIds = $obj->field("stlSpId")->where($where)->select();
            $this->assign("choseIds", $choseIds);

            unset($where);
            $where['stlStuId'] = session('ID');
            $where['state'] = 2;
            $this->assign('FF', M('stlinks')->where($where)->Count() == 1 ? true : false);
            
            $thrList = M('gproject')->join("left join teacher on gproject.gpThrId = teacher.thrId")->distinct(true)->field('gpThrId, thrRealName')->where(array('gproject.state' => array('in', array(1, 2))))->select();
            $this->assign("thrList", $thrList);
            
            $this->display();
        }

        /*
         *  2015年3月22日12:31:05
         *  学生管理学生数据更新
         */
        public function modifyInfo(){
            if(IS_POST){
                $where['stuId'] = I("post.usr_id");

                $Pwd = I("post.pwd");
                $newPwd = I("post.newpwd");
                if($Pwd != $newPwd){
                    $data['stuPwd'] = md5($newPwd);
                    $where['stuPwd'] = md5($Pwd);
                }

                $data['stuRealName'] = I("post.realName");
                $data['stuAge'] = I("post.age");
                $data['stuSex'] = I("post.sex");
                $data['stuPhone'] = I("post.phone");
                $data['stuEmail'] = I("post.email");
                $data['stuMajor'] = I("post.usr_lvl");
                $data['updateTime'] = time();

                $obj = M("student");
                if($obj->where($where)->save($data)){
                    $this->success("用户信息修改成功, 请重新登陆", U('Student/loginout'));
                }else{
                    $this->error("用户信息修改失败，请检查");
                }
            }
        }

        /*
         *  2015年3月22日13:29:31
         *  学生管理课题详情
         */
        public function checkGPDetail($id = 0){
            if($id != 0){
                $where["gpId"] = $id;

                $obj = M("gproject");
                $field = "gproject.*, thrRealName, showState, thrStudy, thrPhone, thrEmail, thrAddress";
                $GpDetail =$obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->field($field)->where($where)->find();

                if($GpDetail['showState'][0] == 0){
                    $GpDetail['thrPhone'] = "不公布";
                }
                if($GpDetail['showState'][1] == 0){
                    $GpDetail['thrEmail'] = "不公布";
                }
                if($GpDetail['showState'][2] == 0){
                    $GpDetail['thrAddress'] = "不公布";
                }
                if($GpDetail['showState'][3] == 0){
                    $GpDetail['thrStudy'] = "不公布";
                }

                if(is_array($GpDetail) && !empty($GpDetail)){
                    echo json_encode(array("state" => true, "detail" => $GpDetail));
                }
            }else{
                echo json_encode(array("state" => false, "detail" => array()));
            }
        }

        /*
         *  2015年3月22日13:54:13
         *  学生管理课题选定
         */
        public function chooseGP($id = 0, $thrid = 0){
            if($id >= 0 && $thrid >= 0){
                $obj = M("stlinks");
                if($obj->where(array('stlStuId' => session('ID')))->Count() > 6){
                    $this->error('亲，课题列表包裹上限为 6， 请先去减轻负重');
                    return false;
                }


                $data['stlStuId'] = session("ID");
                $data['stlThrId'] = $thrid;
                $data['stlSpId'] = $id;
                $data['state'] = 1;
                $time = time();
                $data['createTime'] = $time;
                $data['updateTime'] = $time;

                if($obj->add($data) && M("gproject")->where(array('gpId' => $id))->save(array("state" => 2))){
                    $this->success("该课题选择成功，等待确认...");
                }else{  
                    $this->error("该课题选择失败，请检查");
                }

            }else{  
                $this->error("操作参数错误，请检查");
            }
        }

        /*
         *  2015年3月8日20:28:12
         *  学生管理中心注销
         */
        public function loginout(){
            session(null);
            $this->redirect("Index/index");
        }

        /*
         * 2015年4月17日16:56:09
         * 学生退选
         */
        public function tuixuan($id = 0){
            if($id == 0 || intval($id) <= 0){
                $this->error('参数错误，请检查');
            }

            $obj = M("stlinks");
            $where['stlSpId'] = $id;
            $where['stlStuId'] = session("ID");

            $flag = true;
            $obj->startTrans();
            if(!$obj->where($where)->delete()){
                $flag = false;
            }
            if(!($obj->where(array('stlSpId' => $id))->Count() == 0 && M("gproject")->where(array("gpId" => $id))->save(array("state" => 1)))){
                $flag = false;
            }

            if($flag){
                $obj->commit();
                $this->success("课题退选成功");
            }else{
                $obj->rollback();
                $this->success("课题退选失败，请检查");
            }
        }

        /*
         * 2015年4月17日17:18:53
         * 无用课题删除
         */
        public function shanchu($id = 0){
            if($id == 0 || intval($id) <= 0){
                $this->error('参数错误，请检查');
            }

            $obj = M("stlinks");
            $where['stlSpId'] = $id;
            $where['stlStuId'] = session("ID");

            if($obj->where($where)->delete()){
                $this->success("课题删除成功");
            }else{
                $this->success("课题删除失败，请检查");
            }
        }

        /*
         *  2015年4月18日14:43:00
         *  新增消息
         */
        public function addMsg(){
            if(IS_POST){
                $data['msgParentId'] = 0;
                $data['msgFromId'] = session("ID");
                $data['msgAccessId'] = I("post.receive");
                $data['msgContent'] = I("post.content");
                $data['createTime'] = time();
                $data['state'] = 1;
                $data['showStu'] = 1;
                $data['showThr'] = 1;

                $obj = M('message');
                if($obj->add($data)){
                    $this->success("消息发送成功");
                }else{
                    $this->error("消息发送失败");
                }
            }
        }

        /*
         *  2015年4月18日15:51:17
         *  删除消息 学生操作
         */
        public function delMsg($id = 0){
            if($id == 0 || intval($id) <= 0){
                $this->error("参数错误，请检查");
                return false;
            }

            $obj = M('message');
            if($obj->where(array('msgId' => $id))->save(array('showStu' => -1))){
                $this->success("消息删除成功");
            }else{
                $this->error("消息删除失败");
            }

        }

        /*
         *  2015年4月19日15:39:01
         *  学生添加进度
         */
        public function addPlan(){
            if(IS_POST){
                $obj = M('stlinks');
                $where['stlStuId'] = session("ID");
                $where['stlinks.state'] = 2;
                
                $dd = $obj->field("stlStuId, stlThrId, stlSpId")->where($where)->find();
                if(!isset($dd)){
                    $this->error("当前未有课题被选定。。。", U('Student/bslist'));
                }

                $data = array();
                $data['plnStuId'] = $dd['stlStuId'];
                $data['plnThrId'] = $dd['stlThrId'];
                $data['plnGpId'] = $dd['stlSpId'];

                $data['endtime1'] = I("post.endtime1") == "____/__/__ __:__" ? "" : I("post.endtime1");
                $data['title1'] = I("post.title1");
                $data['other1'] = I("post.other1");
                $data['endtime2'] = I("post.endtime2") == "____/__/__ __:__" ? "" : I("post.endtime2");
                $data['title2'] = I("post.title2");
                $data['other2'] = I("post.other2");
                $data['endtime3'] = I("post.endtime3") == "____/__/__ __:__" ? "" : I("post.endtime3");
                $data['title3'] = I("post.title3");
                $data['other3'] = I("post.other3");
                $data['endtime4'] = I("post.endtime4") == "____/__/__ __:__" ? "" : I("post.endtime4");
                $data['title4'] = I("post.title4");
                $data['other4'] = I("post.other4");
                $data['endtime5'] = I("post.endtime5") == "____/__/__ __:__" ? "" : I("post.endtime5");
                $data['title5'] = I("post.title5");
                $data['other5'] = I("post.other5");
                $data['endtime6'] = I("post.endtime6") == "____/__/__ __:__" ? "" : I("post.endtime6");
                $data['title6'] = I("post.title6");
                $data['other6'] = I("post.other6");
                $data['endtime7'] = I("post.endtime7") == "____/__/__ __:__" ? "" : I("post.endtime7");
                $data['title7'] = I("post.title7");
                $data['other7'] = I("post.other7");
                $data['flag'] = 1;
                $data['createTime'] = time();

                $obj = M('plan');
                if($obj->add($data)){
                    $this->success("进度节点添加成功", U('Student/plan'));
                }else{
                    $this->error("进度节点添加失败，请检查");
                }
            }
        }

    }
?>