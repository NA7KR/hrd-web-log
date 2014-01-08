delimiter //
DROP TRIGGER IF EXISTS `QSL_Trigger`//
CREATE TRIGGER `QSL_Trigger` AFTER UPDATE ON `TABLE_HRD_CONTACTS_V01`
 FOR EACH ROW BEGIN
	IF new.COL_EQSL_QSL_RCVD <> old.COL_EQSL_QSL_RCVD  THEN
		INSERT into HRD_Web.tb_Cards
			(COL_PRIMARY_KEY,COL_File_Path_E,COL_File_Path_F,COL_File_Path_B)VALUES (old.COL_PRIMARY_KEY, CONCAT('E-' , old.COL_PRIMARY_KEY, '-', replace(old.Col_Call,'/','-'), '.jpg'),'',''); 
			SET @exec_var = sys_exec(CONCAT('/usr/bin/php /var/www/qsl.php  & >> /dev/null ', old.COL_PRIMARY_KEY));
	END IF;
END
//
delimiter ;