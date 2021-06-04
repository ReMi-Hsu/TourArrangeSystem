<?php
    if(isset($_POST['join']))
    {
        $tid = $_POST['themeid'];
        $aid = $_POST['attendee'];
        echo $tid;
        echo $aid;
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "insert into participation values($tid, $aid, true)";
        mysqli_query($conn, $sql);
    }

    header('Location: ./themePage.php');
    exit();
?>