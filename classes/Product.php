<?php

require_once __DIR__."/../helper/requirements.php";

class Product{
    private $table = "products";
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
            'name' => [
                'required' => true,
                'minlength' => 2,
                'maxlength' => 255,
                //'unique' => $this->table
            ],
            'specification' => [
                'required' => true,
                'minlength' => 2,
                'maxlength' => 255
            ],
            'category_id' => [
                'required' => true
                //'exists' => 'category|id'
            ]
        ]);
    }
    /**
     * This function is responsible to accept the data from the Routing and add it to the Database.
     */
    public function addProduct($data)
    {
        $validation = $this->validateData($data);
        if(!$validation->fails())
        {
            //Validation was successful
            try
            {
                //Begin Transaction
//                Util::dd($data);
                $columnsOfProductTable = ["name", "specifications", "hsn_code", "category_id", "eoq_level", "danger_level", "quantity"];
                $data_to_be_inserted = Util::createAssocArray($columnsOfProductTable, $data);
                $this->database->beginTransaction();
                $product_id = $this->database->insert($this->table, $data_to_be_inserted);
                $data_to_be_inserted = [];
                $data_to_be_inserted['product_id'] = $product_id;
                foreach($data['supplier_id'] as $supplier_id){
                    $data_to_be_inserted['supplier_id'] = $supplier_id;
                    $this->database->insert('product_suppliers', $data_to_be_inserted);
                }
                $data_to_be_inserted = [];
                $data_to_be_inserted['product_id'] = $product_id;
                $data_to_be_inserted['selling_rate'] = $data['selling_rate'];
                $this->database->insert('product_selling_rates', $data_to_be_inserted);
                $this->database->commit();
                return ADD_SUCCESS;
            }
            catch(Exception $e)
            {
//                Util::dd($e);
                $this->database->rollback();
                return ADD_ERROR;
            }
        }
        else
        {
            //Validation Failed!
            return VALIDATION_ERROR;
        }
    }
    public function getJSONDataForDataTable($draw,$searchParameter,$orderBy,$start,$length)
    {
        $columns = ["products.name","products.specifications","product_selling_rates.selling_rate","product_selling_rates.with_effect_from", "products.eoq_level", "category.name"];
        $query = "SELECT products.id, products.name as product_name, products.specifications, products.eoq_level, products.danger_level, category.name as category_name, product_selling_rates.selling_rate, product_selling_rates.with_effect_from, GROUP_CONCAT(CONCAT(first_name, ' ', last_name)) as supplier_name FROM products INNER JOIN category ON products.category_id=category.id INNER JOIN product_suppliers ON products.id = product_suppliers.product_id INNER JOIN suppliers ON product_suppliers.supplier_id = suppliers.id INNER JOIN product_selling_rates ON products.id = product_selling_rates.product_id INNER JOIN (SELECT product_id, MAX(with_effect_from) as wef FROM (SELECT * FROM `product_selling_rates` WHERE with_effect_from <= CURRENT_TIMESTAMP) as temp GROUP BY product_id) as max_date_table ON max_date_table.product_id=product_selling_rates.product_id AND product_selling_rates.with_effect_from = max_date_table.wef WHERE products.deleted=0";

        $groupBy = " GROUP BY products.id";
        $totalRowCountQuery = "SELECT DISTINCT(count(*) OVER()) as total_count FROM products INNER JOIN category ON products.category_id=category.id INNER JOIN product_suppliers ON products.id = product_suppliers.product_id INNER JOIN suppliers ON product_suppliers.supplier_id = suppliers.id INNER JOIN product_selling_rates ON products.id = product_selling_rates.product_id INNER JOIN (SELECT product_id, MAX(with_effect_from) as wef FROM (SELECT * FROM `product_selling_rates` WHERE with_effect_from <= CURRENT_TIMESTAMP) as temp GROUP BY product_id) as max_date_table ON max_date_table.product_id=product_selling_rates.product_id AND product_selling_rates.with_effect_from = max_date_table.wef WHERE products.deleted=0";
        $filteredRowCountQuery = $totalRowCountQuery;        
        if($searchParameter!=null)
        {
            $condition = " AND products.name like '${$searchParameter}%' OR specifications like '%{$searchParameter}%' OR category.name like '%{$searchParameter}%' OR suppliers.first_name like '%{$searchParameter}%' OR suppliers.last_name like '%{$searchParameter}%'";
            $query .= $condition;
            $filteredRowCountQuery .= $condition;
        }
        $query .= $groupBy;
        $filteredRowCountQuery .= $groupBy;
        $totalRowCountQuery .= $groupBy;
        if($orderBy != null)
        {
            $query .= " ORDER BY {$columns[$orderBy[0]['column']]} {$orderBy[0]['dir']}";
        }
        else
        {
            $query .= " ORDER BY {$columns[0]} ASC";
        }
        if($length != -1)
        {
            $query .= " LIMIT {$start},{$length}";
        }
        
       // Util::dd($totalRowCountQuery);

        $totalRowCountResult = $this->database->raw($totalRowCountQuery);
        $numberOfTotalRows = is_array($totalRowCountResult) ? $totalRowCountResult[0]->total_count : 0;
        
        $filteredRowCountResult = $this->database->raw($filteredRowCountQuery);
        $numberOfFilteredRows = is_array($filteredRowCountResult) ? ($filteredRowCountResult[0]->total_count ?? 0) : 0;
        
        $filteredData = $this->database->raw($query);
        $numberOfRowsToDisplay = is_array($filteredData) ? count($filteredData) : 0;
        $data = [];
        for($i=0; $i<$numberOfRowsToDisplay; $i++)
        {
            $subarray = [];
            $subarray[] = $filteredData[$i]->product_name;
            $subarray[] = $filteredData[$i]->specifications;
            $subarray[] = $filteredData[$i]->selling_rate;
            $subarray[] = $filteredData[$i]->with_effect_from;
            $subarray[] = $filteredData[$i]->eoq_level;
            $subarray[] = $filteredData[$i]->danger_level;
            $subarray[] = $filteredData[$i]->category_name;
            $subarray[] = $filteredData[$i]->supplier_name;
            $subarray[] = <<<BUTTONS
            <button class='edit btn btn-outline-primary' id='{$filteredData[$i]->id}' data-toggle="modal" data-target="#editModal"><i class='fas fa-pencil-alt'></i></button>
            <button class='delete btn btn-outline-danger' id='{$filteredData[$i]->id}' data-toggle="modal" data-target="#deleteModal"><i class='fas fa-trash'></i></button>
BUTTONS;
            $data[] = $subarray;
            
        }
        $output = array(
            "draw"=>$draw,
            "recordsTotal"=>$numberOfTotalRows,
            "recordsFiltered"=>$numberOfFilteredRows,
            "data"=>$data
        );
        
        echo json_encode($output);
    }
    
    public function getProductById($productId, $mode=PDO::FETCH_OBJ)
    {
        $query = "SELECT * FROM {$this->table} wHERE deleted=0 AND id = {$productId}";
        $result = $this->database->raw($query,$mode);
//        Util::dd($result);
        return $result;
    }
    
    public function update($data,$id)
    {
        $validationData['name'] = $data['product_name'];
        $validationData['specifications'] = $data['product_specification'];
        $validationData['hsn_code'] = $data['product_hsn_code'];
        $validationData['category_id'] = $data['product_category_id'];
        $validationData['eoq_level'] = $data['product_eoq_level'];
        $validationData['danger_level'] = $data['product_danger_level'];
        $validationData['quantity'] = $data['product_quantity'];

        
        $validation = $this->validateData($validationData);
        if(!$validation->fails())
        {
            try{
                $this->database->beginTransaction();
//                Util::dd($data);
                $filteredData['name'] = $data['product_name'];
                $filteredData['specifications'] = $data['product_specification'];
                $filteredData['hsn_code'] = $data['product_hsn_code'];
                $filteredData['category_id'] = $data['product_category_id'];
                $filteredData['eoq_level'] = $data['product_eoq_level'];
                $filteredData['danger_level'] = $data['product_danger_level'];
                $filteredData['quantity'] = $data['product_quantity'];

                $this->database->update($this->table,$filteredData,"id={$id}");
                $this->database->commit();
                return EDIT_SUCCESS;
                
            }catch(Exception $e){
                $this->database->rollback();
                return EDIT_ERROR;
            }
        }
        else
        {
            return VALIDATION_ERROR;
        }
    }
    
    public function delete($id)
    {
        try{
            $this->database->beginTransaction();
//            Util::dd($id);
            $this->database->delete($this->table,"id={$id}");
//            Util::dd($id);
            $this->database->commit();
            return DELETE_SUCCESS;
        }catch(Exception $e){
//            Util::dd($e);
            $this->database->rollback();
            return DELETE_ERROR;
        }
    }
    public function getProductsByCategoryID($category_id){
        return $this->database->readData('products', ['id', 'name'], "category_id={$category_id} and deleted=0");
    }
    
    public function getDiscountedPrice($fr, $disc){
        return $val = $fr*(1-($disc/100));
    }
    public function getSellingPriceByProductID($product_id){
        //return $this->wefrom($product_id);
        $query = "SELECT t1.product_id, t1.selling_rate, t1.with_effect_from FROM product_selling_rates  t1 INNER JOIN (SELECT product_id, selling_rate, max(with_effect_from) as wef FROM product_selling_rates WHERE with_effect_from<=CURRENT_TIMESTAMP GROUP BY product_id and product_id={$product_id}) t2 ON t1.with_effect_from=t2.wef AND t1.product_id={$product_id}";
        return $this->database->raw($query)[0]->selling_rate;
    }
}