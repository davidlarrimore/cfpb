## Overview



##Installation and Setup

This project crosses several IT domains from Infrastructure, Web Development, Database Management, Analytics, etc. and therefore has several logical components that make it work.


### Environment

This was developed using the LAMPP Stack [XAMPP version 5.5.24](https://www.apachefriends.org/download.html) which includes the following components and versions:

* Apache 2.4.12
* MySQL 5.6.24
* PHP 5.5.24 & PEAR
* SQLite 2.8.17/3.7.17 + multibyte (mbstring) support
* Perl 5.16.3



### Data Prep

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



### Database Setup


#### XAMPP Access on OSX

If using OSX to locally connect to MySQL database, use the following command to envoke xampp

	/Applications/XAMPP/xamppfiles/bin/mysql -u root


#### Create Database Account

	CREATE USER 'cfpb'@'localhost' IDENTIFIED BY 'cfpb';
	GRANT USAGE ON *.* TO 'cfpb'@'localhost' IDENTIFIED BY 'cfpb' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
	CREATE DATABASE IF NOT EXISTS `cfpb`;
	GRANT ALL PRIVILEGES ON `cfpb`.* TO 'cfpb'@'localhost';
	GRANT ALL PRIVILEGES ON `cfpb\_%`.* TO 'cfpb'@'localhost';





####Load Data

From this point, you can leverage the ./administrator.php url to load the data. Manual instructions are below



	LOAD DATA LOCAL INFILE '/Applications/XAMPP/xamppfiles/htdocs/cfpb/data/e20135us0015000-baked.csv' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;


	LOAD DATA LOCAL INFILE '/Applications/XAMPP/xamppfiles/htdocs/cfpb/data/m20135us0015000-baked.csv' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;


	LOAD DATA LOCAL INFILE '/Applications/XAMPP/xamppfiles/htdocs/cfpb/data/Consumer_Complaints.csv' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;




### Required Tools


* Microsoft Excel 2003 or Newer (XLSX)
* Text editor




