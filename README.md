STEPS TO LAUNCH IPL-WIKI

STEP-1
clone or download the zip file of ipl-wiki with the given link-https://github.com/harshiteuro/ipl-wiki

STEP-2
install xampp

STEP-3
go to C:/xampp/htdocs and copy the ipl-wiki-master unzip file and paste in the htdocs folder atlast rename it to ipl-wiki.

STEP-4
delete the localSettings.php file so that we can start the db configuration again.

STEP-5
launch xampp and check config with php.ili file containing the following extension or not if not present then add it.
extension=php_intl.dll

STEP-5
run the apache server and sql server

STEP-6
comment out the script code present in index.php file from line 55 to 73.

STEP-7
open localhost/ipl-wiki then start creating localSettings.php file with new configuration after that in phpMyAdmin a database is created with name you have provided. Please make sure that while creating user account use the same username and password in loginAPI on line 113 and 114 of index.php.

STEP-8
download localSetting.php file copy it in c/xampp/htdocs/ipl-wiki open the settings file and add the following extensions-
wfLoadExtension( 'Cargo' );
wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'PageForms' );

STEP-9
go to c/xampp/htdocs/ipl-wiki/maintenance in command-prompt then run the following command 
php update.php
This includes all the cargo files in the database.
For checking the databse you can go to localhost then go to phpMyAdmin.

STEP-10
Uncomment the script code in index.php from line 55 to 73.

STEP-11
Run the localhost/ipl-wiki in your browser it will take few minutes after that you can comment the script again in index.php then refresh it.
After that you can see all the IPL page are imported successfully...

You can refer the video for detailed instructions.

THANKS!!
