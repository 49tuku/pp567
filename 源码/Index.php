<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\logic\IndexLogic;
use think\Db;
use think\Request;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $lotteryType = $this->request->param('lt', 1);
        if($lotteryType == 1){
            $vstr = '澳门星星彩';
            $this->assign('tongzhi', '重要通知：'.$vstr.'21:05开奖');
        }else{
            $vstr = '澳门豆豆彩';
            $this->assign('tongzhi', '重要通知：'.$vstr.'22:05开奖');
        }
        $this->assign('vstr', $vstr);
        $this->assign('lotteryType', $lotteryType);
    }

    //{$val.ju|bg=###,'#FF0000',''}
    //{include file="index:ydjj"}
    public function index()
    {
        $lotteryType = $this->request->param('lt', 1);
        $this->assign('page', IndexLogic::getPage());
        $this->assign('data', IndexLogic::getdata($lotteryType));
        $this->assign('alert', Db::name('alert')->where(['status'=>1])->order('id desc')->find());
        $view = 'am';
        if(session('tag') == 'hk'){
        }
        $this->assign('tag', $view);
        $html = $this->view->fetch($view);
        //file_put_contents(ROOT_PATH.'/public/index.html', $html);
        return $html;
    }

    public function zoushi()
    {
        $data = IndexLogic::zoushi();
        $this->assign('data', $data);
        $html = $this->view->fetch('zsaomen');
        file_put_contents(ROOT_PATH.'/public/six_cai/attr.html', $html);
        return $html;
    }

    public function zoushixg()
    {
        $data = IndexLogic::zoushixg();
        $this->assign('data', $data);
        $html = $this->view->fetch('zsaomen');
        file_put_contents(ROOT_PATH.'/public/six_cai/attr2.html', $html);
        return $html;
    }


    public function gst(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_hkgst')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_hkgst')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        //$this->assign('page', IndexLogic::getPage());
        $this->assign('pname', '高手帖');
        //$data['apps'] = Db::name('appdown')->where(['status'=>1])->find();
        //$this->assign('data', $data);
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/xggst/'.$id.'.html', $html);
        return $html;
    }

    public function amgst(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_amgst')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_amgst')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $this->assign('pname', '高手榜');
        $data['apps'] = Db::name('appdown')->where(['status'=>1])->find();
        $this->assign('data', $data);
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/amgst/'.$id.'.html', $html);
        return $html;
    }

    public function amgstxs(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_amgsxs')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_amgsxs')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $this->assign('pname', '公式规律');
        $data['apps'] = Db::name('appdown')->where(['status'=>1])->find();
        $this->assign('data', $data);
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/amgstxs/'.$id.'.html', $html);
        return $html;
    }


    public function pmzq(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_pmzq')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_pmzq')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $this->assign('pname', '心水贴');
        $data['apps'] = Db::name('appdown')->where(['status'=>1])->find();
        $this->assign('data', $data);
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/pmzq/'.$id.'.html', $html);
        return $html;
    }

    public function wcsl(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_wcsl')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_wcsl')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $this->assign('pname', '大神公式');
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/wcsl/'.$id.'.html', $html);
        return $html;
    }

    public function ptzq(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_ptzq')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_ptzq')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/ptzq/'.$id.'.html', $html);
        return $html;
    }


    public function zbxjs(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_zbxjs')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_zbxjs')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/zbxjs/'.$id.'.html', $html);
        return $html;
    }

    public function slzq(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_slzq')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_slzq')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/slzq/'.$id.'.html', $html);
        return $html;
    }

    public function dsgs(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('dg_dsgs')->field('*,zz zhuozhe')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        Db::name('dg_dsgs')->where(['id'=>$id])->setInc('view', rand(1,5));
        $this->assign('info',$info);
        $this->assign('page', IndexLogic::getPage());
        $html = $this->view->fetch('xggst');
        //file_put_contents(ROOT_PATH.'/public/dsgs/'.$id.'.html', $html);
        return $html;
    }


    public function zbgp()
    {

        $this->assign('list', Db::name('zbgp')->order('id desc')->limit(16)->select());
        return $this->view->fetch();
    }

    public function ppg()
    {
        $id = $this->request->param('id', 0);
        if(!in_array($id, [3,4,5,6])){
            $id = 3;
        }
        $this->assign('info', Db::name('page')->find($id));
        return $this->view->fetch();
    }


    public function zbgpinfo(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('zbgp')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        $this->assign('info',$info);
        return $this->view->fetch();
    }

    public function zhzl(){
        $id = $this->request->param('id', 0);
        $info =  Db::name('zhzl')->where(['id'=>$id])->find();
        if(!$info){
            header('Location: /');exit;
        }
        $this->assign('info',$info);
        return $this->view->fetch();
    }


    public function caitu(){
        $type = 1;
        $data = Db::name('tu'.session('tabexp'))->where(['type'=>$type])->order('uptime desc')->select();
        $this->assign('list',$data);
        return $this->view->fetch('caitu');
    }

    public function heibai(){
        $type = 2;
        $data = Db::name('tu'.session('tabexp'))->where(['type'=>$type])->order('uptime desc')->select();
        $this->assign('list',$data);
        return $this->view->fetch('caitu');
    }

    public function sxb(){

        return $this->view->fetch();
    }

    public function kj2(){
        return $this->view->fetch('common:kjurl');
    }

    public function kj(){
        $this->assign('tag', 'gc');
        return $this->view->fetch('kj');
    }

    public function pic(){
        $typeid = request()->param('id', 1);
        $data = Db::name('tulist'.session('tabexp'))->where(['tu_id'=>$typeid])->order(Db::raw('qi desc'))->select();
        $this->assign('list',$data);
        $tu = Db::name('tu'.session('tabexp'))->where(['id'=>$typeid])->find();
        $this->assign('tu',$tu);
        return $this->view->fetch();
    }


}
