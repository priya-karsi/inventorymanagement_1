var TableDataTables = function(){
    var handleCustomerTable = function(){
        var manageCustomerTable = $("#manage-customer-datatable");
        var baseURL = window.location.origin;
        var filePath = "/helper/routing.php";
        var oTable = manageCustomerTable.dataTable({
            "processing": true,
            "serverSide":true,
            "ajax": {
                url: baseURL + filePath,
                method: "POST",
                data: {
                    "page": "manage_customer"
                }
            },
            "lengthMenu": [
                [5,10,20,-1],
                [5,10,20,"All"]
            ],
            "order": [
                [1,"ASC"]
            ],
            "columnDefs": [{
                'orderable': false,
                'targets': [0,-1]
            }],
        });
        
        manageCustomerTable.on('click','.edit',function(){
            id = $(this).attr('id');
            $("#customer_id").val(id);
            $.ajax({
                url: baseURL + filePath,
                method: "POST",
                data: {
                    "customer_id": id,
                    "fetch": "customer"
                },
                dataType : "json",
                success: function(data){
                    console.log(data);
                    $("#customer_name").val(data[0].name);
                }
            });
        });
        
        manageCustomerTable.on('click','.delete',function(){
            id = $(this).attr('id');
            $("#record_id").val(id);
            $.ajax({
                url: baseURL + filePath,
                method: "POST",
                data: {
                    "customer_id": id,
                    "fetch": "customer"
                },
                dataType: "json",
                success: function(data){
                }
            });
        });
    }
    return{
        init: function(){
            handleCustomerTable();
        }
    }
}();
jQuery(document).ready(function(){
    TableDataTables.init();
})