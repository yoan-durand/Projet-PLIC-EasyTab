<?php

require_once 'config.php';

class Bdd {

    /**
     * @var PDO 
     */
    private static $instance = null;

    /**
     * @global array $config
     * @return PDO 
     */
    public static function get() {
        if (self::$instance == null) {
            global $config;
            self::$instance = new PDO(
                            $config['bdd driver'] . ':dbname=' . $config['bdd dbname'] . ';host=' . $config['bdd host'],
                            $config['bdd user'], $config['bdd pass']);
        }
        return self::$instance;
    }

}