<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Cache;
use think\Controller;
use think\Db;
use think\Lang;
use think\Response;

/**
 * Ajax异步请求接口
 * @internal
 */
class Ajax extends Controller
{

    protected $noNeedLogin = ['lang', 'upload', 'amkjls', 'xgkjls', 'ttk', 'ttklsjl', 'wy', 'am'];
    protected $noNeedRight = ['*'];
    protected $layout = '';

    /**
     * 加载语言包
     */
    public function lang()
    {
        $this->request->get(['callback' => 'define']);
        $header = ['Content-Type' => 'application/javascript'];
        if (!config('app_debug')) {
            $offset = 30 * 60 * 60 * 24; // 缓存一个月
            $header['Cache-Control'] = 'public';
            $header['Pragma'] = 'cache';
            $header['Expires'] = gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        }

        $controllername = input("controllername");
        $this->loadlang($controllername);
        //强制输出JSON Object
        return jsonp(Lang::get(), 200, $header, ['json_encode_param' => JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE]);
    }

    /**
     * 生成后缀图标
     */
    public function icon()
    {
        $suffix = $this->request->request("suffix");
        $suffix = $suffix ? $suffix : "FILE";
        $data = build_suffix_image($suffix);
        $header = ['Content-Type' => 'image/svg+xml'];
        $offset = 30 * 60 * 60 * 24; // 缓存一个月
        $header['Cache-Control'] = 'public';
        $header['Pragma'] = 'cache';
        $header['Expires'] = gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        $response = Response::create($data, '', 200, $header);
        return $response;
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        return action('api/common/upload');
    }


    public function amkjls(){
        $ck = 'history_amkjls';
        if(Cache::has($ck)){
            return Cache::get($ck);
        }
        $url = 'https://www.49878.am/app-api/api/v2/lottery/getTopResults?page=1&gameId=90&rows=200&openYear=2024';
        $res = curl_get_https($url);
        $arr = json_decode($res, true);
        if($arr){
            $data = [];
            foreach ($arr['data']['data'] as $item){
                $vo = [];
                $vo['issue'] = $item['turnNum'];
                $vo['openCode'] = $item['openNum'];
                $vo['openTime'] = $item['openTime'];
                $vo['nextTime'] = $item['openTime'];
                $vo['videoUrl'] = '';
                $data[] = $vo;
            }
            $obj = ['code'=>0, 'message'=>'Success', 'data'=>$data];
            $result = 'var historyAO ='. json_encode($obj);
            Cache::set($ck, $result, 60);
            echo $result;
        }
    }

    public function xgkjls(){
        $ck = 'history_xgkjls';
        if(Cache::has($ck)){
            return Cache::get($ck);
        }
        $url = 'https://www.49878.am/app-api/api/v2/lottery/getTopResults?page=1&gameId=70&rows=200&openYear=2024';
        $res = curl_get_https($url);
        $arr = json_decode($res, true);
        if($arr){
            $data = [];
            foreach ($arr['data']['data'] as $item){
                $vo = [];
                $vo['issue'] = $item['turnNum'];
                $vo['openCode'] = $item['openNum'];
                $vo['openTime'] = $item['openTime'];
                $vo['nextTime'] = $item['openTime'];
                $vo['videoUrl'] = '';
                $data[] = $vo;
            }
            $obj = ['code'=>0, 'message'=>'Success', 'data'=>$data];
            $result = 'var historyAO ='. json_encode($obj);
            Cache::set($ck, $result, 60);
            echo $result;
        }
    }

    public function ttklsjl(){
        $ck = 'history_xgkjls';
        if(Cache::has($ck)){
            return Cache::get($ck);
        }
        $arr = Db::name('twkj')->where(['num1'=>['neq', ''], 'addtime'=>['lt', time()+60]])->order(Db::raw('qihao*1 desc'))->select();
        $data = [];
        foreach ($arr as $item){
            $vo = [];
            $vo['issue'] = $item['qihao'];
            $vo['openCode'] = $item['num1'].','.$item['num2'].','.$item['num3'].','.$item['num4'].','.$item['num5'].','.$item['num6'].','.$item['num7'];
            $vo['openTime'] = date('Y-m-d H:i:s', $item['addtime']);
            $vo['nextTime'] = $vo['openTime'];
            $vo['videoUrl'] = '';
            $data[] = $vo;
        }
        $obj = ['code'=>0, 'message'=>'Success', 'data'=>$data];
        $result = 'var historyAO ='. json_encode($obj);
        Cache::set($ck, $result, 60);
        echo $result;
    }



