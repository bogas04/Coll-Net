<?php 

// Fix for angular post sending application/json request
if(file_get_contents('php://input')) {
  foreach(json_decode(file_get_contents('php://input')) as $key => $value) {
    $_POST[$key] = $value;
  }
}
/*
=============
  INJECTION  
=============
 */

require_once('../controller/user.php');
require_once('../controller/institute.php');
//require_once('../controller/company.php');
//require_once('../controller/post.php');
//require_once('../controller/comment.php');


/*
===========
  ROUTING  
===========
 */
switch($_REQUEST['action']) {
case 'login':       login($_POST);      break;
case 'logout':      logout();           break;
case 'loginStatus': loginStatus();      break;
case 'signup':      signup($_POST);     break;
case 'getUser':     getUser($_GET);     break;
case 'updateUser':  updateUser($_POST); break;
case 'deleteUser':  deleteUser($_GET);  break;

case 'createPost': createPost($_POST); break;
case 'getPost':    getPost($_GET);     break;
case 'updatePost': updatePost($_POST); break;
case 'deletePost': deletePost($_GET);  break;

case 'createComment': createComment($_POST); break;
case 'getComment':    getComment($_GET);     break;
case 'updateComment': updateComment($_POST); break;
case 'deleteComment': deleteComment($_GET);  break;

case 'getInstitute':     getInstitute($_GET); break;
case 'getAllInstitutes': getAllInstitutes($_GET); break;

case 'getCompany': getCompany($_GET); break;
}

/*
============
  USER API  
============
 */
function login($details) {
  $userCtrl = new UserController();
  if(!isset($details['username']) || !isset($details['password'])) {
    $userCtrl->respond(true, 'Invalid username or password');
  }
  $userCtrl->login($details['username'], $details['password']);
}
function signup($details) {
  $userCtrl = new UserController();
  if(!isset($details['username']) || !isset($details['password'])) {
    $userCtrl->respond(true, 'Invalid username or password', $_REQUEST);
  }
  $userCtrl->signup($details);
}
function logout() {
  $userCtrl = new UserController();
  $userCtrl->logout();
}
function loginStatus() {
  $userCtrl = new UserController();
  $userCtrl->getLoginStatus();
}
function profile($details) {
  $userCtrl = new UserController();
  if(!isset($details['username'])) {
    $userCtrl->respond(true, 'Invalid username');
  }
  $userCtrl->getProfile($details['username']);
}
function updateUser($details) {
  $userCtrl = new UserController();
  if(!isset($details['username'])) {
    $userCtrl->respond(true, 'Invalid username');
  }
  $userCtrl->update($details['username'], $details['updated']);
}
function deleteUser($details) {
  return json_encode([
    'error' => false,
    'message' => 'This API is not yet supported',
    'data' => $details
  ]);
}
/*
============
  POST API  
============
 */
function createPost($details) {

}
function getPost($details) {

}
function updatePost($details) {

}
function deletePost($details) {

}
/*
================
  COMMENTS API  
================
 */
function createComment($details) {

}
function getComment($details) {

}
function updateComment($details) {

}
function deleteComment($details) {

}
/*
=================
  INSTITUTE API  
=================
*/
function getInstitute($details) {
  
}
function getAllInstitutes() {
  $userCtrl = new UserController();
  $userCtrl->respond(false, '3 colleges', [
    [
      'id' => 13,
      'name' => 'NSIT',
      'about' => 'An Autonomous Institution under Govt. of NCT of Delhi and affiliated to University of Delhi, Netaji Subhas Institute of Technology is a seat of higher technical education in India. It was established in year 1983 as Delhi Institute of Technology with the objective to meet the growing demands of manpower in the emerging fields of engineering and technology with a close social and industrial interface. Over a period of time the Institute has carved a niche for itself, both nationally and internationally, for excellence in technical education and research.'
    ],
    [
      'id' => 23,
      'name' => 'DTU',
      'about' => 'DTU to be a leading world class technology university playing its role as a key node in national and global knowledge network thus empowering india with the wings of knowledge and power of innovations' 
    ]
  ]);
}
