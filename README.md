Arogita Sync API
=============================

Installation:

1) Copy the files into your desired directory.
2) Database changes have to be made before the API can be used. Run the add_auto_update_fields.php file in the root directory (point your browser to it). This will create updated_on fields for all the necessary tables.
3) Run the tests at test/runAll.php and ensure that all the functionality works on the new server.
4) An example sync request can be found at test/api_test.php. Open the page, then open the console and run the function test1() in the console to see the results of the test. See the source for the proper request structure. 

