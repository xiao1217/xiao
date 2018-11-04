<?php
$newclub =$encode->encode("cmd=npc&&nid=$nid&canshu=newclub&sid=$sid");
$player = \player\getplayer($sid,$dblj);
$gnhtml =<<<HTML
=======门派创建=======<br/>
路漫漫兮而修远兮<br/>吾将上下而求索<br/>
<a href="?cmd=$newclub" >创建门派</a><br/>
HTML;
$clubplayer = \player\getclubplayer_once($sid,$dblj);
if (isset($canshu)){
    switch ($canshu){
        case "newclub":
            if ($clubplayer){
                $gnhtml= "<br/>你已经有门派了<br/>";
                break;
            }
            $gnhtml=<<<HTML
<form>
<input type="hidden" name="cmd" value="npc">
<input type="hidden" name="nid" value="$nid">
<input type="hidden" name="sid" value="$sid">
<input type="hidden" name="canshu" value="addclub">
门派大名:<input name="clubname"><br/>
门派说明:<textarea name="clubinfo" style="height: 80px"></textarea>
<br/>
<input type="submit" value="创建">
</form>
HTML;
            break;
        case "addclub":
            if ($clubplayer){
                $gnhtml= "<br/>你已经有门派了<br/>";
                break;
            }

            $clubname = htmlspecialchars($clubname);
            $clubinfo = htmlspecialchars($clubinfo);
            if (strlen($clubname)<6 || strlen($clubname)>12){
                $gnhtml = "<br/>名称过长或过短<br/>";
                break;
            }
            $sql = "insert into club(clubname, clubinfo, clublv, clubexp, clubno1) VALUES ('$clubname','$clubinfo',1,0,$player->uid)";
            $row = $dblj->exec($sql);
            $clubid = $dblj->lastInsertId();

            $sql = "insert into clubplayer(clubid, uid, sid, uclv) VALUES ($clubid,$player->uid,'$sid',1)";
            $row = $dblj->exec($sql);
            $clubcmd = $encode->encode("cmd=club&sid=$sid");
            $gnhtml.= "<br/>门派创建成功<br/><a href='?cmd=$clubcmd'>点击进入</a>";
            break;
    }
}

?>

