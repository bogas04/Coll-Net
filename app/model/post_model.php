<?php
require_once('model.php');

class PostModel extends Model {
  private $collection;
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
    $this->set($d);
    $data = $this->to_array();
    unset($data['_id']);
    $this->collection->insert($data); 
    $this->_id = $data['_id'];
    $this->retrieve();
  }
  public function retrieveAll($filters) {
    $d = $this->collection->find();
    $posts = [];
    if($d->count() > 0) {
      $i = 0;
      foreach($d as $doc) {
        $temp = new PostModel();
        $temp->set($doc);
        $posts[$i++] = $temp->to_array();
      }
    }
    return $posts;
  }
  // cRud - use to_array or to_json 
  private function retrieve() {
    if($this->_id === null) {
      throw new Exception('Invalid _id passed');
    }
    $d = $this->collection->findOne(['_id' => new MongoId($this->_id)]);
    if(!$d) { // not found
      throw new Exception('Post  `'. $this->_id.'` not found');  
    } else { // found
      $this->set($d);
    }
  }
  // crUd
  public function update() {
    if($this->_id === null) { throw new Exception('Illegal operation'); }
    // TODO: Think of potential risks
    // TODO: If nothing modified, throw exception
    // TODO: Read API to see if modify returns what it has added
    $d = $this->collection->findAndModify(['_id' => $this->_id], $this->to_array()); 
    $this->retrieve();
  }
  // cruD
  public function delete() {
    if($this->_id) { throw new Exception('Illegal operation'); }
    // TODO: Very risky function
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
