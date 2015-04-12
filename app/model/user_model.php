<?php
require('model.php');

class UserModel extends Model {
  private $collection;

  public $username = null;
  private $password = null;
  private $hashed_password = null;
  public $email = null;
  public $dob = null; 
  public $location = null;
  public $education_history = null;
  public $work_history = null;
  public $about_me = null;
  public $social = null;

  /*
   * Constructors
   */

  public function __construct() {  
    $a = func_get_args();
    $i = func_num_args();
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
    $collection = new MongoCollection($this->db, 'users');
  }

  public function __construct0($d) {  
    $this->set($d);
    if($username === null || $password === null || $email === null) {
      throw new Exception('username, password & email are required fields'); 
    } 
    $this->create(); 
  }
  public function __construct1($username) { 
    $this->username = $username;
    $this->retrieve();
    $this->hashed_password = null; // read only object
  }
  public function __construct2($username, $password) {
    $this->username = $username;
    $this->password = $password;
    $this->autheticate();
  }


  /*
   * CRUD Functions
   */

  // Crud
  private function create() {
    // TODO: Think of potential risks 
    if($this->exists()) {
      throw new Exception('username is in use');
    }
    $hashed_password = $this->hash_password($password);
    // TODO: Read API to see if create returns what it has added
    $collection->create($this->to_array()); 
    $collection->retrieve(true);
  }
  // cRud
  private function retrieve($force = false) {
    if($this->hashed_password !== null || $force === true) { // need to get from db
      $d = $collection->find(['username' => $username]);
      if($d->count() !== 1) { // not found
        throw new Exception('username `'. $username .'` not found');  
      } else { // found
        $this->set($d);    
      }
    }  
  }
  // crUd
  public function update() {
    if($hashed_password === null) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $collection->findAndModify(['username' => $username], $this->to_array()); 
    $collection->retrieve(true);
  }
  // cruD
  public function delete() {
    if($hashed_password === null) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
    $collection->remove(['username' => $username], ['justOne' => true]);
  }
  private function exists() {
    return $collection->find(['username' => $username])->count !== 0;
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
    $username = isset($d['username'])? $d['username'] : null; 
    $password = isset($d['password'])? $d['password'] : null; 
    $hashed_password = isset($d['hashed_password'])? $d['hashed_password'] : null; 
    $dob = isset($d['dob'])? $d['dob'] : null; 
    $location = isset($d['location'])? $d['location'] : null; 
    $image = isset($d['image'])? $d['image'] : null; 
    $name = isset($d['name'])? $d['name'] : null; 
    $about_me = isset($d['about_me'])? $d['about_me'] : null; 
    $education_history = isset($d['education_history'])? $d['education_history'] : null; 
    $work_history = isset($d['work_history'])? $d['work_history'] : null; 
    $social = isset($d['social'])? $d['social'] : null; 
  }

  /*
   * Return data functions
   */
  public function to_array($internal = true) {
    $data = [
      'username' => $username,
      'dob' => $dob,
      'location' => $location,
      'image' => $image,
      'name' => $name,
      'about_me' => $about_me,
      'education_history' => $education_history,
      'work_history' => $work_history,
      'social' => $social
    ]; 
    if($internal) {
      $data['hashed_password'] = $hashed_password;
    }
    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array());
  }

  /*
   * Password Functions
   */
  private function hash_password($rounds = 10) {
    if($password === null) { throw new Exception('Password not set'); }
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => $rounds]);
  }

  private function verify_password() {
    if($hashed_password === null || $password === null) { throw new Exception('Password not set'); }
    return password_verify($password, $hashed_password);
  }
}
