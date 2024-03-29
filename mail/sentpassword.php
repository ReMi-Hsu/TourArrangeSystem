<?php
    session_start();
    if(!($_SESSION['email']))
    {
        header("location:./login.php?act=1");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTFF-8">
    <!-- FIXME:content -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="../schema/themePage.css">
</head>
<body>
    <header id="header">
        <a href="../main/themePage.php">
            <img id="iconImg" src="../schema/resource/logo.png">
        </a>
    </header>
    
        <!-- FIXME:連結 -->
        <?php            
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            require 'PHPMailer/src/Exception.php';
            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';

            /*** check whether login or not ***/
            $isLogin = false;
            $HostID = -1;
            if($_SESSION){
                if($_SESSION['email'])
                {
                    $isLogin=true;

                    // get host_id
                    $conn=mysqli_connect("localhost", "root","","theme_arrangement");
                    $SessionM = $_SESSION['email'];
                    $sql = "select id from account where email = '$SessionM'";
                    $result=mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    $HostID = $row['id'];
                    //echo "host id: ". $HostID ."<br>";
                }
            }

            /*** display nav acoording to login status ***/
            if($isLogin)
            {
                $SessionN = $_SESSION['name'];
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../main/myThemePage.php'>我的議程</a></li>
                    <li><a href='../invite/acceptInvite.php'>議程邀請</a></li>
                    <li><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/main.php'>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php'>我的議程</a></li>
                    <li><a href='../mail/login.php'>議程邀請</a></li>
                    <li><a href='../mail/login.php'>懲罰轉盤</a></li>
                    <li class='register' id='rightHere'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";
            }    

            if(isset($_POST['email']))
            {
                $PostM = $_POST['email'];
                $conn=mysqli_connect("localhost", "root","","theme_arrangement");
                $sql="select * from account where email='$PostM'";
                $result=mysqli_query($conn, $sql);
                $num=mysqli_num_rows($result);
                if($num==1)
                {
                    $row=mysqli_fetch_assoc($result);
                    //Instantiation and passing `true` enables exceptions
                    $mail = new PHPMailer(true);
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'tina50336@gmail.com';                     //SMTP username
                    $mail->Password   = 'qooqoo1122';                               //SMTP password
                    $mail->SMTPSecure = "ssl";         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                    $body='您的密碼為:&nbsp;&nbsp;'.$row['password'];
                    $mail->CharSet = "utf-8";
                    //Recipients
                    $mail->setFrom('tina50336@gmail.com', '行程安排系統');
                    $mail->addAddress($_POST['email'], $row['name']);     //Add a recipient
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = '行程安排系統密碼';
                    $mail->Body    = $body;
                    $mail->send();
                    echo "<script>alert('您的密碼已經寄至信箱');window.location.href='./login.php';</script>";
                }
                else
                {
                    echo "<script>alert('您填寫的資料錯誤');window.location.href='./forgot.php';</script>";
                }  
            }
        ?>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>