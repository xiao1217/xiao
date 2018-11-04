<?php
include 'pdo.php';
$a = '';
    if (isset($_POST[ 'submit']) && $_POST['submit'] ){
        $username = $_POST['username'];
        $userpass = $_POST['userpass'];
        $username = htmlspecialchars($username);
        $userpass = htmlspecialchars($userpass);
        $sql = "select * from userinfo where username=?";
        $stmt = $dblj->prepare($sql);
        $stmt->execute(array($username));
        $stmt->bindColumn('username',$cxusername);
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
		if (strlen($username) < 6 or strlen($userpass)< 6){
            $a = '账号或密码长度请大于或等于6位';
        }elseif ($ret){
            $a = '注册失败,账号'.$cxusername.'已经存在';
        }else{
            $token = md5("$username.$userpass".strtotime(date('Y-m-d H:i:s')));
            $sql = "insert into userinfo(username,userpass,token) values('$username','$userpass','$token')";
            $cxjg = $dblj->exec($sql);
            $a = '注册成功';
            header("refresh:1;url=index.php");
        }
    }

    ?>
<html lang="en">
<head>
    <meta charset="utf-8" content="width=device-width,user-scalable=no" name="viewport">
    <title>天空之城</title>

    <link rel="stylesheet" href="css/gamecss.css">
</head>
<body>
<img src="images/logo.png" width='200px' height='60px'>
<p>纪念一起走过的日子</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    账号：<input type="text" name="username"><br/><br/>
    密码：<input type="password" name="userpass"><br/>
    <?php echo $a ?>
    <p><input type="submit" name="submit" value="确定注册"> <a href="index.php" id="btn">登陆</a></p>
</form>
<a href='http://snsky.com'>天空社区</a>-<a href='http://snsky.com/apply.php'>免费自助建站</a><br/>
</body>
<?php echo date('Y-m-d H:i:s') ?>
</html>