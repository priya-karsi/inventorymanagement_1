
  
var id=2;
var baseURL = window.location.origin;
var filePath = "/helper/Routing.php";

function checkEmail(){
  var email = document.getElementById("customer_email").value;
  //console.log(email);
  $.ajax({
    url: baseURL+filePath,
    method: 'POST',
    data: {
      email: email
    },
    dataType: 'json',
    success: function(is_verified){
        console.log(is_verified);
      if(is_verified != false){
        console.log(is_verified);
        document.getElementById('customer_id').value=is_verified[0]["id"];
        $('#email_verify_fail').addClass('d-none');
        $('#add_customer_btn').addClass('d-none');
        $('#email_verify_success').removeClass('d-none');

      }
      else{
        $('#email_verify_fail').removeClass('d-none');
      $('#add_customer_btn').removeClass('d-none');
      $('#email_verify_success').addClass('d-none');
      }
      

}
    
  });
}

function deleteProduct(delete_id) {
    var elements = document.getElementsByClassName("product_row");
    if(elements.length > 1) {
      var final_price = document.getElementById("final_rate_"+delete_id).value;
    var final_total = document.getElementById("finalTotal").value;
    console.log(final_price, final_total);
    document.getElementById("finalTotal").value = final_total-final_price;
        $("#element_"+delete_id).remove();
    }
}

function addProduct() {
    $("#products_container").append(

        `   <!-- BEGIN: PRODUCT CUSTOM CONTROL -->
        <div class="row product_row" id="element_${id}">
          <!-- BEGIN: CATEGORY SELECT -->
          <div class="col-md-2">
              <div class="form-group">
                  <label for="category_${id}">Category</label>
                  <select id="category_${id}" class="form-control category_select">
                      <option disabled selected>Select Category</option>
                
                  </select>
              </div>
          </div>
          <!-- END: CATEGORY SELECT -->
          <!-- BEGIN: PRODUCT SELECT -->
          <div class="col-md-3">
              <div class="form-group">
                  <label for="product_${id}">Products</label>
                  <select name="product_id[]" id="product_${id}" class="form-control product_select">
                      <option disabled selected>Select Product</option>
                  </select>
              </div>
          </div>
          <!-- END: PRODUCT SELECT -->
          <!--Begin selling price -->
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="">Selling Price</label>
                              <input type="text" name="selling_price[]" id="selling_price_${id}"
                              class="form-control"
                              disabled
                              >
                          </div>
                      </div>
                      <!--End selling price-->

                      <!--Begin quantity -->
                      <div class="col-md-1">
                          <div class="form-group">
                              <label for="">Quantity</label>
                              <input type="number" value=0 name="quantity[]" id="quantity_${id}"
                              class="form-control quantity_ip"
                              placeholder="Enter Quantity">                              
                          </div>
                      </div>
                      <!--End quantity -->
                      <!--Begin discount -->
                      <div class="col-md-1">
                          <div class="form-group">
                              <label for="">Discount</label>
                              <input type="number" value =0 name="discount[]" id="discount_${id}" class="form-control discount_ip" 
                              placeholder="Enter Disount">
                          </div>
                      </div>
                      <!--End discount -->
                      
                      <!--Begin final rate -->
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="">Final Rate</label>
                              <input type="text" name="final_rate[]" id="final_rate_${id}"
                              class="form-control"
                              disabled
                              value="0"
                              >
                          </div>
                      </div>
                      <!--End final price-->
                      <!--Begin delete button-->
                      <div class="col-md-1">
                          <button onclick="deleteProduct(${id})"
                          type="button"
                          class="btn btn-danger"
                          style="margin-top:40%"
                          >
                          <i class="far fa-trash-alt"></i>
                          </button>                          
                      </div>
                      <!--End delete button-->        `
);


    $.ajax({
        url: baseURL+filePath,
        method: 'POST',
        data: {
            getCategories: true
        },
        dataType: 'json',
        success: function(categories){
            categories.forEach(function(category){
                $("#category_"+id).append(
                    `<option value='${category.id}'>${category.name}</option>`
                );
            });
            id++;
        }
    });
}

$('#products_container').on('change', '.category_select', function(){
    var element_id = $(this).attr('id').split("_")[1];
    var category_id = this.value;
    //console.log(element_id, category_id);
    $.ajax({
        url: baseURL+filePath,
        method: 'POST',
        data: {
            getProductsByCategoryID: true,
            categoryID: category_id
        },
        dataType: 'json',
        success:function(products){
            $('#product_'+element_id).empty();
            $('#product_'+element_id).append(`<option disabled selected>Select Product</option>`);
            products.forEach(function(product){
                $("#product_"+element_id).append(
                    `<option value='${product.id}'>${product.name}</option>`
                );
            });
        }
    });

});

$('#products_container').on('change', '.product_select',function(){
    var element_id = $(this).attr('id').split("_")[1];
    var product_id = this.value;
    console.log(element_id, product_id);
    $.ajax({
        url: baseURL+filePath,
        method: 'POST',
        data: {
            getSellingPrice: true,
            productID: product_id
        },
        dataType: 'json',
        success: function(sellingprice){
            document.getElementById("selling_price_"+element_id).value=sellingprice;
        }

    })
});

$('#products_container').on('change','.quantity_ip', function(){
    var element_id = $(this).attr('id').split("_")[1];
    if($(this).val()=="" || parseInt($(this).val()) <=0){
      console.log("HI");
      $(this).addClass("text-field-error");
      return;
    }
    $(this).removeClass("text-field-error");
    //console.log(element_id, product_id);
    var sp=document.getElementById('selling_price_'+element_id).value;
    var q=document.getElementById('quantity_'+element_id).value;
    var total=document.getElementById('finalTotal').value;
    console.log(total);
    $.ajax({
        url: baseURL+filePath,
        method: 'POST',
        data: {
            getFinalRate: true,
            sp: sp,
            q: q
        },
        dataType: 'json',
        success: function(ans){
            document.getElementById('final_rate_'+element_id).value = ans;
            var final_rate_arr=document.getElementsByName('final_rate[]');
            var total=0;
            for(var i=0;i<final_rate_arr.length;i++){
                if(parseInt(final_rate_arr[i].value))
                    total += parseInt(final_rate_arr[i].value);
            }
            document.getElementById('finalTotal').value=total; 
        }
    })
});

$('#products_container').on('change','.discount_ip',function(){
    var element_id = $(this).attr('id').split("_")[1];
    //console.log(element_id, product_id);
    if($(this).val()=="" || parseInt($(this).val()) <=0){
      console.log("HI");
      $(this).addClass("text-field-error");
      return;
    }
    $(this).removeClass("text-field-error");
    
    var sp=document.getElementById('selling_price_'+element_id).value;
    var q=document.getElementById('quantity_'+element_id).value;
    var total=document.getElementById('finalTotal').value;

    fr=sp*q;
    var disc=document.getElementById('discount_'+element_id).value;
    //console.log(fr,disc);
    $.ajax({
        url: baseURL+filePath,
        method: 'POST',
        data: {
            getDiscountedFinalRate: true,
            fr: fr,
            disc: disc
        },
        dataType: 'json',
        success: function(ans){
            document.getElementById('final_rate_'+element_id).value = ans;
            var final_rate_arr=document.getElementsByName('final_rate[]');
            var total=0;
            for(var i=0;i<final_rate_arr.length;i++){
                if(parseInt(final_rate_arr[i].value))
                    total += parseInt(final_rate_arr[i].value);
            }
            document.getElementById('finalTotal').value=total;
        }
    })
});

