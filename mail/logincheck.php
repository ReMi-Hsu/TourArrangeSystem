<?php
    session_start();
    if(isset($_POST['email']))
    {
        $PostM = $_POST['email'];
        $PostPsw = $_POST['password'];
        $conn=mysqli_connect("localhost", "root","","theme_arrangement");
        $sql="select * from account where email='$PostM' and password='$PostPsw'";
        $result=mysqli_query($conn, $sql);
        if ($result) {
            $num=mysqli_num_rows($result);
            if($num==1)
            {
                $row=mysqli_fetch_assoc($result);
                $_SESSION['email']=$row['email'];
                $_SESSION['name']=$row['name'];
                $_SESSION['birthday']=$row['birthday'];
                // $_SESSION['sex']=$row['sex'];
                header("location: ../main/myThemePage.php");
            }
            else
            {
                
                $sql="select * from valid where email='$PostM'";
                $result=mysqli_query($conn, $sql);
                $num=mysqli_num_rows($result);
                if($num>0)
                {
                    $text='您的帳號尚未完成認證\n'. '請先至您的信箱點選驗證連結';
                    echo "<script>alert('$text');window.location.href='./login.php';</script>";
                }
                else
                {
                    echo "<script>alert('您的email或密碼錯誤');window.location.href='./login.php';</script>";
                }
            } 
        }
        else{
            header("location: login.php?act=3");
        }
    }
?>