<?php include('../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $error = '';
        $game = new Game();
        
        if (isset($_POST['keno']))
        {
            $guess = mysql_real_escape_string($_POST['num']);
            $error = $game->Keno($guess);
        }
        echo $error;
    ?>
                <h1 class='pagetitle'>Keno</h1>
                
                <?php if ($game->Kenolimit)
                {
                    echo "<p>You have already played this game enough today!/p>";
                }
                else
                { ?>
                <form class='prettyform' action='' method='post'>
                <p>Enter a number between 0 and 100! (inclusively)</p>
                    <input type='text' name='num'></input>
                    <input type='submit' name='keno' value='Submit Guess'></input>
                </form>

<?php }
    }
include('../footer.php');?>