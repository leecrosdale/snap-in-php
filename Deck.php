<?php

class Deck {


    private $availableCards = ['A','K','Q','J','10','9','8','7','6','5','4','3','2'];
    private $suites = ['Clubs', 'Diamonds', 'Hearts', 'Spades'];

    public $cards = [];
    public $totalCards = 0;

    function __construct()
    {
        $cardNumber = 0;
        foreach($this->suites as $suite)
        {
            foreach ($this->availableCards as $availableCard)
            {
                $this->cards[$cardNumber] = [
                    'suite' => $suite,
                    'card' => $availableCard
                ];
                $cardNumber++;
            }            
        }

        $this->totalCards = count($this->cards);
        
        
        echo "Total Cards: " . $this->totalCards . "\n";

    }

    public function generateDeckInRandomOrder()
    {
        shuffle($this->cards);

        return $this->cards;
    }

}