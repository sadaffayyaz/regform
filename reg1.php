<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
?>

<?php 
    require_once("dealer-functions.php");
    require_once("db-const.php");
    session_start();
    if (logged_in() == true) {
        redirect_to("profile.php");
    }
?>

<?php 
$title = "Dealer Member Registration";

include ( 'includes/header.php' );
?>

<hr />


<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data"> 
<label>Passcode:</label>
<input type="text" name="passcode" />
<br />
<label>Password:</label>
<input type="password" name="password" />
<br>
<label>First name:</label>
<input type="text" name="first_name" />
<br>
<label>Last name:</label>
<input type="text" name="last_name" />
<br>
<label>Email:</label>
<input type="type" name="email" />
<label>Upload a company logo:</label> 
<input type="hidden" name="MAX_FILE_SIZE" value="100000" /> 
<input name="image" type="file" value="Upload" /> 
<br />
<input type="submit" name="submit" value="Register" /> 
<br><br>
<a href="dealer-member-login.php">I already have an account...</a>
</form>
<?php
if (isset($_POST['submit'])) {
## connect mysql server
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    # check connection
    if ($mysqli->connect_errno) {
        echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
        exit();
    }
    
## query database
    # prepare data for insertion
    $passcode    = mysqli_real_escape_string($mysqli, $_POST['passcode']);
    $password    = mysqli_real_escape_string($mysqli, $_POST['password']);
    $first_name    = mysqli_real_escape_string($mysqli, $_POST['first_name']);
    $last_name    = mysqli_real_escape_string($mysqli, $_POST['last_name']);
    $email        = mysqli_real_escape_string($mysqli, $_POST['email']);
 
    # check if username and email exist else insert
    // u = username, e = emai, ue = both username and email already exists
    $exists = "";
    $result = $mysqli->query("SELECT passcode from dealermembers WHERE passcode = '{$passcode}' LIMIT 1");
    if ($result->num_rows == 1) {
        $exists .= "u";
    }    
    $result = $mysqli->query("SELECT email from dealermembers WHERE email = '{$email}' LIMIT 1");
    if ($result->num_rows == 1) {
        $exists .= "e";
    }
 
    if ($exists == "u") echo "<p><b>Error:</b> Passcode already exists!</p>";
    else if ($exists == "e") echo "<p><b>Error:</b> Email already exists!</p>";
    else if ($exists == "ue") echo "<p><b>Error:</b> Passcode and Email already exists!</p>";
    else {
        
        // create an MD5 hash of the password
        $password = md5($password);
        
        # insert data into mysql database
        $sql = "INSERT  INTO `dealermembers` (`id`, `passcode`, `password`, `first_name`, `last_name`, `email`) 
                VALUES (NULL, '{$passcode}', '{$password}', '{$first_name}', '{$last_name}', '{$email}')";
 
        if ($mysqli->query($sql)) {
            redirect_to("dealer-member-login.php?msg=Registered successfully");
        } else {
            echo "<p>MySQL error no {$mysqli->errno} : {$mysqli->error}</p>";
            exit();
        }
    }
}
?>    
<hr />

<?php include( 'includes/footer.php' ); ?>