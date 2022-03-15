<?php

if($_SERVER['SERVER_ADDR']==='127.0.0.1'){
    $url='http://edunovaapp.xyz/';
    $dev=true;
    $baza=[
        'server'=>'localhost',
        'baza'=>'edunovapp24',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
    ];
}else{
    $url='https://predavac01.edunova.hr/';
    $dev=false;
    $baza=[
        'server'=>'localhost',
        'baza'=>'cesar_edunovapp24',
        'korisnik'=>'cesar_korisnik',
        'lozinka'=>'xs7v,uMlH8hl'
    ];
}

return [
    'dev'=>$dev,
    'url'=>$url,
    'rps'=>10, // rezultata po stranici
    'naslovApp'=>'Edunova APP',
    'baza'=>$baza
];
