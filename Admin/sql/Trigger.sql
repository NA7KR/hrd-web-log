OLD
delimiter //
DROP TRIGGER IF EXISTS `QSL_Trigger`//
CREATE TRIGGER `QSL_Trigger` AFTER UPDATE ON `$db_Table`
 FOR EACH ROW BEGIN
	IF new.COL_EQSL_QSL_RCVD <> old.COL_EQSL_QSL_RCVD  THEN
		INSERT into HRD_Web.tb_Cards
			(COL_PRIMARY_KEY,COL_File_Path_E,COL_File_Path_F,COL_File_Path_B)VALUES (old.COL_PRIMARY_KEY, CONCAT('E-' , old.COL_PRIMARY_KEY, '-', replace(old.Col_Call,'/','-'), '.jpg'),'',''); 
			SET @exec_var = sys_exec(CONCAT('/usr/bin/php /var/www/qsl.php  ', old.COL_PRIMARY_KEY , ' &' ));
	END IF;
END
//
delimiter ;


oldNEW
delimiter //
DROP TRIGGER IF EXISTS `QSL_Trigger`//
CREATE TRIGGER `QSL_Trigger` AFTER UPDATE ON `$db_Table`
 FOR EACH ROW BEGIN
        IF new.COL_EQSL_QSL_RCVD <> old.COL_EQSL_QSL_RCVD  THEN
                INSERT into HRD_Web.tb_Cards (COL_PRIMARY_KEY,COL_File_Path_E,COL_File_Path_F,COL_File_Path_B)
		VALUES (old.COL_PRIMARY_KEY, CONCAT('E-' , old.COL_PRIMARY_KEY, '-', replace(old.Col_Call,'/','-'), '.jpg'),'','');
                INSERT into HRD_Web.tb_to_download (KeyNumber)
		VALUES (old.COL_PRIMARY_KEY); 
        END IF;
END
//
delimiter ;


Clean up 
UPDATE `tb_Cards` SET `COL_File_Path_F` = '' WHERE WHERE`COL_File_Path_F` like 'E%'


New 2022
DELIMITER $$
CREATE TRIGGER `QSL_Trigger` AFTER UPDATE ON `$db_Table` FOR EACH ROW BEGIN
        IF new.COL_EQSL_QSL_RCVD <> old.COL_EQSL_QSL_RCVD  THEN
                INSERT into HRD_Web.tb_Cards (COL_PRIMARY_KEY,COL_File_Path_E)
                VALUES (old.COL_PRIMARY_KEY, CONCAT('E-' , old.COL_PRIMARY_KEY, '-', replace(old.Col_Call,'/','-'), '.jpg'));
                INSERT into HRD_Web.tb_to_download (KeyNumber,updated)
                VALUES (old.COL_PRIMARY_KEY,0);
        END IF;
END
$$
DELIMITER ;
