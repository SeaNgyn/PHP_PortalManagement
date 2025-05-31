-- TẠO SCHEMA VÀ SỬ DỤNG
--CREATE DATABASE IF NOT EXISTS db_php_ver4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--USE db_php_ver4;

-- BẢNG MÔN HỌC
CREATE TABLE tn_mon_hoc (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ten_mon VARCHAR(255) DEFAULT NULL,
    ma_mon VARCHAR(255) UNIQUE DEFAULT NULL, -- Ví dụ: HUS1011
    so_tin_chi int DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
	updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	created_by int DEFAULT NULL,
	updated_by int DEFAULT NULL
);
DELIMITER $$

CREATE TRIGGER mon_hoc_time_insert
BEFORE INSERT ON tn_mon_hoc
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

-- BẢNG GIẢNG VIÊN
CREATE TABLE tn_giang_vien (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `Name` varchar(32) DEFAULT NULL,
	`Ten` varchar(16) NOT NULL,
	`sex` tinyint NOT NULL DEFAULT 1,
	`HocVi` tinyint DEFAULT NULL,
	`HocHam` tinyint DEFAULT NULL,
	`Email` varchar(32) DEFAULT NULL,
	`Status` tinyint NOT NULL DEFAULT 1,
	`Office` varchar(128) DEFAULT NULL,
	`BoMon` tinyint NOT NULL,
	`BoMon2` tinyint DEFAULT NULL,
	`DOB` date NOT NULL,
	`Ngach` tinyint NOT NULL,
	`ChucVu` tinyint DEFAULT NULL,
	`KiemNhiem` tinyint DEFAULT NULL,
	`GhiChu` varchar(128) DEFAULT NULL,
	`GoogleScholar` varchar(1024) DEFAULT NULL,
	UNIQUE KEY `Email` (`Email`),
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
	updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	created_by int DEFAULT NULL,
	updated_by int DEFAULT NULL
);
DELIMITER $$
CREATE TRIGGER giang_vien_time_insert
BEFORE INSERT ON tn_giang_vien
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

