<?php include('header.php'); ?>                
                <?php if ($id != -1)
                {?>
                    <h2 class='minortitle'>Welcome</h2>
                    
                    <div id="newscontainer">
                    <h2 class='minortitle'>Recent News Updates</h2>
                    <?php
                        $query = mysql_query("SELECT * FROM news__update ORDER BY updatedate DESC");
                        $i = 0;
                        while (($newsarray = mysql_fetch_array($query)) && ($i<10))
                        {
                            $auth = new User($newsarray['updateposter']);
                            echo "<a href='".$site->baseurl."/news/".$newsarray['updateid']."'><h3>".$newsarray['updatetitle']."</h3></a>
                                <p>Posted ".$newsarray['updatedate']." by <a href='".$site->baseurl."/user/index/".$newsarray['updateposter']."'>".$auth->username."</a></p>
                                <p>".$newsarray['updatepost']."</p><hr class='light'>";
                            $i++;
                        }
                    ?>
                </div>
                    <?php echo "<a class='mainlink' href='".$site->baseurl."/news' style='margin-left: 35%;'>View all News Updates</a>";
                }
                else
                {
                    echo "<img style='margin-left: 20%' src='".$site->baseurl."/images/banner.png'>";
                }
                ?>

<?php include('footer.php') ?>