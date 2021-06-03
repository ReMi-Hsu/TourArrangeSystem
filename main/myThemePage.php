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
            <img id="iconImg" src="../schema/resource/logo.png">
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
                    <li id='rightHere'><a href='../main/myThemePage.php'>我的議程</a></li>
                    <li><a href='../invite/acceptInvite.php'>議程邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/main.php'>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";
            }
            else
            {
                echo
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li id='rightHere'><a href='../mail/login.php'>我的議程</a></li>
                    <li><a href='../mail/login.php'>議程邀請</a></li>
                    <li><a href='../mail/login.php'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";
            }

            /*** get all themes ***/
            echo '<div class="container">';
            $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
            echo '<div class="main">';

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

                        $sql = "select theme_id from participation where attendee_id='$HostID'";
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
                $sql = "select theme_id from participation where attendee_id='$HostID'";
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

            // each dialog data
            $themes = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($themes))
            {
                $RowH= $row['host'];
                $sql = "select name from account where id = '$RowH'";
                $name_result = mysqli_query($conn, $sql);
                $host_name = mysqli_fetch_array($name_result);
                echo '<div><form method="POST" action="myThemePage.php">
                        <input name="themeid" type="hidden" value="' . $row['id'] . '">
                        <button class="themes" type ="submit" value="">
                        <h2>' . $row['title'] . '</h2>
                        <div>Host : ' . $host_name['name'] . '</div>';
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
                echo '  <div>' . $row['time'] . '</div>
                        </button></form></div>';
            }
            echo '</div>';

            // filter
            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<div class="side">';
            echo '<div>
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
            echo '<input type="submit" class="btn" value="Filter">
                <input type="reset" class="btn" onclick="resetCheckbox()" value="reset">
                </form> </div> </div>';
            echo '</div>';


            //edit and view dialog
            if(isset($_POST['themeid']))
            {
                $t_id = $_POST['themeid'];
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
                    echo '<dialog id="viewThemeDialog">
                            <button id = "closeDia" onClick="closeVD()">X</button>
                            <form method="POST" action="" enctype="multipart/form-data">';
                    echo '      <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                <input name="attendee" type="hidden" value="'.$HostID.'">';
                    echo '      <label for="title">Title:</label>
                                <input type="text" id="title" name="title" value=" '. $row['title'] . '" readonly="readonly"><br><br>';
                                if($row['img'] != "null")
                                {
                                    echo '<img class="themeImg" src="' . $row['img'] . '">';                
                                }
                                else
                                {
                                    echo 'NULL';
                                }
                    echo '      <br><br>
                                <label for="time">Time:</label>
                                <input type="datetime-local" id="time" name="time" value="' . date('Y-m-d\TH:i', strtotime($row['time'])) . '" readonly="readonly"><br><br>
                                <label for="description">Description:</label><br>
                                <textarea type="text" id="description" name="description" readonly="readonly">' . $row['description'] . '</textarea><br><br>
                                <img class="icon" src="../schema/icon/tag.png" style="width:2%;">';
                    while($rowTags = mysqli_fetch_array($tags))
                    {
                        while($rowT = mysqli_fetch_array($t_tag))
                        {
                            if($rowTags['name'] == $rowT['tag'])
                            {
                                echo '  <label> ' . $rowTags['name'] . '  </label>';
                            }
                        }
                                            
                        mysqli_data_seek($t_tag, 0);
                    }

                    echo       '<br><br>
                                </form> 
                        </dialog>'; 
                }           
            }

            echo 
            '<script>
                var viewDia = document.getElementById("viewThemeDialog");
                if(viewDia)
                {
                    viewDia.show();
                }
                function closeVD()
                {
                    viewDia.close();
                }


                var filterChecked = document.querySelectorAll("#filterchecked"); 
                filterChecked.forEach(item => {
                    console.log("item");
                    item.checked = true;
                    })
                function resetCheckbox()
                {
                    var filterCheckbox = document.querySelectorAll(".filterBox"); 
                    filterCheckbox.forEach(item => {
                        console.log("item");
                        item.checked = false;
                      })
                }
                          
            </script>'

        ?>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>