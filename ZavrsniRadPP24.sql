drop database if exists ZavrsniRadPP24;
create database ZavrsniRadPP24 character set utf8;

use ZavrsniRadPP24;

create table kupac(
    sifra       int not null primary key auto_increment,
    ime         varchar(50) not null,
    prezime     varchar(50) not null,
    oib         char(11),
    email       varchar(50),
    proizvod    varchar(50)
    
);

create table proizvod(
    sifra       int not null primary key auto_increment,
    naziv       varchar (50),
    cijena      decimal(18,2),
    kolicina    int not null,
    kupac       int not null
);

create table kosarica(
    sifra       int not null primary key auto_increment,
    kupac       int not null,
    klijent     int not null,
    proizvod    int not null 
);

create table adresa(
 sifra          int not null primary key auto_increment,
 postanskibroj  varchar(50)not null,
 ulica          varchar(50)not null,
 kucnibroj      int not null,
 kupac          int not null,
 proizvod       int not null
);

alter table kosarica add foreign key (kupac)     references kupac(sifra);
alter table adresa   add foreign key (kupac)     references kupac(sifra);
alter table adresa   add foreign key (proizvod)  references proizvod(sifra);
alter table proizvod add foreign key (kupac)     references kupac(sifra);
alter table kosarica add foreign key (proizvod)  references proizvod(sifra);
drop database if exists ZavrsniRadPP24;
create database ZavrsniRadPP24 character set utf8;

use ZavrsniRadPP24; 

create table kupac(
    sifra       int not null primary key auto_increment,
    ime         varchar(50) not null,
    prezime     varchar(50) not null,
    oib         char(11),
    email       varchar(50),
    proizvod    varchar(50)
    
);

create table proizvod(
    sifra       int not null primary key auto_increment,
    naziv       varchar (50),
    cijena      decimal(18,2),
    kolicina    int not null,
    kupac       int not null
);

create table kosarica(
    sifra       int not null primary key auto_increment,
    kupac       int not null,
    klijent     int not null,
    proizvod    int not null 
);

create table adresa(
 sifra          int not null primary key auto_increment,
 postanskibroj  varchar(50)not null,
 ulica          varchar(50)not null,
 kucnibroj      int not null,
 kupac          int not null,
 proizvod       int not null
);

alter table kosarica add foreign key (kupac)     references kupac(sifra);
alter table adresa   add forign key (kupac)     references kupac(sifra);
alter table adresa   add foreign key (proizvod)  references proizvod(sifra);
alter table proizvod add foreign key (kupac)     references kupac(sifra);
alter table kosarica add foreign key (proizvod)  references proizvod(sifra);
 