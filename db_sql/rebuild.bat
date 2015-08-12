echo off
echo **************************************

echo   Rebuilding the SHADOW database.....
echo   NOTICE: NEED ROOT PASWORD

cd d:\mysql-5.1.48-win32\bin
mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\delete.sql
mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\create-database.sql
mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\log_trigger.sql
mysql -u root -p < d:\xampp\htdocs\shadow\shadow-git\db_sql\properties.sql

echo   DONE!!!!!!

echo **************************************
pause