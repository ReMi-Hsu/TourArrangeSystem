<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTFF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Arrangement</title>
    <link rel="stylesheet" type="text/css" href="css/themePage.css" >
</head>
<body>
    <?php
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "select * from themes";
        $themes = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($themes))
        {
            echo '<div><form method="POST" action="themePage.php">
                    <input name="themeid" type="hidden" value="' . $row[id] . '">
                    <button class="themes" type ="submit" value="">
                    <h2>' . $row[title] . '</h2>
                    <div>Host : ' . $row[host] . '</div>';
            if($row[img] != "null")
            {
                echo '<img class="themeImg" src="' . $row[img] . '">';                
            }
            echo '  <div>' . $row[time] . '</div>
                    </button></form></div>';
        }

        echo '<button onclick="creatTheme()"> Create </button>';

        //create dialog
        if(empty($_POST[themeid]))
        {
            $sql = "select * from tags";
            $tags = mysqli_query($conn, $sql);
            echo '<dialog id="createThemeDialog">
                    <form method="POST" action="/themeArrangement/creatTheme.php" enctype="multipart/form-data">';
                    //session user
            // echo '      <input name="host" type="hidden" value="' . $row[host] . '">';
            echo '      <button id = "closeDia" onclick="closeDialog()">X</button>
                        <input name="host" type="hidden" value="0">
                        <label for="title">Title*:</label><br>
                        <input type="text" id="title" name="title" placeholder="Theme of the conference" required><br><br>
                        <label for="time">Time:</label><br>
                        <input type="date" id="time" name="time" required><br><br>
                        <input type="file" name="file" accept="image/*"><br><br>
                        <label for="description">Description:</label><br>
                        <textarea type="text" id="description" name="description" maxlength="500" placeholder=""></textarea><br><br>
                        <img class="icon" src="./icon/tag.png" style="width:2%;">';
            while($row = mysqli_fetch_array($tags))
            {
                echo '<label><input type="checkbox" name="tag[]" value="' . $row[name] . '">' . $row[name] . '  </label>';
            }
            echo        '<br><br>
                        <input type="submit" name="create" value="Submit">
                    </form> 
                </dialog>';            
        }


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
            //if session user == host
                if(false)
                {
                    echo '<dialog id="editThemeDialog" open>
                            <form method="POST" action="/themeArrangement/editTheme.php" enctype="multipart/form-data">';
                    echo '      <input name="themeid" type="hidden" value="' . $row[id] . '">
                                <input name="host" type="hidden" value="' . $row[host] . '">
                                <button id = "closeDia" onclick="closeEDDialog()">X</button>
                                <label for="title">Title*:</label><br>
                                <input type="text" id="title" name="title" value=" '. $row[title] . '" required><br><br>
                                <label for="file">Original Image:<label><br>';
                                if($row[img] != "null")
                                {
                                    echo '<img class="themeImg" src="' . $row[img] . '">';                
                                }
                                else
                                {
                                    echo 'NULL';
                                }
                    echo '      <input type="file" name="file" accept="image/*"><br><br>
                                <label for="time">Time:</label><br>
                                <input type="date" id="time" name="time" value=' . $row[time] . ' required><br><br>
                                <label for="description">Description:</label><br>
                                <textarea type="text" id="description" name="description" maxlength="500">' . $row[description] . '</textarea><br><br>
                                <img class="icon" src="./icon/tag.png" style="width:2%;">';
                    while($rowTags = mysqli_fetch_array($tags))
                    {
                        while($rowT = mysqli_fetch_array($t_tag))
                        {
                            if($rowTags[name] == $rowT[tag])
                            {
                                $check = "checked";
                            }
                        }
                        echo '  <label><input type="checkbox" name="tag[]" value="' . $rowTags[name] . '" '. $check .'>' . $rowTags[name] . '  </label>';
                        
                        mysqli_data_seek($t_tag, 0);
                        $check = "";
                    }
                    echo       '<br><br>
                                <input type="submit" name="update" value="Update">
                                </form> 
                                <form method="POST" action="/themeArrangement/deleteTheme.php">
                                    <input name="themeid" type="hidden" value="' . $row[id] . '">
                                    <input type="submit" name="delete" value="Delete">
                                </form>
                        </dialog>'; 
                }
                else
                {
                    echo '<dialog id="viewThemeDialog" open>
                            <form method="POST" action="/themeArrangement/join.php" enctype="multipart/form-data">';
                    echo '      <input name="themeid" type="hidden" value="' . $row[id] . '">
                                <input name="attendee" type="hidden" value="1">';
                                //session user
                                // <input name="attendee" type="hidden" value="'.$_SESSION[] . '">
                    echo '      <button id = "closeDia" onclick="closeVDialog()">X</button>
                                <label for="title">Title:</label>
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

                    while($rowAtt = mysqli_fetch_array($t_att))
                    {
                        //session user
                        //if($rowAtt[attendee_id] == $_SESSION[])
                        if($rowAtt[attendee_id] == 1)
                        {
                            $disBtn = "disabled";
                        }
                    }

                    if($disBtn != "disabled")
                    {
                        $disCancel = "disabled";
                    }

                    echo       '<br><br>
                                <input type="submit" name="join" value="Join" ' . $disBtn . '>
                                </form> 
                                <form method="POST" action="/themeArrangement/cancel.php">
                                    <input name="themeid" type="hidden" value="' . $row[id] . '">
                                    <input name="attendee" type="hidden" value="1">';
                                    //session user
                                    // <input name="attendee" type="hidden" value="'.$_SESSION[] . '">
                    echo       '    <input type="submit" name="cancel" value="Cancel"' . $disCancel . '>
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
                createDia.show();
            }

            function closeDialog()
            {
                createDia.close();
            }

            var editDia = document.getElementById("editThemeDialog");
            function closeEDDialog()
            {
                editDia.close();
            }

            var viewDia = document.getElementById("viewThemeDialog");
            function closeVDialog()
            {
                viewDia.close();
            }            
        </script>'

    ?>
</body>
</html>