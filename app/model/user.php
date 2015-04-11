<?php
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




