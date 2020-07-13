<?php

use Carbon\Carbon;

require_once 'init.php';

if(isset($_POST['add_category']))
{
    //USER HAS REQUESTED TO ADD A NEW CATEGORY
    
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('category')->addCategory($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting record, please try again later!');
                Util::redirect('manage-category.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('manage-category.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-category.php');
                break;
        }
    }
}

if(isset($_POST['add_customer']))
{
    //USER HAS REQUESTED TO ADD A NEW CUSTOMER
    //Util::dd($_POST);
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('customer')->addCustomer($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting record, please try again later!');
                Util::redirect('manage-customer.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('manage-customer.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-customer.php');
                break;
        }
    }
}
if(isset($_POST['add_employee']))
{
    //USER HAS REQUESTED TO ADD A NEW CUSTOMER
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('employee')->addEmployee($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting record, please try again later!');
                Util::redirect('manage-employee.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('manage-employee.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-employee.php');
                break;
        }
    }
}
if(isset($_POST['page']) && $_POST['page'] == 'manage_category')
{
    $search_parameter = $_POST['search']['value'] ?? null;
    $order_by = $_POST['order'] ?? null;
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];
        $di->get('category')->getJSONDataForDataTable($draw,$search_parameter,$order_by,$start,$length);
    
}
if(isset($_POST['fetch']) && $_POST['fetch'] == 'category')
{
    $category_id = $_POST['category_id'];
    $result = $di->get('category')->getCategoryById($category_id,PDO::FETCH_ASSOC);
//    Util::dd($result);
    echo json_encode($result);
}
if(isset($_POST['edit_category']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('category')->update($_POST,$_POST['category_id']);
        switch($result)
        {
            case EDIT_ERROR:
                Session::setSession(EDIT_ERROR, 'There was problem while editing record, please try again later!');
                Util::redirect('manage-category.php');
                break;
            case EDIT_SUCCESS:
                Session::setSession(EDIT_SUCCESS, 'The record has been added successfully!');
                // Util::dd();
                Util::redirect('manage-category.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('manage-category.php');
                break;
        }
    }
}
if(isset($_POST['delete_category']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
//        Util::dd($_POST['record_id']);
        $result = $di->get('category')->delete($_POST['record_id']);
        switch($result)
        {
            case DELETE_ERROR:
                Session::setSession(DELETE_ERROR, 'There was problem while deleting record, please try again later!');
                Util::redirect('manage-category.php');
                break;
            case DELETE_SUCCESS:
                Session::setSession(DELETE_SUCCESS, 'The record has been deleted successfully!');
                // Util::dd();
                Util::redirect('manage-category.php');
                break;
        }
    }
}
if(isset($_POST['page']) && $_POST['page'] == 'manage_customer')
{
    //Util::dd($_POST);
    $search_parameter = $_POST['search']['value'] ?? null;
    $order_by = $_POST['order'] ?? null;
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];
    $di->get('customer')->getJSONDataForDataTable($draw,$search_parameter,$order_by,$start,$length);
}
if(isset($_POST['fetch']) && $_POST['fetch'] == 'customer')
{
    $customer_id = $_POST['customer_id'];
    $result = $di->get('customer')->getCustomerById($customer_id,PDO::FETCH_ASSOC);
//    Util::dd($result);
    echo json_encode($result);
}
if(isset($_POST['edit_customer']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('customer')->update($_POST,$_POST['customer_id']);
        switch($result)
        {
            case EDIT_ERROR:
                Session::setSession(EDIT_ERROR, 'There was problem while editing record, please try again later!');
                Util::redirect('manage-customer.php');
                break;
            case EDIT_SUCCESS:
                Session::setSession(EDIT_SUCCESS, 'The record has been added successfully!');
                // Util::dd();
                Util::redirect('manage-customer.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('manage-customer.php');
                break;
        }
    }
}
if(isset($_POST['delete_customer']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
//        Util::dd($_POST['record_id']);
        $result = $di->get('customer')->delete($_POST['record_id']);
        switch($result)
        {
            case DELETE_ERROR:
                Session::setSession(DELETE_ERROR, 'There was problem while deleting record, please try again later!');
                Util::redirect('manage-customer.php');
                break;
            case DELETE_SUCCESS:
                Session::setSession(DELETE_SUCCESS, 'The record has been deleted successfully!');
                // Util::dd();
                Util::redirect('manage-customer.php');
                break;
        }
    }
}

if(isset($_POST['page']) && $_POST['page'] == 'manage_employee')
{
    $search_parameter = $_POST['search']['value'] ?? null;
    $order_by = $_POST['order'] ?? null;
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];
    $di->get('employee')->getJSONDataForDataTable($draw,$search_parameter,$order_by,$start,$length);
}
if(isset($_POST['fetch']) && $_POST['fetch'] == 'employee')
{
    $employee_id = $_POST['employee_id'];
    $result = $di->get('employee')->getEmployeeById($employee_id,PDO::FETCH_ASSOC);
//    Util::dd($result);
    echo json_encode($result);
}
if(isset($_POST['edit_employee']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('employee')->update($_POST,$_POST['employee_id']);
        switch($result)
        {
            case EDIT_ERROR:
                Session::setSession(EDIT_ERROR, 'There was problem while editing record, please try again later!');
                Util::redirect('manage-employee.php');
                break;
            case EDIT_SUCCESS:
                Session::setSession(EDIT_SUCCESS, 'The record has been added successfully!');
                // Util::dd();
                Util::redirect('manage-employee.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('manage-employee.php');
                break;
        }
    }
}
if(isset($_POST['delete_employee']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
//        Util::dd($_POST['record_id']);
        $result = $di->get('employee')->delete($_POST['record_id']);
        switch($result)
        {
            case DELETE_ERROR:
                Session::setSession(DELETE_ERROR, 'There was problem while deleting record, please try again later!');
                Util::redirect('manage-employee.php');
                break;
            case DELETE_SUCCESS:
                Session::setSession(DELETE_SUCCESS, 'The record has been deleted successfully!');
                // Util::dd();
                Util::redirect('manage-employee.php');
                break;
        }
    }
}

if(isset($_POST['page']) && $_POST['page'] == 'manage_product')
{
    $search_parameter = $_POST['search']['value'] ?? null;
    $order_by = $_POST['order'] ?? null;
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];
    $di->get('product')->getJSONDataForDataTable($draw,$search_parameter,$order_by,$start,$length);
}
if(isset($_POST['fetch']) && $_POST['fetch'] == 'product')
{
    $product_id = $_POST['product_id'];
    $result = $di->get('product')->getProductById($product_id,PDO::FETCH_ASSOC);
//    Util::dd($result);
    echo json_encode($result);
}


