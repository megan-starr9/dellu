<?php include('header.php'); ?>

                <?php
                    mysql_query("UPDATE users SET online = '0' WHERE userid = $id");
                    session_unset();
                    session_destroy();
                    header('Location:'.$site->baseurl.'/');
                ?>
                <p>You have successfully logged out.</p>

<?php include('footer.php') ?>