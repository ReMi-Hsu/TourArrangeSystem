<?php
    session_start();
    if(!($_SESSION['email']))
    {
        header("location:./login.php?act=1");
    }
    $conn=mysqli_connect("localhost", "root","","theme_arrangement");

    $SessionM = $_SESSION['email'];
    $PostPsw = $_POST['password'];
    $PostN = $_POST['name'];
    $PostB = $_POST['birthday'];
    $PostS = $_POST['sex'];

    $sql="update account set name='$PostN',birthday='$PostB',sex='$PostS' where email='$SessionM'";
    $result=mysqli_query($conn, $sql);
    $text='您的會員資料已成功修改\n'. '姓名: '. $PostN . '\n生日: '.$PostB. '\n性別: '.$PostS;
    $_SESSION['name']=$PostN;
    $_SESSION['birthday']=$PostB;
    $_SESSION['sex']=$PostS;
    echo "<script>alert('$text');window.location.href='../main/myThemePage.php';</script>";
?>