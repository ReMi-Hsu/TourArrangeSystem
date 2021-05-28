<?php
    if(isset($_POST[themeid]))
    {
        $tid = $_POST[themeid];
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        // $sql = "select * from themes id = $tid";
        // $theme = mysqli_query($conn, $sql);

        // while($row = mysqli_fetch_array($theme))
        // {
            if($_FILES[file][name])
            {
                $file_name = $_FILES[file][name];
                $file_tmp = $_FILES[file][tmp_name];
                $file_path = "./image/" . rand(1000,9999) . $file_name;
                move_uploaded_file($file_tmp, $file_path); 
            }
            else
            {
                $file_path = $row[img];
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

            $sql = "update themes set title='$_POST[title]', img='$file_path', description='$des', time='$_POST[time]' where id=$tid";
            echo $sql;
            mysqli_query($conn, $sql);

            $sql = "delete from theme_tag where theme_id=$tid";
            echo $sql;
            mysqli_query($conn, $sql);
            if(isset($_POST['tag'])) 
            {
                $tag = $_POST['tag'];
                $count = count($tag);
                for($i=0; $i < $count; $i++)
                {
                    $sql = "insert into theme_tag (theme_id, tag) values($tid, '$tag[$i]')";
                    mysqli_query($conn, $sql);
                }
            }
        // }
    }

    header('Location: ./themePage.php');
    exit();
?>