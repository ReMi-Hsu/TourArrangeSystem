<?php
    if(isset($_POST['cancel']))
    {
        $tid = $_POST['themeid'];
        $aid = $_POST['attendee'];
        echo $tid;
        echo $aid;
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "delete from participation where theme_id = $tid and attendee_id = $aid";
        mysqli_query($conn, $sql);
    }

    header('Location: ./themePage.php');
    exit();
?>