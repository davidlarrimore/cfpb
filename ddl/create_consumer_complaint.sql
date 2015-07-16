CREATE TABLE consumer_complaint(
   DATE_RECEIVED                DATE
  ,PRODUCT                      VARCHAR(13)
  ,SUBPRODUCT                   VARCHAR(34)
  ,ISSUE                        VARCHAR(40)
  ,SUBISSUE                     VARCHAR(1)
  ,CONSUMER_COMPLAINT_NARRATIVE VARCHAR(1)
  ,COMPANY_PUBLIC_RESPONSE      VARCHAR(72)
  ,COMPANY                      VARCHAR(17)
  ,STATE                        VARCHAR(2)
  ,ZIP_CODE                     VARCHAR(5) 
  ,SUBMITTED_VIA                VARCHAR(5) 
  ,DATE_SENT_TO_COMPANY         DATE
  ,COMPANY_RESPONSE_TO_CONSUMER VARCHAR(23) 
  ,TIMELY_RESPONSE              VARCHAR(3) 
  ,CONSUMER_DISPUTED            VARCHAR(2)
  ,COMPLAINT_ID                 INTEGER(7) NOT NULL PRIMARY KEY
);