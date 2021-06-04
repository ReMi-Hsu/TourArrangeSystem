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
            <!-- <img id="iconImg" src="../schema/resource/logo.png"> -->
            <div id="system">TURNING TOUR</div>
        </a>
    </header>
    
        <!-- FIXME:連結 -->
        <?php  
            session_start();
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

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../main/myThemePage.php">我的議程</option>
                    <option value="../invite/acceptInvite.php">議程邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php" selected> Hello, '.$SessionN.'</option>
                </select>';
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

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../mail/login.php">我的議程</option>
                    <option value="../mail/login.php">議程邀請</option>
                    <option value="../mail/login.php">懲罰轉盤</option>
                    <option value="../mail/login.php" selected>會員登入</option>
                </select>';
            }    
        ?>
        <form method="POST" action="./sentpassword.php">
            Email:&nbsp;<input type="email" name="email" required ><br>
            <input type="reset" value="重設" >
            <input type="submit" value="提交">
        </form>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>