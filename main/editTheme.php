<?php
    if(isset($_POST['update']))
    {
        $tid = $_POST['themeid'];
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "select * from themes where id = '$tid'";
        $theme = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($theme);

        $sql = "delete from participation where theme_id = '".$tid."' and is_valid = false";
        echo $sql. "<br>";
        $deleted = mysqli_query($conn, $sql);
        // ? whether you add <select name = invites></select> or not
        if(isset($_POST['invites'])){
            foreach ($_POST['invites'] as $selectedOption){
                $sql = "insert into participation values($tid, $selectedOption, false)";
                echo $sql."<br>";
                $ins_result = mysqli_query($conn, $sql);
            }
        }

        // while($row = mysqli_fetch_array($theme))
        // {
            if($_FILES['file']['name'])
            {
                $file_name = $_FILES['file']['name'];
                $file_tmp = $_FILES['file']['tmp_name'];
                if($file_tmp!=""){
                    $img = $row['img'];
                    if($img!=""){
                        if(file_exists($img)){
                            unlink($img);
                        }
                    }    
                    $file_path = "../schema/image/" . rand(1000,9999) . $file_name;
                    move_uploaded_file($file_tmp, $file_path);
                }
                else
                {
                    $file_path = $row['img'];
                }
            }
            else
            {
                $file_path = $row['img'];
            }

            if(isset($_POST['description']))
            {
                $des = $_POST['description'];
                if($des == "")
                {
                    $des = "none";
                }
            }
            else
            {
                $des = "none";
            }
            
            $PostTitle = $_POST['title'];
            $PostTime = $_POST['time'];
            $sql = "update themes set title='$PostTitle', img='$file_path', description='$des', time='$PostTime' where id=$tid";
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