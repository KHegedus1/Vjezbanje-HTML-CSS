<?php

class App
{
    public static function start()
    {
        //echo '<pre>';
        //print_r($_SERVER);
        //echo '</pre>';
        $ruta = Request::getRuta();
        //echo $ruta;

        $djelovi = explode('/',$ruta);
        
        //echo '<pre>';
        //print_r($djelovi);
        //echo '</pre>';

        $klasa='';
        if(!isset($djelovi[1]) || $djelovi[1]===''){
            $klasa='Index';
        }else{
            $klasa=ucfirst($djelovi[1]);
        }
        $klasa .= 'Controller';
        //echo $klasa;

        $metoda='';
        if(!isset($djelovi[2]) || $djelovi[2]===''){
            $metoda='index';
        }else{
            $metoda=$djelovi[2];
        }

        $parametar=null;
        if(!isset($djelovi[3]) || $djelovi[3]===''){
            $parametar=null;
        }else{
            $parametar=$djelovi[3];
        }

        //echo $klasa . '->' . $metoda . '()';

        if(class_exists($klasa) && method_exists($klasa,$metoda)){
            // klasa i metoda postoje, instancirati klasu i pozvati metodu
            $instanca = new $klasa();
            if($parametar==null){
                $instanca->$metoda();
            }else{
                $instanca->$metoda($parametar);
            }
            
        }else{
            // metoda na klasi ne postoji, obavijestiti korisnika
            $view = new View();
            $view->render('error404',[
                'onoceganema' =>$klasa . '->' . $metoda
            ]);
            //echo $klasa . '->' . $metoda . '() ne postoji';
        }

        //$kontroler = new IndexController();
        //$kontroler->index();

    }

    public static function config($kljuc)
    {
        $config = include BP_APP . 'konfiguracija.php';
        return $config[$kljuc];
    }
    public static function autoriziran()
    {
        if(isset($_SESSION) && isset($_SESSION['autoriziran'])){
            return true;
        }

        return false;
    }
}