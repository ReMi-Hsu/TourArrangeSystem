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
            <!-- <img id="iconImg" src="../schema/resource/logo.png"> -->
            <div id="system">TURNING TOUR</div>
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

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../main/myThemePage.php">我的議程</option>
                    <option value="../invite/acceptInvite.php">議程邀請</option>
                    <option value="../turn/turningTable.php">懲罰轉盤</option>
                    <option value="../mail/main.php" selected> Hello, '.$SessionN.'</option>
                </select>';
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
                
                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../mail/login.php">我的議程</option>
                    <option value="../mail/login.php">議程邀請</option>
                    <option value="../mail/login.php">懲罰轉盤</option>
                    <option value="../mail/login.php" selected>會員登入</option>
                </select>';
            }    

            function generateRandomString($length = 25) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                    return $randomString;
            }
                               
                $PostM = $_POST['email'];
                $PostPsw = $_POST['password'];
                $PostN = $_POST['name'];
                $PostB = $_POST['birthday'];
                // $PostS = $_POST['sex'];
            
                $conn=mysqli_connect("localhost", "root","","theme_arrangement");
                $sql="select email from account where email='$PostM'";
                $result=mysqli_query($conn, $sql);
                $isDupli = false;
                if($result){
                    $num=mysqli_num_rows($result);
                    if($num!=0)
                    {
                        $isDupli = true;
                    }
                }
                
                if($isDupli)
                {
                    echo "<script>alert('您的email已被註冊，請重新嘗試'); window.location.href='./form.php';</script>";
                }
                else
                {
                    $id=generateRandomString();
                    $sql="select * from valid where id='$id'";
                    $result=mysqli_query($conn, $sql);
                    if($result){
                        $num=mysqli_num_rows($result);
                        while($num>0)
                        {
                            $id=generateRandomString();
                            $sql="select * from valid where id='$id'";
                            $result=mysqli_query($conn, $sql);
                            $num=mysqli_num_rows($result);
                        }
                    }
                    
                    $sql="insert into valid values('$id','$PostM','$PostPsw','$PostN','$PostB');";
                    $result=mysqli_query($conn, $sql);
                    if($result)
                    {
                        /*** email ***/ 
                        $mail = new PHPMailer(true);
                        // $mail->SMTPDebug = 2;
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'o7777777o30@gmail.com';                     //SMTP username
                        $mail->Password   = 'o07284670O';                               //SMTP password
                        $mail->SMTPSecure = 'ssl';         // 'tls' //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                        $mail->Port       = 465;                                   // 587 //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                        $link="http://localhost/TourArrangeSystem/mail/valid.php?email=".$PostM."&id=".$id;
                        $body="歡迎加入行程安排系統<br>請按連結進行驗證:<a href=$link>驗證</a>";
                        $mail->CharSet = "utf-8";
                        //Recipients
                        $mail->setFrom('o7777777o30@gmail.com', '行程安排系統');
                        $mail->addAddress($PostM, $PostN);     //Add a recipient
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = '行程安排系統註冊驗證';
                        $mail->Body    = $body;
                        $mail->send();
                        echo "<script>alert('您的驗證信已寄至$PostM'); window.location.href='./login.php';</script>";
                        /*** email end ***/
                    } 
                } 
        ?>
    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>