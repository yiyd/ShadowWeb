echo off
:loop
echo 	***************************************************
echo 	  		SHADOW-ASSISTENT	
echo  		   Current Time : %Time:~0,10%
echo 	 	   NOTICE: need root password!				
echo 	  	1. Truncate all the tables				
echo   		2. Insert default datas 		 	
echo 	  	3. Rebuild the database 		
echo 	  	4. Backup whole database(as shadow.sql in local folder)
echo 	  	5. Backup whole database
echo 	 	6. NONE              
echo 		Please  input your choice! 1 or 2 or 3   
echo 	***************************************************
set /p ifo=
cls
echo Your choice is %ifo%
if %ifo% == 1 (
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\truncate.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 2 (
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 3 (
	cd D:\mysql-5.1.48-win32\bin
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\delete.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\create-database.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\log_trigger.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 4 (
	mysqldump -u root -p --routines --default-character-set=utf8 --databases shadow > shadow_%date:~0,10%.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 6 (
	pause>null
)



