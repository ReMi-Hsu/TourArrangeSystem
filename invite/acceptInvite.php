<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTFF-8">
    <!-- FIXME:content -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Arrangement</title>
    <link rel="stylesheet" href="../schema/themePage.css">
    <link rel="stylesheet" href="../schema/invite.css">
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
                    <li><a href='../main/myThemePage.php'>我的活動</a></li>
                    <li id='rightHere'><a href='../invite/acceptInvite.php'>活動邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='logined' onmouseover='openAccount()'><a>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../main/myThemePage.php">我的活動</option>
                    <option value="../invite/acceptInvite.php" selected>活動邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php"> Hello, '.$SessionN.'</option>
                </select>';
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php?act=1'>我的活動</a></li>
                    <li id='rightHere'><a href='../mail/login.php?act=1'>活動邀請</a></li>
                    <li><a href='../mail/login.php?act=1'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../mail/login.php?act=1">我的活動</option>
                    <option value="../mail/login.php?act=1" selected>活動邀請</option>
                    <option value="../mail/login.php?act=1">懲罰轉盤</option>
                    <option value="../mail/login.php?act=1">會員登入</option>
                </select>';
            }    

            if($isLogin)
            {
                /*** get all inivitations ***/
                echo '
                    <div class="container">
                        <div class="main" style = "flex: 100%; height: 1500px;">';

                $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
                    
                /*** invite block ***/
                $sql = "select * from participation where attendee_id = '$HostID' and is_valid = false";
                $not_valid_result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($not_valid_result)){
                    $row_theme_id = $row['theme_id'];
                    $sql = "select p.theme_id, t.title, a.name 
                            from participation as p, themes as t, account as a 
                            where p.theme_id = '$row_theme_id' and t.id=p.theme_id and p.attendee_id = t.host and t.host=a.id";
                    $theme_result = mysqli_query($conn, $sql);
                    $theme_row = mysqli_fetch_array($theme_result);
                    $theme_n = $theme_row['title'];
                    $theme_h = $theme_row['name'];
                    echo '
                            <div id = "inviteDiv">
                                <button id="inviteBtn" onclick="showDialog('.$row_theme_id.')">
                                    <span><b>'.$theme_h.'</b> 已邀請你加入<b> '.$theme_n.' </b>活動</span>
                                </button>
                                <form method="POST" action="./accept.php" class = "invContainer">
                                    <input name="themeid" type="hidden" value="' . $row_theme_id . '">
                                    <input name="attendeeid" type="hidden" value="' . $HostID . '">
                                    <input type="submit" class="btn" id="Invsubmit" name="reject" value="Reject" >
                                    <input type="submit" class="btn" id="Invsubmit" name="accept" value="Accept">
                                </form>
                            </div>';
                }
                echo '
                        </div>
                    </div>';

                /*** view dialog ***/
                $sql = "select * from participation where attendee_id = '$HostID' and is_valid = false";
                $not_valid_result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($not_valid_result)){
                    $sql = "select id, name from account";
                    $account = mysqli_query($conn, $sql);
                    $row_theme_id = $row['theme_id'];
                    $sql = "select * from theme_tag where theme_id = $row_theme_id";
                    $t_tag = mysqli_query($conn, $sql);
                    $sql = "select * from tags";
                    $tags = mysqli_query($conn, $sql);
                    $sql = "select * from participation where theme_id = $row_theme_id and is_valid = true";
                    $t_att = mysqli_query($conn, $sql);
                    $sql = "select p.theme_id, t.title, t.img, t.description, t.time, a.name 
                                from participation as p, themes as t, account as a 
                                where p.theme_id = '$row_theme_id' and t.id=p.theme_id and p.attendee_id = t.host and t.host=a.id";
                    $theme_result = mysqli_query($conn, $sql);
                    $theme_row = mysqli_fetch_array($theme_result);
                    $theme_t= $theme_row['title'];
                    $theme_h = $theme_row['name'];
                    $theme_i = $theme_row['img'];
                    $theme_time = $theme_row['time'];
                    $theme_d = $theme_row['description'];

                    echo '<div class="blackMask" id="blackMask'.$row_theme_id.'">
                        <dialog class="dialogs" id = "viewInvThemeDia'.$row_theme_id.'">
                            <button id = "closeDia" onClick="closeDialog('.$row_theme_id.')">X</button>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input name="themeid" type="hidden" value="' . $row_theme_id . '">
                                <input name="attendee" type="hidden" value="'.$HostID.'">
                                <label for="title">Title:</label><br>
                                <input type="text" id="title" name="title" value="'. $theme_t . '" readonly="readonly"><br><br>';
                                if($theme_i != "null")
                                {
                                    echo '<img class="themeImg" src="' . $theme_i . '"><br><br>';                
                                }
                    echo '      <label for="attendees">Attendees:</label><br>';
                                $isFirst=true;
                                $attNames = "";
                                while($row_account = mysqli_fetch_array($account)){
                                    $sql = "select * from participation where theme_id = $row_theme_id and is_valid = true";
                                    $t_att = mysqli_query($conn, $sql);
                                    while($row_att =  mysqli_fetch_array($t_att))
                                    {
                                        if($row_account['id'] == $row_att['attendee_id']){
                                            if($isFirst){
                                                $isFirst=false;
                                                $attNames = $attNames . $row_account['name'];
                                            }
                                            else{
                                                $attNames = $attNames . ", " . $row_account['name'];
                                            }
                                        }
                                    }
                                }
                    echo '      <input type="text" id="attendees" name="attendees" value="'.$attNames.'" readonly="readonly">
                                <br><br><label for="time">Time:</label><br>
                                <input type="date" id="time" name="time" id="time" value=' . $theme_time . ' required readonly="readonly"><br><br>';
                                
                    echo '      <label for="description">Description:</label><br>
                                <textarea type="text" id="description" name="description" readonly="readonly">' . $theme_d . '</textarea><br><br>
                                <img class="icon" src="../schema/icon/tag.png" style="width:2%;">';

                    while($rowTags = mysqli_fetch_array($tags))
                    {
                        $sql = "select * from theme_tag where theme_id = $row_theme_id";
                        $t_tag = mysqli_query($conn, $sql);
                        while($rowT = mysqli_fetch_array($t_tag))
                        {
                            if($rowTags['name'] == $rowT['tag'])
                            {
                                echo '  <label class="filterGroup"> ' . $rowTags['name'] . '  </label>';
                            }
                        }
                                            
                        mysqli_data_seek($t_tag, 0);
                    }

                    echo '       <br><br>';
                    echo '
                            </form> 
                        </dialog></div>'; 
                }
            }

            echo '
            <div class="select" id="select">
                <a class="acc" href="../mail/logout.php">登出</a>&nbsp;<br>
                <a class="acc" href="../mail/upadate_account.php">修改帳戶</a>&nbsp;<br>
                <a class="acc" href="../mail/update_password.php">修改密碼</a>&nbsp;<br>
            </div>';

            echo'
            <script>
                
                function showDialog(i) {
                    var diaMask2 = document.getElementById("blackMask" + i.toString());
                    var id = "viewInvThemeDia"+i.toString();
                    diaMask2.style.display = "flex";
                    document.getElementById(id).open = true;
                }

                function closeDialog(i) {
                    var diaMask2 = document.getElementById("blackMask" + i.toString());
                    var id= "viewInvThemeDia"+i.toString();
                    diaMask2.style.display = "none";
                    document.getElementById(id).open = false;
                }

                let target=document.getElementById("select");
                let lo=document.getElementById("logined");
                target.style.display="none";
                function openAccount()
                {
                    target.style.display="block";
                    lo.append(target);
                    setTimeout(function() {
                        target.style.display="none";
                    }, 1000);
                }
            </script>';
        ?>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>