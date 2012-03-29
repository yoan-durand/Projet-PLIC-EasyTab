<?php

class User {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $pseudo;

    /**
     * @var int
     */
    public $dateInscription;

    public function __construct($id, $pseudo, $dateInscription) {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->dateInscription = $dateInscription;
    }

}