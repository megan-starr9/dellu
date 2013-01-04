<?php include('../header.php');
    if (($id == -1) || ($currentuser->userlevel < 3))
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
                <h3>Admin CP</h3>
                <a href="<?php echo $site->baseurl; ?>/admin/adminforum">Forum Administration</a><br>
                <a href="<?php echo $site->baseurl; ?>/admin/adminnews">News Administration</a><br>
                <a href="<?php echo $site->baseurl; ?>/admin/adminusers">User Administration</a><br>
                <?php if($currentuser->userlevel >4) {echo "<a href='".$site->baseurl."/admin/adminpets'>Pet Administration</a><br>";} ?>
                <a href="<?php echo $site->baseurl; ?>/admin/adminitems">Item/Currency Administration</a><br>
                <?php if($currentuser->userlevel >4) {echo "<a href='".$site->baseurl."/admin/adminexplore'>Exploration Administration</a>";} ?><br>
                
                <br><a href="<?php echo $site->baseurl; ?>/admin/adminsuggest">User Suggestions</a><br>

<?php
    }
include('../footer.php');?>