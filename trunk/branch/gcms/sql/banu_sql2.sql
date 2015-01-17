CREATE TABLE G_SUBGROUPFRONTMENUS (
  NID INTEGER NOT NULL,
  CNAME VARCHAR(255) CHARACTER SET NONE NOT NULL COLLATE NONE,
  NURUT INTEGER DEFAULT 0 NOT NULL,
  BHIDE INTEGER DEFAULT 0 NOT NULL);


ALTER TABLE G_SUBGROUPFRONTMENUS ADD CONSTRAINT PK_SUBGROUPFRONTMENUS PRIMARY KEY (NID);

SET TERM ^ ;

CREATE TRIGGER G_SUBGROUPFRONTMENUS_BI FOR G_SUBGROUPFRONTMENUS
ACTIVE BEFORE INSERT
POSITION 0
AS
BEGIN 
	/* enter trigger code here */ 
	IF(NEW.NID IS NULL) then
	NEW.NID = GEN_ID(GEN_SUBGROUPFRONTMENUS,1);
END^

SET TERM ; ^

COMMIT WORK;

ALTER TABLE G_FRONTMENUS
ADD NID_HEADER INTEGER;

COMMIT WORK;

ALTER TABLE G_FRONTMENUS ALTER NID POSITION 1;
ALTER TABLE G_FRONTMENUS ALTER NID_GROUPFRONTMENUS POSITION 2;
ALTER TABLE G_FRONTMENUS ALTER NID_HEADER POSITION 3;
ALTER TABLE G_FRONTMENUS ALTER CMENU POSITION 4;
ALTER TABLE G_FRONTMENUS ALTER CFUNCTION POSITION 5;
ALTER TABLE G_FRONTMENUS ALTER CPARAM POSITION 6;
ALTER TABLE G_FRONTMENUS ALTER NURUT POSITION 7;
ALTER TABLE G_FRONTMENUS ALTER BHIDE POSITION 8;
ALTER TABLE G_FRONTMENUS ALTER BSECURE POSITION 9;
ALTER TABLE G_FRONTMENUS ALTER WIDTH POSITION 10;
ALTER TABLE G_FRONTMENUS ALTER HEIGHT POSITION 11;
ALTER TABLE G_FRONTMENUS ALTER IS_MAIN POSITION 12;

COMMIT WORK;

/* 11/03/2010 */
/* P.S karena bbrpa tipe diisi ukuran kecil, di form diset maxlength sebesar ukuran kolom */
ALTER TABLE BADAN_USAHA 
ADD rt varchar(10),
ADD rw varchar(10),
ADD pemilik_rt varchar(10),
ADD pemilik_rw varchar(10),
add kodepos varchar(10),
add pemilik_kodepos varchar(10);

ALTER TABLE PEMOHON 
ADD pekerjaan Varchar(100),
ADD kodepos Varchar(10);

ALTER TABLE PENDAFTARAN 
ADD objek_nama Varchar(200),
ADD objek_alamat Varchar(200);

ALTER TABLE PENDATAAN_PHIBURAN 
ADD id_rekening integer,
ADD dasar_pengenaan Decimal(18,2);

ALTER TABLE PENDATAAN_PPARKIR 
ADD id_rekening Integer,
ADD dasar_pengenaan Decimal(18,2);

ALTER TABLE PENDATAAN_PWALET 
add id_rekening Integer,
ADD jumlah integer,
ADD dasar_pengenaan Decimal(18,2),
ADD dasar_tarif Decimal(18,2);

ALTER TABLE PENDATAAN_RESTORAN 
add id_rekening Integer,
ADD dasar_pengenaan Decimal(18,2);

ALTER TABLE PENDATAAN_RETRUBUSI 
ADD id_rekening Integer,
ADD dasar_tarif Decimal(18,2),
ADD dasar_pengenaan Decimal(18,2);

