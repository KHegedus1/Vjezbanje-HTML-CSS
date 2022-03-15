<?php

class Polaznik
{

    public static function ukupnoPolaznika($uvjet)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select count(a.sifra)
            from polaznik a 
            inner join osoba b on a.osoba = b.sifra 
            where concat(b.ime, \' \', b.prezime, \' \', ifnull(b.oib,\'\')) like :uvjet
      
        
        '); 
        $uvjet = '%' . $uvjet . '%';
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->execute();
        return $izraz->fetchColumn();
    }


    public static function readOne($kljuc)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select a.sifra, b.ime, b.prezime, b.email, b.oib, a.brojugovora 
            from polaznik a inner join osoba b on
            a.osoba = b.sifra 
            where a.sifra=:parametar
        
        '); 
        $izraz->execute(['parametar'=>$kljuc]);
        return $izraz->fetch();
    }

    // CRUD

    //R - Read
    public static function read($stranica, $uvjet)
    {

        $rps = App::config('rps');
        $od = $stranica * $rps - $rps;

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select a.sifra, b.ime, b.prezime, b.email, b.oib, a.brojugovora, 
            count(c.grupa) as grupa
            from polaznik a 
            inner join osoba b on a.osoba = b.sifra 
            left join clan c on a.sifra =c.polaznik 
            where concat(b.ime, \' \', b.prezime, \' \', ifnull(b.oib,\'\')) like :uvjet
            group by 
            a.sifra, b.ime, b.prezime, b.email, b.oib, a.brojugovora
            order by 3, 2
            limit :od, :rps
        
        '); 
        $uvjet = '%' . $uvjet . '%';
        $izraz->bindValue('od',$od,PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps,PDO::PARAM_INT);
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    //C - Create
    // $parametri su asocijativni niz - tako mi odgovara
    public static function create($parametri)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
        insert into osoba (ime,prezime,oib,email) values 
        (:ime,:prezime,:oib,:email)
        
        '); 
        $izraz->execute([
            'ime'=>$parametri['ime'],
            'prezime'=>$parametri['prezime'],
            'oib'=>$parametri['oib'],
            'email'=>$parametri['email']
        ]);


        $zadnjaSifra = $veza->lastInsertId();

        $izraz = $veza->prepare('
        
        insert into polaznik (osoba,brojugovora) values 
        (:osoba,:brojugovora)
        
        '); 
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'brojugovora'=>$parametri['brojugovora']
        ]);

        $veza->commit();        
    }
    

    //U - Update
    public static function update($parametri)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
        select osoba from polaznik where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$parametri['sifra']
        ]);

        $sifraOsoba = $izraz->fetchColumn();


        $izraz = $veza->prepare('
        
            update osoba set
            ime=:ime,
            prezime=:prezime,
            oib=:oib,
            email=:email
            where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$sifraOsoba,
            'ime'=>$parametri['ime'],
            'prezime'=>$parametri['prezime'],
            'oib'=>$parametri['oib'],
            'email'=>$parametri['email']
        ]);

        $izraz = $veza->prepare('
        
            update polaznik set
            brojugovora=:brojugovora
            where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$parametri['sifra'],
            'brojugovora'=>$parametri['brojugovora']
        ]);


        $veza->commit();
    }

    //D - Delete
    public static function delete($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
        select osoba from polaznik where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$sifra
        ]);

        $sifraOsoba = $izraz->fetchColumn();

        $izraz = $veza->prepare('
        
        delete from polaznik where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$sifra
        ]);

        $izraz = $veza->prepare('
        
        delete from osoba where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$sifraOsoba
        ]);


        $veza->commit();
    }
}