<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<?php
    // For any mistakes... >>
    /*error_reporting(E_ALL);
        ini_set('display_errors', TRUE);*/
    ?>
    <?php
        // Here because in Site class caused warnings
        session_start();
        
        // Class includes!!
        include($_SERVER['DOCUMENT_ROOT'].'/classes/site_class.php');
        include($_SERVER['DOCUMENT_ROOT'].'/classes/user_class.php');
        include($_SERVER['DOCUMENT_ROOT'].'/classes/pet_class.php');
        include($_SERVER['DOCUMENT_ROOT'].'/classes/forum_class.php');
        include($_SERVER['DOCUMENT_ROOT'].'/classes/game_class.php');
        include($_SERVER['DOCUMENT_ROOT'].'/classes/item_class.php');
        
        $site = new Site;
        $id = ($site->loggeduser == NULL) ? -1 : $site->loggeduser->userid;
        $template = 'default';
        $fluid = false;
        $petimg = '';
        
        // ===== Set Notifications ============================================================
       if($id != -1)
        {
            $currentuser = new User($id);
            $currentuser->activity();
            // Set necessary user variables
            $template = $currentuser->template;
            $fluid = $currentuser->fluid;
            
            $notquery = mysql_query("SELECT * FROM notifications WHERE userid = '$id' && new = '1'");
            $msgquery = mysql_query("SELECT * FROM pm__post WHERE new = '1'");
            $notcount = mysql_num_rows($notquery);
            
            $notifications = ($notcount == 0) ? "" : "<div id='alert'><a href='".$site->baseurl."/user/notifications'>You have new notifications!</a></div>";
            
            $count = 0;
            while ($pm = mysql_fetch_array($msgquery))
            {
                $convo = $pm['pmconvo'];
                $convoquery = mysql_query("SELECT * FROM pm__convo WHERE convoid = '$convo'");
                $convoresult = mysql_fetch_array($convoquery);
                if ($pm['pmauthor'] != $id && ($convoresult['convostarter'] == $id ||$convoresult['convoreceiver'] == $id))
                {
                    $count ++;
                }
            }
        }
        else
            $notifications = "";
        
        //====== Set Userbars =============================================
        if ($id != -1)
        {
            $adminlink = ($currentuser->userlevel > 2) ? " || <a href='".$site->baseurl."/admin/'>Admin CP</a>" : "";
            
            $userbar1 = "<a href='".$site->baseurl."/user/index/$id'>".$currentuser->username."</a> (# $id) || ".$currentuser->userrank." || <a href='".$site->baseurl."/logout.php'>Logout</a> $adminlink";
            $userbar2 = "Bits: $".$currentuser->sitecash." || <a href='".$site->baseurl."/purchase'>Bobs:</a> $".$currentuser->realcash." || <a href='".$site->baseurl."/community/pm/'>Messages: $count</a> || <a href='".$site->baseurl."/user/notifications'>Notifications: $notcount</a>";
        }
        else
        {
            $userbar1 = "<a href='".$site->baseurl."/login.php'>Login</a> || Register";
            $userbar2 = "Welcome!";
            $template = 'default';
        }
        
        //===== Set Sub Navigation Bar ======================================================
        $uri = $_SERVER['REQUEST_URI'];
        $segment = explode("/", $uri);
        $section = (isset($segment[1])) ? $segment[1] : "";
        if ($section=="user") // User Category Links
        {
            $subnavbar = "<a href='".$site->baseurl."/user/modifyaccount'>Modify Account</a> || <a href='".$site->baseurl."/user/'>View Profile</a> || <a href='".$site->baseurl."/user/inventory'>Inventory</a> || Notepad";
        }
        else if ($section=="pets")
        {
            $subnavbar = "<a href='".$site->baseurl."/pets/'>View/Manage Pets</a> || <a href='".$site->baseurl."/pets/den'>View Den</a> || Manage Den";
        }
        else if ($section=="world")
        {
            $subnavbar = "Scout || <a href='".$site->baseurl."/world/games'>Games</a> || <a href='".$site->baseurl."/world/shop'>Shops</a> || Marketplace";
        }
        else if ($section=="community")
        {
            $subnavbar = "<a href='".$site->baseurl."/community/forum/'>Forums</a> || <a href='".$site->baseurl."/community/pm/'>PM</a> || Friend List || Chat || <a href='".$site->baseurl."/community/online'>Users Online</a>";
        }
        else if ($section=="info")
        {
            $subnavbar = "<a href='".$site->baseurl."/info/story'>Story</a> || Search || FAQ || <a href='".$site->baseurl."/info/staff'>Staff</a>";
        }
        else // Home Category Links
        {
            $subnavbar = "<a href='".$site->baseurl."/tos'>TOS</a> || <a href='".$site->baseurl."/purchase'>Purchase Bobs</a>";
        }
    ?>
    
    <link rel="icon" type="image/png" href="<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/favicon.png">
    
    <head>
        <title>Dellusionary</title>
        <?php if($fluid)
        		echo"<link rel='stylesheet' type='text/css' href='".$site->baseurl."/styles/technical_fluid.css' />";
        	else 
        		echo "<link rel='stylesheet' type='text/css' href='".$site->baseurl."/styles/technical.css' />";?>
        <link rel="stylesheet" type="text/css" href="<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/main.css" />
        <script language="javascript" type="text/javascript"></script>
    </head>
    
    <body>
        <div id="buffer">&nbsp;</div>
        <div id="l_container">
            
            <div id="l_userinfo1">
                <p class='topbars'><?php echo $userbar1; ?></p>
            </div>
            
            <div id="l_topcontent">
                <!-- Sub Navigation Links -->
                <div id="l_userinfo2">
                    <p class='topbars'><?php echo $userbar2; ?></p>
                </div>
                <!-- Main Navigation Buttons -->
                <div id="l_lownav">
                    <p>
                        <a class='mainnav1' href='<?php echo $site->baseurl; ?>/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/home.png' alt='Home'></a>
                        <a class='mainnav2' href='<?php echo $site->baseurl; ?>/user/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/user.png' alt='User'></a>
                        <a class='mainnav3' href='<?php echo $site->baseurl; ?>/pets/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/pets.png' alt='Pets'></a>
                        <a class='mainnav4' href='<?php echo $site->baseurl; ?>/world/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/world.png' alt='World'></a>
                        <a class='mainnav5' href='<?php echo $site->baseurl; ?>/community/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/community.png' alt='Community'></a>
                        <a class='mainnav6' href='<?php echo $site->baseurl; ?>/info/'><img src='<?php echo $site->baseurl; ?>/styles/<?php echo $template; ?>/info.png' alt='Info'></a>
                    </p>
                </div>
                
                <!-- User Controls -->
                <div id="l_midnav">
                    <p class='topbars'><?php echo $subnavbar; ?></p>
                </div>
            </div>
            
            <!-- Active Pet Image -->
            <div id="l_pic">
                <?php if($id != -1 && $currentuser->activepet != 0)
                {
                    $active = new Pet($currentuser->activepet);
                    echo "<img width='160px' src='".$active->Petimage()."'>";
                }
                ?>
            </div>
            
            <div id="l_maincontent">
            
            <?php echo $notifications; ?>