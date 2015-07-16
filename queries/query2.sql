SELECT substr(date_received, 6-10) date_received,
       count(*) as 'number of complaints'
  FROM consumer_complaint
  WHERE date_received is not null
 GROUP 
    BY substr(date_received, 6-10);