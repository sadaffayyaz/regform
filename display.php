<?php 
require_once("dealer-functions.php");
require_once("db-const.php");
session_start();
?>

<?php 
$title = "Dealer Member Profile";

include ( 'includes/header.php' );
?>

<html>
<head>
<script src="./js/dealer-script.js" type="text/javascript"></script><!-- put it on user area pages -->
</head>

<hr />
<?php
if (logged_in() == false) {
    redirect_to("dealer-member-login.php");
} else {
    if (isset($_GET['id']) && $_GET['id'] != "") {
        $id = $_GET['id'];
    } else {
        $id = $_SESSION['dealer_user_id'];
    }
 
    ## connect mysql server
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        # check connection
        if ($mysqli->connect_errno) {
            echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
            exit();
        }
    ## query database
        # fetch data from mysql database
        $sql = "SELECT * FROM dealermembers WHERE id = {$id} LIMIT 1";
 
        if ($result = $mysqli->query($sql)) {
            $user = $result->fetch_array();
        } else {
            echo "<p>MySQL error no {$mysqli->errno} : {$mysqli->error}</p>";
            exit();
        }
 
        if ($result->num_rows == 1) {
            # calculating online status
            if (time() - $user['status'] <= (5*60)) { // 300 seconds = 5 minutes timeout
                $status = "Online";
            } else {
                $status = "Offline";
            }
 
            # echo the user profile data
            echo "<div class='profile-text'>";
            echo "<p>User ID: {$user['id']}</p>";
            echo "<p>Passcode: {$user['passcode']}</p>";
            echo "<p>Status: {$status}</p>";            
        } else { // 0 = invalid user id
            echo "<p><b>Error:</b> Invalid user ID.</p>";
        }
}

?>

<?php
mysql_connect("host","username","password");
mysql_select_db("dbname");
    
    $content=file_get_contents($_FILES['pic']['tmp_name']);
    $content=mysql_real_escape_string($content);
    
    @list(, , $imtype, ) = getimagesize($_FILES['pic']['tmp_name']);

    if ($imtype == 3){
        $ext="png"; 
    }elseif ($imtype == 2){
        $ext="jpeg";
    }elseif ($imtype == 1){
        $ext="gif";
    
    $q="insert into dealerlogoimages set profile_pic='".$content."',ext='".$ext."'";
    mysql_query($q);
    header("location: dealer-member-profile.php");
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div>
       <label>Image:</label>
       <input type="file" name="image" value="">
    </div>
    <div>
       <input type="submit" value="Upload">
    </div>
</form>
 
 <?php
if(isset($_FILES['image']['name'])){
        // *** Add your validation code here *** //
        // Include Connection
    include_once('conn.php');

    // Get Image
    $name = $_FILES['image']['name'];
    $type = $_FILES['image']['type'];
    $get_content = file_get_contents($_FILES['image']['tmp_name']);
    $escape = mysql_real_escape_string($get_content);
    $sql = "INSERT INTO `dealerlogoimages` (`ID`, `name`, `src`, `type`) VALUES (NULL, '$name', '$escape', '$type');";
    if(mysql_query($sql)){
        echo 'Image inserted to database';
    }else{
        echo 'Error data inserting';
    }
}
?>
 
 <?php
// Connection
include_once('conn.php');
$sql = "SELECT * FROM  `dealerlogoimages`";
$qur = mysql_query($sql);
while($r = mysql_fetch_array($qur)){
    extract($r);
    echo '<img src="data:image/png;base64,'.base64_encode($src).'">';
    //echo '<img src="data:image/png;base64,'.base64_encode($src).'" width="450px" height="550px">';
}
?>
 
 <?php
// showing the login & register or logout link
if (logged_in() == true) {
    echo'<br>';
    echo '<a href="dealer-member-logout.php">Log Out</a>';
} else {
    echo '<a href="dealer-member-login.php">Login</a> | <a href="dealer-member-register.php">Register</a>';
}
    echo '</div>';
?>
<hr />

</html>

<?php include( 'includes/footer.php' ); ?>