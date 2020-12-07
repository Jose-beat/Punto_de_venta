@echo off
set mySqlPath=C:\xampp\mysql\bin\mysql.exe
set dbUser=root
set dbPassword=
set dbName=fotos
set file=%dbName%_%date:~-4,4%%date:~-7,2%%date:~-10,2%_%time:~0,2%%time:~3,2%%time:~6,2%.sql
set path=C:\Users\Jose Uriel\Desktop\Portafolio\Backup

echo Running dump for database %dbName% ^> ^%path%\%file%
"%mySqlPath%\bin\mysqldump.exe" -u %dbUser% -p%dbPassword%  %dbName% >"%path%\%file%"
echo Done!