-- BẢNG MÃ LỚP HỌC PHẦN
CREATE TABLE tn_ma_lop_hp (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    STT int DEFAULT NULL,
    ten_ma_lop_hp VARCHAR(255) DEFAULT NULL,         -- Ví dụ: HUS1011 1, HUS1011 2
    phan_bo_tin_chi varchar(255) DEFAULT NULL,
    loai_lop varchar(255) DEFAULT NULL,
	nganh varchar(255) DEFAULT NULL,
	khoa varchar(255) DEFAULT NULL,
	chuong_trinh_dao_tao varchar(100) DEFAULT NULL,
	so_luong_sv int DEFAULT NULL,
	thu varchar(255) DEFAULT NULL,
	tiet varchar(255) DEFAULT NULL,
	ngon_ngu_giang_day varchar(100) DEFAULT NULL,
	giang_duong varchar(100) DEFAULT NULL,
	created_at datetime DEFAULT CURRENT_TIMESTAMP,
	updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	created_by int DEFAULT NULL,
	updated_by int DEFAULT NULL,
    id_mon_hoc int NOT NULL,
    FOREIGN KEY (id_mon_hoc) REFERENCES tn_mon_hoc(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
DELIMITER $$
CREATE TRIGGER ma_lop_hp_time_insert
BEFORE INSERT ON tn_ma_lop_hp
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

-- BẢNG GIẢNG VIÊN VÀ MÃ LỚP HỌC PHẦN
CREATE TABLE tn_giangvien_malophp (
	giang_vien_id INT,
    id_ma_lop_hp int,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT DEFAULT NULL,
    updated_by INT DEFAULT NULL,
    PRIMARY KEY (giang_vien_id, id_ma_lop_hp),
    FOREIGN KEY (giang_vien_id) REFERENCES tn_giang_vien(id),
    FOREIGN KEY (id_ma_lop_hp) REFERENCES tn_ma_lop_hp(id)

);
DELIMITER $$

CREATE TRIGGER giangvien_malophp_insert
BEFORE INSERT ON tn_giangvien_malophp
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

-- BẢNG N-N: GIẢNG VIÊN - MÔN HỌC
CREATE TABLE tn_giangvien_monhoc (
    giang_vien_id INT,
    id_mon_hoc int,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT DEFAULT NULL,
    updated_by INT DEFAULT NULL,
    PRIMARY KEY (giang_vien_id, id_mon_hoc),
    FOREIGN KEY (giang_vien_id) REFERENCES tn_giang_vien(id),
    FOREIGN KEY (id_mon_hoc) REFERENCES tn_mon_hoc(id)
	-- FOREIGN KEY (ma_mon_id) REFERENCES mon_hoc(id)
);
DELIMITER $$
CREATE TRIGGER giangvien_monhoc_time_insert
BEFORE INSERT ON tn_giangvien_monhoc
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

CREATE TABLE tn_hoc_ky (
  id int NOT NULL AUTO_INCREMENT,
  ten varchar(255) DEFAULT NULL,
  nam_hoc varchar(100) DEFAULT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_by int DEFAULT NULL,
  updated_by int DEFAULT NULL,
  PRIMARY KEY (`id`)
);
DELIMITER $$
CREATE TRIGGER hoc_ky_time_insert
BEFORE INSERT ON tn_hoc_ky
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$
DELIMITER ;
INSERT INTO tn_hoc_ky (ten, nam_hoc) VALUES 
('Học kỳ 1', '2025'),
('Học kỳ 2', '2025');


CREATE TABLE tn_hocky_malophp (
  hocky_id int NOT NULL,
  malophp_id int NOT NULL,
  created_at datetime DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_by int DEFAULT NULL,
  updated_by int DEFAULT NULL,
  PRIMARY KEY (`hocky_id`,`malophp_id`),
  CONSTRAINT `hocky_malophp_ibfk_1` FOREIGN KEY (`hocky_id`) REFERENCES `tn_hoc_ky` (`id`),
  CONSTRAINT `hocky_malophp_ibfk_2` FOREIGN KEY (`malophp_id`) REFERENCES `tn_ma_lop_hp` (`id`)
);
DELIMITER $$
CREATE TRIGGER hocky_malophp_time_insert
BEFORE INSERT ON tn_hocky_malophp
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;

INSERT INTO `tn_giang_vien`(
  id, Name, Ten, sex, HocVi, HocHam, Email, Status, Office, BoMon, BoMon2,
  DOB, Ngach, ChucVu, KiemNhiem, GhiChu, GoogleScholar
) VALUES
(1,'Trịnh Thị Loan','Loan',0,4,2,'loan.trinhthi@gmail.com',1,NULL,3,0,'1980-02-15',0,0,0,'','https://scholar.google.com/citations?hl=vi&user=oFa5kFYAAAAJ&scilu=&scisig=AMD79ooAAAAAX65AexWE3WXOnobY43IldT2rndMX3nry'),
(2,'Nguyễn Từ Niệm','Niệm',0,4,NULL,'nguyentuniem@gmail.com',1,NULL,3,0,'1979-12-18',0,0,0,'','https://scholar.google.com.vn/citations?user=Ky-vT8YAAAAJ&hl=vi&authuser=1'),
(3,'Vương Văn Hiệp','Hiệp',1,3,NULL,'vuonghiepcms@gmail.com',1,NULL,3,0,'1978-07-25',0,0,0,'','https://scholar.google.com.vn/citations?hl=vi&user=uCsfdXcAAAAJ'),
(4,'Nguyễn Xuân Hãn','Hãn',1,5,3,'lienbat76@yahoo.com',4,NULL,6,0,'1948-07-02',0,0,0,'',NULL),
(5,'Nguyễn Thế Toàn','Toàn',1,4,3,'toannt@vnu.edu.vn',1,'P308A Bldg. T1',14,6,'1973-08-20',0,2,7,'','https://scholar.google.com.vn/citations?hl=en&user=XXx7ze0AAAAJ&view_op=list_works&authuser=1&sortby=pubdate'),
(6,'Nguyễn Quang Hòa','Hòa',1,4,NULL,'hoanq@hus.edu.vn',1,NULL,2,0,'1979-04-20',0,5,0,'',NULL),
(7,'Nguyễn Đức Vinh','Vinh',1,4,NULL,'ndvinh19@yahoo.com.vn',4,NULL,4,0,'1955-06-19',0,0,0,'',NULL),
(8,'Đỗ Đức Thanh','Thanh',1,4,2,'doducthanh1956@gmail.com',4,NULL,4,0,'1956-08-19',0,0,0,'','https://scholar.google.com/citations?hl=en&user=S6qhI5wAAAAJ'),
(9,'Giang Kiên Trung','Trung',1,3,NULL,'trunggk@hus.edu.vn',1,NULL,4,0,'1980-11-27',0,0,0,'','https://scholar.google.com/citations?user=fX8Z83MAAAAJ'),
(10,'Đỗ Trung Kiên','Kiên',1,4,NULL,'dtkien@hus.edu.vn',1,NULL,10,0,'1976-09-23',0,0,0,'','https://scholar.google.com/citations?view_op=new_profile&hl=vi'),
(11,'Trần Vĩnh Thắng','Thắng',1,4,NULL,'tranvinhthang@hus.edu.vn',1,NULL,10,0,'1976-08-24',0,0,0,'','https://scholar.google.com/citations?user=TLpG6TEAAAAJ&hl=en'),
(12,'Nguyễn Thế Nghĩa','Nghĩa',1,4,NULL,'05.nghia@gmail.com',1,NULL,5,0,'1965-04-01',0,4,0,'',NULL),
(13,'Hà Thụy Long','Long',1,4,NULL,'hathuylong@hus.edu.vn',1,NULL,5,0,'1977-02-12',0,0,0,'',NULL),
(14,'Lê Văn Vũ','Vũ',1,4,2,'levanvu@hus.edu.vn',4,NULL,11,0,'1956-08-06',0,7,0,'',NULL),
(15,'Bạch Thành Công','Công',1,4,3,'congbachthanh@gmail.com',4,NULL,12,14,'1952-10-30',0,6,0,'','https://scholar.google.com/citations?user=emD64gQAAAAJ&hl=en&oi=ao'),
(16,'Bạch Hương Giang','Giang',0,4,2,'gianghuongbach@gmail.com',1,NULL,11,14,'1982-10-12',0,15,0,'','https://scholar.google.com/citations?user=TQLSbv4AAAAJ&hl=en'),
(17,'Phạm Thành Luân','Luân',1,4,NULL,'luanpt@hus.edu.vn',1,NULL,4,0,'1990-04-03',0,5,0,'','https://scholar.google.com/citations?user=ZaZHAEgAAAAJ&hl=vi#'),
(18,'Đỗ Quang Lộc','Lộc',1,4,NULL,'quanglocphys@gmail.com',3,NULL,10,0,'1992-11-07',0,5,0,'','https://scholar.google.com/citations?user=4At8hAsAAAAJ&hl=en'),
(19,'Bùi Thị Hoa','Hoa',0,3,NULL,'buithihoa.k55@hus.edu.vn',1,NULL,5,0,'1992-10-25',0,0,0,'',NULL),
(20,'Vũ Hoàng Hướng','Hướng',1,3,NULL,'huongvh@hus.edu.vn',1,NULL,3,0,'1990-08-06',0,0,0,'','https://scholar.google.com.vn/citations?view_op=list_works&amp;hl=vi&amp;user=vwyaMm8AAAAJ&amp;gmla=AJsN-F7sd0B3-1c9regnYdTRLHsCbtYs-Lx2nAw-BPwBTRGsgpAq-NrsR7P6wwVXLISDUU_9Bwbt_gPL8a46IirQ5t-Fg1QSgwF8y68EL_vbSAoJfnHS1B-w1TF23TNKvpkUO890x3CS'),
(21,'Bùi Thị Hồng','Hồng',0,3,NULL,'buithihong.k56@hus.edu.vn',1,NULL,5,0,'1993-04-15',0,0,0,'',NULL),
(22,'Ngạc An Bang','Bang',1,4,2,'ngacanbang@hus.edu.vn',3,NULL,3,0,'1971-02-14',0,14,0,'',NULL),
(23,'Nguyễn Đình Dũng','Dũng',1,4,2,'nddung1952@gmail.com',4,NULL,6,0,'1952-09-18',0,0,0,'',NULL),
(24,'Nguyễn Viết Đạt','Đạt',1,3,NULL,'nguyenvietdat@gmail.com',1,NULL,4,0,'1987-10-16',0,0,0,'',NULL),
(25,'Phạm Văn Thành','Thành',1,4,NULL,'phamvanthanh@hus.edu.vn',1,NULL,10,0,'1984-07-16',0,3,4,'','https://scholar.google.com/citations?user=Ez04_EMAAAAJ&hl=vi&oi=sra'),
(26,'Nguyễn Cảnh Việt','Việt',1,3,NULL,'vietncp@gmail.com',1,NULL,9,0,'1980-11-04',0,0,0,'',NULL),
(27,'Nguyễn Hoàng Nam','Nam',1,4,2,'namnh@hus.edu.vn',1,NULL,16,0,'1979-08-05',0,7,0,'',NULL),
(28,'Nguyễn Văn Quân','Quân',1,2,NULL,'qnv1985@gmail.com',4,NULL,5,0,'1985-03-08',0,0,0,'',NULL),
(29,'Trịnh Thu Thủy','Thủy',0,3,NULL,'trinhthuy.vl07@gmail.com',1,NULL,15,0,'1989-12-13',0,0,0,'',NULL),
(30,'Vũ Thanh Mai','Mai',0,4,NULL,'vuthanhmai031@gmail.com',1,NULL,5,0,'1983-11-04',0,0,0,'','https://scholar.google.com/citations?user=qQb-lOUAAAAJ&hl=ja'),
(31,'Phùng Quốc Thanh','Thanh',1,4,2,'thanhpq61@gmail.com',1,NULL,2,0,'1961-02-09',0,0,0,'',NULL),
(32,'Hoàng Chí Hiếu','Hiếu',1,4,NULL,'hieuhc@hus.edu.vn',1,NULL,8,0,'1980-01-25',0,3,0,'','https://scholar.google.com.vn/citations?user=SO6c170AAAAJ&hl=en&oi=ao'),
(33,'Sái Công Doanh','Doanh',1,3,NULL,'saidoanh@hus.edu.vn',1,NULL,3,0,'1985-08-18',0,4,0,'','https://scholar.google.com/citations?user=8LSoOV8AAAAJ&hl=vi'),
(34,'Trần Thị Ngọc Anh','Anh',0,3,NULL,'chippooh0711@gmail.com',1,NULL,3,0,'1984-02-20',0,0,0,'','https://scholar.google.com/citations?user=niV3u1IAAAAJ&hl=en'),
(35,'Phan Huy Thiện','Thiện',1,4,NULL,'phanhuythienhn@gmail.com',4,NULL,6,0,'1956-08-26',0,0,0,'',NULL),
(36,'Nguyễn Đình Nam','Nam',1,4,NULL,'dinhnamt2@yahoo.com',1,NULL,6,0,'1987-01-24',0,5,0,'','https://scholar.google.com/citations?hl=en&user=d6PgNWoAAAAJ'),
(37,'Đỗ Tuấn Long','Long',1,4,NULL,'dotuanlong@hus.edu.vn',1,NULL,6,0,'1988-10-24',0,0,0,'','https://scholar.google.com/citations?hl=en&user=oLnEnqIAAAAJ'),
(38,'Ngô Thu Hương','Hương',0,4,2,'ngothuhuong2013@gmail.com',1,NULL,2,0,'1966-09-15',0,0,0,'',NULL),
(39,'Nguyễn Ngọc Đỉnh','Đỉnh',1,4,NULL,'nguyenngocdinh@hus.edu.vn',1,NULL,2,0,'1980-04-18',0,3,0,'','https://scholar.google.com/citations?view_op=verify_email_result&hl=vi&user=f1ZX1WQAAAAJ&email_for_op=nguyenngocdinh%40vnu.edu.vn&citsig=AMD79opNtEt9O7LNAtGeQkgebDlLgzgaqw'),
(40,'Lê Tuấn Tú','Tú',1,4,2,'letuantu@gmail.com',1,NULL,2,0,'1978-10-26',0,5,0,'',NULL),
(41,'Lưu Tuấn Tài','Tài',1,4,3,'lttai50@gmail.com',4,NULL,7,0,'1950-11-24',0,0,0,'',NULL),
(42,'Phạm Văn Bền','Bền',1,4,2,'pvbenkhtn@gmail.com',4,NULL,8,0,'1951-10-12',0,0,0,'',NULL),
(43,'Nguyễn Thế Bình','Bình',1,4,3,'thebinh@vnu.edu.vn',4,NULL,8,0,'1954-11-11',0,4,0,'','https://scholar.google.com/citations?user=1IEoJioAAAAJ&hl=en'),
(44,'Võ Lý Thanh Hà','Hà',1,2,NULL,'dothingoclan2000@yahoo.com',4,NULL,10,0,'1958-09-26',0,0,0,'',NULL),
(45,'Đặng Thị Thanh Thuỷ','Thuỷ',0,4,2,'dangthuyhn@gmail.com',1,NULL,10,15,'1978-01-18',0,0,0,'','ttps://scholar.google.com/citations?hl=vi&usehr=GM6PykQAAAAJ&view_op=list_works&sortby=pubdate'),
(46,'Nguyễn Thị Dung','Dung',0,4,NULL,'dungnt.hus@gmail.com',1,NULL,5,0,'1987-09-17',0,5,0,'',NULL),
(47,'Nguyễn Tiến Cường','Cường',1,4,NULL,'ngtiencuong@gmail.com',1,NULL,9,0,'1981-04-06',0,4,0,'',NULL),
(48,'Nguyễn Thị Thu Hằng','Hằng',0,3,NULL,'hanghainguyen@gmail.com',1,NULL,15,0,'1977-11-05',0,0,0,'',NULL),
(49,'Phạm Quốc Triệu','Triệu',1,4,2,'phamtrieu1953@gmail.com',4,NULL,11,0,'1953-01-25',0,0,0,'',NULL),
(50,'Nguyễn Thùy Trang','Trang',0,4,NULL,'trangnguyenphys@gmail.com',1,NULL,11,14,'1985-08-27',0,0,0,'','https://scholar.google.com/citations?hl=en&user=7d878KEAAAAJ'),
(51,'Ngô Gia Long','Long',1,2,NULL,'ngogialong_t57@hus.edu.vn',3,NULL,8,0,'1994-12-09',0,0,0,'',NULL),
(52,'Nguyễn Quang Báu','Báu',1,4,3,'nguyenquangbau@yahoo.com',4,NULL,6,0,'1954-11-29',0,4,0,'','https://scholar.google.com/citations?hl=en&user=6Ofz3qwAAAAJ'),
(53,'Cao Thị Vi Ba','Ba',0,4,NULL,'caoviba@yahoo.com',1,NULL,6,0,'1969-11-01',0,0,0,'',NULL),
(54,'Lê Thị Hải Yến','Yến',0,4,NULL,'lethihaiyen@hus.edu.vn',1,NULL,4,0,'1978-11-16',0,0,0,'','https://scholar.google.com/citations?user=aWEXM2YAAAAJ&hl=vi'),
(55,'Nguyễn Thu Giang','Giang',0,4,NULL,'giangnt_hn@yahoo.com',4,NULL,6,0,'1964-05-09',0,0,0,'',NULL),
(56,'Phạm Nguyên Hải','Hải',1,4,NULL,'phamnguyenhai@hus.edu.vn',1,NULL,2,0,'1968-07-25',0,0,0,'',NULL),
(57,'Nguyễn Việt Tuyên','Tuyên',1,4,2,'nguyenviettuyen@hus.edu.vn',1,NULL,2,0,'1984-12-19',0,4,0,'','https://scholar.google.com/citations?view_op=list_works&hl=vi&user=-zMzi-EAAAAJ&gmla=AJsN-F43FELzFGgIgrKgMVOYU4rX8XrnAHJ9JhCFYEH0DFZduvW08jGf9iWg0U_oT6SGRxbYCiRtySXmxtGEBi4n1MEcNNNCevIGqI27dWkN6sboKT9oQgpjZd9XN-Y17ShN-_nWpIIK'),
(58,'Nguyễn Huy Sinh','Sinh',1,4,3,'nguyenhuysinh@hus.edu.vn',4,NULL,7,0,'1950-05-10',0,0,0,'',NULL),
(59,'Đỗ Thị Kim Anh','Anh',0,4,2,'kimanh72@gmail.com',1,NULL,2,0,'1972-09-02',0,0,0,'',NULL),
(60,'Trần Hải Đức','Đức',1,4,NULL,'dhtran@hus.edu.vn',1,NULL,16,0,'1984-12-06',0,0,0,'','https://scholar.google.com/citations?user=jZ49vfIAAAAJ&hl=en'),
(61,'Tạ Quỳnh Hoa','Hoa',0,3,NULL,'quynhnn0701@yahoo.com',1,NULL,4,0,'1970-07-14',0,0,0,'',NULL),
(62,'Vũ Đức Minh','Minh',1,4,2,'minhvd@vnu.edu.vn',4,NULL,4,0,'1953-06-29',0,0,0,'',NULL),
(63,'Võ Thanh Quỳnh','Quỳnh',1,4,2,'vtquynh57@yahoo.com',1,NULL,4,0,'1959-09-30',0,4,0,'',NULL),
(64,'Bùi Hồng Vân','Vân',0,4,NULL,'buihongvan@hus.edu.vn',1,NULL,8,0,'1983-08-28',0,0,0,'','https://scholar.google.com/citations?user=AaNZT2UAAAAJ&hl=en'),
(65,'Phùng Quốc Bảo','Bảo',1,4,2,'baopq@vnu.edu.vn',4,NULL,8,0,'1952-05-31',0,0,0,'',NULL),
(66,'Mai Hồng Hạnh','Hạnh',0,4,2,'hanhhongmai@hus.edu.vn',1,NULL,8,14,'1984-11-27',0,4,0,'','https://scholar.google.com/citations?hl=en&user=gnaHEnkAAAAJ&view_op=list_works&authuser=1&gmla=AJsN-F4QYb2GY4wCDDesS_nLyTGjWVu6g06CNWxIYikalnNNH4zZHsrrB5H-m3kgZgJ4uy5Bh3fTggo_042cUgFK0N80Ckj997NEARAy1xjmwjVmQQ0FgCM'),
(67,'Nguyễn Anh Tuấn','Tuấn',1,4,NULL,'anhtuanb1@gmail.com',1,NULL,8,0,'1982-11-19',0,5,0,'','https://scholar.google.com/citations?user=eFkoEHwAAAAJ'),
(68,'Lê Quang Thảo','Thảo',1,4,NULL,'thaolq@hus.edu.vn',1,NULL,10,0,'1982-10-28',0,0,0,'','https://scholar.google.com/citations?hl=en&user=qkNuT3oAAAAJ'),
(69,'Lương Thị Minh Thúy','Thúy',0,3,NULL,'minhthuyhus@yahoo.com.vn',1,NULL,10,0,'1987-06-08',0,0,0,'','https://scholar.google.com.vn/citations?view_op=list_works&hl=vi&user=8hi_KekAAAAJ'),
(70,'Nguyễn Mậu Chung','Chung',1,4,2,'nguyenmauchung57@gmail.com',4,NULL,5,0,'1957-08-03',0,0,0,'',NULL),
(71,'Trần Thế Anh','Anh',1,3,NULL,'ttanhhus@gmail.com',1,NULL,5,0,'1982-12-15',0,0,0,'',NULL),
(72,'Bùi Văn Loát','Loát',1,4,2,'loat.bv58@gmail.com',4,NULL,5,0,'1958-03-22',0,0,0,'',NULL),
(73,'Nguyễn Quang Hưng','Hưng',1,4,2,'sonnet3001@gmail.com',1,NULL,9,0,'1971-01-30',0,0,0,'',NULL),
(74,'Nguyễn Thị Thanh Nhàn','Nhàn',0,4,NULL,'nhan_khtn@yahoo.com.vn',1,NULL,6,0,'1980-07-01',0,0,0,'',NULL),
(75,'Nguyễn Thu Hường','Hường',0,4,NULL,'thnguyen1985@gmail.com',1,NULL,6,0,'1985-07-25',0,0,0,'','https://scholar.google.com/citations?user=nGEF7mEAAAAJ&hl=vi'),
(76,'Hà Huy Bằng','Bằng',1,4,3,'hahuybang@yahoo.co.uk',1,NULL,6,0,'1961-08-23',0,0,0,'',NULL),
(77,'Vương Thị Thu Hường','Hường',0,2,NULL,'vuonghuong83@gmail.com',1,NULL,15,0,'1983-08-11',0,0,0,'',NULL),
(78,'Lưu Mạnh Quỳnh','Quỳnh',1,4,NULL,'luumanhquynh@hus.edu.vn',1,NULL,11,0,'1980-12-10',0,0,0,'','https://scholar.google.com/citations?view_op=list_works&hl=en&user=jiHdrTEAAAAJ&gmla=AJsN-F4r2W4Lk8C7j3yVivKVGIOfYOVgFYOTOWv8awscS_dPZPKS2PbY1EsMfinXuaCgl9ZLdyOmt2Fpx7TlplKgSR83Fb8o9-E1CeeI23JJVTJbKoQ1BO0'),
(79,'Nguyễn Duy Thiện','Thiện',1,3,NULL,'thiennd@hus.edu.vn',1,NULL,11,0,'1986-03-30',0,7,0,'',NULL),
(80,'Nguyễn Duy Huy','Huy',1,4,NULL,'nguyenduyhuy0312@gmail.com',1,NULL,11,14,'1987-12-03',0,0,0,'','https://scholar.google.com/citations?hl=en&user=oPtf4A0AAAAJ'),
(81,'Phạm Bá Duy','Duy',1,3,NULL,'duy.pham.ba@gmail.com',3,NULL,12,0,'1989-10-30',0,0,0,'',NULL),
(82,'Đỗ Quốc Tuấn','Tuấn',1,4,NULL,'tuanqdo@hus.edu.vn',3,NULL,9,0,'1985-11-18',0,0,0,'',NULL),
(83,'Vi Hồ Phong','Phong',1,4,NULL,'hophongmc@gmail.com',3,NULL,5,0,'1989-09-29',0,0,0,'',NULL),
(84,'Đào Quang Duy','Duy',1,4,NULL,'daoquangduy@hus.edu.vn',1,NULL,11,0,'1983-01-04',0,0,0,'','https://scholar.google.co.id/citations?user=hcU04NoAAAAJ&fbclid=IwAR2K8CjvdkPFqWST5tBjCqa4qbB2pUq8sm4A-sfK0i1eOQTRADtAwbLBmKM'),
(85,'Nguyễn Văn Hùng','Hùng',1,5,3,'hungnv@hus.edu.vn',4,NULL,6,0,'1943-01-01',0,0,0,'',NULL),
(86,'Nguyễn Hoàng Lương','Lương',1,5,3,'luongnh@hus.edu.vn',3,NULL,11,0,'1953-12-31',0,0,0,'',NULL),
(90,'Nguyễn Hoàng Hải','Hải',1,4,2,'nhhai@vnu.edu.vn',3,NULL,11,0,'1973-04-05',0,0,0,'',NULL),
(92,'Nguyễn Ngọc Long','Long',1,4,2,'nguyenngoclong@vnu.edu.vn',4,NULL,2,0,'1943-03-16',0,0,0,'',NULL),
(93,'Nguyễn Thế Bình B','Bình',1,2,1,'thebinhB@vnu.edu.vn',4,NULL,8,0,'1957-06-07',0,4,0,'',NULL),
(94,'Đỗ Thị Ngọc','Ngọc',0,2,NULL,'ngocdt129@hus.edu.vn',1,NULL,14,0,'1993-09-12',0,8,0,'',NULL),
(95,'Đoàn Minh Quang','Quang',1,2,NULL,'quangmd@vnu.edu.vn',1,NULL,6,0,'1996-10-20',0,0,0,'','https://scholar.google.com/citations?user=oc9QsrYAAAAJ&hl=vi'),
(98,'Trần Trọng Đức','Đức',1,3,NULL,'ductt@hus.edu.vn',1,NULL,8,0,'1987-08-23',0,0,0,'','https://scholar.google.com.vn/citations?user=88lecmkAAAAJ&hl=en'),
(99,'Lê Tuấn Anh','Anh',1,2,NULL,'anhlt1987@hus.edu.vn',3,NULL,0,0,'1987-03-26',0,0,0,'',NULL),
(102,'Nguyễn Anh Tuấn A','Tuấn',1,4,2,'tuanna2910@gmail.com',3,NULL,7,0,'1978-10-29',0,0,0,'',NULL),
(103,'Lại Thị Thu Hiền','Hiền',0,2,NULL,NULL,2,'P207F Bldg. T1',14,0,'1997-01-12',0,0,0,'',NULL),
(104,'Công Phương Cao','Cao',1,3,NULL,'congphuongcao@gmail.com',3,'P207F Bldg. T1',9,0,'1996-09-01',0,0,0,'',NULL),
(105,'Nguyễn Hải Lý','Lý',0,2,NULL,NULL,2,'P207F Bldg. T1',14,0,'1997-11-28',0,0,0,'',NULL),
(106,'Nguyễn Thị Hoa','Hoa',0,2,NULL,NULL,3,'P207F Bldg. T1',14,0,'1996-06-14',0,12,0,'',NULL),
(107,'Hoàng Gia Linh','Linh',0,2,NULL,NULL,3,'P207F Bldg. T1',14,0,'1997-06-02',0,12,0,'',NULL),
(108,'Lê Hoàng Phong','Phong',1,2,NULL,NULL,3,'P207F Bldg. T1',14,0,'1995-05-10',0,12,0,'',NULL),
(109,'Nguyễn Trọng Tâm','Tâm',1,2,NULL,NULL,3,NULL,8,0,'1997-07-27',0,0,0,'',NULL),
(110,'Phạm Thế An','An',1,2,NULL,NULL,1,NULL,16,0,'1997-01-12',0,0,0,'',NULL),
(111,'Bùi Việt Hà','Hà',1,4,NULL,'habuiviet@yahoo.com',1,NULL,9,0,'1984-01-17',0,0,0,'',NULL),
(112,'Nguyễn Hải Phong','Phong',1,NULL,NULL,'nguyenhaiphong@gmail.com',1,NULL,11,NULL,'1996-01-15',4,NULL,NULL,NULL,NULL),
(113,'Phạm Đức Thắng','Thắng',1,4,2,'pdthang@hus.edu.vn',1,NULL,11,NULL,'1973-01-28',6,NULL,NULL,NULL,NULL),
(114,'Nguyễn Việt Anh','Anh',1,3,NULL,'anhnguyenviet@hus.edu.vn',3,NULL,9,NULL,'1999-10-05',0,NULL,NULL,NULL,NULL),
(115,'Nguyễn Văn Phú','Phú',1,1,NULL,'phynvphys@hus.edu.vn',1,NULL,10,NULL,'2001-01-01',0,NULL,NULL,NULL,NULL),
(116,'Vi Hồ Quân','Quân',1,2,NULL,'vihoquan@hus.edu.vn',1,NULL,9,NULL,'2002-03-20',0,NULL,NULL,NULL,NULL),
(117,'Nguyễn Nhật Tùng','Tùng',1,2,NULL,'nguyennhatung@hus.edu.vn',1,NULL,9,NULL,'2002-11-11',11,NULL,NULL,NULL,NULL);

CREATE TABLE tn_tai_khoan (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE, 
    mat_khau VARCHAR(255) NOT NULL,     
    trang_thai TINYINT DEFAULT 1,        -- 1: hoạt động, 0: khóa
    vai_tro TINYINT DEFAULT 1,
    giang_vien_id INT DEFAULT NULL UNIQUE,         -- Liên kết tới bảng giang_vien
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT DEFAULT NULL,
    updated_by INT DEFAULT NULL,
    FOREIGN KEY (giang_vien_id) REFERENCES tn_giang_vien(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
DELIMITER $$

CREATE TRIGGER tai_khoan_time_insert
BEFORE INSERT ON tn_tai_khoan
FOR EACH ROW
BEGIN
    SET NEW.created_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
    SET NEW.updated_at = UTC_TIMESTAMP + INTERVAL 7 HOUR;
END$$

DELIMITER ;
INSERT INTO tn_tai_khoan (email, mat_khau, giang_vien_id) VALUES
('loan.trinhthi@gmail.com', '1980', 1),
('nguyentuniem@gmail.com', '1979', 2),
('vuonghiepcms@gmail.com', '1978', 3),
('lienbat76@yahoo.com', '1948', 4),
('toannt@vnu.edu.vn', '1973', 5),
('hoanq@hus.edu.vn', '1979', 6),
('ndvinh19@yahoo.com.vn', '1955', 7),
('doducthanh1956@gmail.com', '1956', 8),
('trunggk@hus.edu.vn', '1980', 9),
('dtkien@hus.edu.vn', '1976', 10),
('tranvinhthang@hus.edu.vn', '1976', 11),
('05.nghia@gmail.com', '1965', 12),
('hathuylong@hus.edu.vn', '1977', 13),
('levanvu@hus.edu.vn', '1956', 14),
('congbachthanh@gmail.com', '1952', 15),
('gianghuongbach@gmail.com', '1982', 16),
('luanpt@hus.edu.vn', '1990', 17),
('quanglocphys@gmail.com', '1992', 18),
('buithihoa.k55@hus.edu.vn', '1992', 19),
('huongvh@hus.edu.vn', '1990', 20),
('buithihong.k56@hus.edu.vn', '1993', 21),
('ngacanbang@hus.edu.vn', '1971', 22),
('nddung1952@gmail.com', '1952', 23),
('nguyenvietdat@gmail.com', '1987', 24),
('phamvanthanh@hus.edu.vn', '1984', 25),
('vietncp@gmail.com', '1980', 26),
('namnh@hus.edu.vn', '1979', 27),
('qnv1985@gmail.com', '1985', 28),
('trinhthuy.vl07@gmail.com', '1989', 29),
('vuthanhmai031@gmail.com', '1983', 30),
('thanhpq61@gmail.com', '1961', 31),
('hieuhc@hus.edu.vn', '1980', 32),
('saidoanh@hus.edu.vn', '1985', 33),
('chippooh0711@gmail.com', '1984', 34),
('phanhuythienhn@gmail.com', '1956', 35),
('dinhnamt2@yahoo.com', '1987', 36),
('dotuanlong@hus.edu.vn', '1988', 37),
('ngothuhuong2013@gmail.com', '1966', 38),
('nguyenngocdinh@hus.edu.vn', '1980', 39),
('letuantu@gmail.com', '1978', 40),
('lttai50@gmail.com', '1950', 41),
('pvbenkhtn@gmail.com', '1951', 42),
('thebinh@vnu.edu.vn', '1954', 43),
('dothingoclan2000@yahoo.com', '1958', 44),
('dangthuyhn@gmail.com', '1978', 45),
('dungnt.hus@gmail.com', '1987', 46),
('ngtiencuong@gmail.com', '1981', 47),
('hanghainguyen@gmail.com', '1977', 48),
('phamtrieu1953@gmail.com', '1953', 49),
('trangnguyenphys@gmail.com', '1985', 50),
('ngogialong_t57@hus.edu.vn', '1994', 51),
('nguyenquangbau@yahoo.com', '1954', 52),
('caoviba@yahoo.com', '1969', 53),
('lethihaiyen@hus.edu.vn', '1978', 54),
('giangnt_hn@yahoo.com', '1964', 55),
('phamnguyenhai@hus.edu.vn', '1968', 56),
('nguyenviettuyen@hus.edu.vn', '1984', 57),
('nguyenhuysinh@hus.edu.vn', '1950', 58),
('kimanh72@gmail.com', '1972', 59),
('dhtran@hus.edu.vn', '1984', 60),
-- 
('quynhnn0701@yahoo.com', '1970', 61),
('minhvd@vnu.edu.vn', '1953', 62),
('vtquynh57@yahoo.com', '1959', 63),
('buihongvan@hus.edu.vn', '1983', 64),
('baopq@vnu.edu.vn', '1952', 65),
('hanhhongmai@hus.edu.vn', '1984', 66),
('anhtuanb1@gmail.com', '1982', 67),
('thaolq@hus.edu.vn', '1982', 68),
('minhthuyhus@yahoo.com.vn', '1987', 69),
('nguyenmauchung57@gmail.com', '1957', 70),
('ttanhhus@gmail.com', '1982', 71),
('loat.bv58@gmail.com', '1958', 72),
('sonnet3001@gmail.com', '1971', 73),
('nhan_khtn@yahoo.com.vn', '1980', 74),
('thnguyen1985@gmail.com', '1985', 75),
('hahuybang@yahoo.co.uk', '1961', 76),
('vuonghuong83@gmail.com', '1983', 77),
('luumanhquynh@hus.edu.vn', '1980', 78),
('thiennd@hus.edu.vn', '1986', 79),
('nguyenduyhuy0312@gmail.com', '1987', 80),
('duy.pham.ba@gmail.com', '1989', 81),
('tuanqdo@hus.edu.vn', '1985', 82),
('hophongmc@gmail.com', '1989', 83),
('daoquangduy@hus.edu.vn', '1983', 84),
('hungnv@hus.edu.vn', '1943', 85),
('luongnh@hus.edu.vn', '1953', 86),
('nhhai@vnu.edu.vn', '1973', 90),
('nguyenngoclong@vnu.edu.vn', '1943', 92),
('thebinhB@vnu.edu.vn', '1957', 93),
('ngocdt129@hus.edu.vn', '1993', 94),
('quangmd@vnu.edu.vn', '1996', 95),
('ductt@hus.edu.vn', '1987', 98),
('anhlt1987@hus.edu.vn', '1987', 99),
('tuanna2910@gmail.com', '1978', 102),
-- fake mail id 103
('thuhien@gmail.com', '1997', 103), 
('congphuongcao@gmail.com', '1996', 104),
-- fake mail 
('haily@gmail.com', '1997', 105),
('hoanguyen@gmail.com', '1996', 106),
('gialinh@gmail.com', '1997', 107),
('hoangphong@gmail.com', '1995', 108),
('trongtam@gmail.com', '1997', 109),
('thean@gmail.com', '1997', 110),
-- real
('habuiviet@yahoo.com', '1984', 111),
('nguyenhaiphong@gmail.com', '1996', 112),
('pdthang@hus.edu.vn', '1973', 113),
('anhnguyenviet@hus.edu.vn', '1999', 114),
('phynvphys@hus.edu.vn', '2001', 115),
('vihoquan@hus.edu.vn', '2002', 116),
('nguyennhatung@hus.edu.vn', '2002', 117),
('admin', '2003', Null);