<?php
    class Game extends Site
    {
        // ====================================== Properties ====================
        public $Kenolimit;
        public $RPSlimit;
        
        //======================================== Methods =========================
        /* Constructor */
        public function Game()
        {
            $Kenolimit = false;
            $RPSlimit = false;
            parent::__construct();
        }
        
        public function Keno($num)
        {
            $actual = rand(0,100);
            
            if ($num == $actual)
            {
                $error = "<div id='alert'>YAY! You've guessed correctly!</div>";
                $this->loggeduser->GiveSitecash(5000);
            }
            else
            {
                $error = "<div id='warning'>BOO, the number was $actual.</div>";
            }
            
            return $error;
            
        }
        
        public function RPS($choice)
        {
            // Rock = 1, Paper = 2, Scissors = 3
            $opp = rand(1,3);
            if ($opp ==1)
                $oppweap = "Rock";
            else if ($opp == 2)
                $oppweap = "Paper";
            else
                $oppweap = "Scissors";
                
            if ((($choice == 1) && ($opp == 3)) ||
                (($choice == 2) && ($opp == 1)) ||
                (($choice == 3) && ($opp == 2)))
            {
                $error = "<div id='alert'>You Win!</div>";
                $this->loggeduser->GiveSitecash(500);
            }
            else if ($opp == $choice)
            {
                $error = "<div id='warning'>Draw!</div>";
            }
            else
            {
                $error = "<div id='warning'>Opponent chose $oppweap, You Lose... =[ </div>";
            }
            
            return $error;
        }
        
        public function Bingo()
        {
            
        }
    }
?>