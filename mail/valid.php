<?php
    $GetM = $_GET['email'];
    $GetI = $_GET['id'];
    $conn=mysqli_connect("localhost", "root","","theme_arrangement");
    $sql="select * from valid where email='$GetM' and valid_id='$GetI'";
    $result=mysqli_query($conn, $sql);
    if($result){
        $num=mysqli_num_rows($result);
        if($num>0)
        {
            $row=mysqli_fetch_assoc($result);
            $sql="insert into account values ('Null','$row[email]','$row[password]','$row[name]', '$row[birthday]', '$row[sex]' )";
            $result=mysqli_query($conn, $sql);
            $sql="delete from valid where email='$_GET[email]'";
            $result=mysqli_query($conn, $sql);
            echo"<script>alert('歡迎您加入會員');window.location.href='./login.php';</script>";
        }
        else
        {
            $sql="select * from account where email='$_GET[email]'";
            $result=mysqli_query($conn, $sql);
            $num=mysqli_num_rows($result);
            if($num==1)
            {
                echo"<script>alert('您已經是會員，請先完成登入');window.location.href='./login.php';</script>";
            }
            else
            {
                echo"<script>alert('錯誤驗證網址，請重新註冊');window.location.href='./form.php';</script>";
            }
        }
    }
?>