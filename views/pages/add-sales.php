<?php
require_once __DIR__ . '/../../helper/init.php';
$pageTitle = "Easy ERP | Add Category";
$sidebarSection = "transaction";
$sidebarSubSection = "sales";
Util::createCSRFToken();
$errors = "";
$old="";
if(Session::hasSession('errors'))
{
  $errors = unserialize(Session::getSession('errors'));
  Session::unsetSession('errors');
}
if(Session::hasSession('old'))
{
  $old = Session::getSession('old');
  Session::unsetSession('old');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once __DIR__ . "/../includes/head-section.php";
  ?>
  <style>
      .email-verify{
          background: green;
          color: #FFF;
          padding: 5px 10px;
          font-size: .875 rem;
          line-height: 1.5;
          border-radius: .2rem;
          vertical-align:middle;
          /*display: none !important;*/
      }
</style>
  <!--PLACE TO ADD YOUR CUSTOM CSS-->

</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php require_once(__DIR__ . "/../includes/sidebar.php"); ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <?php require_once(__DIR__ . "/../includes/navbar.php"); ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sales</h1>
            <a href="<?= BASEPAGES; ?>manage-category.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fa fa-list-ul fa-sm text-white-75"></i> Manage Sales
            </a>
          </div>


          
          <div class="card shadow mb-4">
            <div class="col-lg-12">
              <!-- Basic Card Example -->
              <!--CARD HEADER-->
              <div class="card-header py-3 px-2 d-flex flex-row justify-content-end">
                <div class="mr-3">
                  <input type="text" class="form-control" name="email" id="customer_email"
                  placeholder="enter email of customer">
                </div>
                <div>
                <p class="email-verify" id="email_verify_success">
                <i class="fas fa-check fa-sm text-white mr-1"></i>Email Verified
                </p>
                <p class="email-verify bg-danger mb-0" id="email_verify_fail">
                <i class="fas fa-times fa-sm text-white mr-1"></i>Email not verified
                </p>

                <a href="<?=BASEPAGES;?>add-customer.php"
                class="btn btn-sm btn-warning shadow-sm"
                id="add_customer_btn"
          
                ><i class="fas fa-users fa-sm text-white"></i>Add Customer</a>
                <button type="button" onclick="checkEmail();" class="d-sm-inline-block btn btn-primary shadow-sm"
                name="check_email" id="check_email">
                <i class="fas fa-envelope fa-sm text-white"></i>Check Email                  
                </button>
                </div>
              </div>
  
             <div class="card-header py-3 d-flex flex-row align-items-center justify-content-lg-between">
               <h6 class="m-0 font-weight-bold text-primary">
                 <i class="fas fa-plus"></i>Sales
               </h6>
                  <button type="button"
                  onclick="addProduct();"
                  class="d-sm-inline-block btn btn-secondary shadow-sm">
                  <i class="fas fa-plus fa-sm text-white"></i>Add one more product
                  </button>
                </div>
                <!--END OF CARD HEADER-->

              <form action="../../helper/Routing.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?=Session::getSession('csrf_token');?>">
                <input type="text" name="customer_id" id="customer_id">
                <div class="card-body">
                  <div id="products_container">
                    <!-- Begin product custom control-->
                    <div class="row product_row" id="element_1">
                      <!--Begin category select-->
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="category_1">Category</label>
                          <select id="category_1" class="form-control category_select">
                          <option disabled selected>Select Category</option>
                          <?php
                          $categories = $di->get('database')->readData('category', ['id','name'], "deleted=0");
                          foreach($categories as $category){
                              echo "<option value='{$category->id}'>{$category->name}</option>";
                          }
                          ?>
                          </select>
                        </div>

                      </div>
                      <!--End category select-->
                      <!--Begin product -->
                      <div class="col-md-3">
                          <div class="form-group">
                              <label for="">Products</label>
                              <select name="product_id[]" id="product_1" class="form-control product_select">
                                  <option disabled selected>Select Product</option>
                              </select>
                          </div>

                      </div>
                      <!--End product -->

                      <!--Begin selling price -->
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="">Selling Price</label>
                              <input type="text" name="selling_price[]" id="selling_price_1"
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
                              <input type="number" value=0 name="quantity[]" id="quantity_1"
                              class="form-control quantity_ip"
                              placeholder="Enter Quantity">                              
                          </div>
                      </div>
                      <!--End quantity -->
                      <!--Begin discount -->
                      <div class="col-md-1">
                          <div class="form-group">
                              <label for="">Discount</label>
                              <input type="number" value =0 name="discount[]" id="discount_1" class="form-control discount_ip" 
                              placeholder="Enter Disount">
                          </div>
                      </div>
                      <!--End discount -->
                      
                      <!--Begin final rate -->
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="">Final Rate</label>
                              <input type="text" name="final_rate[]" id="final_rate_1"
                              class="form-control"
                              disabled
                              value="0"
                              >
                          </div>
                      </div>
                      <!--End final price-->
                      <!--Begin delete button-->
                      <div class="col-md-1">
                          <button onclick="deleteProduct(1)"
                          type="button"
                          class="btn btn-danger"
                          style="margin-top:40%"
                          >
                          <i class="far fa-trash-alt"></i>
                          </button>                          
                      </div>
                      <!--End delete button-->
                    </div>
                    <!--end product custom control-->
                  </div>
                  

                </div>

                <!--End of card body-->
                <!-- begin card footer-->
                <div class="d-flex card-footer justify-content-between">
                  <div>
                  <input type="submit" class="btn btn-primary" name="submit" value="submit">
                        </div>
                  <div class="form-group row">
                    <label for="finalTotal" class="col-sm-4 col-form-label">Final Total</label>
                    <div class="col-sm-8">
                      <input type="number" readonly class="form-control" id="finalTotal" value=0>
                    </div>
                  </div>
                </div>
              </form>
    </div>
    <!--end basic card-->


            </div>
          </div>
        
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      <div>
      <?php require_once(__DIR__ . "/../includes/footer.php"); ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <?php
  require_once(__DIR__ . "/../includes/scroll-to-top.php");
  ?>
  <?php require_once(__DIR__ . "/../includes/core-scripts.php"); ?>
  
  <!--PAGE LEVEL SCRIPTS-->
  <?php require_once(__DIR__ . "/../includes/page-level/transactions/add-sales-scripts.php");?>
</body>

</html>