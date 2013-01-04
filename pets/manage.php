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
        $petid = (isset($segment[3])) ? $segment[3] : -1;
        
        if ($petid == -1 || !valid_pet($petid))
        {          
            echo "<p>That creature does not exist!</p>";
        }
        else if (!valid_owner($id, $petid))
        {
            echo "<p>That creature is not yours to edit!</p>";
        }
        else //If all cases pass, allow edit of pet information
        {
        	$pet = new Pet($petid);
            $error = "";
            if (isset($_POST['modifypet']))
            {
                $newname = mysql_real_escape_string($_POST['petname']);
                $error = $pet->change_name($newname);
            }
            else if (isset($_POST['setactive']))
            {
                $error = $pet->set_active();
            }
            echo "<p>$error</p>";
            ?>
            
            <!-- Pet Update Form -->
            <h2 class='minortitle'>Modify <?php echo $pet->petname; ?>'s Information</h1>
            <form class="prettyform" action="" method="post">
                <p>Change Name: <input type="text" name="petname" value="<?php echo $pet->petname; ?>"></p>
                <input type="submit" name="modifypet" value="Submit Modifications"><br>
                <input type="submit" name="setactive" value="Set <?php echo $pet->petname; ?> as your active pet">
            </form>
            
        <?php }
        
    }

include('../footer.php');

    
    /******************************************* Pet Edit Functions *********************************************/
    
    function valid_pet($petid)
    {
        $query = mysql_query("SELECT * FROM pets__owned WHERE id = $petid");
        $num = mysql_num_rows($query);
        return ($num != 0);
    }
    
    function valid_owner($userid, $petid)
    {
        $query = mysql_query("SELECT * FROM pets__owned WHERE id = $petid AND owner = $userid");
        $num = mysql_num_rows($query);
        return ($num != 0);
    }
    
?>