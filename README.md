## Overview
This prototype/proof of concept is a full web application leveraging PHP and MySQL. It contains 3 major components; Dashboard App, Administrative App, and API.

The Administrative app is a mechanism for people to bootstrap or setup all of the database tables and load all of the data.

The API is a collection of queries against the database that are exposed as a JSON API.

The Dashboard app consumes data from the API to present the data.


**NOTE: This application works best in a modern web browser (aka > IE 9.0)**


##Installation and Setup

This project crosses several IT domains from Infrastructure, Web Development, Database Management, Analytics, etc. and therefore has several logical components that make it work.


### Environment

This was developed using the LAMPP Stack [XAMPP version 5.5.24](https://www.apachefriends.org/download.html) which includes the following components and versions:

* Apache 2.4.12
* MySQL 5.6.24
* PHP 5.5.24 & PEAR
* SQLite 2.8.17/3.7.17 + multibyte (mbstring) support
* Perl 5.16.3

**Note**: Currently, both DB and Apache must exist on the same server due to the data loading mechanism that is currently being utilized.

### LAMPP Installation on Ubuntu 14.04

* [Use this link](http://howtoubuntu.org/how-to-install-lamp-on-ubuntu) to get the base install.
* install git
* Upgrade MySQL server to 5.6


	sudo apt-get update
	sudo apt-get upgrade
	sudo apt-get install mysql-server-5.6


* Navigate to /var/www/html/
* Clone the repository

	sudo git clone https://github.com/davidlarrimore/cfpb.git

* Some install scripts do not include the mysqli install, so use the following command


	sudo apt-get install php5-mysql


* PHP.ini needs to be modified to support the amount of data that will be flowing through it. 512 should be enough for most operations.


	sudo vi /etc/php5/apache2/php.ini

	set memory_limit=512M


* My.cnf needs to be modified to support the larger queries.


	sudo vi /etc/mysql/my.cnf

	change key_buffer              = 128M


* Once you are done both of these tasks, you should restart both services


	sudo /etc/init.d/mysql restart 
	sudo service apache2 restart


* I have also found a lot of value in increasing the buffer pool size for mysql

	SET GLOBAL innodb_buffer_pool_size=402653184;


###Database Optimization

Depending upon the server size, it may be necessary to optimize the settings to get the bigger queries running. Please follow these steps if necessary. Each of these steps are run using the mysql cli, and require a reboot once performed.


* increase tmp_table_size: @@tmp_table_size=134217728;

	set @@tmp_table_size=134217728;

* increase max_heap_table_size

	set @@max_heap_table_size=536870912;




### Connect to MYSQL server


**Note**: If using OSX to locally connect to MySQL database, use the following command to envoke xampp

	/Applications/XAMPP/xamppfiles/bin/mysql -u root



#### Create Database Account

Login to the mysql database and run the following commands to create the cfpb user and database

	CREATE USER 'cfpb'@'localhost' IDENTIFIED BY 'cfpb';
	GRANT USAGE ON *.* TO 'cfpb'@'localhost' IDENTIFIED BY 'cfpb' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
	CREATE DATABASE IF NOT EXISTS `cfpb`;
	GRANT ALL PRIVILEGES ON `cfpb`.* TO 'cfpb'@'localhost';
	GRANT ALL PRIVILEGES ON `cfpb\_%`.* TO 'cfpb'@'localhost';





#### Accessing the Application

Depending upon your configuration, you should be able to access the application at http://<hostname>/cfpb/



#### Load Data into Database

From this point, you can leverage the ./administrator.php url to load the data. The application can handle most of the database creation/setup, but manual data load commands have been provided.


	LOAD DATA LOCAL INFILE '<root directory>/cfpb/data/e20135us0015000-baked.csv' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;

	LOAD DATA LOCAL INFILE '<root directory>/cfpb/data/m20135us0015000-baked.csv' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;

	LOAD DATA LOCAL INFILE '<root directory>/cfpb/data/Consumer_Complaints.csv' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;

	LOAD DATA LOCAL INFILE '<root directory>/cfpb/data/g20135us.csv.csv' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;


**NOTE: If you receive an "Row 1 was truncated" error, please look at [this site](http://www.alanjames.org/2009/08/mysql-row-n-was-truncated-a-solution/) for ways to modify the statements to get things working.


**NOTE: Depending upon system resources the "load tables and data" function can take a considerable amount of time. Much of this is due to pre-aggregation, indexing, and other performance related functions.




### Data Preparation

As this is a prototype/demo/proof of concept project, manual data preparation activities were performed to reduce over all development time. More formal implementations should include automated data preparation and/or more complex database architecture to reduce error and duplicative work.

	Note: Any file in the project noted with "-baked" have had the following steps performed and may be used in liue of manual data preparation.


#### e2013us0015000.txt & m2013us0015000.txt


Follow basic instructions on [ftp://ftp.census.gov/acs2013_5yr/summaryfile/ACS_SF_Excel_Import_Tool.pdf](ftp://ftp.census.gov/acs2013_5yr/summaryfile/ACS_SF_Excel_Import_Tool.pdf) using  to convert the .txt file into a baked .csv file.


Notes:


* These files use Sequence 15
* For "Add geographies by using common merged keys. " section, use /data/g20135us-baked.xlsx
* In the E file, an extra carriage return was found after element 3 of the first record. Once that is removed, data should map properly in Excel.
* Once you have prepped the E & M sheets, remove row 2 from each sheet and export as 2 separate .csv's
* Column Geography name should be changed to GEOGRAPHY_NAME



#### g20135us.csv

Follow instructions on [http://www2.census.gov/acs2013_5yr/summaryfile/ACS_2013_SF_Tech_Doc.pdf](http://www2.census.gov/acs2013_5yr/summaryfile/ACS_2013_SF_Tech_Doc.pdf) to bake in headers.


#### Consumer_Complaints.csv

No specific data prep activites are required for this file. Data retrieved from [Here](http://www.consumerfinance.gov/complaintdatabase/#download-the-data).




### Libraries Used

* Twitter Bootstrap 3
* Google Charts API


