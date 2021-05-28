<?php
    if(isset($_POST[create]))
    {
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql_old = "select * from themes order by id desc limit 1";
        $newest_themes = mysqli_query($conn, $sql_old);
        while($row = mysqli_fetch_array($newest_themes))
        {
            $new_id = $row[id] + 1;
        }

        if($_FILES[file][name])
        {
            $file_name = $_FILES[file][name];
            $file_tmp = $_FILES[file][tmp_name];
            $file_path = "./image/" . rand(1000,9999) . $file_name;
            move_uploaded_file($file_tmp, $file_path); 
        }
        else
        {
            $file_path = "null";
        }
        if(isset($_POST[description]))
        {
            $des = $_POST[description];
            if($des == "")
            {
                $des = "none";
            }
        }
        else
        {
            $des = "none";
        }
        //session user
        $sql = "insert into themes (id, title, img, host, description, time) values($new_id, '$_POST[title]', '$file_path', 0, '$des', '$_POST[time]')";
        // $sql = "insert into themes (id, title, img, host, description) values($new_id, '$_POST[title]', '$file_path', $_POST[host], '$des')";
        $themes = mysqli_query($conn, $sql);

        if(isset($_POST['tag'])) 
        {
            $tag = $_POST['tag'];
            $count = count($tag);
            for($i=0; $i < $count; $i++)
            {
                $sql = "insert into theme_tag (theme_id, tag) values($new_id, '$tag[$i]')";
                echo $sql;
                $tags = mysqli_query($conn, $sql);
            }
        }
    }

    header('Location: ./themePage.php');
    exit();
?>