<?php

require('Deck.php');

class Game {

    const NOT_STARTED = 0;
    const BUILDING_CARDS = 1;
    const GAME_RUNNING = 2;
    const GAME_ENDED = 3;
    const ERROR = 4;
    
    private $playerTurn = 0;
    private $players = 2;
    private $gameState = null;
    private $winner = null;
    private $playerCards = [];
    private $deck;

    public function run()
    {
        $this->gameState = static::NOT_STARTED;
        $this->startGame();
    }   

    private function startGame()
    {

        $this->deck = new Deck();
        $cards = $this->deck->generateDeckInRandomOrder();
        $splitCards = array_chunk($cards, ($this->deck->totalCards / $this->players));
        $this->giveCardsToPlayers($splitCards);
        $this->executeGame();
    }


    private function giveCardsToPlayers($cards)
    {

        $this->gameState = static::BUILDING_CARDS;
        for ($i = 0; $i < $this->players; $i++)
        {
            $this->playerCards[$i]['number'] = $i;
            $this->playerCards[$i]['cards'] = $cards[$i];
            $this->playerCards[$i]['matches'] = 0;
            $this->playerCards[$i]['used'] = 0;
        }        
    }

    private function executeGame()
    {

        $this->gameState = static::GAME_RUNNING;

        $previousCard = null;


        // Loop 1 card for each player
        for ($i = 0; $i<$this->deck->totalCards; $i++)
        {

            echo "Player Turn: " . ($this->playerTurn + 1) . "\n";
            
            $playerCards = $this->playerCards[$this->playerTurn]['cards'];
            $cardNumber = $this->playerCards[$this->playerTurn]['used'];            

            $card = $playerCards[$cardNumber];

            if ($this->checkMatch($card, $previousCard))
            {
                echo "Match! \n";
                $this->playerCards[$this->playerTurn]['matches'] += 1;
            }

            $previousCard = $card;

            $this->playerCards[$this->playerTurn]['used'] += 1;

            $this->nextPlayerTurn();


            echo "----------\n";
        }      
        
        
        $this->displayWinner();

    }


    private function checkMatch($card1, $card2)
    {
        echo $card1['suite'] . '-' . $card1['card'] . ' vs ' . $card2['suite'] . '-' . $card2['card'] . "\n";



        return $card1['card'] === $card2['card'];
    }

    private function nextPlayerTurn()
    {   
        $this->playerTurn++;
            
        if ($this->playerTurn >= $this->players)
        {
            $this->playerTurn = 0;
        }
    }

    private function calculateWinner()
    {
        
        $previousPlayer = null;

        for ($i = 0; $i < $this->players; $i++)
        {

            echo "Player " . ($i + 1) . ' has ' . $this->playerCards[$i]['matches'] . " matches! \n";

            if (!is_null($previousPlayer)) {

                if ($this->playerCards[$i]['matches'] > $this->playerCards[$previousPlayer]['matches'])
                {
                    $this->winner = $i;
                }
            }

            $previousPlayer = $i;
        }

        return $this->winner + 1;
    }

    private function displayWinner()
    {
        echo 'The winner is player ' . $this->calculateWinner() . '!';
    }
}