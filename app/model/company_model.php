<?php
require_once('model.php');

class CompanyModel extends Model {
  private $collection;
  private $_id = null;
  private $name = null;
  private $location = null;
  private $field = null;
  private $image = null;
  private $email = null;
  private $about = null;
  private $website = null;

  /*
   * Constructors
   */

  public function __construct() {  
    parent::__construct();
    $a = func_get_args();
    $i = func_num_args();
    $this->collection = new MongoCollection($this->db, 'companies');
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }
  public function __construct0() {  

  }
  public function __construct1($_id) { 
    $this->_id = $_id;
    $this->retrieve();
  }
  /*
   * CRUD Functions
   */

  // Crud
  public function create($d) {
    if(!isset($d['name']) || !isset($d['field']) || !isset($d['location'])) { 
      throw new Exception('Company Name, Branch & Headquarter are required fields'); 
    }
    if(isset($d['id'])) {
      throw new Exception('invalid usage of API');   
    }
    $this->set($d);
    if($this->name === null || $this->field === null || $this->location === null) {
      throw new Exception('Company Name, Branch & Headquarter are required fields'); 
    } 
    // TODO: Think of potential risks 
    if($this->exists()) {
      throw new Exception('This Company Name already exists');
    }
    // TODO: Read API to see if create returns what it has added
    $this->collection->insert($this->to_array()); 
    $this->retrieve();
  }
  public function retrieveAll($filters) {
    $d = $this->collection->find();
    $companies = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new CompanyModel();
        $temp->set($doc);
        $companies[$i++] = $temp->to_array();
      }
    }
    return $companies;
  }
  // cRud - use to_array or to_json 
  private function retrieve() {
    if($this->_id === null) {
      throw new Exception('Invalid _id passed');
    }
    $d = $this->collection->findOne(['_id' => new MongoId($this->_id)]);
    if(!$d) { // not found
      throw new Exception('Company Name `'. $this->name .'` not found');  
    } else { // found
      $this->set($d);
    }
  }
  // crUd
  public function update() {
    if($this->email === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $d = $this->collection->findAndModify(['_id' => $this->_id], $this->to_array()); 
    $this->retrieve();
  }
  // cruD
  public function delete() {
    if($this->email === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
    $this->collection->remove(['_id' => $this->_id], ['justOne' => true]);
    $this->unsetAll();
  }
  public function exists() {
    if($this->name === null) { throw new Exception('Company Name is not set'); }
    return $this->collection->find(['_id' => new MongoId($this->_id)])->count() !== 0;
  }

  /*
   * Setting the object
   */
  private function set($d) {
    $this->_id = isset($d['_id'])? $d['_id']->{'$id'} : $this->_id;
    $this->name = isset($d['name'])? $d['name'] : $this->name; 
    $this->location = isset($d['location'])? $d['location'] : $this->location; 
    $this->field = isset($d['field'])? $d['field'] : $this->field; 
    $this->image = isset($d['image'])? $d['image'] : $this->image; 
    $this->email = isset($d['email'])? $d['email'] : $this->email;
    $this->about = isset($d['about'])? $d['about'] : $this->about; 
    $this->website = isset($d['website'])? $d['website'] : $this->website; 
  }

  private function unsetAll() {
    $this->_id = null;
    $this->name = null; 
    $this->location = null; 
    $this->field = null; 
    $this->image = null; 
    $this->email = null; 
    $this->about = null; 
    $this->website = null; 
  }

  /*
   * Return data functions
   */
  public function to_array() {
    $data = [
      'name' => $this->name,
      '_id' => $this->_id,
      'location' => $this->location,
      'field' => $this->field,
      'image' => $this->image,
      'email' => $this->email,
      'about' => $this->about,
      'website' => $this->website
    ]; 

    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array());
  }
}
