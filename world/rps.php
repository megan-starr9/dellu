<?php include('../header.php');
    if ($id == -1)
    {
        header('Location:'.$site->baseurl.'/');
    }
    else
    {
        $error = '';
        $game = new Game();
        
        if (isset($_POST['rock']))
        {
            $error = $game->RPS(1);
        }
        else if (isset($_POST['paper']))
        {
            $error = $game->RPS(2);
        }
        else if (isset($_POST['scissors']))
        {
            $error = $game->RPS(3);
        }
        echo $error;
    ?>
                <h1 class='pagetitle'>Rock, Paper, Scissors</h1>
                
                <form class='prettyform' action='' method='post'>
                <p>Choose Your Weapon!</p>
                    <input type='submit' name='rock' value='Rock'></input>
                    <input type='submit' name='paper' value='Paper'></input>
                    <input type='submit' name='scissors' value='Scissors'></input>
                </form>

<?php
    }
include('../footer.php');?>