<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inivitation</title>
    <script type="text/javascript">
        function who(){
            var sub=document.getElementById("acc").options;
            var ans="select: ";
            var seNum=0;
            for(let i=0;i<sub.length;i++){
                if(sub[i].selected){
                    if(seNum==0){
                        ans+=(sub[i].value);
                        seNum++;
                    }
                    else{
                        ans+=", "+(sub[i].value);
                    }
                }
            }
            alert(ans);
        }
    </script>
</head>
<body>
    <?php
        $conn = mysqli_connect("localhost","root","","theme_arrangement");
        if(!$conn){
            echo "failed"."<br>";
        }
        else{
            echo "success"."<br>";
        }
        $sql = "select * from account order by id desc";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo "success"."<br>";
            
            while( $row = mysqli_fetch_array($result)){
                if($row['id'] == 3){
                    echo $row['id']."-".$row['name']."-".$row['email']."<br>";
                    echo "invite: ";
                    echo "<select name='acc' id='acc' multiple>";
                }
                else{
                    echo "<option value =" . $row['name'] . ">" . $row['id']."-".$row['name'] . "</option>";
                }
            }
            echo "</select>";
            echo "<form><input type='submit' value='invite' onclick='who()'></form>";
        }
        else{
           echo "failed"."<br>";
        }
        //https://laravel-news.com/user-invitation-system
    ?>
</body>
</html>
