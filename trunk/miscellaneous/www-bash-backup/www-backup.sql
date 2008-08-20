UPDATE wcf1_cronjobs
   SET active = 0
 WHERE classPath LIKE '%/InactiveUserQuitCronjob.class.php';

UPDATE wcf1_option
   SET optionValue = 'WBB3 Test-Board'
 WHERE optionName = 'mail_from_name';

UPDATE wcf1_option
   SET optionValue = 'WBB3 Test-Board'
 WHERE optionName = 'page_title';

UPDATE wcf1_option
   SET optionValue = 'http://YOUR_LOCAL_URL'
 WHERE optionName = 'page_url';

UPDATE wcf1_option
   SET optionValue = 'YOUR_GOOGLE_MAP_KEY'
 WHERE optionName = 'map_api';

UPDATE wcf1_cronjobs
   SET active = 0
 WHERE classPath LIKE '%/AdminToolsCronjob.class.php';
