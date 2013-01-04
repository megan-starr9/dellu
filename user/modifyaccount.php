<?php include('../header.php');

    if ($id == -1)
        {
            header('Location:'.$site->baseurl.'/');
        }
    else
        { ?>
                
                <?php
                    $error= "";
                    
                    // Account Settings Variables
                    if (isset($_POST['account']))
                    {
                        $displayname= mysql_real_escape_string($_POST['displayname']);
                        $email= mysql_real_escape_string($_POST['email']);
                        $userbio = mysql_real_escape_string($_POST['userbio']);
                        $staffbio= (isset ($_POST['staffbio'])) ? mysql_real_escape_string($_POST['staffbio']) : NULL;
                        
                        $error= modify_account($id, $displayname, $email, $userbio, $staffbio);
                    }
                    // Avatar Upload Variables
                    if (isset($_POST['avatar']))
                    {
                        $error= upload_avatar($id, $_FILES['avatarfile']);
                    }
                    // Template Select variables
                    if (isset($_POST['updatetemp']))
                    {
                    	$fluid = (isset($_POST['fluid']));
                        $template = mysql_real_escape_string($_POST['template']);
                        $error= $site->loggeduser->set_template($template,$fluid);
                    }
                    // Password Change Variables
                    if (isset($_POST['pass']))
                    {
                        $oldpass= mysql_real_escape_string($_POST['oldpass']);
                        $newpass= mysql_real_escape_string($_POST['newpass']);
                        $newpass2= mysql_real_escape_string($_POST['newpass2']);
                        
                        $error= change_pass($id, $oldpass, $newpass, $newpass2);
                    }
                    
                    echo $error;
                    
                    // Get Current Values
                    $query = mysql_query("SELECT * FROM users WHERE userid = $id");
                    $result = mysql_fetch_array($query);
                    $currentemail = $result['email'];
                ?>
                
                <h1 class='pagetitle'>Account Management</h1>
                <div id="generalcontainerleft">
                    <!-- General Account Settings Form -->
                    <h2 class='minortitle'>Modify Account Settings</h2>
                    <form class="prettyform" action="" method="post">
                        <p>Change Display Name: <input type="text" name="displayname" value="<?php echo $currentuser->username; ?>"></p>
                        <p>Change E-mail Address: <input type="text" name="email" value="<?php echo $currentemail; ?>"></p>
                        <p>Modify Profile Bio: <textarea name="userbio"><?php echo $currentuser->bio; ?></textarea></p>
                        <?php if ($currentuser->userlevel > 0) { ?>
                            <p>Modify Staff Bio: <textarea name="staffbio"><?php echo $currentuser->staffbio; ?></textarea></p>
                        <?php } ?>
                        <input type="submit" name="account" value="Submit Changes">
                    </form><hr class='light'>
                    
                    <!-- Icon Upload Form -->
                    <h2 class='minortitle'>Upload Icon</h2>
                    <form class="prettyform" action="" enctype="multipart/form-data" method="post">
                        <p>Current Icon: <img src="<?php echo $site->baseurl; ?>/images/avatars/<?php echo "$id"; ?>.png"></p>
                        <p><input type="file" name="avatarfile"></p>
                        <input type="submit" name="avatar" value="Upload">
                    </form><hr class='light'>
                    
                    <!-- Template Selection Form -->
                    <h2 class='minortitle'>Change Site Template</h2>
                    <img src="<?php echo $site->baseurl; ?>/styles/<?php echo "$template"; ?>/screen.png">
                    <form class="prettyform" action="" method="post">
                        <select name="template">
                            <option value='default'>Default Theme</option>
                            <option value='reddefault'>Red Theme</option>
                            <option value='cherryblossom'>Cherry Blossom Theme</option>
                            <option value='cherryblossomfluid'>Cherry Blossom Fluid Theme</option>
                        </select>
                       	<p>Check for fluid layout: <input type="checkbox" name="fluid"></input></p>
                        <input type="submit" name="updatetemp" value="Change Template">
                    </form><hr class='light'>
                    
                    <!-- Password Change Form -->
                    <h2 class='minortitle'>Change Password</h2>
                    <form class="prettyform" action="" method="post">
                        <p>Old Password: <input type="password" name="oldpass"></p>
                        <p>New Password: <input type="password" name="newpass"></p>
                        <p>Verify New Password: <input type="password" name="newpass2"></p>
                        <input type="submit" name="pass" value="Change Password">
                    </form>
                </div>
        
    <?php }

include('../footer.php');

    /******************************************* Modify Functions *********************************************/
    function modify_account($id, $displayname, $email, $userbio, $staffbio) // Modifies General Account Details
    {
        
        
        if ($displayname != "") // Change Username
        {
            if ($email != "") // Change Email
            {
                $query = "UPDATE users SET displayname='$displayname', email='$email', userbio = '$userbio', staffbio = '$staffbio' WHERE userid='$id'";
                mysql_query($query);
                $error="<div id='alert'>Your changes were successful.</div>";
            }
            else
            {
                $error="<div id='warning'>Please fill out an email address.</div>";
            }
    }
        else
        {
            $error="<div id='warning'>Please fill out a display name.</div>";
        }
        
        return $error;
    }
    
    function upload_avatar($user, $file)
    {
        $targetpath = "../images/avatars/$user.png";
        $maxdim = '150px';
        $maxsize = '100000';
        $dim = getimagesize($file['tmp_name']);
        if (($file["type"] == "image/gif")
            || ($file["type"] == "image/jpeg")
            || ($file["type"] == "image/png" ))
        {
            if ($file["size"] < $maxsize)
            {
                if (($dim[0] <= $maxdim) && ($dim[1] <= $maxdim))
                    {
                        move_uploaded_file($file["tmp_name"],$targetpath);
                        $error = "<div id='alert'>Icon successfully updated!</div>";
                    }
                else
                {
                    $error = "<div id='warning'>Images can be, at most, $maxdim"."x"."$maxdim pixels in size.</div>";
                }
            }
            else
            {
                $error = "<div id='warning'>Images can be, at most, $maxsize"."kb in size.</div>";
            }
        }
        else
        {
            $error = "<div id='warning'>Files must be either JPEG, GIF, or PNG.</div>";
        }
        return $error;
    }
    
    function change_pass($id, $oldpass, $newpass, $newpass2)
    {
        if ($newpass != $newpass2)
        {
            $error = "<div id='warning'>The new passwords you entered do not match!</div>";
        }
        else if (strlen($newpass) < 6)
        {
            $error = "<div id='warning'>Please enter a password with six or more characters.</div>";
        }
        else
        {
            $usernamequery = mysql_query("SELECT * FROM users WHERE userid = '$id'");
            $user = mysql_fetch_array($usernamequery);
            $oldencryptpass = md5($oldpass.$user['username']);
            if ($user['password'] != $oldencryptpass)
            //if ($user['password'] != $oldpass)
            {
                $error = "<div id='warning'>You must enter your correct former password.</div>";
            }
            else
            {
            $encryptpass = md5($newpass.$user['username']);
            $query = "UPDATE users SET password= '$encryptpass' WHERE userid= '$id'";
            
            $error = (!mysql_query($query)) ? "<div id='warning'>There seems to have been an error: ".mysql_error()."</div>" : "<div id='alert'>Password successfully changed!</div>";
            }
        }
        
        return $error;
    }
    ?>