delimiter //
DROP TRIGGER IF EXISTS `QSL_Trigger`//
CREATE TRIGGER `QSL_Trigger` AFTER UPDATE ON `TABLE_HRD_CONTACTS_V01`
 FOR EACH ROW BEGIN
	IF new.COL_EQSL_QSL_RCVD <> old.COL_EQSL_QSL_RCVD  THEN
		INSERT into HRD_Web.tb_Cards
			(COL_PRIMARY_KEY,COL_File_Path_E)VALUES (old.COL_PRIMARY_KEY, CONCAT('E-' , old.COL_PRIMARY_KEY, '-', old.Col_Call, '.jpg')); 
			SET @exec_var = sys_exec(CONCAT('php /var/www/qsl.php ', COL_PRIMARY_KEY));
	END IF;
END
//
delimiter ;
