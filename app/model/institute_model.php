<?php
require_once('model.php');

class InstituteModel extends Model {
  private $collection;
  private $name = null;
  private $email = null;
  private $yearOfEstablishment = null;
  private $disciplines = [];
  private $website = null;
  private $location = null;
  private $image = null;
  private $bannerImage = null;
  private $about = null;
  private $social = [];//fb page linked in pa
  private $_id=null;//recieved from controller
  /*
   * Constructors
   */

  public function __construct() {  
    parent::__construct();
    $a = func_get_args();
    $i = func_num_args();
    $this->collection = new MongoCollection($this->db, 'institutes');
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }

  public function __construct0() {  

  }
  public function __construct1($_id) { // This can be the unique key to accesss a particular institute
    $this->_id = $_id;
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
  public function retrieveAll($filters) {
    $d = $this->collection->find();
    $institutes = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new InstituteModel();
        $temp->set($doc);
        $institutes[$i++] = $temp->to_array();
      }
    }
    return $institutes;
  }
  // cRud - use to_array or to_json 
  private function retrieve() {
    if($this->_id === null) {
      throw new Exception('Invalid _id passed');
    }
    // need to get from db
    $d = $this->collection->findOne(['_id' => new MongoId($this->_id)]);
    if(!$d) { // not found
      throw new Exception('Not found ' . $d->count());  
    } else { // found
      $this->set($d);
    }

  }
  // crUd
  public function update() {
    if(!$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $this->collection->findAndModify(['_id' => $this->_id], $this->to_array()); 
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

  public function set($d) {
    $this->email = isset($d['email'])? $d['email'] : $this->email;
    $this->yearOfEstablishment = isset($d['yearOfEstablishment'])? $d['yearOfEstablishment'] : $this->yearOfEstablishment;  
    $this->disciplines = isset($d['disciplines'])? $d['disciplines'] : $this->disciplines;  
    $this->location = isset($d['location'])? $d['location'] : $this->location;
    $this->image = isset($d['image'])? $d['image'] : $this->image;
    $this->bannerImage = isset($d['bannerImage'])? $d['bannerImage'] : $this->bannerImage;
    $this->name = isset($d['name'])? $d['name'] : $this->name; 
    $this->website = isset($d['website'])? $d['website'] : $this->website; 
    $this->about = isset($d['about'])? $d['about'] : $this->about;
    $this->social = isset($d['social'])? $d['social'] : $this->social; 
    $this->_id = isset($d['_id'])? $d['_id']->{'$id'} : $this->_id;
  }
  private function unsetAll() {
    $this->email = null;  
    $this->yearOfEstablishment = null; 
    $this->disciplines = null; 
    $this->location = null; 
    $this->image = null; 
    $this->bannerImage = null; 
    $this->website = null;
    $this->name = null; 
    $this->about = null;
    $this->social = null; 
  }

  /*
   * Return data functions
   */
  public function to_array() {
    $data = [
      'email' => $this->email,
      'yearOfEstablishment' => $this->yearOfEstablishment,
      'disciplines' => $this->disciplines,
      'location' => $this->location,
      'image' => $this->image,
      'bannerImage' => $this->bannerImage,
      'name' => $this->name,
      'website' => $this->website,
      'about' => $this->about,
      'social' => $this->social,
      '_id' => $this->_id
    ]; 
    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array());
  }
}