    public function am(){
        $str = file_get_contents('https://www.macaumarksix.com/api/live');
        $arr = json_decode($str, true);
        $data = $arr[0];
        if($data){
            $vo['expect'] = $data['expect'];
            $vo['openCode'] = $data['openCode'];
            $vo['info'] = '';
            $vo['openTime'] = date('Y-m-d H:i:s', strtotime($data['openTime'])+86400);
            $color = '';
            $sx = '';
            //foreach ($data['content'] as $num){
            //    $color .= $this->getnum($num)['bs'].',';
            //    $sx .= $this->shengxiao($num).',';
            //}
            $vo['wave'] = $data['wave'];
            $vo['zodiac'] = $data['zodiac'];
            file_put_contents(ROOT_PATH.'public/kj2/am.json', json_encode([$vo]));
            echo json_encode([$vo]);
        }
        echo "";
    }

    public function wy1(){
        $nextqi = Db::name('twkj')->where(['addtime'=>['gt', time()]])->order(Db::raw('qihao*1 asc'))->find();
        $item = Db::name('twkj')->where(['addtime'=>['lt', time()]])->order(Db::raw('addtime desc,qihao*1 desc'))->find();
        $return = [];
        if ($item){
            //生成预告
            if(!$nextqi){
                $qihao = $item['qihao']+1;
                if(date('md',$item['addtime']+86400)=='0101'){
                    if(strlen($qihao)==7){
                        $qihao = (date('Y')+1).'001';
                    }else{
                        $qihao = '001';
                    }
                }
                Db::name('twkj')->insertGetId(['qihao'=>$qihao, 'addtime'=>$item['addtime']+86400,'num1'=>'','num2'=>'','num3'=>'','num4'=>'','num5'=>'','num6'=>'','num7'=>'']);
                $nextqi = Db::name('twkj')->where(['addtime'=>['gt', time()]])->order(Db::raw('qihao*1 asc'))->find();
            }
            //处理开奖号码跳动
            $second = time() - $item['addtime'];
            if($second >= 0){
                if($item['num1']!=''){
                    $bsec = 10;
                    if($second < $bsec){
                        $item['num1'] = '';
                        $item['num2'] = '';
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*2){
                        $item['num2'] = '';
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*3){
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*4){
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*5){
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*6){
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*7){
                        $item['num7'] = '';
                    }
                }
            }
            $vo = [];
            $vo['expect'] = $item['qihao'];
            $vo['nextexpect'] = $nextqi['qihao']??($item['qihao']+1);
            $vo['nextTime'] = date('Y-m-d H:i', $nextqi['addtime']);
            $vo['openCode'] = $item['num1'].','.$item['num2'].','.$item['num3'].','.$item['num4'].','.$item['num5'].','.$item['num6'].','.$item['num7'];
            $vo['zodiac'] = $this->shengxiao($item['num1'],$item['addtime']).','.$this->shengxiao($item['num2'],$item['addtime']).','.$this->shengxiao($item['num3'],$item['addtime']).','.$this->shengxiao($item['num4'],$item['addtime']).','.$this->shengxiao($item['num5'],$item['addtime']).','.$this->shengxiao($item['num6'],$item['addtime']).','.$this->shengxiao($item['num7'],$item['addtime']);
            $vo['openTime'] = date('Y-m-d H:i:s', $item['addtime']);
            $vo['wave'] = $this->getnum($item['num1'])['bs'].','.$this->getnum($item['num2'])['bs'].','.$this->getnum($item['num3'])['bs'].','.$this->getnum($item['num4'])['bs'].','.$this->getnum($item['num5'])['bs'].','.$this->getnum($item['num6'])['bs'].','.$this->getnum($item['num7'])['bs'];
            $vo['wuxin'] = $this->getnum($item['num1'])['wx'].','.$this->getnum($item['num2'])['wx'].','.$this->getnum($item['num3'])['wx'].','.$this->getnum($item['num4'])['wx'].','.$this->getnum($item['num5'])['wx'].','.$this->getnum($item['num6'])['wx'].','.$this->getnum($item['num7'])['wx'];
            $return[] = $vo;
        }
        file_put_contents(ROOT_PATH.'public/kj1/wy1.json', json_encode($return));
        echo 1;
    }
    
    private function cjxx(){
        $f = 'http://yl9988.cc:21122/kj2/ttk.json?t='.time();
        $res = curl_get_https($f);
        $arr = json_decode($res, true);
        $openCode = explode(',',$arr[0]['openCode']);
        $vo['qihao'] = $arr[0]['expect'];
        $vo['addtime'] = strtotime($arr[0]['openTime']);
        $vo['num1'] = $openCode[0];
        $vo['num2'] = $openCode[1];
        $vo['num3'] = $openCode[2];
        $vo['num4'] = $openCode[3];
        $vo['num5'] = $openCode[4];
        $vo['num6'] = $openCode[5];
        $vo['num7'] = $openCode[6];
        $nextqi = Db::name('twkj')->where(['addtime'=>$vo['addtime'], 'qihao'=>$vo['qihao']])->find();
        if($nextqi){
            Db::name('twkj')->where(['id'=>$nextqi['id']])->update($vo);
        }else{
            Db::name('twkj')->insert($vo);
        }
        echo '采集'.$arr[0]['expect'].'期：'.$arr[0]['openCode'];
    }

