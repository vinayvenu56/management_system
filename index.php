<!DOCTYPE html>
<html>
<head>
<title>Management System</title>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}
input[type=email], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    max-width: 400px;
    margin: auto;
}
</style>
<script>
function lettersOnly2(evt) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
            ((evt.which) ? evt.which : 0));
        if (charCode == 32 || charCode == 95 || charCode == 9 || charCode == 10 || charCode == 11 || charCode == 8 || charCode == 37 || charCode == 39)
            return true;
        if (charCode > 31 && (charCode < 65 || charCode > 90) &&
            (charCode < 97 || charCode > 122)) {
            return false;
        }
        else
            return true;
    }





</script>
</head>
<body>
<?php
if(!session_id()) session_start();
date_default_timezone_set("Asia/Kolkata");
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");

define("DB_NAME", "management_system");
try
{
  $connection = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
}
catch (PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
function cleanInput( $post = array() ) {
	foreach ( $post as $k => $v ) {

		if ( preg_match( '[<>!$%]', $v ) ) {

			$check = true;
		}
		$v = str_replace( "'", "", $v );
		$v = str_replace( "--", "", $v );
		$v = str_replace( "drop", "", $v );
		$v = str_replace( "union", "", $v );
		$v = str_replace( "union all", "", $v );
		$v = str_replace( "UNION", "", $v );
		$v = str_replace( "UNION ALL", "", $v );
		$v = str_replace( '"', "", $v );
		$v = str_replace( "alert", "", $v );
		$v = str_replace( "select", "", $v );
		$v = str_replace( "*", "", $v );
		$v = str_replace( "document.cookie", "", $v );
		$v = str_replace( ";", "", $v );
		$v = str_replace( "<", "", $v );
		$v = str_replace( ">", "", $v );
		$v = str_replace( "&", "", $v );
		$v = str_replace( "%", "", $v );
		$v = str_replace( "$", "", $v );
		$v = str_replace(" or ","",$v);
		$v = str_replace(" OR ","",$v);
		$v = str_replace(" AND ","",$v);
		$v = str_replace(" and ","",$v);
		$v = str_replace("script","",$v);
		$v = str_replace("=","",$v);
		$v = str_replace("(","",$v);
		$v = str_replace(")","",$v);
		$v = str_replace(";","",$v);
		$v = str_replace("sleep","",$v);
		//$post[$k] = $v;
		$post[ $k ] = trim( htmlspecialchars( $v ) );
	}
	if ( isset( $check ) ) {
		//return 0;
		$post[ 'checkdata' ] = true;

		return $post;
	} else {

		return $post;
	}

}
function validateName($name) {
  $regex = "/^[a-zA-Z' ]+$/";
  return preg_match($regex, $name);
}
function validate_mobile_number($mobile_number) {
    $regex = "/^[6-9]\d{9}$/";
    return preg_match($regex, $mobile_number);
}
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['subaction']=="Submit")
		{
			$get=$post = $_POST = $_REQUEST =  cleanInput($_REQUEST);
			$massage="";
			if((isset($_REQUEST['fname']) && isset($_REQUEST['email']) && isset($_REQUEST['mobile'])) && (strlen($_REQUEST['fname'])==0 || strlen($_REQUEST['email'])==0 || strlen($_REQUEST['mobile'])==0)){
				if(isset($_REQUEST['fname']) && strlen($_REQUEST['fname'])==0){
					$massage.="Please Enter Name.";
				}
				if(isset($_REQUEST['email']) && strlen($_REQUEST['email'])==0){
					$massage.="Please Enter Email.";
				}
				if(isset($_REQUEST['mobile']) && strlen($_REQUEST['mobile'])==0){
					$massage.="Please Enter Mobile Number.";
				}
				?>
				<script>
							alert("<?php echo $massage; ?>");
							
							</script>
				<?php
			}else{
				if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $_REQUEST["email"])) {
				  ?>
					<script>
							alert("Invalid email format");
							</script>
							<?php
				 
				}else{
					if (validateName($_REQUEST['fname'])) {
						if(validate_mobile_number($_REQUEST['mobile'])) {
						
			$stmta = $connection->prepare("select id from users WHERE email=:email");
				$stmta->execute(array("email"=>$_REQUEST["email"]));
				if ($stmta->rowCount() > 0) {
					?>
					<script>
							alert("Email Already Exist");
							</script>
							<?php
				}else{
			$otp=rand(100000, 999999);
			$to = "vinaykumar@gmail.com";   
	
	$sub = "web-message@skylinemeridian.com";
	$msg ="Hi Welcome to Skyline Meridian Your Verification Code : ".$otp;    
	$headers = "content-type: text/html"."\r\n";
	$headers .= "from: ".$_POST['fname'].'<'.$_POST['email'].'>';  
	mail($to, $sub, $msg, $headers);        
	$_SESSION['msg']='yes';
			$insert = $connection->prepare("INSERT INTO users VALUES(NULL,:name,:email,:mobile,:verification_code,'verification code sent','0',:timestamp)");
                        $insert->execute(array(
                            'name' => $_REQUEST['fname'],
                            'email' => $_REQUEST['email'],
                            'mobile' => $_REQUEST['mobile'],
                            'verification_code' => $otp,
                            'timestamp' => time()
                        ));
						$_SESSION['email']=$_REQUEST['email'];
						if($insert){
							?>
							<script>
							alert("Data Successfully Submitted");
							window.location='verification.php?vid=<?php echo base64_encode($_REQUEST['email']);?>'
							</script>
							<?php
						}else{
							?>
							<script>
							alert("Something Went Wrong");
							</script>
							<?php
						}
				}
				}else{ ?>
				<script>
							alert("Invalid Mobile Number Please Start With 6");
							</script>
							<?php
					}
					}else{ ?>
				<script>
							alert("Invalid Name");
							</script>
							<?php
					}
			}
		}	
		}	
$connection=NULL;		
?>


<div class="container">
<h3>Contact Form</h3>
  <form action="index.php" method="POST">
  
    <label for="fname">Name</label>
    <input type="text" id="fname" name="fname"  autocomplete="off" value="<?php if(isset($_REQUEST['fname'])) echo $_REQUEST['fname'];?>"  tabindex="1" minlength="1" maxlength="50" onkeypress="return lettersOnly2(event);" onpaste="return false;" ondrop="return false;" placeholder="Enter name..">

    <label for="email">Email</label>
    <input type="text" id="email" name="email" autocomplete="off"  value="<?php if(isset($_REQUEST['email'])) echo $_REQUEST['email'];?>"  tabindex="2" minlength="1" maxlength="50" onpaste="return false;" ondrop="return false;" placeholder="Enter Email..">
	
	<label for="mobile">Mobile</label>
    <input type="text" id="mobile" name="mobile" autocomplete="off"  value="<?php if(isset($_REQUEST['mobile'])) echo $_REQUEST['mobile'];?>"  tabindex="3" minlength="1" maxlength="10" onpaste="return false;" ondrop="return false;" placeholder="Enter Mobile..">

    

    

    <input type="submit" value="Submit" name="subaction">
  </form>
</div>

</body>
</html>
