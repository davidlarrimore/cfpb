## Overview




## Requirements

LAMPP Stack develped using [XAMPP version 5.5.24](https://www.apachefriends.org/download.html)

* Apache 2.4.12
* MySQL 5.6.24
* PHP 5.5.24 & PEAR
* SQLite 2.8.17/3.7.17 + multibyte (mbstring) support
* Perl 5.16.3



## Data Prep

### e2013us0015000.txt & m2013us0015000.txt

	Note: These files use Sequence 15

* Follow basic instructions on [ftp://ftp.census.gov/acs2013_5yr/summaryfile/ACS_SF_Excel_Import_Tool.pdf](ftp://ftp.census.gov/acs2013_5yr/summaryfile/ACS_SF_Excel_Import_Tool.pdf) using  to convert the .txt file into a baked .csv file.

* For "Add geographies by using common merged keys. " section, use /data/g20135us-baked.xlsx



### g20135us.csv

Follow instructions on [http://www2.census.gov/acs2013_5yr/summaryfile/ACS_2013_SF_Tech_Doc.pdf](http://www2.census.gov/acs2013_5yr/summaryfile/ACS_2013_SF_Tech_Doc.pdf) to bake in headers.


