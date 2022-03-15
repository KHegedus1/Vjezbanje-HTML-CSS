<?php

class PredavacController extends AutorizacijaController
{

    private $viewDir = 
                'privatno' . DIRECTORY_SEPARATOR . 
                    'predavaci' . DIRECTORY_SEPARATOR;

    private $predavac;
    private $poruka;

    public function __construct()
    {
        parent::__construct();
        $this->predavac = new stdClass();
        $this->predavac->sifra=0;
        $this->predavac->ime='';
        $this->predavac->prezime='';
        $this->predavac->oib='';
        $this->predavac->email='';
        $this->predavac->iban='';
    }


    public function index()
    {
       $this->view->render($this->viewDir . 'index',[
           'predavaci'=>Predavac::read()
       ]);
    }   

    public function detalji($sifra=0)
    {
        if($sifra===0){
            $this->view->render($this->viewDir . 'detalji',[
                'predavac'=>$this->predavac,
                'poruka'=>'Unesite tražene podatke',
                'akcija'=>'Dodaj novi'
            ]);
        }else{
            $this->view->render($this->viewDir . 'detalji',[
                'predavac'=>Predavac::readOne($sifra),
                'poruka'=>'Promjenite podatke',
                'akcija'=>'Promjena'
            ]);
        }

    }

    public function akcija()
    {
        if($_POST['sifra']==0){
            //prvo kontrole 
            if($this->kontrolaOIB($_POST['oib'])){
                Predavac::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'detalji',[
                    'predavac'=>(object)$_POST,
                    'poruka'=>'Neispravan OIB',
                    'akcija'=>'Dodaj novi'
                ]);
                return;
            }
            
        }else{
            //prvo kontrole 
            Predavac::update($_POST);
        }
        header('location:' . App::config('url').'predavac/index');
    }


    public function brisanje($sifra)
    {
        Predavac::delete($sifra);
        header('location:' . App::config('url').'predavac/index');
    }



    private function kontrolaOIB($oib) {

        if (strlen($oib) != 11 || !is_numeric($oib)) {
            return false;
        }
    
        $a = 10;
    
        for ($i = 0; $i < 10; $i++) {
    
            $a += (int)$oib[$i];
            $a %= 10;
    
            if ( $a == 0 ) { $a = 10; }
    
            $a *= 2;
            $a %= 11;
    
        }
    
        $kontrolni = 11 - $a;
    
        if ( $kontrolni == 10 ) { $kontrolni = 0; }
    
        return $kontrolni == intval(substr($oib, 10, 1), 10);
    }
















    public function test($sto){
        switch ($sto) {
            case 'dodaj':
                Predavac::create([
                    'ime'=>'Pero',
                    'prezime'=>'Pero',
                    'oib'=>'Pero',
                    'iban'=>'Pero',
                    'email'=>'Pero'
                ]);
                break;
            case 'promjeni':
                Predavac::update([
                    'ime'=>'Pero1',
                    'prezime'=>'Pero1',
                    'oib'=>'Pero1',
                    'iban'=>'Pero1',
                    'email'=>'Pero1',
                    'sifra'=>3
                ]);
                break;
            case 'obrisi':
                Predavac::delete(3);
                break;
            case 'index':
                print_r(Predavac::read());
                break;
            case 'read':
                print_r(Predavac::readOne(1));
                break;
            default:
                # code...
                break;
        }
    }
}