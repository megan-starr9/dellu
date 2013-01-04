<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        if ($currentuser->userlevel >= 4)
        {
            if(isset($_POST['adduser']))
            {
                $userid = mysql_real_escape_string($_POST['userid']);
                $username = mysql_real_escape_string($_POST['username']);
                $displayname = mysql_real_escape_string($_POST['displayname']);
                $password = mysql_real_escape_string($_POST['password']);
                $rank = mysql_real_escape_string($_POST['rank']);
                
                $error = add_user($userid,$username,$displayname,$password,$rank);
                echo $error;
            }
        ?>            
            <table width="100%">
                    <tr>
                        <td>
                            <h4>Add A User</h4>
                            <form class="prettyform" method="post" action="">
                                <p>User ID #: <input type="text" name="userid"></input></p>
                                <p>Username: <input type="text" name="username"></input></p>
                                <p>Display Name: <input type="text" name="displayname"></input></p>
                                <p>Password: <input type="text" name="password"></input></p>
                                <p>Rank: <select name="rank">
                                    <?php $rankquery = mysql_query("SELECT * FROM ranks");
                                    while ($rank = mysql_fetch_array($rankquery))
                                    {
                                        echo "<option value='".$rank['rankid']."'>".$rank['ranktitle']."</option>";
                                    } ?>
                                </select></p>
                                <input type="submit" name="adduser" value="Add User">
                            </form>
                        </td>
                        <td>
                            <h4>Promote/Demote/Entitle User</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Ban/Freeze Account</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                        <td>
                            <h4>Something?</h4>
                            <form class="prettyform" action="" method="post">
                                <p>Coming...</p>
                            </form>
                        </td>
                    </tr>
                </table>
            
        <?php
        }
    }
include('../footer.php');

    /******************************************* News Admin Functions *********************************************/
    function add_user($userid,$username,$displayname,$password,$rank)
    {
        $passencrypt = md5("$password"."$username");
        $query = "INSERT INTO users (userid,username,displayname,password,rank) VALUES ('$userid','$username','$displayname','$passencrypt','$rank')";
        $error = (!mysql_query($query)) ? "<div id='warning'>I'm sorry, there has been an error: ".mysql_error()."</div>" : "<div id='alert'>User $displayname successfully added!</div>";
        return $error;
    }
    
?>