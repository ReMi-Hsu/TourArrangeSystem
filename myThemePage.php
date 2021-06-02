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
    <link rel="stylesheet" href="themePage.css">
</head>
<body>
    <header id="header">
        <a href="/themeArrangement/themePage.php">
            <img id="iconImg" src="/themeArrangement/resource/logo.png">
        </a>
    </header>
    <nav>
        <!-- FIXME:連結 -->
        <ul class="menu">
            <li><a href="/themeArrangement/themePage.php">首頁</a></li>
            <li id="rightHere"><a href="/themeArrangement/myThemePage.php">我的議程</a></li>
            <li><a href="">懲罰轉盤</a></li>
            <li class="register"><a href="">會員登入</a></li>
        </ul>
    </nav>

        <?php
            echo '<div class="container">';
            $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
            echo '<div class="main">';
            if(isset($_POST['tag']))
            {
                $filterT = $_POST['tag'];
                $ts = join("','",$filterT);   
                $sql = "select theme_id from theme_tag where tag in ('$ts')";
                $themeids = mysqli_query($conn, $sql);
                while($ids = mysqli_fetch_array($themeids))
                {
                    $rows[] = $ids["theme_id"];
                }
                $tids = join("','",$rows); 

                //session user
                $sql = "select theme_id from participation where attendee_id=0";
                $attTheme = mysqli_query($conn, $sql);
                while($ids2 = mysqli_fetch_array($attTheme))
                {
                    $rows2[] = $ids2["theme_id"];
                }
                $atttids = join("','",$rows2);
                //session user
                $sql = "select * from themes where id in ('$tids') and id in ('$atttids')";
            }
            else
            {
                //session user
                $sql = "select theme_id from participation where attendee_id=0";
                $attTheme = mysqli_query($conn, $sql);
                while($ids = mysqli_fetch_array($attTheme))
                {
                    $rows0[] = $ids["theme_id"];
                }
                $atttids = join("','",$rows0);
                //session user
                $sql = "select * from themes where id in ('$atttids')";
            }
            $themes = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($themes))
            {
                echo '<div><form method="POST" action="myThemePage.php">
                        <input name="themeid" type="hidden" value="' . $row[id] . '">
                        <button class="themes" type ="submit" value="">
                        <h2>' . $row[title] . '</h2>
                        <div>Host : ' . $row[host] . '</div>';
                if($row[img] != "null")
                {
                    echo '<img class="themeImg" src="' . $row[img] . '">';                
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
                echo '  <div>' . $row[time] . '</div>
                        </button></form></div>';
            }
            echo '</div>';

            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<div class="side">';
            echo '<div>
                <form method="POST" action="myThemePage.php" id="filterForm">';
            while($row = mysqli_fetch_array($tags))
            {
                if(isset($_POST['tag'])) 
                {
                    $filterT = $_POST['tag'];
                    $count = count($filterT);
                    for($i=0; $i < $count; $i++)
                    {
                        if($filterT[$i] == $row[name])
                        {
                            $check = "checked";
                        }
                    }
                }
                echo '<label class="filter"><input type="checkbox" name="tag[]" class="filterBox" id="filter'. $check .'" value="' . $row[name] . '">' . $row[name] . '  </label><br><br>';
                $check = "";
            } 
            echo '<input type="submit" class="btn" value="Filter">
                <input type="reset" class="btn" onclick="resetCheckbox()" value="reset">
                </form> </div> </div>';
            echo '</div>';


            //edit and view dialog
            if(isset($_POST[themeid]))
            {
                $t_id = $_POST[themeid];
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
                    echo '      <input name="themeid" type="hidden" value="' . $row[id] . '">
                                <input name="attendee" type="hidden" value="1">';
                                //session user
                                // <input name="attendee" type="hidden" value="'.$_SESSION[] . '">
                    echo '      <label for="title">Title:</label>
                                <input type="text" id="title" name="title" value=" '. $row[title] . '" readonly="readonly"><br><br>';
                                if($row[img] != "null")
                                {
                                    echo '<img class="themeImg" src="' . $row[img] . '">';                
                                }
                                else
                                {
                                    echo 'NULL';
                                }
                    echo '      <label for="time">Time:</label>
                                <input type="date" id="time" name="time" value=' . $row[time] . ' required><br><br>
                                <label for="description">Description:</label><br>
                                <textarea type="text" id="description" name="description" readonly="readonly">' . $row[description] . '</textarea><br><br>
                                <img class="icon" src="./icon/tag.png" style="width:2%;">';
                    while($rowTags = mysqli_fetch_array($tags))
                    {
                        while($rowT = mysqli_fetch_array($t_tag))
                        {
                            if($rowTags[name] == $rowT[tag])
                            {
                                echo '  <label> ' . $rowTags[name] . '  </label>';
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