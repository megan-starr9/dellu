<?php include('../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
                <h1 class='pagetitle'>Games</h1>
                
                <!--<a href=''>Daily Chance</a><br> -->
                <a href='<?php echo $site->baseurl; ?>/world/keno.php'>Keno</a><br>
                <a href='<?php echo $site->baseurl; ?>/world/rps.php'>Rock, Paper, Scissors</a><br>
                <!--<a href=''>Bingo</a> -->

<?php
    }
include('../footer.php');?>