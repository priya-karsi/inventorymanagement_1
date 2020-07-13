<?php

require_once __DIR__."/../helper/requirements.php";

class Sales{
    private $table = "sales";
    private $database;
    protected $di;
    
    public function __construct(DependencyInjector $di)
    {
        $this->di = $di;
        $this->database = $this->di->get('database');
    }
    
    private function validateData($data)
    {
        $validator = $this->di->get('validator');
        return $validator->check($data, [
            'customer_id' => [
                'required' => true,
            ]
        ]);
    }

    public function addInvoice($customer_id){
        try{
            $this->database->beginTransaction();
            $current_date = date("Y-m-d H:i:s");
            $data_to_be_inserted = ['customer_id' => $customer_id, 'created_at' => $current_date, 'updated_at'=> $current_date, 'deleted' => 0];
            //echo"HI";
            $invoice_id = $this->database->insert('invoice', $data_to_be_inserted );
            $this->database->commit();

            return $invoice_id;
        }
        catch(Exception $e){
                $this->database->rollback();
                return ADD_ERROR;
            }

    }
    public function addSales($data){
    	//var_dump($data);
        $validation = $this->validateData($data);
        var_dump($data);
        if(!$validation->fails()){
            try{
                //echo "HI";
                $invoice_id=$this->addInvoice($data['customer_id']);
                //echo ($this->addInvoice($data['customer_id']));
                //var_dump($data);
                

                for($i=0; $i< count($data['product_id']); $i++){
                    $this->database->beginTransaction();
                    $data_to_be_inserted = 
                    ['product_id'=> $data['product_id'][$i],
                      'quantity' => $data['quantity'][$i],
                      'discount' => $data['discount'][$i],
                      'invoice_id' => $invoice_id
                    ];
                $sales_id = $this->database->insert('sales', $data_to_be_inserted);
                $this->database->commit();
                
                }
                return ADD_SUCCESS;
            }
            catch(Exception $e){
                $this->database->rollback();
                return ADD_ERROR;
            }

        }
        else{
            return VALIDATION_ERROR;
        }
    }
}