ALTER TABLE PENDATAAN_RETRUBUSI DROP PERSEN_TARIF;
ALTER TABLE PENDATAAN_RESTORAN DROP KODE_REKENING;
ALTER TABLE PENDATAAN_PWALET DROP KODE_REKENING;
ALTER TABLE PENDATAAN_PPARKIR DROP KODE_REKENING;
ALTER TABLE PENDATAAN_PHIBURAN DROP KODE_REKENING;
ALTER TABLE PENDATAAN_LISTRIK DROP KODE_REKENING;

/*--------------------------------*/

/* 12/03/2010 */
alter table PENDATAAN_SPT
add nominal decimal(18,2) default 0.00 not null;

alter table pendataan_hotel
add dasar_pengenaan decimal(18,2) default 0.00 not null;
/*------------------------------------------------*/

/* 15/03/2010 */
ALTER TABLE PENDATAAN_SPT ADD 
nama_kegiatan Varchar(200);

CREATE TABLE PENDATAAN_AIR
(
  AIR_ID Integer NOT NULL,
  PENDATAAN_ID Integer NOT NULL,
  ID_REKENING Integer,
  LOKASI Varchar(200),
  JUMLAH Integer DEFAULT 0 NOT NULL,
  DASAR_TARIF Decimal(18,2) DEFAULT 0.00 NOT NULL,
  DASAR_PENGENAAN Decimal(18,2) DEFAULT 0.00 NOT NULL,
  PERSEN_TARIF Decimal(3,2) DEFAULT 0.00 NOT NULL,
  NOMINAL Decimal(18,2) DEFAULT 0.00 NOT NULL,
  CONSTRAINT PK_PENDATAAN_AIR PRIMARY KEY (AIR_ID)
);

create generator gen_pendataan_air;

SET TERM ^ ;
CREATE TRIGGER PENDATAAN_AIR_BI FOR PENDATAAN_AIR
ACTIVE BEFORE INSERT POSITION 0
AS 
BEGIN 
	if(new.AIR_ID is null) then
	new.AIR_ID = gen_id(GEN_PENDATAAN_AIR,1);	
END^
SET TERM ; ^ 

INSERT INTO G_FRONTMENUS (NID, NID_GROUPFRONTMENUS, NID_HEADER, CMENU, CFUNCTION, CPARAM, NURUT, BHIDE, BSECURE, WIDTH, HEIGHT, IS_MAIN) VALUES ('41', '4', '3', 'Pajak Air Bawah Tanah', 'm_pendataan_airBawahTanah_entri', ' ', '5', '0', '1', '0', '0', '0');
INSERT INTO G_MODULS (NID, CPATH) VALUES ('37', '../extensions/pendataan/pendataan_airBawahTanah/pendataan_airBawahTanah.php');

/*17/03/2010*/
INSERT INTO G_MODULS (NID, CPATH) VALUES ('42', '../extensions/simpatda/simpatda_core/simpatda_core.php');

CREATE TABLE PENDATAAN_GALIANC
(
  GALIANC_ID Integer NOT NULL,
  PENDATAAN_ID Integer NOT NULL,
  ID_REKENING Integer,
  LOKASI Varchar(200),
  JUMLAH Integer DEFAULT 0 NOT NULL,
  DASAR_TARIF Decimal(18,2) DEFAULT 0.00 NOT NULL,
  DASAR_PENGENAAN Decimal(18,2) DEFAULT 0.00 NOT NULL,
  PERSEN_TARIF Decimal(3,2) DEFAULT 0.00 NOT NULL,
  NOMINAL Decimal(18,2) DEFAULT 0.00 NOT NULL,
  CONSTRAINT PK_PENDATAAN_GALIANC PRIMARY KEY (GALIANC_ID)
);

create generator gen_pendataan_galianc;

SET TERM ^ ;
CREATE TRIGGER PENDATAAN_GALIANC_BI FOR PENDATAAN_GALIANC
ACTIVE BEFORE INSERT POSITION 0
AS 
BEGIN 
	if(new.GALIANC_ID is null) then
	new.GALIANC_ID = gen_id(GEN_PENDATAAN_GALIANC,1);
END^
SET TERM ; ^ 

