AJAX testing of the Arogita Sync API

<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>

<script>
var url = "../api.php";

function test1 () {
    var data = [
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
                mname: parseInt(new Date().getTime() / 100000) + "me"
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
                payer_id: 1232,
                pid: 1000,
                bill_process: 0,
                notecodes: "tf"
            }
        },
        {
            sync: 'pull',
            patients: [24, 26, 27],
            last_sync: new Date().getTime() / 1000 - 100000
        }
    ];
    $.post(url, JSON.stringify(data)).always(function(r) {
        console.log(r);
    });
}
</script>