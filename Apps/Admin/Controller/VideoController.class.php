<?php
namespace Admin\Controller;
use Think\Controller;
class VideoController extends BaseController {
    public function index(){
        $model = M('Video');
        $typemodel = M('ChanpinType');
        $info = $model->select();
        $i=0;
        foreach ($info as $v){
            $info[$i]['content'] = htmlspecialchars($v['content']);
            $typename = $typemodel->where(array('id'=>$v['type']))->find();
            $info[$i]['type'] = $typename['name'];
            $i++;
        }
        $this->assign('list',$info);
        $this->display();
    }
    public function addchanpin(){
        $model = D('ChanpinType');
        $tourContent = $model->getAdminCate();
        $data['title_img'] = I('title_img');
        if (IS_POST) {
          if ($_FILES['title_img']['tmp_name'] !='') {

              $upload = new \Think\Upload();
              $upload->maxSize = 3145728;
              $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
              $upload ->rootPath = './';
              $upload->savePath = './Public/Uploads/';
              $info = $upload->uploadOne($_FILES['title_img']);
              if(!$info) {
                   $this->error($upload->getError());
              } else {
                  $data['title_img'] = $info['savepath'].$info['savename'];
              }
          }
        }
        $this->assign('list', $tourContent);// 赋值数据集
        $this->display();
    }
    public function edit(){
        $model = M('Video');
        $id = I('get.id');
        $info = $model->where(array('id'=>$id))->find();
        $model = D('ChanpinType');
        $tourContent = $model->getAdminCate();
        $this->assign('list', $tourContent);// 赋值数据集
        $info['content'] = html_entity_decode($info['content']);
        $info['en_content'] = html_entity_decode($info['en_content']);
        $this->assign('info',$info);
        $this->display();
    }
    public function doedit(){
        $info = I('post.');
        $model = M('Video');
        $id = $info['info_id'];
        if($_FILES['image']['name']==''){

        }else{
            $data = $this->uppic();
            $info['title_img']=$data['savepath'].$data['savename'];
        }
        if($model->where(array('id'=>$id))->save($info)){
            $this->success('数据修改成功',U('Admin/Video/index'));
        }
    }
    public function del(){
        $id = I('get.id');
        $model = M('Video');
        if($model->where(array('id'=>$id))->delete()){
            $this->success('删除分类成功');
        }
    }
    public function type(){
        $model = D('ChanpinType');
        $tourContent = $model->getAdminCate();
        $this->assign('list', $tourContent);// 赋值数据集
        $this->display();
    }
    public function edittype(){

    }
    public function deltype(){
        $id = I('get.id');
        $model = M('ChanpinType');
        if($model->where(array('id'=>$id))->delete()){
            $this->success('删除分类成功');
        }
    }
    public function addtype(){
        $data = I('post.');
        $model = M('ChanpinType');
        $add = $model->create($data);
        if($model->add($add)){
            $this->success('产品分类添加成功');
        }
    }
    public function doadd(){
        $info = I('post.');
        $model = M('Video');
        $addinfo = $model->create($info);
        if($model->add($addinfo)){
            $this->success('添加成功',U('Admin/video/index'));
        }
    }

    private function uppic(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = 'Public/'; // 设置附件上传根目录
        $upload->savePath = 'upload/'; // 设置附件上传(子)目录
// 上传文件
        return $upload -> uploadOne($_FILES['image']);
    }

}
