<?php

include 'pdo.php';
require_once 'class/encode.php';

//header('Access-Control-Allow-Origin:*');

$encode = new \encode\encode();
$a = '';
if (isset($_POST[ 'submit']) && $_POST['submit']){

    $username = $_POST['username'];
    $userpass = $_POST['userpass'];
    $username = htmlspecialchars($username);
    $userpass = htmlspecialchars($userpass);
    $sql = "select * from userinfo where username = ? and userpass = ?";
    $stmt = $dblj->prepare($sql);
    $bool = $stmt->execute(array($username,$userpass));
    $stmt->bindColumn('username',$cxusername);
    $stmt->bindColumn('userpass',$cxuserpass);
    $stmt->bindColumn('token',$cxtoken);
    $exeres = $stmt->fetch(PDO::FETCH_ASSOC);

    if ((strlen($username) < 6 || strlen($userpass) < 6) && !$exeres){
        $a = '账号或密码错误';
    }elseif ($cxusername == $username && $cxuserpass == $userpass){

        $sql = "select * from game1 where token='$cxtoken'";
        $cxjg = $dblj->query($sql);
        $cxjg->bindColumn('sid',$sid);
        $cxjg->fetch(PDO::FETCH_ASSOC);
        if ($sid==null){
            $cmd = "cmd=cj&token=$cxtoken";
        }else{
            $cmd = "cmd=login&sid=$sid";
            $nowdate = date('Y-m-d H:i:s');
            $sql = "update game1 set endtime ='$nowdate',sfzx=1 WHERE sid=?";
            $stmt = $dblj->prepare($sql);
            $stmt->execute(array($sid));
        }
        $cmd = $encode->encode($cmd);
        header("refresh:1;url=game.php?cmd=$cmd");
    }

}
?>
<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport" />
    <title>天空之城</title>
    <link rel="stylesheet" href="css/gamecss.css">
</head>
<body>
<img src="images/logo.png" width='200px' height='60px'><br/>
<div id="mainfont">
<p>畅快PK，天友娱乐家园</p>
</div>
<form action=<?php echo $_SERVER['PHP_SELF']; ?> method="post">
    账号:<input type="text" name="username"><br/><br/>
    密码:<input type="password" name="userpass"><br/>
    <?php echo $a ?>
    <br/><input type="submit" name="submit" value="马上登陆"> <a href="reguser.php" id="btn">注册</a>
</form>
<a href='http://snsky.com'>天空社区</a>-<a href='http://snsky.com/apply.php'>免费自助建站</a>
</body>
<footer>
    <?php  date_default_timezone_set('Aisa/Shanghai'); echo date('Y-m-d H:i:s') ?>
</footer>
</html>