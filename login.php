<?php
//ini_set('session.cookie_httponly', true);
session_start();
if (isset($_POST['submit']))
{ 
    
    $mysqli=new mysqli('localhost','root','','test1');
    if (!$mysqli) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit; 
}
//  $Username=$_POST['username'];  
//    $Password=$_POST['password'];
/* $Username=mysqli_real_escape_string($mysqli, $_POST['username']); //First sql attack, to solve it I am using -> mysqli_real_escape_string 
  $Password=mysqli_real_escape_string($mysqli, $_POST['password']);  // or use addslashes which does the same thing

   $result=$mysqli->query("select * from users where Username='$Username' AND Password='$Password'");
   $row= $result->fetch_array(MYSQLI_BOTH);
       if ($row){   
    session_start();
    echo "Succesfully login <br>";
    echo "Input query is <br>";
   echo "select * from users where Username='$Username' AND Password='$Password'";
    echo "<pre>"; print_r($row); echo "</pre>";
    
    }else{
    echo "Sorry, login information is incorrect <br>";
   echo "Input query is <br>";
  echo "select * from users where Username='$Username' AND Password='$Password'";
    }
    
    */ 
   $Username=$_POST['username'];  
    $Password=$_POST['password']; 

    $sql="select First_name, Last_name, Email from users where Username=? AND Password=?";
    $stmt=$mysqli->prepare($sql);
    $stmt->bind_param('ss', $Username, $Password);             // Second approach is using a Mysqli prepared statements
    $stmt->execute();
    $stmt->bind_result($First_name, $Last_name, $Email);
    if ($stmt->fetch()){
        echo "Succesfully login <br>";
        echo "First_name ----> ".$First_name."<br>";
        echo "Last_name ----> ".$Last_name."<br>";
        echo "Email ----> ".$Email."<br>";
    }else{
        echo "Sorry, login information is incorrect <br>";
    }
    
  
   
/*$pdo = new PDO('mysql:host=localhost;dbname=test1', 'root', '');     //     PDO method
 if (!$pdo){
    die("Not connected:" .mysql_error());
}

$sql="CALL GetData($Username, $Password)";
    $stmt=$pdo->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);               // Third approach is stored procedures where I use PDO instead of Mysqli
    $stmt->execute();
    while($values=$stmt->fetch())
    {  
        print "<pre>";
        print_r($values);
    }
    */

 
}     

 
$Name=isset($_GET['search']) ? $_GET['search'] : " ";

?>
<html>
    <body>
       
  <!--      <form action="" method="GET">
     Search:<input type="text" name="search" placeholder="Search for members" />
            <input type="submit" value="Submit" />
        </form>
<?php echo "Search result is $Name <br>"; ?>
-->
         <form action="" method="POST">
             
<label>Please leave your comment below <br></label>
<label>Title: <br><input type="text" name="title"><br></label>
            <label>Message: <br><textarea cols="35" rows="5" name="mes"></textarea></label><br>
<input type="submit" name="post" value="Post">
        </form>
    </body>
</html>

<?php
  $title=isset($_POST["title"]) ? $_POST["title"] : " ";     // PHP comment page 
 $text=isset($_POST["mes"]) ? $_POST["mes"] : " ";           // <img src=x onerror=alert("XSS")> persistance, stored XSS 
 $post=isset($_POST["post"]) ? $_POST["post"] : " ";       // <script>alert("XSS")</script> 

 //$title=filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);     //  use sanitizer string or char against XSS
 //$text=filter_input(INPUT_POST, "mes", FILTER_SANITIZE_SPECIAL_CHARS);
                                                       
                                                                //<script>window.location='https:/www.google.com/search?q=what+is+XSS'</script>
                //<script>alert(String.fromCharCode(88,83,83))</script> bypassing basic filters but all 3 don't work
                    //<a href=javascript:alert(String.fromCharCode(88,83,83))>Click me!</a
                       //<a href=javascript:alert(&quot;XSS&quot;)>Click me!</a>
//or reflected XSS mostly on url and search fields
// CSRF <img src="attack?Screen=89&menu=900&transferFunds=4000"  width="20" height="20">
//<script>window.location='https://localhost/hw8/hw8/csrf.php'</script>
if($post){
   
$write=fopen("com.txt", "a+");
fwrite($write, "<u><b>$title</b></u><br>$text<br>");
fclose($write);
    
$read=fopen("com.txt", "r+t");
    echo "All comments:<br>";
    
while(!feof($read)){
    echo fread($read, 1024);
}
    fclose($read);
 }
else{
    $read=fopen("com.txt", "r+t");
    echo "All comments:<br>";
    
while(!feof($read)){
    echo fread($read, 1024);
   }
    
fclose($read);
    }
// Is there any input? 
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) { 
    // Feedback for end user 
    echo '<pre>Hello ' . $_GET[ 'name' ] . '</pre>'; 
} 
?>

 <html>
    <body>
  
      <form name="XSS" action="#" method="GET">
			<p>
				Search for member?
				<input type="text" name="name">
				<input type="submit" value="Submit">
			</p>
        </form>

    </body>
</html>

		


