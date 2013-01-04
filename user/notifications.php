<?php include('../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    { ?>
        
        <h1 class='pagetitle'>Notifications</h1>
        
        <?php
        $notquery = mysql_query("SELECT * FROM notifications WHERE userid = $id ORDER BY date DESC");
        if (mysql_num_rows($notquery) == 0)
            echo "<p>You have no notifications!";
        else
        {
            echo "<div id='newscontainer'>";
            while ($not = mysql_fetch_array($notquery))
            {
                echo $not['message']."<br>Recieved: ".$not['date']."<hr class='light'>";
                $notid = $not['id'];
                mysql_query("UPDATE notifications SET new = '0' WHERE id = $notid");
            }
            echo "</div>";
        }
        
    }
include('../footer.php');

    
    /******************************************* Notification Functions *********************************************/
    
    

    
?>