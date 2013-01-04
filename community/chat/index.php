<?php include('../../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {?>
                <h1 class='pagetitle'>Chat</h1>

                <div style="width:800px;margin:auto;">
                  <?php include('../../chat/chat.php'); ?>
                </div>

<?php
    }
include('../../footer.php');
?>
