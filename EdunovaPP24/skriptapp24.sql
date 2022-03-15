# Ovo je komentar i ne utječe na izvođenje
drop database if exists edunovapp24;
create database edunovapp24 character set utf8mb4;
# otvoriti cmd i zaljepiti sljedeći red bez # - pripaziti na putanju sql datoteke
# c:\xampp\mysql\bin\mysql -uedunova -pedunova --default_character_set=utf8 < C:\Users\CP\Documents\PP24\EdunovaPP24\skriptapp24.sql

use edunovapp24;

# za server
alter database cesar_edunovapp24 character set utf8mb4;

create table operater(
    sifra           int not null primary key auto_increment,
    email           varchar(50) not null,
    lozinka         char(60) not null, # čitati https://medium.com/analytics-vidhya/password-hashing-pbkdf2-scrypt-bcrypt-and-argon2-e25aaf41598e
    ime             varchar(50) not null,
    prezime         varchar(50) not null,
    uloga           varchar(10) not null
);

create table smjer(
    sifra           int not null primary key auto_increment,
    naziv           varchar(50) not null,
    trajanje        int not null,
    cijena          decimal(18,2),
    certificiran    boolean,
    vrijemeunosa    datetime not null default now()
);

create table osoba(
    sifra   int not null primary key auto_increment,
    ime     varchar(50) not null,
    prezime varchar(50) not null,
    oib     char(11),
    email   varchar(50)
);

create table grupa(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    smjer int not null,
    predavac int,
    datumpocetka datetime
);

create table predavac(
    sifra int not null primary key auto_increment,
    osoba int not null,
    iban varchar(50)
);

create table polaznik(
    sifra int not null primary key auto_increment,
    osoba int not null,
    brojugovora varchar(50) not null
);

create table clan(
    grupa int not null,
    polaznik int not null
);


alter table grupa add foreign key (smjer) references smjer(sifra);
alter table grupa add foreign key (predavac) references predavac(sifra);

alter table predavac add foreign key (osoba) references osoba(sifra);

alter table polaznik add foreign key (osoba) references osoba(sifra);

alter table clan add foreign key (grupa) references grupa(sifra);
alter table clan add foreign key (polaznik) references polaznik(sifra);


# unos operatera
insert into operater(email,lozinka,ime,prezime, uloga) values
# lozinka a
('admin@edunova.hr','$2a$12$gcFbIND0389tUVhTMGkZYem.9rsMa733t9J9e9bZcVvZiG3PEvSla','Administrator','Edunova','admin'),
# lozinka o
('oper@edunova.hr','$2a$12$S6vnHiwtRDdoUW4zgxApvezBlodWj/tmTADdmKxrX28Y2FXHcoHOm','Operater','Edunova','oper');



# Loš način
# 1
insert into smjer values (null,'PHP programiranje',130,5999.99,false,now());

# Bolji način
# 2
insert into smjer (naziv,trajanje) values ('Java programiranje',130);

# Primjer dobre prakse način
# 3
insert into smjer (sifra,naziv,trajanje,cijena,certificiran)
values (null,'Računovodstvo',180,null,true);

# 1
insert into osoba (sifra,ime,prezime,oib,email)
values (null,'Tomislav','Jakopec','97415127262','tjakopec@gmail.com');

# unesite sebe
# 2 - 21
insert into osoba (sifra,ime,prezime,oib,email) values
(null,'Tomislav','Sabolić',null,'sabolic55@gmail.com'),
(null,'Kristijan','Hegeduš',null,'kico.kiki@gmail.com'),
(null,'Matej','Papac',null,'papac.tmrip@gmail.com'),
(null,'Mladen','Božić',null,'mbozic987@gmail.com'),
(null,'Josip','Ervačić',null,'jokijoki7@gmail.com'),
(null,'Grgo','Kvesić',null,'grgo0203@gmail.com'),
(null,'Dušan','Strajinić',null,'dusanstrajinic9@gmail.com'),
(null,'Filip','Đunda',null,'filip.djunda1@gmail.com'),
(null,'Marija','Mikić',null,'marija.snowml@gmail.com'),
(null,'Hrvoje','Furi',null,'hrvoje.furi@gmail.com'),
(null,'Roman','Grubić',null,'grubic.roman@gmail.com'),
(null,'Magdalena','Janić',null,'lenchi.janic@gmail.com'),
(null,'Luka','Grigić',null,'luka.grigic94@gmail.com'),
(null,'Dario','Šalić',null,'dasalic1@gmail.com'),
(null,'Jakob','Brkić',null,'jale.pnv@gmail.com'),
(null,'Stefan','Lazarević',null,'slazar81095@gmail.com'),
(null,'Kristina','Galić',null,'drozdek.kristina@gmail.com'),
(null,'Mateo','Opančar',null,'opancarmateo@gmail.com'),
(null,'Ante','Dragičević',null,'dragicevicante7@gmail.com'),
(null,'Luka','Runje',null,'luka.runje@hotmail.com'),
(null,'Pero','Perić',null,'Pero@hotmail.com');


# 1
insert into predavac (sifra,osoba,iban) values (null,1,null);
insert into predavac (sifra,osoba,iban) values (null,22,null);


# 1 - 20
insert into polaznik (sifra,osoba,brojugovora) values
(null,2,''),
(null,3,''),
(null,4,''),
(null,5,''),
(null,6,''),
(null,7,''),
(null,8,''),
(null,9,''),
(null,10,''),
(null,11,''),
(null,12,''),
(null,13,''),
(null,14,''),
(null,15,''),
(null,16,''),
(null,17,''),
(null,18,''),
(null,19,''),
(null,20,''),
(null,21,'');


# 1
insert into grupa (sifra,naziv,smjer,predavac,datumpocetka)
values (null,'PP24',1,1,'2021-10-27');

# 2
insert into grupa (sifra,naziv,smjer,predavac,datumpocetka)
values (null,'JP25',2,null,'2021-10-27');

# 3
insert into grupa (sifra,smjer,naziv,predavac)
values (null,3,'R12',null);


insert into clan(grupa,polaznik) values
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20);








