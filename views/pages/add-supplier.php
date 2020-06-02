<?php
require_once __DIR__ . '/../../helper/init.php';
$pageTitle = "Easy ERP | Add Customers";
$sidebarSection = "customer";
$sidebarSubSection = "add-customer";
Util::createCSRFToken();
$errors = "";
if(Session::hasSession('errors'))
{
  $errors = unserialize(Session::getSession('errors'));
  Session::unsetSession('errors');
}
$old = "";
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
            <h1 class="h3 mb-0 text-gray-800">Customer</h1>
            <a href="<?= BASEPAGES; ?>manage-customer.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fa fa-list-ul fa-sm text-white-75"></i> Manage Customer
            </a>
          </div>

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Add Customer</h6>
                </div>
                <div class="card-body">
                  <div class="col-md-12">

                    <form action="<?=BASEURL;?>helper/routing.php" method="POST" id="add-customer">
                      <input type="hidden" name="csrf_token" value="<?= Session::getSession('csrf_token');?>">
                      <!--FORM GROUP-->
                      <div class="form-group">
                        <label for="name">First Name</label>
                        <input  type="text" 
                                name="first_name" 
                                id="first_name" 
                                class="form-control <?= $errors!='' && $errors->has('first_name') ? 'error' : '';?>"
                                placeholder = "Enter First Name"
                                value="<?=$old != '' && isset($old['first_name']) ?$old['first_name']: '';?>"/>
                        <label for="name">Last Name</label>
                        <input  type="text" 
                                name="last_name" 
                                id="last_name" 
                                class="form-control <?= $errors!='' && $errors->has('last_name') ? 'error' : '';?>"
                                placeholder = "Enter Last Name"
                                value="<?=$old != '' && isset($old['last_name']) ?$old['last_name']: '';?>"/>
                        <label for="name">GST Number</label>
                        <input  type="text" 
                                name="gst_no" 
                                id="gst_no" 
                                class="form-control <?= $errors!='' && $errors->has('gst_no') ? 'error' : '';?>"
                                placeholder = "Enter GST Number"
                                value="<?=$old != '' && isset($old['gst_no']) ?$old['gst_no']: '';?>"/>
                        <?php
                          if($errors!="" && $errors->has('first_name'))
                          {
                            echo "<span class='error'>{$errors->first('first_name')}</span>";
                          }
                          if($errors!="" && $errors->has('last_name'))
                          {
                            echo "<span class='error'>{$errors->first('last_name')}</span>";
                          }
                          if($errors!="" && $errors->has('gst_no'))
                          {
                            echo "<span class='error'>{$errors->first('gst_no')}</span>";
                          }
                        ?>
                      </div>
                      <!--/FORM GROUP-->
                      <button type="submit" class="btn btn-primary" name="add_customer" value="addCustomer"><i class="fa fa-check"></i> Submit</button>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
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
  <?php require_once(__DIR__ . "/../includes/page-level/customer/add-customer-scripts.php");?>
</body>

</html>