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
        $denid = (isset($segment[3])) ? $segment[3] : $id;
        
        $query=mysql_query("SELECT * FROM pets__owned WHERE owner='$denid'");
        $result=mysql_num_rows($query);
        
        if ($result == 0) // Does den have pets within?
        { ?>
            
            <p>This den is empty!</p>
            
        <?php }
        else //If so, display them
        { ?>
            <h1 class='pagetitle'>Den Name Here!</h1>
            <?php
            while ($pet = mysql_fetch_array($query))
            {
                $petinfo = new Pet($pet['id']);
                ?>
                <div id='petbox'>
                    <a href='<?php echo $site->baseurl; ?>/pets/index/<?php echo $petinfo->petid; ?>'><img width='150px' src='<?php echo $petinfo->Petimage(); ?>'></a>
                    <p><?php echo $petinfo->petname; ?><br>
                    <?php echo $petinfo->petgender." ".$petinfo->petcolor." ".$petinfo->petspecies; ?></p>
                </div>
            <?php }
        }
    }
include('../footer.php'); ?>