/* 18/03/2010*/
CREATE VIEW V_DAFTAR_PENDAFTARAN_PAJAK (ID, NAMA, NPWP)
AS 
    select
        pendaftaran.pendaftaran_id as id,
        badan_usaha.nama as nama,
        pendaftaran.npwp
    from
      pendaftaran
      join badan_usaha on (badan_usaha.id = pendaftaran.id_pemohon)
      left join pendataan_spt on pendataan_spt.pendaftaran_id=pendaftaran.pendaftaran_id
    where pendaftaran.jenis_pendaftaran='PAJAK' and pendaftaran.objek_pdrd='BU' and pendataan_spt.pendaftaran_id is null
    union
    select 
        pendaftaran.pendaftaran_id as id,
        pemohon.nama as nama,
        pendaftaran.npwp
    from
      pendaftaran
      join pemohon on (pemohon.pemohon_id = pendaftaran.id_pemohon)
      left join pendataan_spt on pendataan_spt.pendaftaran_id=pendaftaran.pendaftaran_id
    where pendaftaran.jenis_pendaftaran='PAJAK' and pendaftaran.objek_pdrd='PRIBADI' and pendataan_spt.pendaftaran_id is null;

CREATE VIEW V_DAFTAR_PENDAFTARAN_RETRIBUSI (ID, NAMA, NPWP)
AS 
    select
        pendaftaran.pendaftaran_id as id,
        badan_usaha.nama as nama,
        pendaftaran.npwp
    from
      pendaftaran
      join badan_usaha on (badan_usaha.id = pendaftaran.id_pemohon)
      left join pendataan_spt on pendataan_spt.pendaftaran_id=pendaftaran.pendaftaran_id
    where pendaftaran.jenis_pendaftaran='RETRIBUSI' and pendaftaran.objek_pdrd='BU' and pendataan_spt.pendaftaran_id is null
    union
    select 
        pendaftaran.pendaftaran_id as id,
        pemohon.nama as nama,
        pendaftaran.npwp
    from
      pendaftaran
      join pemohon on (pemohon.pemohon_id = pendaftaran.id_pemohon)
      left join pendataan_spt on pendataan_spt.pendaftaran_id=pendaftaran.pendaftaran_id
    where pendaftaran.jenis_pendaftaran='RETRIBUSI' and pendaftaran.objek_pdrd='PRIBADI' and pendataan_spt.pendaftaran_id is null;	

CREATE VIEW V_PENDATAAN_LISTRIK (ID, PENDATAAN_NO, TGL_ENTRY, NPWP, NAMA, JENIS_PUNGUTAN, KODE_REKENING, KVA, DISKON, JAM, TARIF_DASAR, DASAR_PENGENAAN, PERSEN_TARIF, NOMINAL_PAJAK, MEMO)
AS 
    SELECT
      a.pendataan_id as id,
      a.pendataan_no,
      a.tgl_entry,
      c.npwp,
      d.nama,
      a.jenis_pungutan,
      e.kode_rekening,
      b.kva,
      b.diskon,
      b.jam,
      b.tarif_dasar,
      b.dasar_pengenaan,
      b.persen_tarif,
      b.nominal as nominal_pajak,
      a.memo
    FROM
      pendataan_spt a
      JOIN pendataan_listrik b on a.pendataan_id=b.pendataan_id
      JOIN pendaftaran c on c.pendaftaran_id=a.pendaftaran_id
      JOIN pemohon d on d.pemohon_id=c.id_pemohon
      JOIN rekening_kode e on e.id=b.id_rekening
    WHERE c.objek_pdrd='PRIBADI' and a.jenis_pendataan='LISTRIK'
    union
    SELECT
      a.pendataan_id as id,
      a.pendataan_no,
      a.tgl_entry,
      c.npwp,
      d.nama,
      a.jenis_pungutan,
      e.kode_rekening,
      b.kva,
      b.diskon,
      b.jam,
      b.tarif_dasar,
      b.dasar_pengenaan,
      b.persen_tarif,				  
      b.nominal as nominal_pajak,
      a.memo
    FROM
      pendataan_spt a
      JOIN pendataan_listrik b on a.pendataan_id=b.pendataan_id
      JOIN pendaftaran c on c.pendaftaran_id=a.pendaftaran_id
      JOIN badan_usaha d on d.id=c.id_pemohon
      JOIN rekening_kode e on e.id=b.id_rekening
    WHERE c.objek_pdrd='BU' and a.jenis_pendataan='LISTRIK';