    public function wy2(){
        $nextqi = Db::name('twkj2')->where(['addtime'=>['gt', time()]])->order(Db::raw('qihao*1 asc'))->find();
        $item = Db::name('twkj2')->where(['addtime'=>['lt', time()]])->order(Db::raw('addtime desc,qihao*1 desc'))->find();
        $return = [];
        if ($item){
            //生成预告
            if(!$nextqi){
                $qihao = $item['qihao']+1;
                if(date('md')==1231){
                    if(strlen($qihao)==7){
                        $qihao = (date('Y')+1).'001';
                    }else{
                        $qihao = '001';
                    }
                }
                Db::name('twkj2')->insertGetId(['qihao'=>$qihao, 'addtime'=>$item['addtime']+86400,'num1'=>'','num2'=>'','num3'=>'','num4'=>'','num5'=>'','num6'=>'','num7'=>'']);
                $nextqi = Db::name('twkj2')->where(['addtime'=>['gt', time()]])->order(Db::raw('qihao*1 asc'))->find();
            }
            //处理开奖号码跳动
            $second = time() - $item['addtime'];
            if($second >= 0){
                if($item['num1']!=''){
                    $bsec = 10;
                    if($second < $bsec){
                        $item['num1'] = '';
                        $item['num2'] = '';
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*2){
                        $item['num2'] = '';
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*3){
                        $item['num3'] = '';
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*4){
                        $item['num4'] = '';
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*5){
                        $item['num5'] = '';
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*6){
                        $item['num6'] = '';
                        $item['num7'] = '';
                    }elseif($second < $bsec*7){
                        $item['num7'] = '';
                    }
                }
            }
            $vo = [];
            $vo['expect'] = $item['qihao'];
            $vo['nextexpect'] = $nextqi['qihao']??($item['qihao']+1);
            $vo['nextTime'] = date('Y-m-d H:i', $nextqi['addtime']);
            $vo['openCode'] = $item['num1'].','.$item['num2'].','.$item['num3'].','.$item['num4'].','.$item['num5'].','.$item['num6'].','.$item['num7'];
            $vo['zodiac'] = $this->shengxiao($item['num1'],$item['addtime']).','.$this->shengxiao($item['num2'],$item['addtime']).','.$this->shengxiao($item['num3'],$item['addtime']).','.$this->shengxiao($item['num4'],$item['addtime']).','.$this->shengxiao($item['num5'],$item['addtime']).','.$this->shengxiao($item['num6'],$item['addtime']).','.$this->shengxiao($item['num7'],$item['addtime']);
            $vo['openTime'] = date('Y-m-d H:i:s', $item['addtime']);
            $vo['wave'] = $this->getnum($item['num1'])['bs'].','.$this->getnum($item['num2'])['bs'].','.$this->getnum($item['num3'])['bs'].','.$this->getnum($item['num4'])['bs'].','.$this->getnum($item['num5'])['bs'].','.$this->getnum($item['num6'])['bs'].','.$this->getnum($item['num7'])['bs'];
            $vo['wuxin'] = $this->getnum($item['num1'])['wx'].','.$this->getnum($item['num2'])['wx'].','.$this->getnum($item['num3'])['wx'].','.$this->getnum($item['num4'])['wx'].','.$this->getnum($item['num5'])['wx'].','.$this->getnum($item['num6'])['wx'].','.$this->getnum($item['num7'])['wx'];
            $return[] = $vo;
        }
        file_put_contents(ROOT_PATH.'public/kj1/wy2.json', json_encode($return));
        echo 1;
    }


