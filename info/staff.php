<?php include('../header.php');
if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
            <h2>Site Staff</h2>
                
            <table style='margin: 0px 70px 20px;'>
                <?php
                    $staffquery = mysql_query('SELECT * FROM users WHERE rank < 9 ORDER BY rank ASC');
                    while ($staff = mysql_fetch_array($staffquery))
                    {
                    $staffmem = new User($staff['userid']);
                    $indicator = ($staffmem->online) ? $site->baseurl."/styles/$template/online.png" : $site->baseurl."/styles/$template/offline.png";
                    $icon = file_exists("../images/avatars/".$staffmem->userid.".png") ? "<img src='".$site->baseurl."/images/avatars/".$staffmem->userid.".png'>" : "<div style='width:150px;height:150px;'></div>";
                ?>
                <tr>
                    <td width='160px'><?php echo $icon; ?></td>
                    <td width='200px'><p><a href='<?php echo $site->baseurl; ?>/user/index/<?php echo $staffmem->userid; ?>'><?php echo $staffmem->username; ?></a> (#<?php echo $staffmem->userid; ?>) <img src="<?php echo $indicator; ?>"></p></td>
                    <td width='100px'><p><?php echo $staffmem->userrank; ?></p></td>
                    <td width='300px' style='text-align: center'><p><?php echo $staffmem->staffbio; ?><br>-----------------------</p></td>
                </tr>
                <?php } ?>
            </table>
                
<?php
    }
include('../footer.php');

    /******************************************* News Functions *********************************************/

?>