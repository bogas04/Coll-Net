<?php
require_once('model.php');

class UserModel extends Model {
  private $collection;
  private $companiesCollection;
  private $institutesCollection;

  private $password = null;
  private $hashed_password = null;
  private $username = null;
  private $_id = null;
  public $name = null;
  public $email = null;
  public $dob = null; 
  public $location = null;
  public $image = null;
  public $educationHistory = [];
  public $workHistory = [];
  public $about_me = null;
  public $social = null;
  private $is_admin = false; 

  /*
   * Constructors
   */

  public function __construct() {  
    parent::__construct();
    $a = func_get_args();
    $i = func_num_args();
    $this->collection = new MongoCollection($this->db, 'users');
    $this->companiesCollection = new MongoCollection($this->db, 'companies');
    $this->institutesCollection = new MongoCollection($this->db, 'institutes');
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }
  public function __construct0() {  

  }
  public function __construct1($username) { 
    if($username === null) {
      throw new Exception('Invalid username');
    }
    $this->username = $username;
    $this->retrieve(true);
    $this->hashed_password = null; // read only object
  }
  public function __construct2($username, $password) {
    if($username === null || $password === null) {
      throw new Exception('Invalid username or password');
    }
    $this->username = $username;
    $this->password = $password;
    $this->authenticate();
  }


  /*
   * CRUD Functions
   */