    public function shengxiao($ball, $optime=0, $type=0){
        if($optime>0){
            $riqi = date('Ymd', $optime);
        }else{
            $riqi = date('Ymd');
        }
        if($riqi >= 20250129){
            //龙年生肖
            $sx_she   = array('01','13','25','37','49');
            $sx_long  = array('02','14','26','38');
            $sx_tu    = array('03','15','27','39');
            $sx_hu    = array('04','16','28','40');
            $sx_niu   = array('05','17','29','41');
            $sx_shu   = array('06','18','30','42');
            $sx_zhu   = array('07','19','31','43');
            $sx_gou   = array('08','20','32','44');
            $sx_ji    = array('09','21','33','45');
            $sx_hou   = array('10','22','34','46');
            $sx_yang  = array('11','23','35','47');
            $sx_ma  = array('12','24','36','48');
        }elseif($riqi >= 20240210){
            //龙年生肖
            $sx_long = array('01','13','25','37','49');
            $sx_tu   = array('02','14','26','38');
            $sx_hu   = array('03','15','27','39');
            $sx_niu  = array('04','16','28','40');
            $sx_shu  = array('05','17','29','41');
            $sx_zhu  = array('06','18','30','42');
            $sx_gou  = array('07','19','31','43');
            $sx_ji   = array('08','20','32','44');
            $sx_hou  = array('09','21','33','45');
            $sx_yang = array('10','22','34','46');
            $sx_ma   = array('11','23','35','47');
            $sx_she  = array('12','24','36','48');
        }else{
            //兔年生肖
            $sx_tu = array('01','13','25','37','49');
            $sx_hu = array('02','14','26','38');
            $sx_niu = array('03','15','27','39');
            $sx_shu = array('04','16','28','40');
            $sx_zhu = array('05','17','29','41');
            $sx_gou = array('06','18','30','42');
            $sx_ji = array('07','19','31','43');
            $sx_hou = array('08','20','32','44');
            $sx_yang = array('09','21','33','45');
            $sx_ma = array('10','22','34','46');
            $sx_she = array('11','23','35','47');
            $sx_long = array('12','24','36','48');
        }
        if($type==1){
            //获取全部
            return [
                '猴' => $sx_hou,
                '羊' => $sx_yang,
                '鼠' => $sx_shu,
                '牛' => $sx_niu,
                '虎' => $sx_hu,
                '兔' => $sx_tu,
                '龙' => $sx_long,
                '蛇' => $sx_she,
                '马' => $sx_ma,
                '鸡' => $sx_ji,
                '狗' => $sx_gou,
                '猪' => $sx_zhu,
            ];
        }elseif($type==2){
            return ['牛','马','羊','鸡','狗','猪','鼠','虎','兔','龙','蛇','猴'];
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

    public function getnum($num, $optime=0, $type=0){
        if($optime>0){
            $riqi = date('Ymd', $optime);
        }else{
            $riqi = date('Ymd');
        }
        if($riqi >= 20240210) {
            //开奖号码处理
            $ball_r = ["01", "02", "07", "08", "12", "13", "18", "19", "23", "24", "29", "30", "34", "35", "40", "45", "46"];
            $ball_b = ["03", "04", "09", "10", "14", "15", "20", "25", "26", "31", "36", "37", "41", "42", "47", "48"];
            $ball_g = ["05", "06", "11", "16", "17", "21", "22", "27", "28", "32", "33", "38", "39", "43", "44", "49"];
            $wuxin_j = array('02', '03', '10', '11', '24', '25', '32', '33', '40', '41');
            $wuxin_m = array('06', '07', '14', '15', '22', '23', '36', '37', '44', '45');
            $wuxin_s = array('12', '13', '20', '21', '28', '29', '42', '43');
            $wuxin_h = array('01', '08', '09', '16', '17', '30', '31', '38', '39', '46', '47');
            $wuxin_t = array('04', '05', '18', '19', '26', '27', '34', '35', '48', '49');
        }else{
            //开奖号码处理
            $ball_r = ["01", "02", "07", "08", "12", "13", "18", "19", "23", "24", "29", "30", "34", "35", "40", "45", "46"];
            $ball_b = ["03", "04", "09", "10", "14", "15", "20", "25", "26", "31", "36", "37", "41", "42", "47", "48"];
            $ball_g = ["05", "06", "11", "16", "17", "21", "22", "27", "28", "32", "33", "38", "39", "43", "44", "49"];
            $wuxin_j = ['01', '02', '09', '10', '23', '24', '31', '32', '39', '40'];
            $wuxin_m = ['05', '06', '13', '14', '21', '22', '35', '36', '43', '44'];
            $wuxin_s = ['11', '12', '19', '20', '27', '28', '41', '42', '49'];
            $wuxin_h = ['07', '08', '15', '16', '29', '30', '37', '38', '45', '46'];
            $wuxin_t = ['03', '04', '17', '18', '25', '26', '33', '34', '47', '48'];
        }
        if($type==1){
            //获取全部
            return [
                'ball' => array_merge($ball_r,$ball_g,$ball_b),
                'ball_r' => $ball_r,
                'ball_b' => $ball_b,
                'ball_g' => $ball_g,
                'wuxin_j' => $wuxin_j,
                'wuxin_m' => $wuxin_m,
                'wuxin_s' => $wuxin_s,
                'wuxin_h' => $wuxin_h,
                'wuxin_t' => $wuxin_t,
            ];
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
