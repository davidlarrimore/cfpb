SELECT cc.state,
       count(*) as 'Number of Complaints',
       SUM(acse.B06010_001) as 'Population',
     (count(*)/SUM(acse.B06010_001)) * 100 as 'Complaint Ratio'
  FROM (select state, zip_code from consumer_complaint where zip_code is not null and zip_code <> ' ') cc,
       (select SUM(B06010_001) as B06010_001, geography.ZCTA5 from acs_estimate, geography where acs_estimate.LOGRECNO = geography.LOGRECNO group by geography.ZCTA5) acse
  WHERE acse.ZCTA5 = cc.ZIP_CODE
  GROUP
     BY cc.state
  ORDER
     BY count(*) desc