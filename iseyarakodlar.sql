SET @MIN = '2019-01-01 14:53:27';
SET @MAX = '2019-03-23 14:53:27';
SELECT TIMESTAMPADD(SECOND, FLOOR(RAND() * TIMESTAMPDIFF(SECOND, @MIN, @MAX)), @MIN);
///////////////////////////////////////////
SET @MIN = '2019-01-01 14:53:27';
SET @MAX = '2019-02-23 14:53:27';
///////////////////////////////////////////
UPDATE sites SET lastcontrol=TIMESTAMPADD(SECOND, FLOOR(RAND() * TIMESTAMPDIFF(SECOND, @MIN, @MAX)), @MIN)
///////////////////////////////////////////
SELECT * FROM `sites`  where DATE(lastcontrol) = DATE(CURDATE())