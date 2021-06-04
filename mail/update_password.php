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
            <!-- <img id="iconImg" src="../schema/resource/logo.png"> -->
            <div id="system">TURNING TOUR</div>
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
                    <li><a href='../main/myThemePage.php'>我的活動</a></li>
                    <li><a href='../invite/acceptInvite.php'>活動邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/main.php'>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../main/myThemePage.php">我的活動</option>
                    <option value="../invite/acceptInvite.php">活動邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php" selected> Hello, '.$SessionN.'</option>
                </select>';
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php?act=1'>我的活動</a></li>
                    <li><a href='../mail/login.php?act=1'>活動邀請</a></li>
                    <li><a href='../mail/login.php?act=1'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";
                
                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../mail/login.php?act=1">我的活動</option>
                    <option value="../mail/login.php?act=1">活動邀請</option>
                    <option value="../mail/login.php?act=1">懲罰轉盤</option>
                    <option value="../mail/login.php?act=1" selected>會員登入</option>
                </select>';
            }    
        ?>
        <div class="loginForm">
            <form method="POST" action="updatep.php" id="loginForm" style="margin-top: 5%; padding: 0px 0px 0px 20%;">
                原本密碼:&emsp;&emsp;&nbsp; <input type="password" id="opsw" name="password" pattern="{8,}" style="margin-top: 5%">
                <span  id="len"><font color="#4CAF50">ok</font></span><br>
                新密碼:&emsp;&emsp;&emsp;&nbsp; <input type="password" id="psw1" name="newpassword1" pattern="{8,}" style="margin-top: 5%">
                <span  id="len1"><font color="#4CAF50">ok</font></span><div style="font-size:1vmin">請輸入8位以上</div>
                重新輸入密碼:&nbsp; <input type="password" id="psw2" name="newpassword2" pattern="{8,}" style="margin-top: 5%">
                <span  id="len2"><font color="#4CAF50">ok</font></span><div style="font-size:1vmin">請輸入8位以上</div><br>
                <div style="display:flex; float: right;">
                <input type="reset" class="btn" value="重設" >
                <input type="submit" class="btn" value="提交">
                </div>
            </form>
        </div>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>

    <script>
        var myInput = document.getElementById("opsw");
        var myInput1 = document.getElementById("psw1");
        var myInput2 = document.getElementById("psw2");
        var len = document.getElementById("len");
        var len1 = document.getElementById("len1");
        var len2 = document.getElementById("len2");
        len.style.display="none";
        len1.style.display="none";
        len2.style.display="none";
        myInput.onkeyup = function() {
            if(myInput.value.length >= 8) {
            len.style.display="inline";
        } else {
            len.style.display="none";
        }
        }
        myInput1.onkeyup = function() {
            if(myInput1.value.length >= 8) {
            len1.style.display="inline";
        } else {
            len1.style.display="none";
        }
        }
        myInput2.onkeyup = function() {
            if(myInput2.value.length >= 8) {
            len2.style.display="inline";
        } else {
            len2.style.display="none";
        }
        }
    </script>
</body>
</html>