<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTFF-8">
    <!-- FIXME:content -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Arrangement</title>
    <link rel="stylesheet" href="../schema/themePage.css">
    <link rel="stylesheet" href="../schema/turn.css">
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
            session_start();
            
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
                    <li><a href='../main/myThemePage.php'>我的活動</a></li>
                    <li><a href='../invite/acceptInvite.php'>活動邀請</a></li>
                    <li id='rightHere'><a href='../turn/turningTable.php'>懲罰轉盤</a></li>
                    <li class='register' id='logined' onmouseover='openAccount()'><a>Hello, ".$SessionN."</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../main/myThemePage.php">我的活動</option>
                    <option value="../invite/acceptInvite.php">活動邀請</option>
                    <option value="../turn/turningTable.php" selected>懲罰轉盤</option>
                    <option value="../mail/main.php"> Hello, '.$SessionN.'</option>
                </select>';
            }
            else{
                echo 
                "<nav>
                    <ul class='menu'>
                    <li><a href='../main/themePage.php'>首頁</a></li>
                    <li><a href='../mail/login.php?act=1'>我的活動</a></li>
                    <li><a href='../mail/login.php?act=1'>活動邀請</a></li>
                    <li id='rightHere'><a href='../mail/login.php?act=1'>懲罰轉盤</a></li>
                    <li class='register'><a href='../mail/login.php'>會員登入</a></li>
                    </ul>
                </nav>";

                echo
                '<select name="page" id="pageSelect" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">
                    <option value="../main/themePage.php">首頁</option>
                    <option value="../mail/login.php?act=1">我的活動</option>
                    <option value="../mail/login.php?act=1">活動邀請</option>
                    <option value="../mail/login.php?act=1" selected>懲罰轉盤</option>
                    <option value="../mail/login.php?act=1">會員登入</option>
                </select>';
            }  
            
            if($isLogin)
            {
                echo "<div class='container'><div class='main'>";
                echo "
                    <div class = 'punishDiv'>
                        <table>
                            <thead>
                                <tr>
                                    <th colspan='3'>懲罰選項</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form>
                                <tr>
                                    <td><input type='text' id='punish1' value='真心話大冒險'></td>
                                    <td><input type='text' id='punish2' value='跳一隻舞'></td>
                                </tr>
                                <tr>
                                    <td><input type='text' id='punish3' value='提供醜照'></td>
                                    <td><input type='text' id='punish4' value='角色扮演'></td>
                                </tr>
                                <tr>
                                    <td><input type='text' id='punish5' value='請飲料'></td>
                                    <td><input type='text' id='punish6' value='跑腿'></td>
                                </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                ";

                echo '
                <div class="select" id="select">
                    <a class="acc" href="../mail/logout.php">登出</a>&nbsp;<br>
                    <a class="acc" href="../mail/upadate_account.php">修改帳戶</a>&nbsp;<br>
                    <a class="acc" href="../mail/update_password.php">修改密碼</a>&nbsp;<br>
                </div>';

                echo "
                    <div class='wrapper' id = 'wrapper'>
                        <div id='wrapperLabel'>
                            <span>轉動轉盤!</span>
                        </div>
                        <div class = 'part2'></div>
                        <div id = 'pointer' class = 'rotate-pointer' data-click = ''></div>
                    </div>
                    <script src='./jquery.min.js'></script>
                    <script>
                        (function(){
                            var btn = $('#pointer'),
                            options = {};

                            options.gifts = {
                                '1':{
                                    id : '1',
                                    name : '1',
                                    angleStart : -30,
                                    angleEnd : 30,
                                    tips : '1'
                                },
                                '2':{
                                    id : '2',
                                    name : '2',
                                    angleStart : 31,
                                    angleEnd : 90,
                                    tips : '6'
                                },
                                '3':{
                                    id : '3',
                                    name : '3',
                                    angleStart : 91,
                                    angleEnd : 150,
                                    tips : '5'
                                },
                                '4':{
                                    id : '4',
                                    name : '4',
                                    angleStart : 151,
                                    angleEnd : 210,
                                    tips : '4'
                                },
                                '5':{
                                    id : '5',
                                    name : '5',
                                    angleStart : 211,
                                    angleEnd : 270,
                                    tips : '3'
                                },
                                '6':{
                                    id : '6',
                                    name : '6',
                                    angleStart : 271,
                                    angleEnd : 330,
                                    tips : '2'
                                }
                            };

                            // 获取[n,m]的随机整数
                            function getRandom (n, m) {
                                var num = Math.floor(Math.random() * (m - n + 1) + n)
                                return num
                            }

                            function _begin(){
                                // parse input data to tips
                                var punishments = new Array(6);
                                $('input').each(function(i){
                                    punishments[i]=$(this).val();
                                });
                                console.log('punishments', punishments);

                                for (var key in options.gifts) {
                                    options.gifts[key].tips = punishments[key-1];
                                }

                                var base = 2160; //和transition: transform 10.5s;对应

                                var result = getRandom(-30,330); // 随机

                                var _key = null; // 内定奖品id

                                if (_key) {
                                    result = getRandom(options.gifts[_key].angleStart,options.gifts[_key].angleEnd)
                                }

                                // result = 40
                                var deg = (base+result)

                                $('.part2').css({'transform':'rotate('+deg+'deg)'});

                                $('.part2').on('transitionend',function(){
                                    console.log(result)
                                    for (var key in options.gifts) {

                                        if (options.gifts[key].angleStart <= result && result<=options.gifts[key].angleEnd) {

                                        confirm(options.gifts[key].tips);
                                        window.location.reload()

                                        }
                                    }
                                })
                            }

                            btn.on('click',_begin);
                        })();

                        let target=document.getElementById('select');
                        let lo=document.getElementById('logined');
                        target.style.display='none';
                        function openAccount()
                        {
                            target.style.display='block';
                            lo.append(target);
                            setTimeout(function() {
                                target.style.display='none';
                            }, 1000);
                        }
                    </script>";
                    echo "</div></div>";

            }
        ?>

    <footer id="footer">
        Copyright &copy; 
        <!-- FIXME:團隊 -->
    </footer>
</body>
</html>