<?php
    if(isset($_POST['cancel']))
    {
        $tid = $_POST['themeid'];
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "delete from participation where theme_id = $tid and attendee_id = $_POST[attendee]";
        mysqli_query($conn, $sql);
    }

    header('Location: ./themePage.php');
    exit();
?>