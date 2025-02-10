<?php


namespace app\index\controller;


use app\common\controller\Frontend;
use app\index\logic\IndexLogic;
use think\Cache;
use think\Db;

class History extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function wy()
    {
        $lotteryType = $this->request->param('lt', 1);
        $year = $this->request->param('year', 2024);
        $this->assign('year', $year);
        $this->assign('lt', $lotteryType);
        $ck = 'history_wyjls'.$year;
        if(0 && Cache::has($ck)){
            //$arr = Cache::get($ck);
        }else{
            if($lotteryType == 1){
                $tb = 'twkj';
            }else{
                $tb = 'twkj2';
            }
            $arr = Db::name($tb)->where(['num1'=>['neq', ''], 'addtime'=>['lt', time()-60]])->where("FROM_UNIXTIME(addtime,'%Y') = '".intval($year)."'")->order(Db::raw('qihao*1 desc'))->select();
            foreach ($arr as &$item){
                $item['sx1'] = $this->shengxiao($item['num1']);
                $item['sx2'] = $this->shengxiao($item['num2']);
                $item['sx3'] = $this->shengxiao($item['num3']);
                $item['sx4'] = $this->shengxiao($item['num4']);
                $item['sx5'] = $this->shengxiao($item['num5']);
                $item['sx6'] = $this->shengxiao($item['num6']);
                $item['sx7'] = $this->shengxiao($item['num7']);
                $bb1 = $this->getnum($item['num1']);
                $item['bs1'] = $bb1['bs'];
                $item['wx1'] = $bb1['wx'];
                $bb = $this->getnum($item['num2']);
                $item['bs2'] = $bb['bs'];
                $item['wx2'] = $bb['wx'];
                $bb = $this->getnum($item['num3']);
                $item['bs3'] = $bb['bs'];
                $item['wx3'] = $bb['wx'];
                $bb = $this->getnum($item['num4']);
                $item['bs4'] = $bb['bs'];
                $item['wx4'] = $bb['wx'];
                $bb = $this->getnum($item['num5']);
                $item['bs5'] = $bb['bs'];
                $item['wx5'] = $bb['wx'];
                $bb = $this->getnum($item['num6']);
                $item['bs6'] = $bb['bs'];
                $item['wx6'] = $bb['wx'];
                $bb = $this->getnum($item['num7']);
                $item['bs7'] = $bb['bs'];
                $item['wx7'] = $bb['wx'];
            }
            Cache::set($ck, $arr, 60);
        }

        $this->assign('data', $arr);
        return $this->view->fetch();
    }



    private function shengxiao($ball, $optime=0){
        if($optime>0){
            $riqi = date('Ymd', $optime);
        }else{
            $riqi = date('Ymd');
        }
        if($riqi >= 20240210){
            //龙年生肖
            $sx_long = array('01','13','25','37','49');
            $sx_tu   = array('02','14','26','38','50');
            $sx_hu   = array('03','15','27','39','51');
            $sx_niu  = array('04','16','28','40','52');
            $sx_shu  = array('05','17','29','41','53');
            $sx_zhu  = array('06','18','30','42','54');
            $sx_gou  = array('07','19','31','43','55');
            $sx_ji   = array('08','20','32','44','56');
            $sx_hou  = array('09','21','33','45','57');
            $sx_yang = array('10','22','34','46','58');
            $sx_ma   = array('11','23','35','47','59');
            $sx_she  = array('12','24','36','48','60');
        }else{
            $sx_tu = array('01','13','25','37','49');
            $sx_hu = array('02','14','26','38','50');
            $sx_niu = array('03','15','27','39','51');
            $sx_shu = array('04','16','28','40','52');
            $sx_zhu = array('05','17','29','41','53');
            $sx_gou = array('06','18','30','42','54');
            $sx_ji = array('07','19','31','43','55');
            $sx_hou = array('08','20','32','44','56');
            $sx_yang = array('09','21','33','45','57');
            $sx_ma = array('10','22','34','46','58');
            $sx_she = array('11','23','35','47','59');
            $sx_long = array('12','24','36','48','60');
        }
        if(in_array($ball, $sx_hou)){
            return '猴';
        }
        if(in_array($ball, $sx_yang)){
            return '羊';
        }
        if(in_array($ball, $sx_shu)){
            return '鼠';
        }
        if(in_array($ball, $sx_niu)){
            return '牛';
        }
        if(in_array($ball, $sx_hu)){
            return '虎';
        }
        if(in_array($ball, $sx_tu)){
            return '兔';
        }
        if(in_array($ball, $sx_long)){
            return '龙';
        }
        if(in_array($ball, $sx_she)){
            return '蛇';
        }
        if(in_array($ball, $sx_ma)){
            return '马';
        }
        if(in_array($ball, $sx_ji)){
            return '鸡';
        }
        if(in_array($ball, $sx_gou)){
            return '狗';
        }
        if(in_array($ball, $sx_zhu)){
            return '猪';
        }
        return '-';
    }

    private function getnum($num, $optime=0){
        //开奖号码处理
        $ball_r = ["01", "02", "07", "08", "12", "13", "18", "19", "23", "24", "29", "30", "34", "35", "40", "45", "46"];
        $ball_b = ["03", "04", "09", "10", "14", "15", "20", "25", "26", "31", "36", "37", "41", "42", "47", "48"];
        $ball_g = ["05", "06", "11", "16", "17", "21", "22", "27", "28", "32", "33", "38", "39", "43", "44", "49"];
        if($optime>0){
            $riqi = date('Ymd', $optime);
        }else{
            $riqi = date('Ymd');
        }
        if($riqi >= 20240210) {
            $wuxin_j = ['02', '03', '10', '11', '24', '25', '32', '33', '40', '41'];
            $wuxin_m = ['06', '07', '14', '15', '22', '23', '36', '37', '44', '45'];
            $wuxin_s = ['12', '13', '20', '21', '28', '29', '42', '43'];
            $wuxin_h = ['01', '08', '09', '16', '17', '30', '31', '38', '39', '46', '47'];
            $wuxin_t = ['04', '05', '18', '19', '26', '27', '34', '35', '48', '49'];
        }else{
            $wuxin_j = ['01', '02', '09', '10', '23', '24', '31', '32', '39', '40'];
            $wuxin_m = ['05', '06', '13', '14', '21', '22', '35', '36', '43', '44'];
            $wuxin_s = ['11', '12', '19', '20', '27', '28', '41', '42', '49'];
            $wuxin_h = ['07', '08', '15', '16', '29', '30', '37', '38', '45', '46'];
            $wuxin_t = ['03', '04', '17', '18', '25', '26', '33', '34', '47', '48'];
        }

        $vo = [];
        if(in_array($num, $ball_r)){
            $vo['bs'] = 'red';
        }elseif(in_array($num, $ball_b)){
            $vo['bs'] = 'blue';
        }else{
            $vo['bs'] = 'green';
        }
        if(in_array($num, $wuxin_j)){
            $vo['wx'] = '金';
        }elseif(in_array($num, $wuxin_m)){
            $vo['wx'] = '木';
        }elseif(in_array($num, $wuxin_s)){
            $vo['wx'] = '水';
        }elseif(in_array($num, $wuxin_h)){
            $vo['wx'] = '火';
        }elseif(in_array($num, $wuxin_t)){
            $vo['wx'] = '土';
        }else{
            $vo['wx'] = '-';
        }
        return $vo;
    }


}