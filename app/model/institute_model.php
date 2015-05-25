<?php
require_once('model.php');

class InstituteModel extends Model {
  private $collection;
  private $name = null;
  private $email = null;
  private $yoe = null; //year of establishment or 
  private $location = null;
  private $image = null;
  private $about_institute = null;
  private $social = null;//fb page linked in pa
  private $_id=null;//recieved from controller
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
  public function __construct1($emailid) { //hat can be the unique key to accesss a particular institute
    $this->emailid = $emailid;
    $this->retrieve();
  }



  /*
   * CRUD Functions
   */

  // Crud
  public function create($d) {
    if(!isset($d['name']) || !isset($d['email'])|| !isset($d['location'] )) { 
      throw new Exception('name of institute ,emailid & location are required fields'); 
    }
    if(isset($d['_id'])) {
      throw new Exception('invalid usage of API'); 
      $this->set($d);
      if($this->name === null || $this->email === null || $this->location === null) {
        throw new Exception('name of the institute, password & emailid are required fields'); 
      } 
      // TODO: Think of potential risks 
      if($this->exists()) {
        throw new Exception('emailid  is in use');// what should be done here as primary key is mongo id
      }
      $this->collection->insert($this->to_array()); 
      $this->retrieve();
    }
  }
  // cRud - use to_array or to_json 
  private function retrieve() {
    // need to get from db
    $d = $this->collection->find(['emailid' => $this->emailid]);//find using what????
    if($d->count() !== 1) { // not found
      throw new Exception('name'. $this->name .'or location : '.$this->location .' not found');  
    } else { // found
      foreach($d as $doc) {
        $this->set($doc);
        break;
      }
    }

  }
  // crUd
  public function update() {
    if(!$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $this->collection->findAndModify(['emailid' => $this->emailid], $this->to_array()); 
    $this->retrieve();
  }
  // cruD
  public function delete() {
    if(!$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
    $this->collection->remove(['emailid' => $this->emailid], ['justOne' => true]);//query using what??
    $this->unsetAll();
  }
  public function exists() {
    if($this->emailid === null || $this->name === null || $this->location === null) { 
      throw new Exception('Email id,name of institute or location of institute is not present'); 
    }//use what in place if username?
    return $this->collection->find(['name' => $this->name],['location' => $this->location],['emailid'=> $this->eamilid]);
  }

  /*
   * Setting the object
   */

  private function set($d)
  {

    $this->email = isset($d['email'])? $d['email'] : null;
    $this->yoe = isset($d['yoe'])? $d['yoe'] : null;  
    $this->location = isset($d['location'])? $d['location'] : null;
    $this->image = isset($d['image'])? $d['image'] : null; //
    $this->name = isset($d['name'])? $d['name'] : null; 
    $this->about_institute = isset($d['about_institute'])? $d['about_institute'] : null; 
    $this->social = isset($d['social'])? $d['social'] : null; 
  }
  private function unsetAll() {
    $this->email = null;  
    $this->yoe = null; 
    $this->location = null; 
    $this->image = null; 
    $this->name = null; 
    $this->about_institute = null;
    $this->social = null; 
  }

  /*
   * Return data functions
   */
  public function to_array() {
    $data = [
      'email' => $this->email,
      'yoe' => $this->yoe,
      'location' => $this->location,
      'image' => $this->image,
      'name' => $this->name,
      'about_institute' => $this->about_institute,
      'social' => $this->social,
      '_id' => $this->id
    ]; 
    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array());
  }

}
