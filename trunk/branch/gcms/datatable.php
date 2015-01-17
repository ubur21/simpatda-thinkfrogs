<?
/*
INSERT INTO G_MODULS (NID, CPATH) VALUES ('21', '../extensions/master/master.php');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('10', '3', 'Master Pemda ', 'm_master_pemda', '', '6', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('11', '3', 'Master Satuan Kerja', 'm_master_SatuanKerja', '', '7', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('12', '3', 'Master Kecamatan', 'm_master_kecamatan_list', '', '8', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('13', '3', 'Master Kelurahan', 'm_master_kelurahan_list', '', '8', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('6', '3', 'Master Objek Pajak', 'm_master_ObjekPajak', '', '4', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('8', '3', 'Master Keterangan SPT', 'm_master_spt', '', '10', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('12', '3', 'Data Printer', 'm_master_DataPrinter', '', '11', '0', '1', '0', '0', '0');



//Tabel Kecamatan
CREATE TABLE kecamatan
(camat_id INTEGER NOT NULL,
camat_kode CHAR(25),
camat_nama VARCHAR(25),

PRIMARY KEY (camat_id));

//Tabel Kelurahan
CREATE TABLE kelurahan
(lurah_id varchar(25) NOT NULL,
lurah_kode CHAR(3),
lurah_kecamatan integer,
lurah_nama varchar(30),
PRIMARY KEY (lurah_id));

CREATE TABLE keterangan_spt
(
  ketspt_id integer NOT NULL,
  ketspt_kode varchar(25) NOT NULL,
  ketspt_ket varchar(150) NOT NULL,
  ketspt_singkat varchar(25),
  ketspt_self char(1),
  ketspt_official char(1),
  PRIMARY KEY (ketspt_id));

CREATE TABLE printer
(
  print_id integer NOT NULL,
  print_nama varchar(20) NOT NULL,
  print_tty varchar(15),
  print_alamat varchar(20),
  print_keterangan varchar(50),
  print_default char(1),
  PRIMARY KEY (print_id)
);

*/
?>