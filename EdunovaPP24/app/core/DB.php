<?php
// čitati https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
class DB extends PDO
{
    private static $instanca=null;

    private function __construct($baza)
    {
        $dsn='mysql:host=' . $baza['server'] . ';dbname=' . $baza['baza'] . ';charset=utf8mb4';
        parent::__construct($dsn,$baza['korisnik'],$baza['lozinka']);
        // postavka da uvijek dobivamo indeksni niza objekata (stdClass)
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    }

    public static function getInstanca()
    {
        if(self::$instanca==null){
            self::$instanca=new self(App::config('baza'));        
        }
        return self::$instanca;
    }

}