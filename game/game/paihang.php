<?php
$sql = 'SELECT * FROM game1 ORDER BY ulv DESC,uexp ASC LIMIT 10';//列表获取
$player = player\getplayer($sid,$dblj);
$phcxjg = $dblj->query($sql);
$phhtml='';
$phlshtml='';
$backcmd=$encode->encode("cmd=gomid&newmid=$player->nowmid&sid=$sid");
if ($phcxjg){
    $ret = $phcxjg->fetchAll(PDO::FETCH_ASSOC);
    for ($i=0;$i < count($ret);$i++){
        $uname = $ret[$i]['uname'];
        $ulv = $ret[$i]['ulv'];
        $uid = $ret[$i]['uid'];
        $cxsid = $ret[$i]['sid'];
        $clubp = \player\getclubplayer_once($cxsid,$dblj);
        if ($clubp){
            $club = \player\getclub($clubp->clubid,$dblj);
            $club->clubname ="[$club->clubname]";
        }else{
            $club = new \player\club();
            $club->clubname ="";
        }
        $ucmd = $encode->encode("cmd=getplayerinfo&uid=$uid&sid=$player->sid");
        $xuhao = $i+1;

        $phlshtml .="$xuhao:[lv$ulv]<a href='?cmd=$ucmd'>{$club->clubname}$uname</a><br/>";
    }
    $phhtml.=<<<HTML
    天空之城等级排行榜<br/>
    $phlshtml
    <br/>
    <button onClick="javascript :history.back(-1);">返回上一页</button><br/>
    <a href="?cmd=$backcmd">返回游戏</a>
HTML;
    echo $phhtml;
}
?>