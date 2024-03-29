<?php
    session_start();
    if(!($_SESSION['email']))
    {
        header("location:./login.php?act=1");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTFF-8">
    <!-- FIXME:content -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../schema/themePage.css">
</head>
<body>
    <header id="header">
        <a href="../main/themePage.php">
            <img id="iconImg" src="../schema/resource/logo.png">
        </a>
    </header>
    
        <!-- FIXME:連結 -->
        <?php            
            /*** check whether login or not ***/
            $isLogin = false;
            $HostID = -1;
            if($_SESSION){
                if($_SESSION['email'])
                {
                    $isLogin=true;

                    // get host_id
                    $conn=mysqli_connect("localhost", "root","","theme_arrangement");
                    $SessionM = $_SESSION['email'];
                    $sql = "select id from account where email = '$SessionM'";
                    $result=mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    $HostID = $row['id'];
                    //echo "host id: ". $HostID ."<br>";
                }
            }

            /*** display nav acoording to login status ***/
            if($isLogin)
            {
                $SessionN = $_SESSION['name'];
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../main/myThemePage.php'>我的議程</a></li>
                    <li><a href='../invite/acceptInvite.php'>議程邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/main.php'>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php'>我的議程</a></li>
                    <li><a href='../mail/login.php'>議程邀請</a></li>
                    <li><a href='../mail/login.php'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";
            }    
        ?>
        <form method="POST" action="./updatea.php">
            Email:&nbsp;<?php echo $_SESSION['email'] ?> <br>
            *姓名:&nbsp;&nbsp; <input type="text" name="name" value="<?php echo $_SESSION['name'] ?>" required ><br>
            *生日:&nbsp;&nbsp; <input type="date" value="<?php echo $_SESSION['birthday'] ?>" name="birthday" required ><br>
            *性別:&nbsp;&nbsp; <select name="sex" value="<?php echo $_SESSION['sex'] ?>" required ><br>
                    <option value="女">女</option>
                    <option value="男">男</option>
                </select><br>
            <input type="reset" value="重設" >
            <input type="submit" value="提交">
        </form>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>