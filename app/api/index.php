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
//require_once('../controller/institute.php');
//require_once('../controller/company.php');
//require_once('../controller/post.php');
//require_once('../controller/comment.php');


/*
===========
  ROUTING  
===========
 */
switch($_REQUEST['action']) {
case 'login':       login($_POST); break;
case 'logout':      logout(); break;
case 'loginStatus': loginStatus(); break;  
case 'signup':      signup($_POST); break;
case 'getUser':     getUser($_GET); break;
case 'updateUser':  updateUser($_POST); break;
case 'deleteUser':  deleteUser($_GET); break;

case 'createPost': die(createPost($_POST)); break;
case 'getPost':    die(getPost($_GET)); break;
case 'updatePost': die(updatePost($_POST)); break;
case 'deletePost': die(deletePost($_GET)); break;

case 'createComment': die(createComment($_POST)); break;
case 'getComment':    die(getComment($_GET)); break;
case 'updateComment': die(updateComment($_POST)); break;
case 'deleteComment': die(deleteComment($_GET)); break;

case 'getInstitute': die(getInstitute($_GET)); break;

case 'getCompany': die(getCompany($_GET)); break;
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

//[
  //'_id' => 1, 
  //'name' => 'Divjot Singh',
  //'email' => 'bogas04@gmail.com',
  //'username' => 'bogas04',
  //'image' => 'assets/img/divjot.jpg',
  //'educationHistory' => [
    //[
      //'id' => 523,
      //'name' => "St. Mary's School, Dwarka",
      //'location' => 'New Delhi, India',
      //'rating' => 4.3,
      //'duration' => '2003-2012',
      //'course' => 'C.B.S.E Boards'
    //],
    //[
      //'id' => 756,
      //'name' => 'Netaji Subhas Institute Of Technology',
      //'location' => 'New Delhi, India',
      //'rating' => 4.5,
      //'duration' => '2012-Present',
      //'course' => 'Computer Engineering, B.E.'
    //]
  //],
  //'workHistory' => [
    //[
      //'id' => 123,
      //'name' => 'Frrole',
      //'title' => 'Frontend Web Development Intern',
      //'duration' => 'Dec, 2013 - Jan, 2014',
      //'description' => 'Bla bla bla'
    //],
    //[
      //'id' => 234,
      //'name' => 'Refiral',
      //'title' => 'Product Developer',
      //'duration' => 'Oct, 2013 - Oct, 2013',
      //'description' => 'Bla bol bla'
    //],
    //[
      //'id' => 634,
      //'name' => 'Rise Consulting',
      //'title' => 'Software Developer',
      //'duration' => 'Jan, 2015 - March, 2015',
      //'description' => 'bla bla bla'
    //]
  //],
  //'social' => [
    //'facebook' => 'https://facebook.com/divjot94'
  //]
//]
function updateUser($details) {
  return json_encode([
    'error' => false,
    'msg' => 'Successfully updated!',
    'data' => $details
  ]);
}
function deleteUser($details) {
  return json_encode([
    'error' => false,
    'msg' => 'Successfully deleted!',
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

