INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS,NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('34', '5','3', 'Penetapan Pajak Hotel', 'm_penetapan_PajakHotel', '', '1', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS,NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('35', '5','3', 'Penetapan Pajak Restoran', 'm_penetapan_PajakRestoran', '', '2', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS,NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('36', '5','3', 'Penetapan Pajak Hiburan', 'm_penetapan_PajakHiburan', '', '3', '0', '1', '0', '0', '0');

//Insert G_MODULS
INSERT INTO G_MODULS (NID, CPATH) VALUES ('31', '../extensions/penetapan/penetapan.php');


CREATE TABLE wp_wr (
  wp_wr_id INTEGER NOT NULL,
  wp_wr_no_form VARCHAR(10) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_jenis VARCHAR(1) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_gol INTEGER NOT NULL,
  wp_wr_no_urut VARCHAR(7) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_kd_camat VARCHAR(2) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_kd_lurah VARCHAR(3) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_nama VARCHAR(50) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_alamat VARCHAR(100) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_lurah VARCHAR(50) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_camat VARCHAR(50) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_kabupaten VARCHAR(50) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_telp VARCHAR(20) CHARACTER SET NONE NOT NULL COLLATE NONE,
  wp_wr_nama_milik VARCHAR(50) ,
  wp_wr_alamat_milik VARCHAR(100),
  wp_wr_lurah_milik VARCHAR(50),
  wp_wr_camat_milik VARCHAR(50),
  wp_wr_kabupaten_milik VARCHAR(50) ,
  wp_wr_telp_milik VARCHAR(20),
  wp_wr_kd_usaha INTEGER NOT NULL,
  wp_wr_tgl_kartu DATE,
  wp_wr_tgl_terima_form DATE,
  wp_wr_tgl_bts_kirim DATE,
  wp_wr_tgl_form_kembali DATE,
  wp_wr_jns_pemungutan INTEGER,
  wp_wr_pejabat integer ,
  wp_wr_status_aktif integer,
  wp_wr_tgl_tutup date
  );


ALTER TABLE wp_wr ADD CONSTRAINT PK_WP_WR PRIMARY KEY (WP_WR_ID);

CREATE GENERATOR GEN_WP_WR;

SET GENERATOR GEN_WP_WR TO 0;


SET TERM ^ ;

CREATE TRIGGER WP_WR_BI FOR WP_WR
ACTIVE BEFORE INSERT
POSITION 0
AS
BEGIN 
	/* enter trigger code here */ 
  IF (NEW.WP_WR_ID IS NULL) THEN
    NEW.WP_WR_ID = GEN_ID(GEN_WP_WR, 1);	
END^
SET TERM ; ^

COMMIT WORK;

ALTER TABLE PENDATAAN_HOTEL
ADD	pemungutan varchar(20),
ADD	tanggal_penjualan_awal date,
ADD	tanggal_penjualan_akhir date,
ADD	id_rekening integer,
ADD	tanggal_entri date,
ADD	tanggal_proses date;
	
ALTER TABLE PENDATAAN_RESTORAN 
ADD	pemungutan varchar(20),
ADD	tanggal_penjualan_awal date,
ADD	tanggal_penjualan_akhir date,
ADD	id_rekening integer,
ADD	tanggal_entri date,
ADD	tanggal_proses date;

//Pajak Restoran
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('43', '4', '3', 'Pendataan Pajak Restoran', 'm_pendataan_restoran_PajakRestoran', '', '6', '0', '1', '0', '0', '0');
INSERT INTO G_MODULS (NID, CPATH) VALUES ('39', '../extensions/pendataan/PENDATAAN_RESTORAN/PENDATAAN_RESTORAN.php');
//Pajak Hiburan
INSERT INTO G_MODULS (NID, CPATH) VALUES ('40', '../extensions/pendataan/pendataan_hiburan/pendataan_hiburan.php');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('44', '4', '3', 'Pendataan Pajak Hiburan', 'm_pendataan_hiburan_PajakHiburan', '', '6', '0', '1', '0', '0', '0');

//30 Maret 2010
INSERT INTO G_FRONTMENUS ( NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('5', NULL, 'Form Teguran WP/WR', 'm_penetapan_teguran', ' ', '1', '0', '1', '0', '0', '0');
INSERT INTO G_MODULS (CPATH) VALUES ('../extensions/penetapan/penetapan_teguran/penetapan_teguran.php');

//09 April 2010
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('51', '5', NULL, 'Form Teguran WP/WR', 'm_penetapan_teguran', ' ', '1', '0', '1', '0', '0', '0');
INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('55', '5', NULL, 'Form Cetak Tunggakan', 'm_laporan_Filter', '', '1', '0', '1', '0', '0', '0');
