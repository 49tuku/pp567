<?php


namespace app\admin\model;


use app\index\controller\Ajax;
use think\Config;
use think\Db;

class DuicuoLogic
{



    public static function updata($row){
        $qi = $row['qihao'];
        $num1 = $row['num1'];
        $num2 = $row['num2'];
        $num3 = $row['num3'];
        $num4 = $row['num4'];
        $num5 = $row['num5'];
        $num6 = $row['num6'];
        $num7 = $row['num7'];

        $a = new Ajax();
        $ballarr = $a->getnum('', 0, 1);
        $tmbb = $a->getnum($row['num7']);
        $wuxing7 = $tmbb['wx'];
        $color7 = $tmbb['bs'];
        $sx1 = $a->shengxiao($row['num1']);
        $sx2 = $a->shengxiao($row['num2']);
        $sx3 = $a->shengxiao($row['num3']);
        $sx4 = $a->shengxiao($row['num4']);
        $sx5 = $a->shengxiao($row['num5']);
        $sx6 = $a->shengxiao($row['num6']);
        $sx7 = $a->shengxiao($row['num7']);
        $wei1 = substr($row['num1'], 1, 1);
        $wei2 = substr($row['num2'], 1, 1);
        $wei3 = substr($row['num3'], 1, 1);
        $wei4 = substr($row['num4'], 1, 1);
        $wei5 = substr($row['num5'], 1, 1);
        $wei6 = substr($row['num6'], 1, 1);
        $wei7 = substr($row['num7'], 1, 1);
        $tou7 = substr($row['num7'], 0, 1);
        $he7 = $wei7 + $tou7;

        //1.一肖一码
        $has = Db::name('dg_amyxym')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            $content = str_replace($sx7, '<span style="background-color: #FFFF00">'.$sx7.'</span>', $has['content']);
            $content = str_replace('<font>'.$row['num7'].'</font>', '<span style="background-color: #FFFF00">'.$row['num7'].'</span>', $content);
            $content = str_replace('????', $num7.'.'.$sx7, $content);
            Db::name('dg_amyxym')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }
        //28码中特
        $has = Db::name('dg_zt24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            $content = str_replace('<span style="background-color: #FFFF00">', '<span>', $has['content']);
            $content = str_replace('<font>'.$num7.'</font>', '<span style="background-color: #FFFF00">'.$num7.'</span>', $content);
            $kai = $num7.$sx7;
            Db::name('dg_zt24m')->where(['id'=>$has['id']])->update(['content'=>$content, 'kai'=>$kai]);
        }
        //1.9肖中
        $has = Db::name('dg_jx24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            $content = str_replace($sx7, '<span style="background-color: #FFFF00">'.$sx7.'</span>', $has['content']);
            $content = str_replace('<font>'.$row['num7'].'</font>', '<span style="background-color: #FFFF00">'.$row['num7'].'</span>', $content);
            $content = str_replace('????', $num7.'.'.$sx7, $content);
            Db::name('dg_jx24m')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }
        //1.杀榜
        $has = Db::name('zhuanshabang')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            Db::name('zhuanshabang')->where(['id'=>$has['id']])->update(['kai'=>$num7.$sx7]);
        }
        //平特一尾
        $vo2 = [];
        $has = Db::name('dg_ptyw')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $wei7) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei6) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num6.$sx6.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei5) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num5.$sx5.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei4) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num4.$sx4.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei3) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num3.$sx3.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei2) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num2.$sx2.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $wei1) !== false ){
                $vo2['kai'] = '<font color="#FF0000">'.$num1.$sx1.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }else{
                $vo2['kai'] = $num7.$sx7.'<font color="#000">错</font>';
            }
            Db::name('dg_ptyw')->where(['id'=>$has['id']])->update($vo2);
        }
        //平特一肖
        $vo2 = [];
        $has = Db::name('dg_ptyx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $sx7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx6) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num6.$sx6.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx5) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num5.$sx5.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx4) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num4.$sx4.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx3) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num3.$sx3.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx2) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num2.$sx2.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], $sx1) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num1.$sx1.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }else{
                $vo2['kai'] = $num7.'<font color="#000">错</font>';
            }
            Db::name('dg_ptyx')->where(['id'=>$has['id']])->update($vo2);
        }
        //4头中特
        $vo2 = [];
        $has = Db::name('dg_4tzt')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $tou7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace($tou7, '<span style="background-color: #FFFF00">'.$tou7.'</span>', strip_tags($has['ju']));
            }else{
                $vo2['kai'] = '<font color="#000000">'.$num7.$sx7.'错</font>';
            }
            Db::name('dg_4tzt')->where(['id'=>$has['id']])->update($vo2);
        }
        //琴棋书画
        $vo2 = [];
        $has = Db::name('dg_qqsh')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], '琴') !== false && in_array($sx7, self::$qing)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('琴', '<span style="background-color: #FFFF00">琴</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '棋') !== false && in_array($sx7, self::$qi)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('棋', '<span style="background-color: #FFFF00">棋</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '书') !== false && in_array($sx7, self::$shu)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('书', '<span style="background-color: #FFFF00">书</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '画') !== false && in_array($sx7, self::$hua)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('画', '<span style="background-color: #FFFF00">画</span>', strip_tags($has['ju']));
            }else{
                $vo2['kai'] = $num7. '<font color="#000">错</font>';
            }
            Db::name('dg_qqsh')->where(['id'=>$has['id']])->update($vo2);
        }
        //四段中特
        $vo2 = [];
        $has = Db::name('dg_ptjh')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], '1') !== false && in_array($num7, self::$duan1)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('1', '<span style="background-color: #FFFF00">1</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '2') !== false && in_array($num7, self::$duan2)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('2', '<span style="background-color: #FFFF00">2</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '3') !== false && in_array($num7, self::$duan3)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('3', '<span style="background-color: #FFFF00">3</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '4') !== false && in_array($num7, self::$duan4)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('4', '<span style="background-color: #FFFF00">4</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '5') !== false && in_array($num7, self::$duan5)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('5', '<span style="background-color: #FFFF00">5</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '6') !== false && in_array($num7, self::$duan6)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('6', '<span style="background-color: #FFFF00">6</span>', strip_tags($has['ju']));
            }elseif(strpos($has['ju'], '7') !== false && in_array($num7, self::$duan7)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace('7', '<span style="background-color: #FFFF00">7</span>', strip_tags($has['ju']));
            }else{
                $vo2['kai'] = $num7. '<font color="#000">错</font>';
            }
            Db::name('dg_ptjh')->where(['id'=>$has['id']])->update($vo2);
        }
        //买啥开啥
        $vo2 = [];
        $has = Db::name('dg_hbzt')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], '大数') !== false && in_array($num7, self::$da)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '小数') !== false && in_array($num7, self::$xiao)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '单数') !== false && in_array($num7, self::$dan)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '双数') !== false && in_array($num7, self::$shuang)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '家禽') !== false && in_array($sx7, self::$jiaqin)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '野兽') !== false && in_array($sx7, self::$yeshou)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }else{
                $vo2['kai'] = $num7. '<font color="#000">错</font>';
            }
            Db::name('dg_hbzt')->where(['id'=>$has['id']])->update($vo2);
        }
        //合数大小
        $vo2 = [];
        $has = Db::name('dg_ynsj')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], '合数小') !== false && in_array($num7, self::$hexiao)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '合数大') !== false && in_array($num7, self::$heda)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }else{
                $vo2['kai'] = $num7. '<font color="#000">错</font>';
            }
            Db::name('dg_ynsj')->where(['id'=>$has['id']])->update($vo2);
        }
        //吉凶六肖
        $vo2 = [];
        $has = Db::name('dg_jmxc')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], '吉美') !== false && in_array($sx7, self::$jimei)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }elseif(strpos($has['ju'], '凶丑') !== false && in_array($sx7, self::$xiongchou)){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = '<span style="background-color: #FFFF00">'.strip_tags($has['ju']).'</span>';
            }else{
                $vo2['kai'] = $num7. '<font color="#000">错</font>';
            }
            Db::name('dg_jmxc')->where(['id'=>$has['id']])->update($vo2);
        }
        //绝杀一头
        $vo2 = [];
        $has = Db::name('dg_bs1t')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $tou7) === false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
            }else{
                $vo2['kai'] = '<font color="#000000">'.$num7.$sx7.'错</font>';
            }
            Db::name('dg_bs1t')->where(['id'=>$has['id']])->update($vo2);
        }
        //三头六尾
        $vo2 = [];
        $has = Db::name('dg_3t6w')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $ju = strip_tags($has['ju']);
        $juarr = explode('+', $ju);
        if($has){
            if(strpos($juarr[0], $tou7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $juarr[0] = str_replace($tou7, '<span style="background-color: #FFFF00">'.$tou7.'</span>', $juarr[0]);
            }
            if(strpos($juarr[1], $wei7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $juarr[1] = str_replace($wei7, '<span style="background-color: #FFFF00">'.$wei7.'</span>', $juarr[1]);
            }
            $vo2['ju'] = $juarr[0].' + '.$juarr[1];
            if(!isset($vo2['kai'])){
                $vo2['kai'] = $num7.$sx7. '<font color="#000">错</font>';
            }
            Db::name('dg_3t6w')->where(['id'=>$has['id']])->update($vo2);
        }
        //必中三头
        $vo2 = [];
        $has = Db::name('dg_zylx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $tou7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace($tou7, '<span style="background-color: #FFFF00">'.$tou7.'</span>', strip_tags($has['ju']));
            }else{
                $vo2['kai'] = '<font color="#000000">'.$num7.$sx7.'错</font>';
            }
            Db::name('dg_zylx')->where(['id'=>$has['id']])->update($vo2);
        }
        //致富九肖
        $vo2 = [];
        $has = Db::name('dg_qhlx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            if(strpos($has['ju'], $sx7) !== false){
                $vo2['kai'] = '<font color="#FF0000">'.$num7.$sx7.'对</font>';
                $vo2['ju'] = str_replace($sx7, '<span style="background-color: #FFFF00">'.$sx7.'</span>', strip_tags($has['ju']));
            }else{
                $vo2['kai'] = '<font color="#000000">'.$num7.$sx7.'错</font>';
            }
            Db::name('dg_qhlx')->where(['id'=>$has['id']])->update($vo2);
        }
        //2.大小3尾
        $vo2 = [];
        $ziliao = Db::name('dg_dx3w')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $content = $ziliao['ju'];
        if(strpos($ziliao['ju'], '大') !== false && $num7 >= 25){
            $vo2['kai'] = $num7.'<font color="#FF0000">对</font>';
            $content = str_replace('大', '<span style="background: #FFFF00; color: red">大</span>', $content);
        }elseif(strpos($ziliao['ju'], '小') !== false && $num7 < 25){
            $vo2['kai'] = $num7.'<font color="#FF0000">对</font>';
            $content = str_replace('小', '<span style="background: #FFFF00; color: red">小</span>', $content);
        }
        if(strpos($ziliao['ju'], $wei7) !== false){
            $vo2['kai'] = $num7.'<font color="#FF0000">对</font>';
            $content = str_replace($wei7, '<span style="background: #FFFF00; color: red">'.$wei7.'</span>', $content);
        }
        if(!isset($vo2['kai'])){
            $vo2['kai'] = $num7. '<font color="#000">错</font>';
        }
        $vo2['ju'] = $content;
        Db::name('dg_dx3w')->where(['qi'=>$qi])->update($vo2);



    }

    public static $dan = ['01','03','05','07','09','11','13','15','17','19','21','23','25','27','29','31','33','35','37','39','41','43','45','47','49'];    //(特单)单
    public static $shuang = ['02','04','06','08','10','12','14','16','18','20','22','24','26','28','30','32','34','36','38','40','42','44','46','48'];      //(特双)双
    public static $da = ['25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49'];     //(特大)大
    public static $xiao = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'];        //(特小)小
    public static $hedan = ['01','03','05','07','09','10','12','14','16','18','21','23','25','27','29','30','32','34','36','38','41','43','45','47','49'];  //合单
    public static $heshuang = ['02','04','06','08','11','13','15','17','19','20','22','24','26','28','31','33','35','37','39','40','42','44','46','48'];    //合双
    public static $heda = ['07','08','09','16','17','18','19','25','26','27','28','29','34','35','36','37','38','39','43','44','45','46','47','48'];        //合大
    public static $hexiao = ['01','02','03','04','05','06','10','11','12','13','14','15','20','21','22','23','24','30','31','32','33','40','41','42'];      //合小
    public static $weixiao = ['01','02','03','04','10','11','12','13','14','20','21','22','23','24','30','31','32','33','34','40','41','42','43','44'];     //尾小
    public static $weida = ['05','06','07','08','09','15','16','17','18','19','25','26','27','28','29','35','36','37','38','39','45','46','47','48','49'];  //尾大
    public static $jiaqin = ['牛','马','羊','鸡','狗','猪'];      //家禽
    public static $yeshou = ['鼠','虎','兔','龙','蛇','猴'];      //野兽
    public static $jimei = ['兔','龙','蛇','马','羊','鸡'];      //吉美
    public static $xiongchou = ['鼠','牛','虎','猴','狗','猪'];      //凶丑
    public static $duan1 = ['01','02','03','04','05','06','07'];  //1段
    public static $duan2 = ['08','09','10','11','12','13','14'];
    public static $duan3 = ['15','16','17','18','19','20','21'];
    public static $duan4 = ['22','23','24','25','26','27','28'];
    public static $duan5 = ['29','30','31','32','33','34','35'];
    public static $duan6 = ['36','37','38','39','40','41','42'];
    public static $duan7 = ['43','44','45','46','47','48','49'];
    public static $qing = ['鸡','兔','蛇'];
    public static $qi = ['鼠','牛','狗'];
    public static $shu = ['马','龙','虎'];
    public static $hua = ['羊','猴','猪'];
    public static $nanxiao = ['鼠','牛','虎','龙','马','猴','狗'];
    public static $nvxiao = ['兔','蛇','羊','鸡','猪'];
    public static $wei0 = ['10','20','30','40'];
    public static $wei1 = ['01','11','21','31','41'];
    public static $wei2 = ['02','12','22','32','42'];
    public static $wei3 = ['03','13','23','33','43'];
    public static $wei4 = ['04','14','24','34','44'];
    public static $wei5 = ['05','15','25','35','45'];
    public static $wei6 = ['06','16','26','36','46'];
    public static $wei7 = ['07','17','27','37','47'];
    public static $wei8 = ['08','18','28','38','48'];
    public static $wei9 = ['09','19','29','39','49'];
    public static $hongxiao = ['马','兔','鼠','鸡'];
    public static $lvxiao = ['羊','龙','牛','狗'];
    public static $lanxiao = ['蛇','虎','猪','猴'];
    public static $feng = ['虎','兔','龙'];
    public static $lei = ['猴','鸡','狗'];
    public static $yu = ['蛇','马','羊'];
    public static $dian = ['鼠','牛','猪'];
    public static $danxiao = ['龙','鼠','马','猴','鼠','虎'];
    public static $shuangxiao = ['猪','兔','蛇','羊','牛','鸡'];




    public static function kainext($row){
        $qi = $row['qihao'];
        $site = Config::get("site");
        $a = new Ajax();
        $sxlist = $a->shengxiao('', 0, 2);  //12个生肖
        shuffle($sxlist);
        $tou = ['1','2','3','4','0'];
        $ballarr = $a->getnum('', 0, 1);
        $balllist = $ballarr['ball'];
        shuffle($balllist);
        $wei = ['0','1','2','3','4','5','6','7','8','9'];

        //1.六肖主一肖
        $content = file_get_contents(ROOT_PATH.DS.'application'.DS.'admin'.DS.'view'.DS.'dd'.DS.'xckj'.DS.'yxym1.html');
        $content = str_replace('{qi}', $row['qihao'], $content);
        $content = str_replace('{name}', $site['name'], $content);
        $content = str_replace('{yumin1}', $site['yumin1'], $content);
        $xiao6 = [];
        foreach ($sxlist as $sx){
            $xiao6[] = '<font>'.$sx.'</font>';
        }
        $content = str_replace('{9x}', $xiao6[0].$xiao6[1].$xiao6[2].$xiao6[3].$xiao6[4].$xiao6[5].$xiao6[6].$xiao6[7].$xiao6[8], $content);
        $content = str_replace('{6x}', $xiao6[0].$xiao6[1].$xiao6[2].$xiao6[3].$xiao6[4].$xiao6[5], $content);
        $content = str_replace('{4x}', $xiao6[0].$xiao6[1].$xiao6[2].$xiao6[3], $content);
        $content = str_replace('{2x}', $xiao6[0].$xiao6[1], $content);
        $numfont = [];
        foreach ($balllist as $n){
            $numfont[] = '<font>'.$n.'</font>';
        }
        $sxballs = [];
        foreach ($balllist as $n){
            if(in_array($a->shengxiao($n), [strip_tags($xiao6[1]),strip_tags($xiao6[2]),strip_tags($xiao6[3]),strip_tags($xiao6[4]),strip_tags($xiao6[5]),strip_tags($xiao6[6]),strip_tags($xiao6[7]),strip_tags($xiao6[8])])) {
                $sxballs[] = '<font>' . $n . '</font>';
            }
        }
        $sxballs2 = [];
        foreach ($balllist as $n){
            if(in_array($a->shengxiao($n), [strip_tags($xiao6[0])])) {
                $sxballs2[] = '<font>' . $n . '</font>';
            }
        }
        shuffle($sxballs);
        shuffle($sxballs2);
        $content = str_replace('{10m}', $sxballs2[0].'.'.$sxballs[0].'.'.$sxballs[1].'.'.$sxballs[2].'.'.$sxballs[3].'.'.$sxballs[4].'.'.$sxballs[5].'.'.$sxballs[6].'.'.$sxballs[7].'.'.$sxballs[8], $content);
        $content = str_replace('{8m}', $sxballs2[0].'.'.$sxballs[0].'.'.$sxballs[1].'.'.$sxballs[2].'.'.$sxballs[3].'.'.$sxballs[4].'.'.$sxballs[5].'.'.$sxballs[6], $content);
        $content = str_replace('{6m}', $sxballs2[0].'.'.$sxballs[0].'.'.$sxballs[1].'.'.$sxballs[2].'.'.$sxballs[3].'.'.$sxballs[4], $content);
        $content = str_replace('{4m}', $sxballs2[0].'.'.$sxballs[0].'.'.$sxballs[1].'.'.$sxballs[2], $content);
        $content = str_replace('{kai}', '????', $content);
        $has = Db::name('dg_amyxym')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            Db::name('dg_amyxym')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->update(['content'=>$content]);
        }else{
            Db::name('dg_amyxym')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'],  'content'=>$content]);
        }
        //12.28码中特
        $has = Db::name('dg_zt24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($numfont);
        $content = '<p>'.
            '<span style="margin: 0px; padding: 2px 5px; text-align: center; border-color: rgb(238, 238, 238); background: rgb(249, 249, 249);">'.$numfont[0].'-'.$numfont[1].'-'.$numfont[2].'-'.$numfont[3].'-'.$numfont[4].'-'.$numfont[5].'-'.$numfont[6].'-'.$numfont[7].'-'.$numfont[8].'-'.$numfont[9].'-'.$numfont[10].'-'.$numfont[11].'-'.$numfont[12].'-'.$numfont[13].'</span><br>'.
            '<span style="margin: 0px; padding: 2px 5px; text-align: center; border-color: rgb(238, 238, 238); background: rgb(249, 249, 249);">'.$numfont[14].'-'.$numfont[15].'-'.$numfont[16].'-'.$numfont[17].'-'.$numfont[18].'-'.$numfont[19].'-'.$numfont[20].'-'.$numfont[21].'-'.$numfont[22].'-'.$numfont[23].'-'.$numfont[24].'-'.$numfont[25].'-'.$numfont[26].'-'.$numfont[27].'</span>'.
            '</p>';
        if($has){
            Db::name('dg_zt24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->update(['content'=>$content, 'kai'=>'?????']);
        }else{
            Db::name('dg_zt24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'status'=>1, 'content'=>$content, 'kai'=>'?????']);
        }
        //转杀棒
        $has = Db::name('zhuanshabang')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($wei);
        $arr1 = ['红波', '蓝波', '绿波'];
        shuffle($arr1);
        $dan1 = rand(1,40);
        if($has){
            Db::name('zhuanshabang')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->update(['xiao'=>$xiao6[10], 'wei'=>$wei[0].'尾', 'banbo'=>$arr1[0], 'yiduan'=>$dan1.'-'.($dan1+9), 'kai'=>'?????']);
        }else{
            Db::name('zhuanshabang')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'xiao'=>$xiao6[10], 'wei'=>$wei[0].'尾', 'banbo'=>$arr1[0], 'yiduan'=>$dan1.'-'.($dan1+9), 'kai'=>'?????']);
        }
        //10.必中9肖
        $sx2 = [$xiao6[0],$xiao6[1],$xiao6[2],$xiao6[3],$xiao6[4],$xiao6[5],$xiao6[6],$xiao6[7],$xiao6[8],$xiao6[9],$xiao6[10]];
        shuffle($sx2);
        $content = file_get_contents(ROOT_PATH.DS.'application'.DS.'admin'.DS.'view'.DS.'dd'.DS.'xckj'.DS.'bz9x.html');
        $content = str_replace('{qi}', $row['qihao'], $content);
        $content = str_replace('{kai}', '????', $content);
        $content = str_replace('{9x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3].'.'.$sx2[4].'.'.$sx2[5].'.'.$sx2[6].'.'.$sx2[7].'.'.$sx2[8], $content);
        $content = str_replace('{8x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3].'.'.$sx2[4].'.'.$sx2[5].'.'.$sx2[6].'.'.$sx2[7], $content);
        $content = str_replace('{7x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3].'.'.$sx2[4].'.'.$sx2[5].'.'.$sx2[6], $content);
        $content = str_replace('{6x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3].'.'.$sx2[4].'.'.$sx2[5], $content);
        $content = str_replace('{5x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3].'.'.$sx2[4], $content);
        $content = str_replace('{4x}', $sx2[0].'.'.$sx2[1].'.'.$sx2[2].'.'.$sx2[3], $content);
        $has = Db::name('dg_jx24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            Db::name('dg_jx24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->update(['content'=>$content]);
        }else{
            Db::name('dg_jx24m')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'status'=>1, 'content'=>$content]);
        }
        //25.平特一尾
        $has = Db::name('dg_ptyw')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($wei);
        if($has){
            Db::name('dg_ptyw')->where(['id'=>$has['id']])->update(['ju'=>$wei[0].$wei[0].$wei[0], 'kai'=>'???????']);
        }else{
            Db::name('dg_ptyw')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$wei[0].$wei[0].$wei[0], 'kai'=>'???????']);
        }
        //25.平特一肖
        $has = Db::name('dg_ptyx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($sxlist);
        if($has){
            Db::name('dg_ptyx')->where(['id'=>$has['id']])->update(['ju'=>$sxlist[0].$sxlist[0].$sxlist[0], 'kai'=>'???????']);
        }else{
            Db::name('dg_ptyx')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$sxlist[0].$sxlist[0].$sxlist[0], 'kai'=>'???????']);
        }
        //24.4头中特
        $has = Db::name('dg_4tzt')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($tou);
        if($has){
            Db::name('dg_4tzt')->where(['id'=>$has['id']])->update(['ju'=>$tou[0].'-'.$tou[1].'-'.$tou[2].'-'.$tou[3], 'kai'=>'???????']);
        }else{
            Db::name('dg_4tzt')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$tou[0].'-'.$tou[1].'-'.$tou[2].'-'.$tou[3], 'kai'=>'???????']);
        }
        //11.琴棋书画
        $has = Db::name('dg_qqsh')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $arr = ['琴','棋','书','画'];shuffle($arr);
        if($has){
            Db::name('dg_qqsh')->where(['id'=>$has['id']])->update(['ju'=>$arr[0].$arr[1], 'kai'=>'???????']);
        }else{
            Db::name('dg_qqsh')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[0].$arr[1], 'kai'=>'???????']);
        }
        //8.四段中特
        $has = Db::name('dg_ptjh')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $arr = ['1', '2', '3', '4', '5', '6','7'];
        shuffle($arr);
        if($has){
            Db::name('dg_ptjh')->where(['id'=>$has['id']])->update(['ju'=>$arr[0].'.'.$arr[1].'.'.$arr[2].'.'.$arr[3].'段', 'kai'=>'???????']);
        }else{
            Db::name('dg_ptjh')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[0].'.'.$arr[1].'.'.$arr[2].'.'.$arr[3].'段', 'kai'=>'???????']);
        }
        //4.买啥开啥
        $has = Db::name('dg_hbzt')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $arr = ['家禽', '野兽', '小数', '大数', '单数', '双数'];
        if($has){
            Db::name('dg_hbzt')->where(['id'=>$has['id']])->update(['ju'=>$arr[rand(0,5)], 'kai'=>'???????']);
        }else{
            Db::name('dg_hbzt')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[rand(0,5)], 'kai'=>'???????']);
        }
        //6.合数大小
        $has = Db::name('dg_ynsj')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $arr = ['合数小', '合数大'];
        if($has){
            Db::name('dg_ynsj')->where(['id'=>$has['id']])->update(['ju'=>$arr[rand(0,1)], 'kai'=>'???????']);
        }else{
            Db::name('dg_ynsj')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[rand(0,1)], 'kai'=>'???????']);
        }
        //11.吉凶六肖
        $has = Db::name('dg_jmxc')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $arr = ['凶丑', '吉美'];shuffle($arr);
        if($has){
            Db::name('dg_jmxc')->where(['id'=>$has['id']])->update(['ju'=>$arr[0], 'kai'=>'???????']);
        }else{
            Db::name('dg_jmxc')->where(['qi'=>$qi])->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[0], 'kai'=>'???????']);
        }
        //5.绝杀一头
        $has = Db::name('dg_bs1t')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        if($has){
            Db::name('dg_bs1t')->where(['id'=>$has['id']])->update(['ju'=>$tou[3], 'kai'=>'???????']);
        }else{
            Db::name('dg_bs1t')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$tou[3], 'kai'=>'???????']);
        }
        //24.三头六尾
        $has = Db::name('dg_3t6w')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($wei);
        $ju = $tou[0].$tou[1].$tou[2].'头 + '.$wei[0].$wei[1].$wei[2].$wei[3].$wei[4].$wei[5].'尾';
        if($has){
            Db::name('dg_3t6w')->where(['id'=>$has['id']])->update(['ju'=>$ju, 'kai'=>'???????']);
        }else{
            Db::name('dg_3t6w')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$ju, 'kai'=>'???????']);
        }
        //24.必中三头
        $has = Db::name('dg_zylx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        $tou = ['1','2','3','4','0'];
        shuffle($tou);
        if($has){
            Db::name('dg_zylx')->where(['id'=>$has['id']])->update(['ju'=>$tou[0].'-'.$tou[1].'-'.$tou[2], 'kai'=>'???????']);
        }else{
            Db::name('dg_zylx')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$tou[0].'-'.$tou[1].'-'.$tou[2], 'kai'=>'???????']);
        }
        //17.致富九肖
        $has = Db::name('dg_qhlx')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($sxlist);
        if($has){
            Db::name('dg_qhlx')->where(['id'=>$has['id']])->update(['ju'=>$sxlist[0].$sxlist[1].$sxlist[2].$sxlist[3].$sxlist[4].$sxlist[5].$sxlist[6].$sxlist[7].$sxlist[8], 'kai'=>'???????']);
        }else{
            Db::name('dg_qhlx')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$sxlist[0].$sxlist[1].$sxlist[2].$sxlist[3].$sxlist[4].$sxlist[5].$sxlist[6].$sxlist[7].$sxlist[8], 'kai'=>'???????']);
        }
        //2.大小3尾
        $has = Db::name('dg_dx3w')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType']])->find();
        shuffle($wei);
        if($has){
            Db::name('dg_dx3w')->where(['id'=>$has['id']])->update(['ju'=>$arr[0].'+'.$wei[0].$wei[1].$wei[2].'尾', 'kai'=>'?????']);
        }else{
            Db::name('dg_dx3w')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'ju'=>$arr[0].'+'.$wei[0].$wei[1].$wei[2].'尾', 'kai'=>'?????']);
        }


        //11.高手贴字-大小3尾 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'大小3尾'])->find();
        $jhtlist = Db::name('dg_dx3w')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 大小3尾 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'大小3尾', 'ym'=>$site['name'].'大小3尾', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-致富九肖 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'致富九肖'])->find();
        $jhtlist = Db::name('dg_qhlx')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 致富九肖 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'致富九肖', 'ym'=>$site['name'].'致富九肖', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-三头六尾 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'三头六尾'])->find();
        $jhtlist = Db::name('dg_3t6w')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 三头六尾 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'三头六尾', 'ym'=>$site['name'].'三头六尾', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-必杀一头 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'必杀一头'])->find();
        $jhtlist = Db::name('dg_bs1t')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 必杀一头 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'必杀一头', 'ym'=>$site['name'].'必杀一头', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-平特一尾 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'平特一尾'])->find();
        $jhtlist = Db::name('dg_ptyw')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 平特一尾 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'平特一尾', 'ym'=>$site['name'].'平特一尾', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-平特一肖 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'平特一肖'])->find();
        $jhtlist = Db::name('dg_ptyx')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 平特一肖 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'平特一肖', 'ym'=>$site['name'].'平特一肖', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-独家精料 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'独家精料'])->find();
        $jhtlist = Db::name('dg_hbzt')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 独家精料 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'独家精料', 'ym'=>$site['name'].'独家精料', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴字-四头必中 -------------------------
        $has = Db::name('dg_hkgst')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'四头必中'])->find();
        $jhtlist = Db::name('dg_4tzt')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 四头必中 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_hkgst')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_hkgst')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'四头必中', 'ym'=>$site['name'].'四头必中', 'zz'=>$site['name'], 'addtime'=>time()]);
        }


        //11.高手贴二区-琴棋书画 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'琴棋书画'])->find();
        $jhtlist = Db::name('dg_qqsh')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 琴棋书画 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'琴棋书画', 'ym'=>$site['name'].'琴棋书画', 'zz'=>$site['name'], 'addtime'=>time()]);
        }

        //11.高手贴二区-四段中特 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'四段中特'])->find();
        $jhtlist = Db::name('dg_ptjh')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 四段中特 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'四段中特', 'ym'=>$site['name'].'四段中特', 'zz'=>$site['name'], 'addtime'=>time()]);
        }

        //11.高手贴二区-4头中特 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'4头中特'])->find();
        $jhtlist = Db::name('dg_4tzt')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 4头中特 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'4头中特', 'ym'=>$site['name'].'4头中特', 'zz'=>$site['name'].'4头中特', 'zz'=>$site['name'], 'addtime'=>time()]);
        }

        //11.高手贴二区-吉凶六肖 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'吉凶六肖'])->find();
        $jhtlist = Db::name('dg_jmxc')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 吉凶六肖 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'吉凶六肖', 'ym'=>$site['name'].'吉凶六肖', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴二区-合数大小 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'合数大小'])->find();
        $jhtlist = Db::name('dg_ynsj')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 合数大小 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'合数大小', 'ym'=>$site['name'].'合数大小', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴二区-必中3头 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'必中3头'])->find();
        $jhtlist = Db::name('dg_zylx')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 必中3头 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'必中3头', 'ym'=>$site['name'].'必中3头', 'zz'=>$site['name'], 'addtime'=>time()]);
        }

        //11.高手贴二区-琴棋书画 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'琴棋书画'])->find();
        $jhtlist = Db::name('dg_qqsh')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 琴棋书画 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'琴棋书画', 'ym'=>$site['name'].'琴棋书画', 'zz'=>$site['name'], 'addtime'=>time()]);
        }
        //11.高手贴二区-4头中特 -------------------------
        $has = Db::name('dg_wcsl')->where(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'name'=>'4头中特'])->find();
        $jhtlist = Db::name('dg_4tzt')->where(['status'=>1, 'lotteryType'=>$row['lotteryType']])->order(Db::raw('qi*1 desc'))->limit(10)->select();
        $content = '';
        foreach ($jhtlist as $item){
            $content .= '<p>'.$item['qi'].'期 4头中特 【<span style="color: #2ecc71">'.$item['ju'].'</span>】开 '.$item['kai'].' </p>';
        }
        if($has){
            Db::name('dg_wcsl')->where(['id'=>$has['id']])->update(['content'=>$content]);
        }else{
            Db::name('dg_wcsl')->insertGetId(['qi'=>$qi, 'lotteryType'=>$row['lotteryType'], 'content'=>$content, 'name'=>'4头中特', 'ym'=>$site['name'].'4头中特', 'zz'=>$site['name'], 'addtime'=>time()]);
        }




    }

}