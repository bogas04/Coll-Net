<?php
require('model.php');

class CompanyModel extends Model {
  private $collection;
  private $id = null;
  private $name = null;
  private $headquarter = null;
  private $branch = null;
  private $image = null;
  private $email = null;
  private $description = null;
  private $webid = null;

  /*
   * Constructors
   */

  public function __construct() {  
    parent::__construct();
    $a = func_get_args();
    $i = func_num_args();
    $this->collection = new MongoCollection($this->db, 'users');
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }

  public function __construct0() {  
    
  }
  public function __construct1($email) { 
    $this->email = $email;
    $this->retrieve();
  }

  /*public function get_id() {
    $d = $this->collection->findOne(['name' => $this->name, 'headquarter' => $this->headquarter, 'branch' => $this->branch]);
    $this->$id = $d['_id'];
  }*/


  /*
   * CRUD Functions
   */

  // Crud
  public function create($d) {
    if(!isset($d['name']) || !isset($d['branch']) || !isset($d['headquarter'])) { 
      throw new Exception('Company Name, Branch & Headquarter are required fields'); 
    }
    if(isset($d['id'])) {
      throw new Exception('invalid usage of API');   
    }
    $this->set($d);
    if($this->name === null || $this->branch === null || $this->headquarter === null) {
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
  // cRud - use to_array or to_json 
  private function retrieve() {
    if($this->email !== null) { // need to get from db
      $d = $this->collection->find(['email' => $this->email]);
      if($d->count() !== 1) { // not found
        throw new Exception('Company Name `'. $this->name .'` not found');  
      } else { // found
        foreach($d as $doc) {
          $this->set($doc);
          break;
        }
      }
    }
  }
  // crUd
  public function update() {
    if($this->email === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $d = $this->collection->findAndModify(['_id' => $this->id], $this->to_array()); 
    $this->retrieve();
  }
  // cruD
  public function delete() {
    if($this->email === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
    $this->collection->remove(['_id' => $this->id], ['justOne' => true]);
    $this->unsetAll();
  }
  public function exists() {
    if($this->name === null) { throw new Exception('Company Name is not set'); }
    return $this->collection->find(['email' => $this->email])->count() !== 0;
  }

  /*
   * Setting the object
   */
  private function set($d) {
    $this->id = isset($d['id'])? $d['id'] : null;
    $this->name = isset($d['name'])? $d['name'] : null; 
    $this->headquarter = isset($d['headquarter'])? $d['headquarter'] : null; 
    $this->branch = isset($d['branch'])? $d['branch'] : null; 
    $this->image = isset($d['image'])? $d['image'] : null; 
    $this->email = isset($d['email'])? $d['email'] : null;
    $this->description = isset($d['description'])? $d['description'] : null; 
    $this->webid = isset($d['webid'])? $d['webid'] : null; 
  }

  private function unsetAll() {
    $this->id = null;
    $this->name = null; 
    $this->headquarter = null; 
    $this->branch = null; 
    $this->image = null; 
    $this->email = null; 
    $this->description = null; 
    $this->webid = null; 
  }

  /*
   * Return data functions
   */
  public function to_array() {
    $data = [
      'name' => $this->name,
      'headquarter' => $this->headquarter,
      'branch' => $this->branch,
      'image' => $this->image,
      'email' => $this->email,
      'description' => $this->description,
      'webid' => $this->webid
    ]; 

    return $data;
  }

  public function to_json() {
    return json_encode($this->to_array());
  }

  public function get_Cname() {
    return $this->name;
  }

}
