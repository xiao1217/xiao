<?php
//error_reporting(0);
require_once 'class/player.php';
require_once 'class/encode.php';
include_once 'pdo.php';

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$encode = new \encode\encode();
$player = new \player\player();
$guaiwu = new \player\guaiwu();
$clmid = new \player\clmid();
$npc = new \player\npc();

$ym = 'game/nowmid.php';
$Dcmd = $_SERVER['QUERY_STRING'];
$pvpts ='';
$tpts = '';
session_start();
//$allow_sep = "220";
//function getMillisecond() {
//    list($t1, $t2) = explode(' ', microtime());
//    return (float)sprintf('%.0f',(floatval($t1) + floatval($t2)) * 1000);
//}
//if (isset($_SESSION["post_sep"]))
//{
//
//    if (getMillisecond() - $_SESSION["post_sep"] < $allow_sep)
//    {
//
//        $msg = '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">你点击太快了^_^!<br/><a href="?'.$Dcmd.'">继续</a>';
//        exit($msg);
//    }
//    else
//    {
//        $_SESSION["post_sep"] = getMillisecond();
//    }
//}
//else
//{
//    $_SESSION["post_sep"] = getMillisecond();
//}

parse_str($Dcmd);
if (isset($cmd)){

    if ($cmd == 'cjplayer'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'djinfo'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'zbinfo'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'npc'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'duihuan'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    if ($cmd == 'sendliaotian'){
        $Dcmd = $encode->encode($Dcmd);
        header("refresh:1;url=?cmd=$Dcmd");
        exit();
    }
    $Dcmd = $encode->decode($cmd);
//    var_dump($Dcmd);
    parse_str($Dcmd);
    switch ($cmd){
        case 'cj':
            $ym = 'game/cj.php';
            break;
        case 'login';
            $player = \player\getplayer($sid,$dblj);
            $gonowmid = $encode->encode("cmd=gomid&newmid=$player->nowmid&sid=$sid");
            $nowdate = date('Y-m-d H:i:s');
            $sql = "update game1 set endtime='$nowdate',sfzx=1 WHERE sid='$sid'";
            $cxjg = $dblj->exec($sql);
            header("refresh:1;url=?cmd=$gonowmid");
            exit();
            break;
        case 'zhuangtai';
            $ym = 'game/zhuangtai.php';
            break;
        case 'cjplayer':

            if (isset($token) && isset($username) && isset($sex)){
                if(strlen($username)<6 || strlen($username)>12){
                    echo "用户名不能太短或者太长";
                    $ym = 'game/cj.php';

                    break;
                }
                $username = htmlspecialchars($username);
                $sid = md5($username.$token.'229');
                $sql="select * from game1 where token='$token'";
                $cxjg = $dblj->query($sql);
                $cxjg->bindColumn('sid',$player->sid);
                $ret = $cxjg->fetch(PDO::FETCH_ASSOC);
                $nowdate = date('Y-m-d H:i:s');
                if ($player->sid ==''){
                    $gameconfig = \player\getgameconfig($dblj);
                    $firstmid = $gameconfig->firstmid;
                    $sql = "insert into game1(token,sid,uname,ulv,uyxb,uexp,uhp,umaxhp,ugj,ufy,uwx,usex,vip,nowmid,endtime,sfzx) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $dblj->prepare($sql);
                    $stmt->execute(array($token,$sid,$username,'1','100','0','100','100','15','5','0',$sex,'0',$firstmid,$nowdate,1));

                    $gonowmid = $encode->encode("cmd=gomid&newmid=$gameconfig->firstmid&sid=$sid");
                    echo '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">';
                    echo $username."欢迎来到天空之城";
                    $sql = "insert into ggliaotian(name,msg,uid) values(?,?,?)";
                    $stmt = $dblj->prepare($sql);
                    $stmt->execute(array('世界',"{$username}踏上了天空之城",'0'));
                    header("refresh:1;url=?cmd=$gonowmid");
                }
                exit();
            }
            break;
        case 'gomid':
            $ym = 'game/nowmid.php';
            break;
        case 'getginfo':
            $ym = 'game/ginfo.php';
            break;
        case 'pve':
            $ym = 'game/pve.php';
            break;
        case 'pvp':
            $ym = 'game/pvp.php';
            break;
        case 'pvegj':
            $ym = 'game/pve.php';
            break;
        case 'sendliaotian':
            if (isset($ltlx) && isset($ltmsg)){
                switch ($ltlx){
                    case 'all':
                        $player = player\getplayer($sid,$dblj);
                        if ($player->uname!=''){
                            $ltmsg = htmlspecialchars($ltmsg);
                            $sql = "insert into ggliaotian(name,msg,uid) values(?,?,?)";
                            $stmt = $dblj->prepare($sql);
                            $exeres = $stmt->execute(array($player->uname,$ltmsg,$player->uid));
                        }
                        $ym = 'game/liaotian.php';
                        break;
                    case "im":
                        $player = player\getplayer($sid,$dblj);
                        if ($player->uname!=''){
                            $ltmsg = htmlspecialchars($ltmsg);
                            $sql = "insert into imliaotian(name,msg,uid,imuid) values('$player->uname','$ltmsg',$player->uid,{$imuid})";

                            $cxjg = $dblj->exec($sql);
                        }
                        $ym = 'game/liaotian.php';
                        break;
                }
            }
            break;
        case 'liaotian':
            $ym ='game/liaotian.php';
            break;
        case 'getplayerinfo':
            $ym ='game/otherzhuangtai.php';
            break;
        case 'zbinfo':
            $ym = 'game/zbinfo.php';
            break;
        case 'npc':
            $ym = "npc/npc.php";
            break;
        case 'paihang';
            $ym = 'game/paihang.php';
            break;
        case 'chakanzb':
            $ym = 'game/zbinfo.php';
            break;
        case 'djinfo':
            $ym = 'game/djinfo.php';
            break;
        case 'getbagzb':
            $ym = 'game/bagzb.php';
            break;
        case 'getbagyp':
            $ym = 'game/bagyp.php';
            break;
        case 'getbagjn':
            $ym = 'game/bagjn.php';
            break;
        case 'xxzb':
            $ym = 'game/zhuangtai.php';
            break;
        case 'setzbwz':
            $ym = 'game/zhuangtai.php';
            break;
        case 'allmap':
            $ym = 'game/allmap.php';
            break;
        case 'delezb':
            $ym = 'game/bagzb.php';
            break;
        case 'getbagdj':
            $ym = 'game/bagdj.php';
            break;
        case 'upzb':
            $ym = 'game/zbinfo.php';
            break;
        case 'goxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'startxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'endxiulian':
            $ym = 'game/xiulian.php';
            break;
        case 'task':
            $ym = 'game/task.php';
            break;
        case 'mytask':
            $ym = 'game/playertask.php';
            break;
        case 'mytaskinfo':
            $ym = 'game/playertaskinfo.php';
            break;
        case 'boss':
            $ym = 'game/bossinfo.php';
            break;
        case 'ypinfo':
            $ym = 'game/ypinfo.php';
            break;
        case 'pvb':
            $ym = 'game/boss.php';
            break;
        case 'chongwu':
            $ym = 'game/chongwu.php';
            break;
        case 'jninfo':
            $ym = 'game/jninfo.php';
            break;
        case "zbinfo_sys":
            $ym = 'game/zbinfo_sys.php';
            break;
        case "tupo":
            $ym = 'game/tupo.php';
            break;
        case "fangshi":
            $ym = "game/fangshi.php";
            break;
        case "club":
            $ym = "game/club.php";
            break;
        case "clublist":
            $ym = "game/clublist.php";
            break;
        case "duihuan":
            $ym = "game/duihuan.php";
            break;
        case "im":
            $ym = "game/im.php";
            break;
    }
    if (!isset($sid) || $sid=='' ){

        if ($cmd!='cj' && $cmd!=='cjplayer'){
            header("refresh:1;url=index.php");
            exit();
        }
    }else{
        if ($cmd != 'pve' && $cmd!='pvegj'){
            $sql = "delete from midguaiwu where sid='$sid'";//删除地图该玩家已经被攻击怪物
            $dblj->exec($sql);
        }
        $player = \player\getplayer($sid,$dblj);
        if ($player->ispvp!=0){
            $pvper = \player\getplayer1($player->ispvp,$dblj);
            $pvpcmd = $encode->encode("cmd=pvp&uid=$pvper->uid&sid=$sid");
            $pvpcmd = "<a href='?cmd=$pvpcmd'>还击</a>";
            $pvpts = "$pvper->uname 正在攻击你：$pvpcmd<br/>";
        }

        $nowdate = date('Y-m-d H:i:s');
        $second=floor((strtotime($nowdate)-strtotime($player->endtime))%86400);//获取刷新间隔
        if ($second>=600){
            echo '<meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">';
            echo $player->uname."离线时间过长，请重新登陆";
            header("refresh:1;url=index.php");
            exit();
        }else{
            $sql = "update game1 set endtime='$nowdate',sfzx=1 WHERE sid='$sid'";
            $dblj->exec($sql);
        }
    }
}else{
    header("refresh:1;url=index.php");
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">
    <title>天空之城</title>
    <link rel="stylesheet" href="css/gamecss.css">
</head>
<body>
<?php

    if (!$ym==''){
        echo$tpts;
        if ($ym!="game/pvp.php"){
            echo $pvpts;
        }

        include "$ym";
    }?>
</body>
<footer>
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
    <?php echo date('Y-m-d H:i:s') ?>
</footer>
</html>