<?php include('../header.php');

    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        // URL select to get profileid
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $profileid = (isset($segment[3])) ? $segment[3] : $id;
        
        $query=mysql_query("SELECT * FROM users WHERE userid='$profileid'");
        $result=mysql_num_rows($query);
        
        if ($result == 0) // Is profileid valid?
        { ?>
            
            <p>This user does not exist!</p>
            
        <?php }
        else //If so, grab user information
        { 
            $profuser = new User($profileid);
            $indicator = ($profuser->online) ? $site->baseurl."/styles/$template/online.png" : $site->baseurl."/styles/$template/offline.png";
            ?>
            <div id='infocontainer'>
                <h1 class='pagetitle'><img src="<?php echo $indicator; ?>"> <?php echo $profuser->username; ?></h1>
                <h4>&nbsp;#<?php echo $profuser->userid; ?> &nbsp;&nbsp;| | &nbsp;&nbsp;<?php echo $profuser->userrank; ?> &nbsp;&nbsp; | | &nbsp;&nbsp;Joined <?php echo $profuser->userdate; ?></h4>
            </div>
            <div id='linkcontainer'>
                <p><a href='<?php echo $site->baseurl; ?>/pets/den/<?php echo $profuser->userid; ?>'>View My Den</a> &nbsp;|  &nbsp;<a href=''>Friend Me</a> &nbsp;| &nbsp;<a href='<?php echo $site->baseurl; ?>/community/pm/new/user/<?php echo $profuser->userid; ?>'>Message Me</a> &nbsp;|  &nbsp;<a href=''>Visit My Shop</a></p>
            </div>
            
            <div id='iconcontainer'>
                <img src='<?php echo $site->baseurl; ?>/images/avatars/<?php echo $profileid; ?>.png'>
            </div>
            
            <div id='avatarcontainer'>
                <p>User Avatar</p>
            </div>
            
            <div id='biocontainer'>
                <p><?php echo $profuser->bio; ?></p>
            </div>
            
    <?php }
    }
include('../footer.php') ?>