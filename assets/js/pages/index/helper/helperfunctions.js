var Charts = function(){
    var monthlyBarChart = function(){
        var baseURL = window.location.origin;
        var filePath = "/helper/routing.php";
        $.ajax({
            url: baseURL + filePath,
            method: "POST",
            data: {
                "monthly": true,
                "fetch": "sales"
            },
            dataType : "json",
            success: function(data){
                console.log(data);
               
            }
        });
    }
    return{
        init: function(){
            monthlyBarChart();
        }
    }
}();
jQuery(document).ready(function(){
    Charts.init();
})