if(isset($_POST['edit_product']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('product')->update($_POST,$_POST['product_id']);
        switch($result)
        {
            case EDIT_ERROR:
                Session::setSession(EDIT_ERROR, 'There was problem while editing record, please try again later!');
                Util::redirect('manage-product.php');
                break;
            case EDIT_SUCCESS:
                Session::setSession(EDIT_SUCCESS, 'The record has been added successfully!');
                // Util::dd();
                Util::redirect('manage-product.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('manage-product.php');
                break;
        }
    }
}
if(isset($_POST['delete_product']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
//        Util::dd($_POST['record_id']);
        $result = $di->get('product')->delete($_POST['record_id']);
        switch($result)
        {
            case DELETE_ERROR:
                Session::setSession(DELETE_ERROR, 'There was problem while deleting record, please try again later!');
                Util::redirect('manage-product.php');
                break;
            case DELETE_SUCCESS:
                Session::setSession(DELETE_SUCCESS, 'The record has been deleted successfully!');
                // Util::dd();
                Util::redirect('manage-product.php');
                break;
        }
    }
}


if(isset($_POST['add_product']))
{
    //USER HAS REQUESTED TO ADD A NEW PRODUCT
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('product')->addProduct($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting record, please try again later!');
                Util::redirect('manage-product.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('manage-product.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession(VALIDATION_ERROR, 'There was some problem in validating your data at server side!');
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-product.php');
                break;
        }
    }
    
}
if(isset($_POST['getCategories'])){
    echo json_encode($di->get('category')->all());
}
if(isset($_POST['getProductsByCategoryID'])){
    $category_id = $_POST['categoryID'];
    echo json_encode($di->get('product')->getProductsByCategoryID($category_id));
}

if(isset($_POST['getSellingPrice'])){
    $product_id = $_POST['productID'];
    echo json_encode($di->get('product')->getSellingPriceByProductID($product_id));
    
}

if(isset($_POST['getFinalRate'])){
    $sp=$_POST['sp'];
    $q=$_POST['q'];
    echo json_encode($sp*$q);
}

if(isset($_POST['getDiscountedFinalRate'])){
    $fr = $_POST['fr'];
    $disc=$_POST['disc'];
    echo json_encode($di->get('product')->getDiscountedPrice($fr, $disc));
}

if(isset($_POST['email'])){
    $email = $_POST['email'];
    echo json_encode($di->get('customer')->VerifyEmail($email));
}

if(isset($_POST['editSellingPrice'])){
    $product_id = $_POST['product_id'];
    $new_sp = $_POST['new_sp'];
    $wef = Carbon::now()->format('Y/m/d H:i:s');
    echo json_encode($di->get('product')->editSellingPrice($product_id, $new_sp, $wef));
}

if(isset($_POST['getSuppliers'])){
    echo json_encode($di->get('supplier')->all());
}

if(isset($_POST['getCategoriesBySupplierId'])){
    //echo json_encode("HI");
    $id=$_POST['supplierId'];
    echo json_encode($di->get('category')->getCategoriesBySupplierId($id));
}

if(isset($_POST['getProductsByCategoryIDandSupplierID'])){
    $s_id = $_POST['supplierId'];
    $c_id = $_POST['categoryId'];    
    echo json_encode($di->get('category')->getProductsByCategoryIDandSupplierID($c_id, $s_id));
}

if(isset($_POST['confirm-purchase']))
{
    //ADDING SALES
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('purchase')->addPurchase($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting sales, please try again later!');
                Util::redirect('add-purchase.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('add-purchase.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-purchase.php');
                break;
        }
    }
}


if(isset($_POST['confirm']))
{
    //ADDING SALES
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('sales')->addSales($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting sales, please try again later!');
                Util::redirect('add-sales.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('view-sales.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-sales.php');
                break;
        }
    }
}


if(isset($_GET['session_id'])){
    \Stripe\Stripe::setApiKey('sk_test_51H1CK7EDb3WIdKcX7KeoXjGoaYoVKwnDnJkeIgtoBIvEmahxc8j4eqKw98DxV1krqONc1MCcBY4u9KNI8xfhfk5u00I9PW3ahN');

$stripe = new \Stripe\StripeClient(
    'sk_test_51H1CK7EDb3WIdKcX7KeoXjGoaYoVKwnDnJkeIgtoBIvEmahxc8j4eqKw98DxV1krqONc1MCcBY4u9KNI8xfhfk5u00I9PW3ahN'
  );
 $session= $stripe->checkout->sessions->retrieve(
      $_GET['session_id']
      
  );
//   Util::dd($_REQUEST);
  Util::dd($session);
  $pm=$stripe->paymentMethods->create([
    'type' => $session['payment_method_types'][0],
    $session['payment_method_types'][0] => [
      'number' => '4242424242424242',
      'exp_month' => 7,
      'exp_year' => 2021,
      'cvc' => '314',
    ],
  ]);
$customer = \Stripe\Customer::create([
  'name' => 'jenny rosen',
  'email' => 'priya.karsi1918@gmail.com',
  'address' => [
      'city'=>'unr',
      'country'=>'BY',
      'line1'=>'line1'
  ],
  'payment_method'=>$pm->id,
  'description' => 'My First Test Customer (created for API docs)'
]);

//var_dump($customer);
$stripe = new \Stripe\StripeClient(
    'sk_test_51H1CK7EDb3WIdKcX7KeoXjGoaYoVKwnDnJkeIgtoBIvEmahxc8j4eqKw98DxV1krqONc1MCcBY4u9KNI8xfhfk5u00I9PW3ahN'
  );
  $product=$stripe->products->create([
    'name' => 'Gold Special',
  ]);
  

\Stripe\InvoiceItem::create([
    'customer' => $customer->id,
    // 'price' => 'price_CBb6IXqvTLXp3f',
    //'payment_method_types' => ['card'],
    'price_data' => [
        'currency' => 'usd',
        'product' => $product->id,
        'unit_amount' => 2000,
      ],
  ]);
  
  $invoice = \Stripe\Invoice::create([
    'customer' => $customer->id,
    'collection_method' => 'send_invoice',
    'days_until_due' => 1,
    'auto_advance' => true, /* auto-finalize this draft after ~1 hour */
  ]);

$invoice->finalizeInvoice();

$stripe->invoices->sendInvoice(
    $invoice->id,
  );
}
if(isset($_POST['add_supplier']))
{
    //USER HAS REQUESTED TO ADD A NEW CUSTOMER
    //Util::dd($_POST);
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('supplier')->addSupplier($_POST);
        switch($result)
        {
            case ADD_ERROR:
                Session::setSession(ADD_ERROR, 'There was problem while inserting record, please try again later!');
                Util::redirect('manage-supplier.php');
                break;
            case ADD_SUCCESS:
                Session::setSession(ADD_SUCCESS, 'The record have been added successfully!');
                // Util::dd();
                Util::redirect('manage-supplier.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('add-supplier.php');
                break;
        }
    }
}

if(isset($_POST['page']) && $_POST['page'] == 'manage_supplier')
{
    //Util::dd($_POST);
    $search_parameter = $_POST['search']['value'] ?? null;
    $order_by = $_POST['order'] ?? null;
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];
    $di->get('supplier')->getJSONDataForDataTable($draw,$search_parameter,$order_by,$start,$length);
}

