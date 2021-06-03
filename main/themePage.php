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
                    <li id='rightHere'><a href='./themePage.php'>首頁</a></li>
                    <li><a href='./myThemePage.php'>我的議程</a></li>
                    <li><a href='../invite/acceptInvite.php'>議程邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/main.php'>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li id='rightHere'><a href='./themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php'>我的議程</a></li>
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
                        $sql = "select * from themes where id in ('$tids')";
                    }
                }
            }
            else
            {
                $sql = "select * from themes";
            }

            // each dialog data
            $themes = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($themes))
            {
                $RowH= $row['host'];
                $sql = "select name from account where id = '$RowH'";
                $name_result = mysqli_query($conn, $sql);
                $host_name = mysqli_fetch_array($name_result);
                echo '<div><form method="POST" action="themePage.php">
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
            echo '<button onclick="creatTheme()" class="btn"> Create </button>';
            echo '</div>';

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
            echo '<input type="submit" class="btn" value="Filter">
                <input type="reset" class="btn" onclick="resetCheckbox()" value="reset">
                </form> </div> </div>';
            echo '</div>';

            //create dialog
            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<dialog id="createThemeDialog">
                    <button id = "closeDia" onClick="closeCD()">X</button>
                    <form method="POST" action="./creatTheme.php" enctype="multipart/form-data">
                        <input name="host" type="hidden" value='.$HostID.'>
                        <label for="title">Title*:</label><br>
                        <input type="text" id="title" name="title" placeholder="Theme of the conference" required><br><br>
                        <label for="time">Time:</label><br>
                        <input type="date" id="time" name="time" required><br><br>
                        <input type="file" name="file" accept="image/*"><br><br>
                        <label for="description">Description:</label><br>
                        <textarea type="text" id="description" name="description" maxlength="500" placeholder=""></textarea><br><br>
                        <img class="icon" src="../schema/icon/tag.png" style="width:2%;">';
            while($row = mysqli_fetch_array($tags))
            {
                echo '<label><input type="checkbox" name="tag[]" value="' . $row['name'] . '">' . $row['name'] . '  </label>';
            }
            echo        '<br><br>
                        <input type="submit" class="btn" name="create" value="Submit">
                    </form> 
                </dialog>';  


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
                    if($HostID == $row['host'])
                    {
                        echo '<dialog id="editThemeDialog">
                                <button id = "closeDia" onClick="closeED()">X</button>
                                <form method="POST" action="./editTheme.php" enctype="multipart/form-data">';
                        echo '      <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                    <input name="host" type="hidden" value="' . $row['host'] . '">
                                    <label for="title">Title*:</label><br>
                                    <input type="text" id="title" name="title" value=" '. $row['title'] . '" required><br><br>
                                    <label for="file">Original Image:<label><br>';
                                    if($row['img'] != "null")
                                    {
                                        echo '<img class="themeImg" src="' . $row['img'] . '">';                
                                    }
                                    else
                                    {
                                        echo 'NULL';
                                    }
                        echo '      <input type="file" name="file" accept="image/*"><br><br>
                                    <label for="time">Time:</label><br>
                                    <input type="date" id="time" name="time" value=' . $row['time'] . ' required><br><br>
                                    <label for="description">Description:</label><br>
                                    <textarea type="text" id="description" name="description" maxlength="500">' . $row['description'] . '</textarea><br><br>
                                    <img class="icon" src="../schema/icon/tag.png" style="width:2%;">';
                        while($rowTags = mysqli_fetch_array($tags))
                        {
                            while($rowT = mysqli_fetch_array($t_tag))
                            {
                                if($rowTags['name'] == $rowT['tag'])
                                {
                                    $check = "checked";
                                }
                            }
                            echo '  <label><input type="checkbox" name="tag[]" value="' . $rowTags['name'] . '" '. $check .'>' . $rowTags['name'] . '  </label>';
                            
                            mysqli_data_seek($t_tag, 0);
                            $check = "";
                        }
                        echo       '<br><br>
                                    <input type="submit" class="btn" name="update" value="Update">
                                    </form> 
                                    <form method="POST" action="./deleteTheme.php">
                                        <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                        <input type="submit" class="btn" name="delete" value="Delete">
                                    </form>
                            </dialog>'; 
                    }
                    else
                    {
                        $BtnToPage = "../mail/login.php";
                        if($HostID!=-1){
                            $BtnToPage = "./join.php";
                        }
                        echo '<dialog id="viewThemeDialog">
                                <button id = "closeDia" onClick="closeVD()">X</button>
                                <form method="POST" action="'.$BtnToPage .'" enctype="multipart/form-data">';
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
                        echo '      <label for="time">Time:</label>
                                    <input type="date" id="time" name="time" value=' . $row['time'] . ' required><br><br>
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

                        $disBtn="";
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
                        
                        echo       '<br><br>
                                    <input type="submit" class="btn" name="join" value="Join" ' . $disBtn . '>
                                    </form> 
                                    <form method="POST" action="'.$BtnToPage.'">
                                        <input name="themeid" type="hidden" value="' . $row['id'] . '">
                                        <input name="attendee" type="hidden" value="'.$HostID.'">';
                        echo       '    <input type="submit" class="btn" name="cancel" value="Cancel"' . $disCancel . '>
                                    </form>
                            </dialog>'; 
                        $disBtn = "";
                        $disCancel = "";
                    }
                }           
            }

            echo 
            '<script>
                var createDia = document.getElementById("createThemeDialog");
                function creatTheme()
                {
                    if('.$HostID.'==-1)
                    {
                        window.location.href = "../mail/login.php";
                    }
                    else{
                        createDia.show();
                    }
                }
                function closeCD()
                {
                    createDia.close();
                }

                var editDia = document.getElementById("editThemeDialog");
                if(editDia)
                {
                    if('.$HostID.'==-1)
                    {
                        window.location.href = "../mail/login.php";
                    }
                    else{
                        editDia.show();
                    }
                }
                function closeED()
                {
                    editDia.close();
                }

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
                        
            </script>';     
        ?>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>