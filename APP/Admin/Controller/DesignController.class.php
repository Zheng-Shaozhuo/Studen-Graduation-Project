<?php
    namespace Admin\Controller;
    use Think\Controller;
    class DesignController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:15:54
         *  后台管理中心-毕设列表
         */
        public function index($gpThrId = null, $gpContent = null, $state = null, $gpSHState = null){
            $titles = array();
            $titles['prt'] = "毕设";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "登录信息";
            $this->assign("titles", $titles);

            $where = array();
            $seachData['gpContent'] = $gpContent;
            if(isset($gpContent) && !empty($gpContent)){
                $where['gpContent'] = array("like", "%{$gpContent}%");
            }
            $seachData['state'] = $state;
            if(isset($state) && !empty($state)){
                switch ($state) {
                    case -1:
                        $where['gproject.state'] = 0;
                        break;
                    case 3:
                        $where['gproject.state'] = 3;
                        break;
                    default:
                        $where['gproject.state'] = array("in", array(-1, 1, 2));
                        break;
                }
            }
            $seachData['gpSHState'] = $gpSHState;
            if(isset($gpSHState) && !empty($gpSHState)){
                $where['gpSHState'] = $gpSHState;
            }
            $seachData['gpThrId'] = $gpThrId;
            if(isset($gpThrId) && !empty($gpThrId)){
                $where['thrRealName'] = $gpThrId;
            }
            $this->assign("seachData", $seachData);

            $obj    = M('gproject'); // 实例化User对象
            $count  = $obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $bsList = $obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("gproject.*, teacher.thrRealName")->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('bsList', $bsList);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            $this->display();
        }

        /*
         *  2015年3月8日22:16:09
         *  后台管理中心-回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "毕设";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "登录信息";
            $this->assign("titles", $titles);

            $obj = M("gproject");
            $bsList = $obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("gproject.*, teacher.thrRealName")->where(array("gproject.state" => -1))->select();
            $this->assign("bsList", $bsList);

            $this->display();
        }

        /*
         *  2015年3月18日17:34:47
         *  中心课题详情
         */
        public function checkGP($id = 0){
            if($id != 0){
                $where["gpId"] = $id;

                $obj = M("gproject");
                $GpDetail =$obj->join("left join teacher on gproject.gpThrId = teacher.thrId")->field("gproject.*, teacher.thrRealName")->where($where)->find();

                if(is_array($GpDetail) && !empty($GpDetail)){
                    echo json_encode(array("state" => true, "detail" => $GpDetail));
                }
            }else{
                echo json_encode(array("state" => false, "detail" => array()));
            }
        }


        /*
         *  2015年3月18日17:34:59
         *  中心删除课题
         */
        public function cycGP($id = 0){
            if($id != 0){
                $where["gpId"] = $id;

                $obj = M("gproject");
                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success("课题删除成功");
                }else{
                    $this->error("课题删除失败");
                }
            }else{
                $this->error("操作失败，请检查");
            }
        }

        /*
         *  2015年3月18日17:42:52
         *  将课题状态恢复
         */
        public function recoverOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('gproject');
                $where['gpId'] = $id;

                $data['state'] = 1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('恢复成功，已将该课题恢复至正常');
                }else{
                    $this->error('恢复失败，请检查');
                }
            }
        }

        /*
         *  2015年3月18日17:42:58
         *  将课题物理删除
         */
        public function clearOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('gp ');
                $where['gpId'] = $id;

                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success('清除成功成功');
                }else{
                    $this->error('清除失败，请检查');
                }
            }
        }

        /*
        *   2015年4月17日14:56:10
        *   管理员审核课题
        */
        public function SH($id = 0, $flag = 0){
            if($id == 0 || $flag == 0){
                $this->error('参数错误，请检查');
            }else{
                $where['gpId'] = $id;
                $data['state'] = $flag;

                $obj = M('gproject');
                if($obj->where($where)->save($data)){
                    $this->success('审核成功');
                }else{
                    $this->error('审核失败');
                }
            }
        }
    }
?>