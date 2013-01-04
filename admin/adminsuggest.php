<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        echo "<h2>User Suggestions</h2>";
        $query = mysql_query("SELECT * FROM suggestions");
        while ($sugg = mysql_fetch_array($query))
        {
            if (($currentuser->rankid == $sugg['staff']) || ($currentuser->rankid == $sugg['staff']-1) || ($currentuser->rankid == 1) || ($currentuser->rankid == 3) || ($currentuser->rankid == 4))
            {
                $submitter = new User($sugg['user']);
                echo "<p>From: ".$submitter->username."<br>
                    ".$sugg['contents']."<br>
                    Submitted: ".$sugg['date']."</p>";
            }
        }
    }
include('../footer.php');

    /******************************************* Suggestion Admin Functions *********************************************/
    
?>