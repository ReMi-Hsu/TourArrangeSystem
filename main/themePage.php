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
                    <li id='rightHere'><a href='./themePage.php'>首頁</a></li>
                    <li><a href='./myThemePage.php'>我的活動</a></li>
                    <li><a href='../invite/acceptInvite.php'>活動邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='logined' onmouseover='openAccount()'><a> Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="./themePage.php" selected>首頁</option>
                    <option value="./myThemePage.php">我的活動</option>
                    <option value="../invite/acceptInvite.php">活動邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php"> Hello, '.$SessionN.'</option>
                </select>';
                $themeAction = "themePage.php";
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li id='rightHere'><a href='./themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php?act=1'>我的活動</a></li>
                    <li><a href='../mail/login.php?act=1'>活動邀請</a></li>
                    <li><a href='../mail/login.php?act=1'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="./themePage.php" selected>首頁</option>
                    <option value="../mail/login.php?act=1">我的活動</option>
                    <option value="../mail/login.php?act=1">活動邀請</option>
                    <option value="../mail/login.php?act=1">懲罰轉盤</option>
                    <option value="../mail/login.php?act=1">會員登入</option>
                </select>';
                $themeAction = "../mail/login.php?act=1";
            }

            /*** get all themes ***/
            echo '<div class="container">';
            $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
            echo '<div class="main">';
            echo '<button onclick="creatTheme()" class="btn" id="createBtn"> Create </button>';
            // get all dialog of login account and tag filter
            if(isset($_POST['tag']))
            {
                $filterT = $_POST['tag'];
                $ts = join("', '",$filterT);   
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
                        $tids = join("', '",$rows); 
                        if($tids == "")
                        {
                            $tids = -1;
                        }
                        $sql = "select * from themes where id in ('$tids') and TO_DAYS(NOW()) - TO_DAYS(time) <= 0 ORDER BY time ASC";
                    }
                }
            }
            else
            {
                $sql = 'select * from themes where TO_DAYS(NOW()) - TO_DAYS(time) <= 0 ORDER BY time ASC';
            }

            // each dialog data
            echo '<div id="themeContainer">';
            $themes = mysqli_query($conn, $sql);
            $countT = 0;
            while($row = mysqli_fetch_array($themes))
            {
                $RowH= $row['host'];
                $sql = "select name from account where id = '$RowH'";
                $name_result = mysqli_query($conn, $sql);
                $host_name = mysqli_fetch_array($name_result);
                echo '<div class="eachTheme'. $countT .'"><form method="POST" action="'. $themeAction .'">
                        <input name="themeid" type="hidden" value="' . $row['id'] . '">
                        <button class="themes" id="' . $row['id'] . '" type ="submit" onmouseover="overTheme('. $row[id] .')" onmouseleave="leaveTheme('. $row[id] .')" value="">
                        <h2 class="themeTitle">' . $row['title'] . '</h2>';
                if($row['img'] != "null")
                {
                    echo '<img class="themeImg" src="' . $row['img'] . '">';                
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
               
                echo '  <div class="themeContentT">Host : <span class="themeContent" id="'. $row['id'] .'host">' . $host_name['name'] . '</span></div>
                        <div id="hideContent'. $row['id'] .'" class="hideContent"><br>
                        <div class="themeContentT" id="preDescription">Description : <span class="themeContent"><br>'. $row['description'] .'</span></div><br>
                        <div class="themeContentT">Time : <span class="themeContent" id="'. $row['id'] .'time">' . $row['time'] . '</span>
                        </div></div>
                        </button></form></div>';
                $countT = ($countT + 1) % 2;
            }
            echo '  <div class="tc" id="tc1"></div>
                    <div class="tc" id="tc2"></div>
                  </div>
                </div>';

            // filter
            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<div class="side">';
            echo '<div>
                <form method="POST" action="themePage.php" id="filterForm">';
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
            echo '<div style="display:flex; float: right; margin: 0 5% 0 0;"><input type="submit" class="btn" value="Filter">
                <input type="reset" class="btn" onclick="resetCheckbox()" value="reset"></div>
                </form> </div> </div>';
            echo '</div>';

            //create dialog
            $sql = "select id, name from account";
            $account = mysqli_query($conn, $sql);
            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<div id="blackMask">
                    <dialog class="dialogs" id="createThemeDialog">
                        <button id = "closeDia" onClick="closeCD()">X</button>
                        <form method="POST" action="./creatTheme.php" enctype="multipart/form-data">
                            <input name="host" type="hidden" value='.$HostID.'>
                            <label for="title">Title*:</label><br>
                            <input type="text" id="title" name="title" placeholder="Theme of the conference" required><br><br>
                            <label for="time">Time:</label><br>
                            <input type="datetime-local" id="time" name="time" required><br><br>
                            <input type="file" name="file" accept="image/*"><br><br>
                            <label for="invite">Invite:</label><br>';
            echo '          <select class="attSelect" name="invites[]" multiple="multiple" size="3">';
                            while($row_account = mysqli_fetch_array($account)){
                                if($row_account['id']!=$HostID){
                                    $name = $row_account['name'];
                                    echo '<option value='.$row_account['id'].'>' . $name .'  </option>';
                                }
                            }
            echo '      </select><br>';
            echo '          <label for="description">Description:</label><br>
                            <textarea type="text" id="description" name="description" maxlength="500" placeholder=""></textarea><br><br>
                            <img class="icon" src="../schema/icon/tag.png" style="width:2%;"><br>';
                $count = 0;
                while($row = mysqli_fetch_array($tags))
                {
                    echo '<label class="filterGroup"><input type="checkbox" name="tag[]" value="' . $row['name'] . '">' . $row['name'] . '  </label>';
                    if($count == 4)
                    {
                        echo '<br><br>';
                    }
                    $count++;
                }
                echo        '<br><br>
                        <div style="display:flex; float: right;">
                            <input type="submit" class="btn" name="create" value="Submit">
                        </form> </div>
                    </dialog></div>';  


            //edit and view dialog
            if(isset($_POST['themeid']))
            {
                $t_id = $_POST['themeid'];
                $sql = "select id, name from account";
                $account = mysqli_query($conn, $sql);
                $sql = "select * from themes where id = $t_id";
                $theme = mysqli_query($conn, $sql);
                $sql = "select * from theme_tag where theme_id = $t_id";
                $t_tag = mysqli_query($conn, $sql);
                $sql = "select * from tags";
                $tags = mysqli_query($conn, $sql);
                $sql = "select * from participation where theme_id = $t_id";
                $t_att = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($theme))
                {
                    if($HostID == $row['host'])
                    {
                        echo '<div id="blackMask2">
                                <dialog class="dialogs" id="editThemeDialog">
                                    <button id = "closeDia" onClick="closeED()">X</button>
                                    <form method="POST" action="./editTheme.php" enctype="multipart/form-data">';
                            echo '      <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                        <input name="host" type="hidden" value="' . $row['host'] . '">
                                        <label for="title">Title*:</label><br>
                                        <input type="text" id="title" name="title" value="'. $row['title'] . '" required><br><br>
                                        <label for="file">Original Image:<label><br>';
                                        if($row['img'] != "null")
                                        {
                                            echo '<img class="themeDiaImg" src="' . $row['img'] . '">';                
                                        }
                            echo '      <input type="file" name="file" accept="image/*"><br><br>';
                            echo '      <label for="attendees">Attendees: </label><br>';
                                            $isFirst=true;
                                            $attNames = "";
                                            $sql = "select id, name from account";
                                            $account = mysqli_query($conn, $sql);
                                            while($row_account = mysqli_fetch_array($account)){
                                                $sql = "select * from participation where theme_id = $t_id";
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
                            echo '          <input type="text" id="attendees" name="attendees" value="'.$attNames.'" readonly="readonly"><br>';
                            echo '          <label for="invite">Invite:</label><br>
                                            <select class="attSelect" name="invites[]" multiple="multiple" size="3">';
                                            $sql = "select id, name from account";
                                            $account = mysqli_query($conn, $sql);
                                            while($row_account = mysqli_fetch_array($account)){
                                                //echo ''.$row_account['id'].'<br>';
                                                $name = $row_account['name'];
                                                $isValid = false;
                                                $isInvite = false;
                                                $sql = "select * from participation where theme_id = '$t_id'";
                                                $t_att = mysqli_query($conn, $sql);
                                                while($row_att =  mysqli_fetch_array($t_att))
                                                {
                                                    //echo ''.$row_att['attendee_id'].'<br>';
                                                    if($row_account['id'] == $row_att['attendee_id']){
                                                        if($row_att['is_valid'] == true){
                                                            $isValid = true;
                                                        }
                                                        else{
                                                            $isInvite = true;
                                                        }
                                                    }
                                                }
                                                if($isValid==false){
                                                    if($isInvite==true){
                                                        echo '<option value='.$row_account['id'].' selected>' . $name .'  </option>';
                                                    }
                                                    else{
                                                        echo '<option value='.$row_account['id'].'>' . $name .'  </option>';
                                                    }
                                                }
                                            }
                                echo '      </select><br><br>';
                        echo '          <label for="time">Time:</label><br>
                                        <input type="datetime-local" id="time" name="time" value= "'. date('Y-m-d\TH:i', strtotime($row['time'])) .'" required><br><br>
                                        <label for="description">Description:</label><br>
                                        <textarea type="text" id="description" name="description" maxlength="500">' . $row['description'] . '</textarea><br><br>
                                        <img class="icon" src="../schema/icon/tag.png" style="width:2%;"><br>';
                            $count = 0;
                            while($rowTags = mysqli_fetch_array($tags))
                            {
                                while($rowT = mysqli_fetch_array($t_tag))
                                {
                                    if($rowTags['name'] == $rowT['tag'])
                                    {
                                        $check = "checked";
                                    }
                                }
                                echo '  <label class="filterGroup"><input type="checkbox" name="tag[]" value="' . $rowTags['name'] . '" '. $check .'>' . $rowTags['name'] . '  </label>';
                                
                                mysqli_data_seek($t_tag, 0);
                                $check = "";
                                if($count == 4)
                                {
                                    echo '<br><br>';
                                }
                                $count++;
                            }
                            echo       '<div style="display:flex; float: right;">
                                        <input type="submit" class="btn" name="update" value="Update">
                                        </form> 
                                        <form method="POST" action="./deleteTheme.php">
                                            <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                            <input type="submit" class="btn" name="delete" value="Delete">
                                        </form></div>
                                </dialog></div>'; 
                    }
                    else
                    {
                        $BtnToPage = "../mail/login.php?act=1";
                        if($HostID!=-1){
                            $BtnToPage = "./join.php";
                        }
                        echo '<div id="blackMask2">
                                <dialog class="dialogs" id="viewThemeDialog">
                                    <button id = "closeDia" onClick="closeVD()">X</button>
                                    <form method="POST" action="'.$BtnToPage .'" enctype="multipart/form-data">';
                            echo '      <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                        <input name="attendee" type="hidden" value="'.$HostID.'">';
                            echo '      <label for="title">Title:</label><br>
                                        <input type="text" id="title" name="title" value="'. $row['title'] . '" readonly="readonly"><br><br>';
                                        if($row['img'] != "null")
                                        {
                                            echo '<img class="themeDiaImg" src="' . $row['img'] . '"><br><br>';                
                                        }
                                        echo '      <label for="attendees">Attendees: </label><br>';
                                    $isFirst=true;
                                    $attNames = "";
                                    $sql = "select id, name from account";
                                    $account = mysqli_query($conn, $sql);
                                    while($row_account = mysqli_fetch_array($account)){
                                        $sql = "select * from participation where theme_id = '$t_id' and is_valid=true";
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
                            echo '      <input type="text" id="attendees" name="attendees" value="'.$attNames.'" readonly="readonly"><br>';          
                            echo '      <br><label for="time">Time:</label><br>
                                        <input type="datetime-local" id="time" name="time" value="' . date('Y-m-d\TH:i', strtotime($row['time'])) . '" readonly="readonly"><br><br>
                                        <label for="description">Description:</label><br>
                                        <textarea type="text" id="description" name="description" readonly="readonly">' . $row['description'] . '</textarea><br><br>
                                        <img class="icon" src="../schema/icon/tag.png" style="width:2%;"><br>';
                            $count = 0;
                            while($rowTags = mysqli_fetch_array($tags))
                            {
                                while($rowT = mysqli_fetch_array($t_tag))
                                {
                                    if($rowTags['name'] == $rowT['tag'])
                                    {
                                        echo '  <label class="filterGroup"> ' . $rowTags['name'] . '  </label>';
                                    }
                                }
                                                    
                                mysqli_data_seek($t_tag, 0);
                                if($count == 4)
                                {
                                    echo '<br><br>';
                                }
                                $count++;
                            }

                            $disBtn="";
                            $sql = "select * from participation where theme_id = '$t_id' and is_valid=true";
                            $t_att = mysqli_query($conn, $sql);
                            while($rowAtt = mysqli_fetch_array($t_att))
                            {
                                if($rowAtt['attendee_id'] == $HostID)
                                {
                                    $disBtn = "disabled";
                                }
                            }

                            $disCancel="";
                            if($disBtn != "disabled")
                            {
                                $disCancel = "disabled";
                            }
                            if($HostID!=-1){
                                $BtnToPage = "./cancel.php";
                            }
                            
                            echo       '<div style="display:flex; float: right;">
                                        <input type="submit" class="btn" name="join" value="Join" ' . $disBtn . '>
                                        </form> 
                                        <form method="POST" action="'.$BtnToPage.'">
                                            <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                            <input name="attendee" type="hidden" value="'.$HostID.'">';
                            echo       '    <input type="submit" class="btn" name="cancel" value="Cancel"' . $disCancel . '>
                                        </form></div>
                                </dialog></div>'; 
                        $disBtn = "";
                        $disCancel = "";
                    }
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
                var createDia = document.getElementById("createThemeDialog");
                var diaMask = document.getElementById("blackMask");
                function creatTheme()
                {
                    if('.$HostID.'==-1)
                    {
                        window.location.href = "../mail/login.php?act=1";
                    }
                    else{
                        diaMask.style.display = "flex";
                        createDia.show();
                    }
                }
                function closeCD()
                {
                    createDia.close();
                    diaMask.style.display = "none";
                }

                var diaMask2 = document.getElementById("blackMask2");
                var editDia = document.getElementById("editThemeDialog");
                if(editDia)
                {
                    if('.$HostID.'==-1)
                    {
                        window.location.href = "../mail/login.php?act=1";
                    }
                    else{
                        diaMask2.style.display = "flex";
                        editDia.show();
                    }
                }
                function closeED()
                {
                    editDia.close();
                    diaMask2.style.display = "none";
                }

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

                    var themeHost = document.getElementById(item.id + "host");
                    if(themeHost.innerText == "'. $SessionN .'")
                    {
                        themeHost.style.color = "blue";
                    }
                })

                window.onload = function()
                {
                    var tc = document.getElementsByClassName("tc");
                    var theme0 = document.querySelectorAll(".eachTheme0"); 
                    theme0.forEach(item => {
                        tc[0].append(item);
                    })

                    var theme1 = document.querySelectorAll(".eachTheme1"); 
                    theme1.forEach(item => {
                        tc[1].append(item);
                    })
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
        © 2021 G3 B10732014 B10732016 B10732021. 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>