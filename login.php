<?php include('header.php'); ?>

                <h1 class='pagetitle'>Login</h1>
                
                
                <?php
                if(isset($_POST['username'])) // Has information been entered?
                {
                    $username = mysql_real_escape_string($_POST['username']);
                    $password = mysql_real_escape_string($_POST['password']);
                    $error = $site->login($username, $password);
		    
		    echo $error;
                }
                
                if(isset($_SESSION['userid'])) { // Is user already logged in? 
                    header('Location:'.$site->baseurl.'/');
                 } else { // If not, display login form ?>
                <!-- Login Form -->
                <form class="prettyform" action="" method="post">
                    <p>Username: <input type="text" name="username"></input></p>
                    <p>Password: <input type="password" name="password"></input></p>
                    <input type="submit" value="Login">
                </form>
                <?php } ?>

<?php include('footer.php') ?>

<?php
    
    /******************************************* Login Functions *********************************************/
    
    function existing_username($username) // CHECKS IF USERNAME EXISTS
    {
        $query = mysql_query("SELECT * FROM users WHERE username = '$username'" );
        $result = mysql_num_rows($query);
	
        return !($result == 0);
    }
    
    function valid_match($username, $password) // CHECKS IF LOGIN VALID
    {
	$encryptpass = md5($password.$username);
        $query= mysql_query("SELECT * FROM users WHERE username = '$username'AND password ='$encryptpass'");
        $result = mysql_num_rows($query);
        
        return ($result == 1);
    }
?>