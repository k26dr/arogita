Arogita Sync API
=============================

**Installation:**

1) Clone the Git repository at https://github.com/k26dr/arogita and run "composer install" on the project root to download the dependencies. If you do not have composer installed, you can download it from https://getcomposer.org.  
2) Database changes have to be made before the API can be used. Run the add_auto_update_fields.php file in the root directory (point your browser to it). This will create updated_on fields for all the necessary tables.  
3) Make sure the MySQL GLOBAL time_zone setting is set to Asia/Kolkata (+05:30) or the sync pull will not function properly. If EasyQueryTest fails at lines 84, 94, and 95, then this is likely the cause of the errors.  
4) Run the tests at test/runAll.php and ensure that all the functionality works on the new server.  
5) An example sync request can be found at test/api_test.php. Open the page, then open the console and run the function test1() in the console to see the results of the test. See the source for the proper request structure.   


**Usage:**  
  
  The push and pull functionalities of the sync API are implemented seperately. They can be used seperately if desired. The sync endpoint is contained at /api.php. The API is accessed by sending a JSON encoded POST request to the endpoint. For a full example, point your browser to /test/api_test.php and follow the instructions contained there. The example request JSON from the api_test.php page is copied below for reference. Explanations for the structure of each of the units is contained below the example.
  
  
  
```
[
    {
        sync: 'auth',
        user: 'admin',
        pass: 'NoLongerUsed',
        client_id: '12321432433'
    },
    {
        sync: 'push',
        table: 'patient_data',
        operation: 'upsert',
        fields: {
            pid: 24,
            mname: "hello"
        }
    },
    {
        sync: 'push',
        table: 'form_camos',
        operation: 'upsert',
        fields: {
            pid: 1001,
            user: 'user',
            groupname: 'teachers'
        }
    },
    {
        sync: 'push',
        table: 'form_camos',
        operation: 'delete',
        where: {pid: 1001}
    },
    {
        sync: 'push',
        table: 'billing',
        operation: 'upsert',
        fields: {
            payer_id: 1235,
            pid: 1000,
            bill_process: 0,
            notecodes: "tf"
        }
    },
    {
        sync: 'push',
        table: 'billing',
        operation: 'delete',
        where: {
            pid: 1000
        }
    },
    {
        sync: 'pull',
        patients: [24, 26, 27],
        last_sync: 0
    }
]
```
  
  
**JSON structure**  
  
  The request JSON is a list of push/pull/auth units to be executed on the database. Each request must contain an auth unit to proceed. Not including a pull unit will throw a MissingAuthUnitException for the pull action, but will not prevent the push units from executing as long as an auth unit is included. The example request JSON from the api_test.php page is copied below for reference. 

**Sync Unit**
The request JSON is structured as a series of sync units. Each sync unit must contain the key 'sync', which can take the values of 'push', 'auth', or 'pull'. If an individual non-auth unit returns an error, the remaining units will still be executed. If the auth unit returns an error, execution is aborted.
  
**Auth Unit**
  
  The auth unit must contain the following fields:
  - user: username
  - pass: password
  - client_id: The Android client ID
  
Authentication is performed against the users table. If you wish to change the database table used for authentication, make the changes in the file /EasyQuery.php in the function EasyQuery::authenticate. If the authentication fails, an error is returned and the remaining units are not evaluated.
  
**Pull Unit**
  
  The pull unit must contain the following fields:
  - patients: an array of database patient ids you would like to pull information for.
  - last_sync: a UNIX timestamp specifiying the time of last sync.

  The pull action returns an array of all updates corresponding to 'patients' that occurred after 'last_sync'. The list is similar in structure to the push units. If the 'patients' array is empty, the unit returns an error. If 'last_sync'=0, all database rows corresponding to 'patients' are returned.
  
**Push Unit**
  
  The push unit allows the client to push database changes to the server. It must contain the following field:
  - operation: 'upsert' or 'delete', all other values will throw an error
  
**Push Upsert**
  
The push upsert operation must contain the following fields
- table: the table must be a valid OpenEMR table and must contain a 'pid' field. A table without a 'pid' field will return an error.
- fields: a key-value array of fields to update. The 'pid' field is mandatory here.
  
and has an optional field

- where: a key-value array of fields which are converted into the SQL phrase WHERE key1=value1, key2=value2,...
  
The update operation first attempts to perform an insert on the listed table with 'fields'. If the insert fails for any reason (missing required fields, duplicate unique key, etc.), it attempts an update with the specified where fields. If no where field is specified, it uses the 'pid' value from 'fields' as 'where'. You can only update one row at a time, so if the where clause does not yield a unique row, it will throw an error.
  
**Push Delete**
  
The push delete operation must contain the following fields
- table: the table must be a valid OpenEMR table and must contain a 'pid' field. A table without a 'pid' field will return an error.
- where: - where: a key-value array of fields which are converted into the SQL phrase WHERE key1=value1, key2=value2,...
  
The where field must yield a unique row, attempts to delete more than one row with a single unit will return an error.
