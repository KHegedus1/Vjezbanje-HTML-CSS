<?php

class VjezbaController extends Controller
{

    private $viewDir = 'vjezbanje' . DIRECTORY_SEPARATOR;

    public function primjer1()
    {
        $this->view->render($this->viewDir . 'primjer1');
    }

    public function primjer2()
    {

        $sb = rand(2,9);
        $ime='Edunova';
        $o = new stdClass();
        $o->ime='Pero';
        $o->prezime='Perić';
        $niz=[
            'Osijek', 'Zagreb', 'Donji Miholjac'
        ];
        shuffle($niz);


        $this->view->render($this->viewDir . 'ispisParametara',[
            'slucajniBroj'=>$sb,
            'skola'=>$ime,
            'voditelj'=>$o,
            'gradovi'=>$niz
        ]);
    }

    public function primjer3()
    {
        $parniBrojevi=[];
        for($i=1;$i<=99;$i++){
            if($i%2===0){
                $parniBrojevi[]=$i;
            }
        }

        $this->view->render($this->viewDir . 'primjer3',[
            'parniBrojevi'=>$parniBrojevi
        ]);
    }

    public function primjer3lose()
    {
        $parniBrojevi='';
        for($i=1;$i<=99;$i++){
            if($i%2===0){
                $parniBrojevi.='<li>'.$i.'</li>';
            }
        }

        $this->view->render($this->viewDir . 'primjer3lose',[
            'parniBrojevi'=>$parniBrojevi
        ]);
    }

    public function testbaza()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from smjer');
        $izraz->execute();
        print_r($izraz->fetchAll());
    }

    public function lozinka()
    {
       echo password_hash('a',PASSWORD_BCRYPT);
    }


}