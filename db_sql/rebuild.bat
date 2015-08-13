echo off
:loop
echo 	***************************************************
echo 	  	Rebuilding the SHADOW database.....		
echo 	 	NOTICE: NEED ROOT PASWORD				
echo 	  	1.Truncate all the tables				
echo   		2.Insert the original data 		 	
echo 	  	3.Rebuild the database 					
echo 	 	4.None                                
echo 		Please  input your choice! 1 or 2 or 3   
echo 	***************************************************
set /p ifo=
cls
echo Your choice is %ifo%
if %ifo% == 1 (
	cd d:\mysql-5.1.48-win32\bin
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\truncate.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 2 (
	cd d:\mysql-5.1.48-win32\bin
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 3 (
	cd d:\mysql-5.1.48-win32\bin
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\delete.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\create-database.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\log_trigger.sql
	mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql
	echo   DONE!!!!!!
	goto loop
)
if %ifo% == 4 (
	pause>null
)



