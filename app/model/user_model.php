<?php
require('model.php');

class UserModel extends Model {
  private $collection;
  private $password = null;
  private $hashed_password = null;
  private $username = null;
  public $name = null;
  public $email = null;
  public $dob = null; 
  public $location = null;
  public $image = null;
  public $education_history = null;
  public $work_history = null;
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
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }

  public function __construct0() {  
    
  }
  public function __construct1($username) { 
    $this->username = $username;
    $this->retrieve(true);
    $this->hashed_password = null; // read only object
  }
  public function __construct2($username, $password) {
    $this->username = $username;
    $this->password = $password;
    $this->authenticate();
  }


  /*
   * CRUD Functions
   */

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
    $hashed_password = $this->hash_password($this->password);
    // TODO: Read API to see if create returns what it has added
    $this->collection->insert($this->to_array2()); 
    $this->retrieve(true);
  }
  // cRud - use to_array or to_json 
  private function retrieve($force = false) {
    if($this->hashed_password !== null || $force === true) { // need to get from db
      $d = $this->collection->find(['username' => $this->username]);
      if($d->count() !== 1) { // not found
        throw new Exception('username `'. $this->username .'` not found');  
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
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    $this->hashed_password = $this->hash_password();
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $this->collection->findAndModify(['username' => $this->username], $this->to_array2()); 
    $this->retrieve(true);
  }
  // cruD
  public function delete() {
    if($this->hashed_password === null || !$this->exists()) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
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
    $this->username = isset($d['username'])? $d['username'] : null; 
    $this->password = isset($d['password'])? $d['password'] : null; 
    $this->email = isset($d['email'])? $d['email'] : null; 
    $this->hashed_password = isset($d['hashed_password'])? $d['hashed_password'] : null; 
    $this->dob = isset($d['dob'])? $d['dob'] : null; 
    $this->location = isset($d['location'])? $d['location'] : null; 
    $this->image = isset($d['image'])? $d['image'] : null; 
    $this->name = isset($d['name'])? $d['name'] : null; 
    $this->about_me = isset($d['about_me'])? $d['about_me'] : null; 
    $this->education_history = isset($d['education_history'])? $d['education_history'] : null; 
    $this->work_history = isset($d['work_history'])? $d['work_history'] : null; 
    $this->social = isset($d['social'])? $d['social'] : null; 
  }
  private function unsetAll() {
    $this->username = null; 
    $this->password = null; 
    $this->email = null; 
    $this->hashed_password = null; 
    $this->dob = null; 
    $this->location = null; 
    $this->image = null; 
    $this->name = null; 
    $this->about_me = null; 
    $this->education_history = null; 
    $this->work_history = null; 
    $this->social = null; 
  }

  /*
   * Return data functions
   */
  private function to_array2() {
    $data = [
      'username' => $this->username,
      'hashed_password' = $this->hashed_password,
      'email' => $this->email,
      'dob' => $this->dob,
      'location' => $this->location,
      'image' => $this->image,
      'name' => $this->name,
      'about_me' => $this->about_me,
      'education_history' => $this->education_history,
      'work_history' => $this->work_history,
      'social' => $this->social
    ]; 
    return $data;
  }
  public function to_array1() {
    $data = [
      'username' => $this->username,
      'email' => $this->email,
      'dob' => $this->dob,
      'location' => $this->location,
      'image' => $this->image,
      'name' => $this->name,
      'about_me' => $this->about_me,
      'education_history' => $this->education_history,
      'work_history' => $this->work_history,
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
    if($this->password === null) { throw new Exception('Password not set'); }
    return password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 10]);
  }

  private function verify_password() {
    if($this->password === null) { throw new Exception('Password not set'); }
    if($this->hashed_password === null) { $this->hashed_password = $this->hash_password(); }
    return password_verify($this->password, $this->hashed_password);
  }
}
