<?php

class Predavac
{


    public static function readOne($kljuc)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select a.sifra, b.ime, b.prezime, b.email, b.oib, a.iban 
            from predavac a inner join osoba b on
            a.osoba = b.sifra 
            where a.sifra=:parametar
        
        '); 
        $izraz->execute(['parametar'=>$kljuc]);
        return $izraz->fetch();
    }

    // CRUD

    //R - Read
    public static function read()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
            select a.sifra, b.ime, b.prezime, b.email, b.oib, a.iban, 
            count(c.sifra) as grupa
            from predavac a 
            inner join osoba b on a.osoba = b.sifra 
            left join grupa c on a.sifra =c.predavac 
            group by 
            a.sifra, b.ime, b.prezime, b.email, b.oib, a.iban
            order by 3, 2
        
        '); 
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
        
        insert into predavac (osoba,iban) values 
        (:osoba,:iban)
        
        '); 
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'iban'=>$parametri['iban']
        ]);

        $veza->commit();        
    }
    

    //U - Update
    public static function update($parametri)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
        select osoba from predavac where sifra=:sifra
        
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
        
            update predavac set
            iban=:iban
            where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$parametri['sifra'],
            'iban'=>$parametri['iban']
        ]);


        $veza->commit();
    }

    //D - Delete
    public static function delete($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz = $veza->prepare('
        
        select osoba from predavac where sifra=:sifra
        
        '); 
        $izraz->execute([
            'sifra'=>$sifra
        ]);

        $sifraOsoba = $izraz->fetchColumn();

        $izraz = $veza->prepare('
        
        delete from predavac where sifra=:sifra
        
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