Arogita Sync API
=============================

**Installation:**

1) Clone the Git repository at https://github.com/k26dr/arogita and run "composer install" on the project root to download the dependencies. If you do not have composer installed, you can download it from https://getcomposer.org.  
2) Database changes have to be made before the API can be used. Run the add_auto_update_fields.php file in the root directory (point your browser to it). This will create updated_on fields for all the necessary tables.  
3) Make sure the MySQL GLOBAL time_zone setting is set to Asia/Kolkata (+05:30) or the sync pull will not function properly. If EasyQueryTest fails at lines 84, 94, and 95, then this is likely the cause of the errors.  
4) Run the tests at test/runAll.php and ensure that all the functionality works on the new server.  
5) An example sync request can be found at test/api_test.php. Open the page, then open the console and run the function test1() in the console to see the results of the test. See the source for the proper request structure.   


** Usage: **  
  
  The push and pull functionalities of the sync API are implemented seperately. They can be used seperately if desired. The sync endpoint is contained at /api.php. The API is accessed by sending a JSON encoded POST request to the endpoint. For a full example, point your browser to /test/api_test.php and follow the instructions contained there.   
  
** JSON structure **  
  
  The request JSON is a list of push/pull/auth units to be executed on the database. Each request must contain an auth unit to proceed. Not including a pull unit wil throw a MissingAuthUnitException for the pull action, but will not prevent the push units from executing as long as an auth unit is included.   
  
