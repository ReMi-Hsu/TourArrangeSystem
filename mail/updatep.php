<?php
    session_start();
    if(!($_SESSION['email']))
    {
        header("location:./login.php?act=1");
    }
    if($_POST['newpassword1']==$_POST['newpassword2'])
    {
        $PostNewPsw1 = $_POST['newpassword1'];
        $PostNewPsw2 = $_POST['newpassword2'];
        $SessionM = $_SESSION['email'];
        $conn=mysqli_connect("localhost", "root","","theme_arrangement");
        $sql="select * from account where email='$SessionM'";
        $result=mysqli_query($conn, $sql);
        $row=mysqli_fetch_assoc($result);
        if($row['password']!=$_POST['password'])
        {
            echo "<script>alert('您的原本密碼錯誤，請重新輸入');window.location.href='update_password.php';</script>";
        }
        else
        {
            $sql="update account set password='$PostNewPsw1' where email='$SessionM'";
            $result=mysqli_query($conn, $sql);
        }
        $text='您的會員資料已成功修改\n'. '密碼為: '. $PostNewPsw1 ;
        echo "<script>alert('$text');window.location.href='../main/myThemePage.php';</script>";
    }
    echo "<script>alert('您的密碼和重新輸入密碼不同，請重新輸入');window.location.href='./update_password.php';</script>";
?>