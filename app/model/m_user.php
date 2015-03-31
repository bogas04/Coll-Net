<?php

class UserModel extends Model
{
  private $userCollection;

  function __construct()
  {
//    try
//    {
      $userCollection = new MongoCollection($db, 'users');
      if(!$userCollection)
        throw new Exception(messsage);
//    }
//    catch(MongoConnectionException $e)
//    {
//      die('Error connecting to MongoDB server');
//    }
//    catch(MongoConnectionException $e)
//    {
//      die('Error: '.$e->getMessage());
//    }
  }

  public function existence($username)
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
    else
	throw new Exception(message);  
  }

  public function create_user($document)
  {
       $document['password'] = hash_password($document['password']);
        return $userCollection->insert($document);
        //returns number of documents inserted
  }

  public function retrieve_user($document)
  {
    $userCollection->findOne(['username' => $document[‘username’]], [’password’ => 0]);
      //returns the document asked for else returns null
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
								      )
						    ]
					  ]
					 );
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
