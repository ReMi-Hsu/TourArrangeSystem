<?php
    if(isset($_POST[themeid]))
    {
        $tid = $_POST[themeid];
        $conn = mysqli_connect("localhost", "root", "", "theme_arrangement");
        $sql = "delete from themes where id = $tid";
        mysqli_query($conn, $sql);

        $sql = "delete from theme_tag where theme_id = $tid";
        mysqli_query($conn, $sql);

        $sql = "delete from participation where theme_id = $tid";
        mysqli_query($conn, $sql);
    }

    header('Location: ./themePage.php');
    exit();
?>