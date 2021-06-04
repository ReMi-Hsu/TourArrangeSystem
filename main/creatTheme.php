<?php
    session_start();
    $HostID = -1;
    $conn=mysqli_connect("localhost", "root","","theme_arrangement");
    $SessionM = $_SESSION['email'];
    $sql = "select id from account where email = '$SessionM'";
    $result=mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $HostID = $row['id'];
    $sql = "select t.id, t.time , p.attendee_id, p.is_valid
            from themes as t, participation as p
            where t.id = p.theme_id";
    $all_theme_attendee_result=mysqli_query($conn, $sql);

    if(isset($_POST['create']))
    {
        console.log("create");
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");

        if($_FILES['file']['name'])
        {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_path = "../schema/image/" . rand(1000,9999) . $file_name;
            move_uploaded_file($file_tmp, $file_path); 
        }
        else
        {
            $file_path = "null";
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
        $sql = "insert into themes values('0', '$PostTitle', '$file_path', $HostID, '$des', '$PostTime')"; // id will modify by db
        $themes = mysqli_query($conn, $sql);

        $sql = "select id from themes order by id desc limit 1";
        $new_theme_conn = mysqli_query($conn, $sql);
        $new_theme = mysqli_fetch_assoc($new_theme_conn);
        $new_id = $new_theme['id'];
        $sql = "insert into participation values($new_id, $HostID, true)";
        mysqli_query($conn, $sql);

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

        // ? whether you add <select name = invites></select> or not
        foreach ($_POST['invites'] as $selectedOption){
            $sql = "insert into participation values($new_id, $selectedOption, false)";
            echo $sql."<br>";
            $tags = mysqli_query($conn, $sql);
        }
    }

    header('Location: ./themePage.php');
    exit();
?>