ALTER TABLE `cfpb`.`consumer_complaint` 
ADD INDEX `cc_join_index` (`STATE` ASC, `ZIP_CODE` ASC);
