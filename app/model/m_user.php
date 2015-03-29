<?php

class M_User extends Model
  // Name it as UserModel
{
  // Create a property user_collection
  function _construct() // TODO:  construct has two underscores
  {
    $user = $db->user; // put in try catch
    // TODO: Add error checking reading 
    // Throw exceptions
  }

  public function m_check_existence($username, $password)
    // Rename as check_existence
  {
    return db.collection.find({username : $username, password : $password});
    //returns the cursor to the document that matches the query else EOF
  }

  public function m_create_user($document)
  {
    return db.collection.insert($document);
    //returns number of documents inserted
  }

  public function m_retrieve_user($document)
  {
    db.collection.findOne({username : $document->$usr_username}, function(err, result) {
      if (err)
        return null;
      else
        return result;
    }
    //returns the document asked for else returns null

  }

  public function m_update_user($document)
  {
    return db.collection.findAndModify({username : $document->$usr_username);
    //returns pre-modified data after updation else null if required updation is not made

    }

    public function m_delete_user($document)
    {
      return db.collection.remove({username : $document->$usr_username});
      //returns object WriteResult.hasWriteError
    }
  }	

?>
