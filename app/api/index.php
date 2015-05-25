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
  $userCtrl->update($details['username'], $details['password'], $details['newDetails']);
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
  $instituteCtrl = new InstituteController();
  if(!isset($details['_id'])) {
    $instituteCtrl->respond(true, 'Please set a valid college _id');
  } else {
    $instituteCtrl->retrieve($details['_id']);
  }
}
function getAllInstitutes($filters) {
  $instituteCtrl = new InstituteController();
  $instituteCtrl->retrieveAll($filters);
}
