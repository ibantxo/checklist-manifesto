checklist-manifesto
===================

Based on "The Checklist Manifesto" book (Atul Gawande)

INTRODUCTION
============
This is my first GIT project and I am not an expert developer,so... with no doubt, you will find a lot of wrong things in the code.


WHAT DO YOU NEED BEFORE RUNNING?
===============================
1. MYSQL
2. APACHE + PHP
3. EMAIL SMTP OUTGOING EMAIL SENDING

INSTALL:
=======
1. CREATE EMPTY DATABASE and ENABLE PERMISSIONS FOR CONNECTING FROM WEB SERVER
    - name: "checklist"
    - user: "check-user"
    - pass: "checl-pass"
2. IMPORT DATABASE FROM config/database.sql file.
3. SET NEEDED PARAMETERES IN config/config.php
    - DATABASE PARAMETERS
    - EMAIL PARAMETERS
    - DEFAULT PARAMETERS (password reset, verification...)

3. ENABLE PROJECT FOLDER IN WEB-SERVER via /etc/apache2/default-server.conf 
	Alias /checklist "/PATH_TO_THE_PROJECT/checklist/checklist-manifesto/members"
	<Directory "/PATH_TO_THE_PROJECT/checklist/checklist-manifesto/members">
	 Options All
	 AllowOverride All
	 Order allow,deny
	 Allow from all
	</Directory>



THIS PROJECT INTEGRATES:
> https://github.com/panique/php-login-advanced/tree/master