if(isset($_POST['fetch']) && $_POST['fetch'] == 'supplier')
{
    $supplier_id = $_POST['supplier_id'];
    $result = $di->get('supplier')->getSupplierById($supplier_id,PDO::FETCH_ASSOC);
//    Util::dd($result);
    echo json_encode($result);
}

if(isset($_POST['edit_supplier']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
        $result = $di->get('supplier')->update($_POST,$_POST['supplier_id']);
        switch($result)
        {
            case EDIT_ERROR:
                Session::setSession(EDIT_ERROR, 'There was problem while editing record, please try again later!');
                Util::redirect('manage-supplier.php');
                break;
            case EDIT_SUCCESS:
                Session::setSession(EDIT_SUCCESS, 'The record has been added successfully!');
                // Util::dd();
                Util::redirect('manage-supplier.php');
                break;
            case VALIDATION_ERROR:
                Session::setSession('errors', serialize($di->get('validator')->errors()));
                Session::setSession('old', $_POST);
                Util::redirect('manage-supplier.php');
                break;
        }
    }
}

if(isset($_POST['delete_supplier']))
{
    if(isset($_POST['csrf_token']) && Util::verifyCSRFToken($_POST))
    {
//        Util::dd($_POST['record_id']);
        $result = $di->get('supplier')->delete($_POST['record_id']);
        switch($result)
        {
            case DELETE_ERROR:
                Session::setSession(DELETE_ERROR, 'There was problem while deleting record, please try again later!');
                Util::redirect('manage-supplier.php');
                break;
            case DELETE_SUCCESS:
                Session::setSession(DELETE_SUCCESS, 'The record has been deleted successfully!');
                // Util::dd();
                Util::redirect('manage-supplier.php');
                break;
        }
    }
}

if(isset($_POST['monthly']) && isset($_POST['fetch']) && $_POST['fetch']==='monthly'){
    
}
