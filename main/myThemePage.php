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
</head>
<body>
    <header id="header">
        <a href="./themePage.php">
            <!-- <img id="iconImg" src="../schema/resource/logo.png"> -->
            <div id="system">TURNING TOUR</div>
        </a>
    </header>

        <?php
            session_start();

            /*** check whether login or not ***/
            $isLogin = false;
            $HostID = -1;
            if($_SESSION)
            {
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
                    <li id='rightHere'><a href='../main/myThemePage.php'>我的活動</a></li>
                    <li><a href='../invite/acceptInvite.php'>活動邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='logined' onmouseover='openAccount()'><a>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="./themePage.php">首頁</option>
                    <option value="./myThemePage.php" selected>我的活動</option>
                    <option value="../invite/acceptInvite.php">活動邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php"> Hello, '.$SessionN.'</option>
                </select>';
            }
            else
            {
                echo
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li id='rightHere'><a href='../mail/login.php?act=1'>我的活動</a></li>
                    <li><a href='../mail/login.php?act=1'>活動邀請</a></li>
                    <li><a href='../mail/login.php?act=1'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="./themePage.php">首頁</option>
                    <option value="../mail/login.php?act=1" selected>我的活動</option>
                    <option value="../mail/login.php?act=1">活動邀請</option>
                    <option value="../mail/login.php?act=1">懲罰轉盤</option>
                    <option value="../mail/login.php?act=1">會員登入</option>
                </select>';
            }

            if($isLogin)
            {
                /*** get all themes ***/
                echo '
                    <div class="container">
                        <div class="main">';
                $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");

                // get all dialog of login account and tag filter
                if(isset($_POST['tag']))
                {
                    $filterT = $_POST['tag'];
                    $ts = join("','",$filterT);   
                    $sql = "select theme_id from theme_tag where tag in ('$ts')";
                    $themeids = mysqli_query($conn, $sql);
                    if($themeids){
                        $num=mysqli_num_rows($themeids);
                        if($num!=0)
                        {
                            while($ids = mysqli_fetch_array($themeids))
                            {
                                $rows[] = $ids["theme_id"];
                            }
                            $tids = join("','",$rows); 
                            if($tids == "")
                            {
                                $tids = -1;
                            }

                            $sql = "select theme_id from participation where attendee_id='$HostID' and is_valid=true";
                            $attTheme = mysqli_query($conn, $sql);
                            if($attTheme){
                                $num1=mysqli_num_rows($attTheme);
                                if($num1!=0)
                                {
                                    while($ids2 = mysqli_fetch_array($attTheme))
                                    {
                                        $rows2[] = $ids2["theme_id"];
                                    }
                                    $atttids = join("','",$rows2);
                                    $sql = "select * from themes where id in ('$tids') and id in ('$atttids') and TO_DAYS(NOW()) - TO_DAYS(time) <= 0 ORDER BY time ASC";
                                }
                            }
                        }
                    }
                }
                else
                {
                    $sql = "select theme_id from participation where attendee_id='$HostID' and is_valid=true";
                    $attTheme = mysqli_query($conn, $sql);
                    if($attTheme){
                        $num=mysqli_num_rows($attTheme);
                        if($num!=0)
                        {
                            while($ids = mysqli_fetch_array($attTheme))
                            {
                                $rows0[] = $ids["theme_id"];
                            }
                            $atttids = join("','",$rows0);
                            $sql = "select * from themes where id in ('$atttids') and TO_DAYS(NOW()) - TO_DAYS(time) <= 0 ORDER BY time ASC";
                        }
                    }
                }

                //for layout
                echo '<div style="margin: 6.4% 100% 0% 0%; float: "right";"></div>';

                // each dialog data
                $themes = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($themes))
                {
                    $RowH= $row['host'];
                    $sql = "select name from account where id = '$RowH'";
                    $name_result = mysqli_query($conn, $sql);
                    $host_name = mysqli_fetch_array($name_result);
                    echo '
                        <div>
                            <form method="POST" action="myThemePage.php">
                                <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                <button class="themes" id="' . $row['id'] . '" type ="submit" onmouseover="overTheme('. $row[id] .')" onmouseleave="leaveTheme('. $row[id] .')" value="">
                                    <h2 class="themeTitle">' . $row['title'] . '</h2>';
                    if($row['img'] != "null")
                    {
                        echo '<img class="themeDiaImg" src="' . $row['img'] . '">';                
                    }
                    if(isset($_POST['tag'])) 
                    {
                        $filterT = $_POST['tag'];
                        $count = count($filterT);
                        for($i=0; $i < $count; $i++)
                        {
                            echo '<input name="tag[]" type="hidden" value="' . $filterT[$i] . '">';
                        }
                    }
                    echo '  
                                    <div class="themeContentT">Host : <span class="themeContent">' . $host_name['name'] . '</span></div>
                                    <div id="hideContent'. $row['id'] .'" class="hideContent"><br>
                                        <div class="themeContentT" id="preDescription">Description : <span class="themeContent"><br>'. $row['description'] .'</span></div><br>
                                        <div class="themeContentT">Time : <span class="themeContent" id="'. $row['id'] .'time">' . $row['time'] . '</span></div>
                                    </div>
                                </button>
                            </form>
                        </div>';
                }
                echo '</div>';

                // filter
                $sql = "select * from tags";
                $tags = mysqli_query($conn, $sql);
                echo '
                    <div class="side">
                        <div>
                            <form method="POST" action="myThemePage.php" id="filterForm">';
                while($row = mysqli_fetch_array($tags))
                {
                    $check="";
                    if(isset($_POST['tag'])) 
                    {
                        $filterT = $_POST['tag'];
                        $count = count($filterT);
                        for($i=0; $i < $count; $i++)
                        {
                            if($filterT[$i] == $row['name'])
                            {
                                $check = "checked";
                            }
                        }
                    }
                    echo '<label class="filter"><input type="checkbox" name="tag[]" class="filterBox" id="filter'. $check .'" value="' . $row['name'] . '">' . $row['name'] . '  </label><br><br>';
                    $check = "";
                } 
                echo '
                                <div style="display:flex; float: right; margin: 0 5% 0 0;"><input type="submit" class="btn" value="Filter">
                                    <input type="reset" class="btn" onclick="resetCheckbox()" value="reset">
                                </div>
                            </form>
                        </div>
                    </div>';
                echo '</div>';


                //view dialog
                if(isset($_POST['themeid']))
                {
                    $t_id = $_POST['themeid'];
                    $sql = "select * from themes where id = $t_id";
                    $theme = mysqli_query($conn, $sql);
                    
                    $sql = "select * from participation where theme_id = $t_id";
                    $t_att = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($theme))
                    {
                        $sql = "select id, name from account";
                        $account = mysqli_query($conn, $sql);
                        $sql = "select * from tags";
                        $tags = mysqli_query($conn, $sql);
                        echo '<div id="blackMask2">
                                <dialog class="dialogs"  id="viewThemeDialog">
                                    <button id = "closeDia" onClick="closeVD()">X</button>
                                    <form method="POST" action="" enctype="multipart/form-data">';
                            echo '      <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                        <input name="attendee" type="hidden" value="'.$HostID.'">';
                            echo '      <label for="title">Title:</label><br>
                                        <input type="text" id="title" name="title" value="'. $row['title'] . '" readonly="readonly"><br><br>';
                                        if($row['img'] != "null")
                                        {
                                            echo '<img class="themeDiaImg" src="' . $row['img'] . '"><br>';                
                                        }
                            echo '       <label for="attendees">Attendees:</label><br>';
                                    $isFirst=true;
                                    $attNames = "";
                                    while($row_account = mysqli_fetch_array($account)){
                                        $sql = "select * from participation where theme_id = $t_id and is_valid = true";
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
                            echo '      <input type="text" id="attendees" name="attendees" value="'.$attNames.'" readonly="readonly"><br><br>';
                            echo '      <label for="time">Time:</label><br>
                                        <input type="datetime-local" id="time" name="time" value="' . date('Y-m-d\TH:i', strtotime($row['time'])) . '" readonly="readonly"><br><br>';
                                    
                            echo '      <label for="description">Description:</label><br>
                                        <textarea type="text" id="description" name="description" readonly="readonly">' . $row['description'] . '</textarea><br><br>
                                        <img class="icon" src="../schema/icon/tag.png" style="width:2%;">';
                            
                            while($rowTags = mysqli_fetch_array($tags))
                            {
                                $sql = "select * from theme_tag where theme_id = $t_id";
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

                            echo       '<br><br>
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

                echo 
                '<script>
                    var diaMask2 = document.getElementById("blackMask2");
                    var viewDia = document.getElementById("viewThemeDialog");
                    if(viewDia)
                    {
                        diaMask2.style.display = "flex";
                        viewDia.show();
                    }
                    function closeVD()
                    {
                        viewDia.close();
                        diaMask2.style.display = "none";
                    }


                    var filterChecked = document.querySelectorAll("#filterchecked"); 
                    filterChecked.forEach(item => {
                        item.checked = true;
                        })
                    function resetCheckbox()
                    {
                        var filterCheckbox = document.querySelectorAll(".filterBox"); 
                        filterCheckbox.forEach(item => {
                            item.checked = false;
                        })
                    }

                    function overTheme(themeId)
                    {
                        var tid = "hideContent" + themeId;
                        var hcId = document.getElementById(tid);
                        hcId.style.maxHeight = "120px";
                    }

                    function leaveTheme(themeId)
                    {
                        var tid = "hideContent" + themeId;
                        var hcId = document.getElementById(tid);
                        hcId.style.maxHeight = "0px";
                    }

                    today = new Date();
                    tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);

                    var allTheme = document.querySelectorAll(".themes"); 
                    allTheme.forEach(item => {
                        var themeTime = document.getElementById(item.id + "time");
                        tTime = new Date(themeTime.innerText);
                        if(today.getYear() == tTime.getYear() && today.getMonth() == tTime.getMonth() &&
                            today.getDate() == tTime.getDate())
                        {
                            item.style.backgroundColor = "rgb(224, 238, 255)";
                            var td = document.createElement("span");
                            td.innerText = " TODAY";
                            td.style.color = "red";
                            themeTime.appendChild(td);
                        }
                    })
                            
                    let target=document.getElementById("select");
                    let lo=document.getElementById("logined");
                    target.style.display="none";
                    function openAccount()
                    {
                        target.style.display="block";
                        lo.append(target);
                        setTimeout(function() {
                            target.style.display="none";
                        }, 2000);
                    }
                </script>';
            }
        ?>

    <footer id="footer">
        © 2021 G3 B10732014 B10732016 B10732021. 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>