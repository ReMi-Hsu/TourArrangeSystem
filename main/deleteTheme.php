<?php
    if(isset($_POST['delete']))
    {
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");

        $tid = $_POST['themeid'];
        $sql = "select img from themes where id = '".$tid."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $img = $row['img'];
        
        if($img!=""){
            if(file_exists($img)){
                unlink($img);
            }
        }        

        $sql = "delete from theme_tag where theme_id = '".$tid."'";
        mysqli_query($conn, $sql);

        $sql = "delete from participation where theme_id = '".$tid."'";
        mysqli_query($conn, $sql);

        $sql = "delete from themes where id = '".$tid."'";
        mysqli_query($conn, $sql);
    }

    header('Location: ./themePage.php');
     exit();
?>