  public function retrieveById($_id) {
    if($_id == null) {
      throw new Exception('Invalid _id of user');  
    }
    $this->_id = $_id;
    $d = $this->collection->findOne(['_id' => new MongoId($this->_id)]);
    if(!$d) { // not found
      throw new Exception('username `'. $this->username .'` not found');  
    } else { // found
      $this->set($d);
    }  
    $this->hashed_password = null; // read only object
  }
  // Crud
  public function create($d) {
    if(!isset($d['username']) || !isset($d['password']) || !isset($d['email'])) { 
      throw new Exception('username, password & email are required fields'); 
    }
    if(isset($d['hashed_password'])) {
      throw new Exception('invalid usage of API');   
    }
    $this->set($d);
    if($this->username === null || $this->password === null || $this->email === null) {
      throw new Exception('username, password & email are required fields'); 
    } 
    // TODO: Think of potential risks 
    if($this->exists()) {
      throw new Exception('username is in use');
    }
    $this->hashed_password = $this->hash_password($this->password);
    // TODO: Read API to see if create returns what it has added
    $this->collection->insert($this->to_array2(false)); 
    $this->retrieve(true);
  }
  public function studentsOf($instituteId) {
    $d = $this->collection->find([ 'educationHistory._id.$id' => $instituteId ]);
    $users = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new UserModel();
        $temp->set($doc);
        $users[$i++] = $temp->to_array();
      }
    }
    return $users;
  }
  public function employeesOf($companyId) {
    $d = $this->collection->find([ 'workHistory._id.$id' => $companyId ]);
    $users = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new UserModel();
        $temp->set($doc);
        $users[$i++] = $temp->to_array();
      }
    }
    return $users;
  }
  // cRud - use to_array or to_json 
  private function retrieve($force = false) {
    if($this->username === null) {
      throw new Exception('Invalid username or password');  
    }
    if($this->hashed_password === null || $force === true) { // need to get from db
      $d = $this->collection->findOne(['username' => $this->username]);
      if(!$d) { // not found
        throw new Exception('username `'. $this->username .'` not found');  
      } else { // found
        $this->set($d);
        if(is_array($this->workHistory)) {
          foreach($this->workHistory as $index => $ele) {
            //var_dump($ele['_id']['$id']);
            //var_dump(MongoDBRef::get($this->db, $ele['_id']));
            //var_dump($this->companiesCollection->findOne([ '_id' => new MongoId($ele['_id']['$id'])]));
            $workInfo =  $this->companiesCollection->findOne(['_id' => new MongoId($ele['_id']['$id'])]);
            unset($workInfo['_id']);
            $this->workHistory[$index] = array_merge($ele, $workInfo);
          }
        }
        if(is_array($this->educationHistory)) {
          foreach($this->educationHistory as $index => $ele) {
            //var_dump($ele['_id']['$id']);
            //var_dump(MongoDBRef::get($this->db, $ele['_id']));
            //var_dump($this->institutesCollection->findOne([ '_id' => new MongoId($ele['_id']['$id'])]));
            $educationInfo =  $this->institutesCollection->findOne(['_id' => new MongoId($ele['_id']['$id'])]);
            unset($educationInfo['_id']);
            $this->educationHistory[$index] = array_merge($ele, $educationInfo);
          }
        }
      }
    }  
  }
  public function addCompany($details) {
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    $this->hashed_password = $this->hash_password();
    $details->_id = MongoDBRef::create("companies",  $details->_id);
    $this->collection->findAndModify(['username' => $this->username], ['$push' => [ 'workHistory' => $details]]);
    $this->retrieve(true);
  }
  public function addInstitute($details) {
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    $this->hashed_password = $this->hash_password();
    $details->_id = MongoDBRef::create("institutes",  $details->_id);
    $this->collection->findAndModify(['username' => $this->username], ['$push' => [ 'educationHistory' => $details]]);
    $this->retrieve(true);
  }
  public function update() {
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    $this->hashed_password = $this->hash_password();
    $data = $this->to_array2();
    unset($data['_id']);
    $this->collection->findAndModify(['username' => $this->username], $data); 
    $this->retrieve(true);
  }
  public function delete() {
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    $this->collection->remove(['username' => $this->username], ['justOne' => true]);
    $this->unsetAll();
  }
  public function exists() {
    if($this->username === null) { throw new Exception('Username is not set'); }
    return $this->collection->find(['username' => $this->username])->count() !== 0;
  }

  /*
   * Authenticate 
   */
  public function authenticate() {
    $this->retrieve();
    if(!$this->verify_password()) {
      throw new Exception('Invalid password');
    }
  }

  /*
   * Setting the object
   */
  private function set($d) {
    $this->username = isset($d['username'])? $d['username'] : $this->username; 
    $this->_id = isset($d['_id'])? $d['_id']->{'$id'} : $this->_id; 
    $this->password = isset($d['password'])? $d['password'] : $this->password; 
    $this->email = isset($d['email'])? $d['email'] : $this->email; 
    $this->hashed_password = isset($d['hashed_password'])? $d['hashed_password'] : $this->hashed_password; 
    $this->dob = isset($d['dob'])? $d['dob'] : $this->dob; 
    $this->location = isset($d['location'])? $d['location'] : $this->location; 
    $this->image = isset($d['image'])? $d['image'] : $this->image; 
    $this->name = isset($d['name'])? $d['name'] : $this->name; 
    $this->about_me = isset($d['about_me'])? $d['about_me'] : $this->about_me; 
    $this->educationHistory = isset($d['educationHistory'])? $d['educationHistory'] : $this->educationHistory; 
    $this->workHistory = isset($d['workHistory'])? $d['workHistory'] : $this->workHistory; 
    $this->social = isset($d['social'])? $d['social'] : $this->social; 
  }
  private function unsetAll() {
    $this->username = null; 
    $this->_id = null; 
    $this->password = null; 
    $this->email = null; 
    $this->hashed_password = null; 
    $this->dob = null; 
    $this->location = null; 
    $this->image = null; 
    $this->name = null; 
    $this->about_me = null; 
    $this->educationHistory = null; 
    $this->workHistory = null; 
    $this->social = null; 
  }

  /*
   * Return data functions
   */
  private function to_array2($noId = true) {
    $data = [
      'username' => $this->username,
      '_id' => $this->_id,
      'hashed_password' => $this->hashed_password,
      'email' => $this->email,
      'dob' => $this->dob,
      'location' => $this->location,
      'image' => $this->image,
      'name' => $this->name,
      'about_me' => $this->about_me,
      'educationHistory' => $this->educationHistory,
      'workHistory' => $this->workHistory,
      'social' => $this->social
    ]; 
    if(!$noId) { 
      unset($data['_id']);
    }
    return $data;
  }
  public function to_array() {
    $data = [
      'username' => $this->username,
      '_id' => $this->_id,
      'email' => $this->email,
      'dob' => $this->dob,
      'location' => $this->location,
      'image' => $this->image,
      'name' => $this->name,
      'about_me' => $this->about_me,
      'educationHistory' => $this->educationHistory,
      'workHistory' => $this->workHistory,
      'social' => $this->social
    ]; 
    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array2());
  }
  public function get_username() {
    return $this->username;
  }
  private function get_is_admin() {
    return $this->is_admin;
  }

  /*
   * Password Functions
   */
  private function hash_password() {
    if($this->password === null) { throw new Exception('Password not set '. $this->password); }
    return password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 10]);
  }

  private function verify_password() {
    if($this->hashed_password === null) { $this->hashed_password = $this->hash_password(); }
    return password_verify($this->password, $this->hashed_password);
  }
}
