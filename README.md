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





###Create Tables


LOAD DATA LOCAL INFILE '/Applications/XAMPP/xamppfiles/htdocs/cfpb/data/e20135us0015000-baked.csv' INTO TABLE cfpb.acs_estimate FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES (FILEID,FILETYPE,STUSAB,CHARITER,SEQUENCE,LOGRECNO,GEOID,GEOGRAPHY_NAME,B06010_001,B06010_002,B06010_003,B06010_004,B06010_005,B06010_006,B06010_007,B06010_008,B06010_009,B06010_010,B06010_011,B06010_012,B06010_013,B06010_014,B06010_015,B06010_016,B06010_017,B06010_018,B06010_019,B06010_020,B06010_021,B06010_022,B06010_023,B06010_024,B06010_025,B06010_026,B06010_027,B06010_028,B06010_029,B06010_030,B06010_031,B06010_032,B06010_033,B06010_034,B06010_035,B06010_036,B06010_037,B06010_038,B06010_039,B06010_040,B06010_041,B06010_042,B06010_043,B06010_044,B06010_045,B06010_046,B06010_047,B06010_048,B06010_049,B06010_050,B06010_051,B06010_052,B06010_053,B06010_054,B06010_055,B06010PR_001,B06010PR_002,B06010PR_003,B06010PR_004,B06010PR_005,B06010PR_006,B06010PR_007,B06010PR_008,B06010PR_009,B06010PR_010,B06010PR_011,B06010PR_012,B06010PR_013,B06010PR_014,B06010PR_015,B06010PR_016,B06010PR_017,B06010PR_018,B06010PR_019,B06010PR_020,B06010PR_021,B06010PR_022,B06010PR_023,B06010PR_024,B06010PR_025,B06010PR_026,B06010PR_027,B06010PR_028,B06010PR_029,B06010PR_030,B06010PR_031,B06010PR_032,B06010PR_033,B06010PR_034,B06010PR_035,B06010PR_036,B06010PR_037,B06010PR_038,B06010PR_039,B06010PR_040,B06010PR_041,B06010PR_042,B06010PR_043,B06010PR_044,B06010PR_045,B06010PR_046,B06010PR_047,B06010PR_048,B06010PR_049,B06010PR_050,B06010PR_051,B06010PR_052,B06010PR_053,B06010PR_054,B06010PR_055,B06011_001,B06011_002,B06011_003,B06011_004,B06011_005,B06011PR_001,B06011PR_002,B06011PR_003,B06011PR_004,B06011PR_005,B06012_001,B06012_002,B06012_003,B06012_004,B06012_005,B06012_006,B06012_007,B06012_008,B06012_009,B06012_010,B06012_011,B06012_012,B06012_013,B06012_014,B06012_015,B06012_016,B06012_017,B06012_018,B06012_019,B06012_020,B06012PR_001,B06012PR_002,B06012PR_003,B06012PR_004,B06012PR_005,B06012PR_006,B06012PR_007,B06012PR_008,B06012PR_009,B06012PR_010,B06012PR_011,B06012PR_012,B06012PR_013,B06012PR_014,B06012PR_015,B06012PR_016,B06012PR_017,B06012PR_018,B06012PR_019,B06012PR_020);


LOAD DATA LOCAL INFILE '/Applications/XAMPP/xamppfiles/htdocs/cfpb/data/Consumer_Complaints.csv' INTO TABLE cfpb.consumer_complaint FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;




### Required Tools


* Microsoft Excel 2003 or Newer (XLSX)
* Text editor




