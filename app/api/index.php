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
require_once('../controller/company.php');
//require_once('../controller/post.php');
//require_once('../controller/comment.php');


/*
===========
  ROUTING  
===========
 */
switch($_REQUEST['action']) {
case 'login':        login($_POST);        break;
case 'logout':       logout();             break;
case 'loginStatus':  loginStatus();        break;
case 'signup':       signup($_POST);       break;
case 'getUser':      getUser($_GET);       break;
case 'updateUser':   updateUser($_POST);   break;
case 'deleteUser':   deleteUser($_GET);    break;
case 'addInstitute': addInstitute($_POST); break;
case 'addCompany':   addCompany($_POST);   break;

case 'getInstitute':     getInstitute($_GET);     break;
case 'getAllInstitutes': getAllInstitutes($_GET); break;

case 'getCompany':      getCompany($_GET);      break;
case 'getAllCompanies': getAllCompanies($_GET); break;

case 'createPost': createPost($_POST); break;
case 'getPost':    getPost($_GET);     break;
case 'updatePost': updatePost($_POST); break;
case 'deletePost': deletePost($_GET);  break;

case 'createComment': createComment($_POST); break;
case 'getComment':    getComment($_GET);     break;
case 'updateComment': updateComment($_POST); break;
case 'deleteComment': deleteComment($_GET);  break;
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
    $userCtrl->respond(true, 'Internal API error');
  }
  if(!isset($details['password'])) {
    $userCtrl->respond(true, 'Please enter password to update your profile');
  }
  $userCtrl->update($details['username'], $details['password'], $details['newDetails']);
}
function addCompany($details) {
  $userCtrl = new UserController();
  if(!isset($details['username'])) {
    $userCtrl->respond(true, 'Internal API error');
  }
  if(!isset($details['password'])) {
    $userCtrl->respond(true, 'Please enter password to add company to your profile');
  }
  $userCtrl->addCompany($details['username'], $details['password'], $details['newDetails']);
}
function addInstitute($details) {
  $userCtrl = new UserController();
  if(!isset($details['username'])) {
    $userCtrl->respond(true, 'Internal API error');
  }
  if(!isset($details['password'])) {
    $userCtrl->respond(true, 'Please enter password to add institute to your profile');
  }
  $userCtrl->addInstitute($details['username'], $details['password'], $details['newDetails']);
}
function deleteUser($details) {
  return json_encode([
    'error' => false,
    'message' => 'This API is not yet supported',
    'data' => $details
  ]);
}
/*
=================
  INSTITUTE API  
=================
 */
function getInstitute($details) {
  $instituteCtrl = new InstituteController();
  if(!isset($details['_id'])) {
    $instituteCtrl->respond(true, 'Please set a valid institute _id');
  } else {
    $instituteCtrl->retrieve($details['_id']);
  }
}
function getAllInstitutes($filters) {
  $instituteCtrl = new InstituteController();
  $instituteCtrl->retrieveAll($filters);
}
/*
===============
  COMPANY API  
===============
 */
function getCompany($details) {
  $companyCtrl = new CompanyController();
  if(!isset($details['_id'])) {
    $companyCtrl->respond(true, 'Please set a valid college _id');
  } else {
    $companyCtrl->retrieve($details['_id']);
  }
}
function getAllCompanies($filters) {
  $companyCtrl = new CompanyController();
  $companyCtrl->retrieveAll($filters);
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
