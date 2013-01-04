<?php include('../header.php');
if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
            <h1 class='pagetitle'>Users Currently Online</h1>
            
            <p>Staff Color Key:<br>
                <a style='color:#576929'>Owner</a> - 
                <a style='color:#990055'>Coder</a> - 
                <a style='color:#ffa200'>Administrator</a> - 
                <a style='color:#aa0000'>Moderator</a> - 
                <a style='color:#00b5dc'>Staff Artist</a></p>
                <br>
                <?php
                    $memberquery = mysql_query('SELECT * FROM users ORDER BY userid ASC');
                    while ($member = mysql_fetch_array($memberquery))
                    {
                    $mem = new User($member['userid']);
                    if ($mem->online)
                    {
                        $color = '#000000';
                        if ($mem->userrank == 'Owner')
                        {
                            $color = '#576929';
                        }
                        else if ($mem->userrank == 'Coder')
                        {
                            $color = '#990055';
                        }
                        else if (($mem->userrank == 'Head Admin') || ($mem->userrank == 'Administrator'))
                        {
                            $color = '#ffa200';
                        }
                        else if (($mem->userrank == 'Head Mod') || ($mem->userrank == 'Moderator'))
                        {
                            $color = '#aa0000';
                        }
                        else if (($mem->userrank == 'Head Artist') || ($mem->userrank == 'Staff Artist'))
                        {
                            $color = '#00b5dc';
                        }
                        
                        echo "<a style='color:$color' href='".$site->baseurl."/user/index/".$mem->userid."'>".$mem->username."</a> (#".$mem->userid.") <br>";
                    }                    
                    } ?>
<?php
    }
include('../footer.php');

    /******************************************* News Functions *********************************************/

?>