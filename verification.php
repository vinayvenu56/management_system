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
function onlyNumbers(event) {
        var charCode = (event.which) ? event.which : event.keyCode

        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

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
  //echo "connected";
}
catch (PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
function cleanInput( $post = array() ) {
	foreach ( $post as $k => $v ) {

		if ( preg_match( '[<>!$%]', $v ) ) {

			$found = true;
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
	if ( isset( $found ) ) {
		//return 0;
		$post[ 'abcdefghi' ] = true;

		return $post;
	} else {

		return $post;
	}

}
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['subaction']=="Submit")
		{
			$get=$post = $_POST = $_REQUEST =  cleanInput($_REQUEST);
			
			$stmta = $connection->prepare("select id from users WHERE verification_code=:verification_code");
				$stmta->execute(array("verification_code"=>$_REQUEST["verification_code"]));
				if ($stmta->rowCount() > 0) {
				
			$insert = $connection->prepare("UPDATE users SET  status='verified',flag='1' WHERE email=:email");
                        $insert->execute(array(
                            'email' => $_REQUEST['vid']
                        ));
						
						if($insert){
							?>
							<script>
								alert("Your verification is completed");
								window.location='index.php'
							</script>
							<?php
						}else{
							?>
							<script>
								alert("Something Went Wrong");
							</script>
							<?php
						}
						}else{
							?>
							<script>
								alert("Invalid Verification Code");
								window.location='verification.php?vid=<?php echo base64_encode($_REQUEST['vid']);?>'
							</script>
							<?php
				}
		}	
$connection=NULL;		
?>


<div class="container">
<h3>Contact Form</h3>
  <form action="verification.php" method="POST">
  
    <label for="verification_code">Verification Code</label>
    <input type="text" id="verification_code" name="verification_code"  autocomplete="off" required  tabindex="1" minlength="1" maxlength="6" onkeypress="return onlyNumbers(event);" onpaste="return false;" ondrop="return false;" placeholder="Enter Verification Code..">
	<input type="hidden" name="vid" value="<?php echo base64_decode($_REQUEST['vid']); ?>" readonly />
    <input type="submit" value="Submit" name="subaction">
  </form>
</div>

</body>
</html>