CREATE VIEW V_PENETAPAN_PENDATAAN (PENDATAAN_ID, PENDATAAN_NO, TGL_ENTRY, NPWP, NAMA, JENIS_PENDATAAN, JENIS_PUNGUTAN, JENIS_PENDAFTARAN, SPT_NO, TGL_SPT, NOMINAL)
AS 
    select
    a.pendataan_id as id,
    a.pendataan_no,
    a.tgl_entry,
    b.NPWP,
    c.NAMA,
    a.jenis_pendataan,
    a.jenis_pungutan,
    b.jenis_pendaftaran,
    e.spt_no,
    e.tgl_kirim as spt_tgl,
    a.nominal
    from pendataan_spt a
    join pendaftaran b on b.pendaftaran_id=a.PENDAFTARAN_ID and b.objek_pdrd='PRIBADI'
    join pemohon c on c.pemohon_id=b.id_pemohon
    left join spt e on e.spt_id=a.spt_id
    union all
    select
    a.pendataan_id as id,
    a.pendataan_no,
    a.tgl_entry,
    b.NPWP,
    c.NAMA,
    a.jenis_pendataan,
    a.jenis_pungutan,
    b.jenis_pendaftaran,
    e.spt_no,
    e.tgl_kirim as spt_tgl,
    a.nominal
    from pendataan_spt a
    join pendaftaran b on b.pendaftaran_id=a.PENDAFTARAN_ID and b.objek_pdrd='BU'
    join badan_usaha c on c.id=b.id_pemohon
    left join spt e on e.spt_id=a.spt_id;

/*19/03/2010*/

CREATE TABLE REF_BUNGA
(
  BUNGA_ANGSUR Integer,
  BUNGA_KB Integer,
  BUNGA_KEBERATAN Integer,
  BUNGA_BANDING Integer,
  BUNGA_SKPDKB Integer,
  BUNGA_SKPDKBT Integer
);
INSERT INTO REF_BUNGA (BUNGA_ANGSUR, BUNGA_KB, BUNGA_KEBERATAN, BUNGA_BANDING, BUNGA_SKPDKB, BUNGA_SKPDKBT, "DB_KEY") VALUES ('2', '2', '50', '100', '2', '100', '000000c2:00000001');

CREATE TABLE REF_JATUH_TEMPO
(
  JATEM_KIRIM_SPT Integer NOT NULL,
  JATEM_BAYAR Integer NOT NULL,
  JATEM_TENGGANG_WAKTU Integer NOT NULL,
  JATEM_LAPORAN Integer NOT NULL,
  JATEM_STPD Integer
);
INSERT INTO REF_JATUH_TEMPO (JATEM_KIRIM_SPT, JATEM_BAYAR, JATEM_TENGGANG_WAKTU, JATEM_LAPORAN, JATEM_STPD, "DB_KEY") VALUES ('30', '30', '30', '30', '15', '000000c1:00000001');

CREATE TABLE REF_TANGGAL_MERAH
(
  ID Integer NOT NULL,
  BULAN Varchar(50) NOT NULL,
  JUMLAH Integer NOT NULL,
  TGL_MERAH Varchar(255),
  CONSTRAINT PK_REF_TANGGAL_MERAH PRIMARY KEY (ID)
);
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('1', 'Januari', '8', '24');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('2', 'Februari', '6', '26');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('3', 'Maret', '11', '16');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('4', 'April', '8', '2');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('5', 'Mei', '8', '13.28');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('6', 'Juni', '7', NULL);
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('7', 'Juli', '7', '10');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('8', 'Agustus', '8', '17');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('9', 'September', '14', '10.11');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('10', 'Oktober', '8', NULL);
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('11', 'November', '11', '17');
INSERT INTO REF_TANGGAL_MERAH (ID, BULAN, JUMLAH, TGL_MERAH) VALUES ('12', 'Desember', '12', '7.25');

