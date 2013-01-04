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
        $profileid = (isset($segment[3])) ? $segment[3] : -1;
        
        //Mysql Calls to collect pet information
        $query=mysql_query("SELECT * FROM pets__owned WHERE id='$profileid'");
        $result=mysql_num_rows($query);
        $mypetquery=mysql_query("SELECT * FROM pets__owned WHERE owner=$id");
        
        if ($profileid == -1)
        {
            echo "<h1 class='pagetitle'>Your Pets</h1>";
            // Loop to display pets on management page
            $petnum = mysql_num_rows($mypetquery);
            if ($petnum == 0)
            {
                echo "<p>You currently have no pets.</p>";
            }
            else
            {
                while($mypet = mysql_fetch_array($mypetquery))
                {
                    $pet = new Pet($mypet['id']);
                    echo "<div id='petbox'>
                        <a href='".$site->baseurl."/pets/index/".$pet->petid."'><img width='150' src='".$pet->Petimage()."'></a><br>"
                        .$pet->petname."<br>
                        <a href='".$site->baseurl."/pets/manage/".$pet->petid."'>Manage Pet</a><br>
                    </div>";
                }            
            }
        }
        else if ($result == 0) // Is profileid valid?
        { 
            
            echo "<p>That creature does not exist!</p>";
            
        }
        else //If so, grab pet information
        { 
        	$petinfo = mysql_fetch_array($query);
            $pet = new Pet($profileid);
            $owner = new User($petinfo['owner']);
            ?>
            <br>
            <div id='generalcontainer'>
                <?php echo "<img src='".$pet->Petimage()."'><br>
                <div id='petcontainer'>
                    <div id='petnamecontainer'>
                        <h2>".$pet->petname."</h2>
                    </div>
                    <div id='petidcontainer'>
                        <h2>#".$pet->petid."</h2>
                    </div>
                    <div id='petcontentcontainer'>
                        <p>".$pet->petgender." ".$pet->petcolor." ".$pet->petspecies."</p>
                        <p>Owned by <a href='".$site->baseurl."/user/index/".$pet->petowner->userid."'>".$pet->petowner->username."</a></p>
                    </div>
                </div>"; ?>
            </div>
            
        <?php } ?>
            
            
        
    <?php }

include('../footer.php') ?>