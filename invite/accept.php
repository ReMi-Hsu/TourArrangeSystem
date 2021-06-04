<?php
    $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
    if(isset($_POST['accept']))
    { 
        $tid = $_POST['themeid'];
        $aid = $_POST['attendeeid'];
        $sql = "update participation set is_valid = true where theme_id = $tid and attendee_id = $aid";
        mysqli_query($conn, $sql);
        echo $sql;
    }
    else if(isset($_POST['reject'])){
        $tid = $_POST['themeid'];
        $aid = $_POST['attendeeid'];
        $sql = "delete from participation where theme_id = $tid and attendee_id = $aid";
        mysqli_query($conn, $sql);
        echo $sql;
    }
    header('Location: ./acceptInvite.php');
    exit();
?>