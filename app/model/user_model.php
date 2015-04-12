<?php
<<<<<<< HEAD
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
=======
class UserModel extends Model {
  private $userCollection;
  public $name = null;
  public $dob = null ;
  public $gender = null;
  public $email = null;
  public $username = null;
  private $password = null;
  private $hashed_password = null;
  public $phone_no = null;
  public $location = null;
  public $is_verified = false;

  function __construct() 
  {
    $userCollection = new MongoCollection($db, 'users');

    if(!$userCollection)
      throw new Exception(messsage);
    else
    {
      $a = func_get_args();
      $i = func_num_args();
      if (method_exists($this,$f='__construct'.$i))
        call_user_func_array(array($this,$f),$a);
    }
  }

  function __construct1($username)
  {
    $this->username = $username;
    if($this->existence())
    {
      $doc = $this->retrieve();
      unset($doc['hashed_password']);
      $this->set($doc);
    }
    else
      throw new Exception(“This username does not exist”);
  }

  function __construct2($username, $password)
  {
    $this->username = $username;
    $this->password = $password;


    if(!$this->authenticate($username, $password))
      throw new Exception(“This username and password do not exist”);

      $doc = $this->retrieve();
      $this->is_verified = true;
      $this->set($doc);
  }
  public function set($doc)
  {
    $name = $doc->user_name;
    $dob = $doc->dob ;
    $gender = $doc->gender;
    $email = $doc->email;
    $username = $doc->username;
    $phone_no = $doc->phone_no;
    $location = $doc->location;
    $city = $doc->city;
    $country = $doc->country;

  }
  public function save()
  {
    $userCollection->insert($doc);
  }
  public function existence()
  {
    //returns the cursor to the document that matches the query else EOF  return    
    $doc = $userCollection->find(['username' => $username]);
    if($doc->count() === 0)
      return false;
    else
      return true;
  }

  private function _existence($username)
  {
    $doc = $userCollection->findOne(['username' => $username]);
    if($doc === Null)
      return Null;    //or throw new Exception(message);
    else 
      return $doc;

  }

  public function authenticate($username,$password)
  {
    $doc = $this->_existence($username);
    if($doc !== Null)
    {
      if(verify_password($password,$doc[‘password’])
          return true;
      else
          return false;
     }
   }
   public function create_user($document)
   {
    $document['password'] = hash_password($document['password']);
    return $userCollection->insert($document);
    //returns number of documents inserted
   }

   public function retrieve()
   {
     if(!$hashed_password)//if hashed_password is null retrieve it
     	{
	  $doc= $userCollection->findOne(['username' => $username]);
	   set($doc);
	}
   	  //returns the document asked for else returns null
     else
	{
         //already retrieved
   	}

   public function update_user($document)
   {
     return $userCollection->findAndModify(['username' => $document[‘username]],
     [‘$set’=>[‘dob’  =>$document[‘dob’],
                ‘gender’  =>$document[‘gender’],
                ‘email’ =>$document[‘email’],
                ‘phone_no’ =>$document[‘phone_no’],
                ‘password’ => $document[‘password’],
                ‘address’ => array(‘location’ => $document[‘location’],
                ‘city’ => $document[‘city’],
                ‘country’ => $document[‘country’]
                  )]]);
            //returns pre-modified data after updation else null if required updation is not made

          }

   public function delete_user($username)
   {
     return $userCollection->remove([‘username’ => $document[‘username’]], [‘justOne’ => true]);
     //returns object WriteResult.hasWriteError
   }

   private function hash_password ($input, $rounds = 10)
   {
     return password_hash($input, PASSWORD_BCRYPT, ['cost' => $rounds]);
   }

   private function verify_password($input, $saved)
   {
     return password_verify($input, $saved);
   }

}    

?>




>>>>>>> e82901ec0c01ed2ad2ffa5f028b7fbdf9fb4b584