CREATE TABLE REF_KOHIR
(
  KOHIR_ID Integer NOT NULL,
  KOHIR_THN Integer NOT NULL,
  KOHIR_NO Integer NOT NULL,
  CONSTRAINT PK_REF_KOHIR PRIMARY KEY (KOHIR_ID)
);

create generator GEN_REF_KOHIR;

SET TERM ^ ;

CREATE TRIGGER REF_KOHIR_BI FOR REF_KOHIR
ACTIVE BEFORE INSERT POSITION 0
AS 
BEGIN 
	/* enter trigger code here */ 
	IF(NEW.KOHIR_ID IS NULL) then
	NEW.KOHIR_ID = GEN_ID(GEN_REF_KOHIR,1);	
END^

SET TERM ; ^ 


create generator GEN_PENETAPAN_PR;
create generator GEN_PENETAPAN_PR_CONTENT;
create generator GEN_PENETAPAN_PR_DETAIL;

CREATE TABLE PENETAPAN_PR
(
  PENETAPAN_PR_ID Integer NOT NULL,
  NO_PENETAPAN Integer NOT NULL,
  TGL_PENETAPAN Date,
  TGL_SETOR Date,
  MEMO Varchar(250),
  NOMINAL_PENETAPAN Decimal(18,2) DEFAULT 0.00 NOT NULL,
  LOGS Timestamp,
  USER_ID Integer,
  CONSTRAINT PK_PENETAPAN_PR PRIMARY KEY (PENETAPAN_PR_ID)
);

CREATE TABLE PENETAPAN_PR_CONTENT
(
  PENETAPAN_PR_CONTENT_ID Integer NOT NULL,
  PENETAPAN_PR_ID Integer NOT NULL,
  PENDATAAN_ID Integer NOT NULL,
  NOMINAL Decimal(18,2) DEFAULT 0.00 NOT NULL,
  JENIS_PENDATAAN Varchar(200),
  JENIS_PUNGUTAN Varchar(200),
  CONSTRAINT PK_PENETAPAN_PR_CONTENT_ID PRIMARY KEY (PENETAPAN_PR_CONTENT_ID)
);

SET TERM ^ ;

CREATE TRIGGER PENETAPAN_PR_CONTENT_BI FOR PENETAPAN_PR_CONTENT
ACTIVE BEFORE INSERT POSITION 0
AS 
BEGIN 
	/* enter trigger code here */ 
	IF(NEW.PENETAPAN_PR_CONTENT_ID IS NULL) then
	NEW.PENETAPAN_PR_CONTENT_ID = GEN_ID(GEN_PENETAPAN_PR_CONTENT,1);
END^

SET TERM ; ^

/*CREATE TABLE PENETAPAN_PR_DETAIL
(
  PENETAPAN_PR_DETAIL_ID Integer NOT NULL,
  PENETAPAN_PR_ID Integer NOT NULL,
  PENDATAAN_ID Integer NOT NULL,
  PENDATAAN_CONTENT_ID Integer NOT NULL,
  ID_REKENING Integer NOT NULL,
  PERSEN_TARIF Decimal(3,2) DEFAULT 0.00,
  DASAR_TARIF Decimal(18,2) DEFAULT 0.00,
  DASAR_PENGENAAN Decimal(18,2) DEFAULT 0.00,
  KVA Decimal(18,2) DEFAULT 0.00,
  DISKON Decimal(12,2) DEFAULT 0.00,
  JAM Decimal(12,2) DEFAULT 0.00,
  NOMINAL Decimal(18,2) DEFAULT 0.00 NOT NULL,
  TGL_JATUH_TEMPO Date,
  CONSTRAINT PK_PENETAPAN_PR_DETAIL_ID PRIMARY KEY (PENETAPAN_PR_DETAIL_ID)
);*/