<?php
require_once('model.php');
require_once('user_model.php');
require_once('institute_model.php');

class PostModel extends Model {
  private $collection;
  private $userCollection;
  private $instituteCollection;
  private $_id;
  private $postBy = null;
  private $postFor = []; // ['institute' => :id, 'batchYear' => :year, 'discipline' => :discipline, 'section' => :section ]
  private $timestamp = null;
  private $text = null;
  private $comments = [];

  /*
   * Constructors
   */

  public function __construct() {  
    parent::__construct();
    $a = func_get_args();
    $i = func_num_args();
    $this->collection = new MongoCollection($this->db, 'posts');
    $this->userCollection = new MongoCollection($this->db, 'users');
    $this->instituteCollection = new MongoCollection($this->db, 'institutes');
    if (method_exists($this,$f='__construct'.$i)) {
      call_user_func_array(array($this,$f),$a); 
    }
  }
  public function __construct0() {  

  }
  public function __construct1($_id) { 
    $this->_id = $_id;
    $this->retrieve();
  }
  /*
   * CRUD Functions
   */

  // Crud
  public function create($d) {
    if(!isset($d['postBy']) || !isset($d['text'])) { 
      throw new Exception('Please fill details of the post'); 
    }
    if(isset($d['_id'])) {
      throw new Exception('invalid usage of API');   
    }
    $d['postBy'] = MongoDBRef::create('users', $d['postBy']); 
    $this->set($d);
    $data = $this->to_array();
    unset($data['_id']);
    $this->collection->insert($data); 
    $this->_id = $data['_id'];
    $this->retrieve();
  }
  public function addComment($d) {
    if(!isset($d['commentBy']) || !isset($d['text']) || $this->timestamp === null) {
      throw new Exception('Please fill details of the comment');
    }
    $d['commentBy'] = MongoDBRef::create('users', $d['commentBy']); 
    $d = $this->collection->findAndModify(['_id' => new MongoId($this->_id)], [ '$push' => [ 'comments' =>  $d ]]);
    $this->retrieve();
  }
  public function retrieveAll($filters) {
    $d = $this->collection->find();
    $d->sort([ 'timestamp' => -1]);
    $posts = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new PostModel($doc['_id']->{'$id'});
        $posts[$i++] = $temp->to_array();
      }
    }
    return $posts;
  }
  private function retrieve() {
    if($this->_id === null) {
      throw new Exception('Invalid _id passed');
    }
    $d = $this->collection->findOne(['_id' => new MongoId($this->_id)]);
    if(!$d) { // not found
      throw new Exception('Post  `'. $this->_id.'` not found');  
    } else { // found
      // Populate Poster
      $this->set($d);
      $user = new UserModel();
      $user->retrieveById($this->postBy['$id']);
      $this->postBy = $user->to_array();
      // Populate Comments
      foreach($this->comments as $index => $value) {
        $user = new UserModel();
        $user->retrieveById($this->comments[$index]['commentBy']['$id']);
        $this->comments[$index]['commentBy'] = $user->to_array();
      }
    }
  }
  public function update() {
    if($this->_id === null) { throw new Exception('Illegal operation'); }
    $d = $this->collection->findAndModify(['_id' => $this->_id], $this->to_array()); 
    $this->retrieve();
  }
  public function delete() {
    if($this->_id) { throw new Exception('Illegal operation'); }
    $this->collection->remove(['_id' => $this->_id], ['justOne' => true]);
    $this->unsetAll();
  }
  public function exists() {
    if($this->_id === null) { throw new Exception('Post _id is not set'); }
    return $this->collection->find(['_id' => new MongoId($this->_id)])->count() !== 0;
  }

  /*
   * Setting the object
   */
  private function set($d) {
    $this->_id = isset($d['_id'])? $d['_id']->{'$id'} : $this->_id;
    $this->postBy = isset($d['postBy'])? $d['postBy'] : $this->postBy; 
    $this->postFor = isset($d['postFor'])? $d['postFor'] : $this->postFor; 
    $this->text = isset($d['text'])? $d['text'] : $this->text; 
    $this->timestamp = isset($d['timestamp'])? $d['timestamp'] : $this->timestamp; 
    $this->comments = isset($d['comments'])? $d['comments'] : $this->comments;
  }

  private function unsetAll() {
    $this->_id = null;
    $this->postBy = null; 
    $this->postFor = []; 
    $this->text = null; 
    $this->timestamp = null; 
    $this->comments = []; 
  }

  /*
   * Return data functions
   */
  public function to_array() {
    $data = [
      '_id' => $this->_id,
      'postBy' => $this->postBy,
      'postFor' => $this->postFor,
      'text' => $this->text,
      'timestamp' => $this->timestamp,
      'comments' => $this->comments,
    ]; 

    return $data;
  }
  public function to_json() {
    return json_encode($this->to_array());
  }
}
