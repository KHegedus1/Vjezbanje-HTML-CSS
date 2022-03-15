<?php

class SmjerController extends AutorizacijaController
{

    private $viewDir = 
                'privatno' . DIRECTORY_SEPARATOR . 
                    'smjerovi' . DIRECTORY_SEPARATOR;
    private $nf;
    private $poruka;
    private $smjer;

    public function __construct()
    {
        parent::__construct();
        $this->nf = new \NumberFormatter("hr-HR", \NumberFormatter::DECIMAL);
        $this->nf->setPattern('#,##0.00 kn');
        $this->smjer = new stdClass();
        $this->smjer->naziv='';
        $this->smjer->trajanje='130';
        $this->smjer->cijena='';
        $this->smjer->certificiran=false;
    }

    public function index()
    {
        $smjerovi = Smjer::read();
       
        foreach($smjerovi as $smjer){
                $smjer->cijena=$this->nf->format($smjer->cijena);
        }

       $this->view->render($this->viewDir . 'index',[
           'smjerovi' => $smjerovi,
           'css'=>'<link rel="stylesheet" href="' . App::config('url') . 'public/css/smjerindex.css">'
       ]);
    }   

    public function novi()
    {
        $this->view->render($this->viewDir . 'novi',[
            'poruka'=>'',
            'smjer'=>$this->smjer
        ]);
    }

    public function promjena($id)
    {
        $this->smjer = Smjer::readOne($id);

        if($this->smjer->cijena==0){
            $this->smjer->cijena='';
        }else{
            $this->smjer->cijena=$this->nf->format($this->smjer->cijena);
        }

        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>'Promjenite podatke',
            'smjer'=>$this->smjer
        ]);
    }

    public function dodajNovi()
    {
        $this->pripremiPodatke();

        if($this->kontrolaNaziv()
        && $this->kontrolaTrajanje()
        && $this->kontrolaCijena()){
            Smjer::create((array)$this->smjer);
            //$this->index();
            header('location:' . App::config('url').'smjer/index');
        }else{
            $this->view->render($this->viewDir.'novi',[
                'poruka'=>$this->poruka,
                'smjer'=>$this->smjer
            ]);
        }
       
    }

    public function promjeni()
    {
        $this->pripremiPodatke();
        
        if($this->kontrolaNaziv()
        && $this->kontrolaCijena()){
            Smjer::update((array)$this->smjer);
            //$this->index();
            header('location:' . App::config('url').'smjer/index');
        }else{
            $this->view->render($this->viewDir.'promjena',[
                'poruka'=>$this->poruka,
                'smjer'=>$this->smjer
            ]);
        }
    }

    public function brisanje($sifra)
    {
        Smjer::delete($sifra);
        //$this->index();
        header('location:' . App::config('url').'smjer/index');
    }

    private function pripremiPodatke()
    {
        $this->smjer=(object)$_POST;
        if($this->smjer->certificiran=='1'){
            $this->smjer->certificiran=true;
        }else{
            $this->smjer->certificiran=false;
        }
    }

    private function kontrolaNaziv()
    {
        if(strlen($this->smjer->naziv)===0){
            $this->poruka='Naziv obavezno';
            return false;
        }
        if(strlen($this->smjer->naziv)>50){
            $this->poruka='Naziv ne smije biti duži od 50 znakova';
            return false;
        }

        return true;
    }

    private function kontrolaTrajanje()
    {
        if(strlen(trim($this->smjer->trajanje))===0){
            $this->poruka='Trajanje obavezno';
            return false;
        }

        $broj = (int) trim($this->smjer->trajanje);
        if($broj<=0){
            $this->poruka='Trajanje mora biti cijeli broj veći od 0, unijeli ste: ' 
            . $this->smjer->trajanje;
            $this->smjer->trajanje='';
            return false;
        }


        return true;
    }

    private function kontrolaCijena()
    {
        if(strlen(trim($this->smjer->cijena))>0){

           // 1.200.99 kn
           // if(strpos($this->smjer->cijena,'kn')>=0){
           //     $this->smjer->cijena = trim(str_replace('kn','',$this->smjer->cijena));
           // }
            // 1.200,99
            $this->smjer->cijena = str_replace('.','',$this->smjer->cijena);
            //echo '1: ' . $this->smjer->cijena;
            //1200,99
            $this->smjer->cijena = (float)str_replace(',','.',$this->smjer->cijena);
            //echo '<br />2: ' . $this->smjer->cijena;
            //1200.99
            if($this->smjer->cijena<=0){
                $this->poruka='Ako unosite cijenu, mora biti decimalni broj veći od 0, unijeli ste: ' 
            . $this->smjer->cijena;
            $this->smjer->cijena='';
            return false;
            }
        }

        return true;
    }

   
}