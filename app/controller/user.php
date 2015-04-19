<?php
require_once ('controller.php');
require_once ('../model/user_model.php');

class UserController extends Controller{

  public function login($username, $password) {
    try {
      $u = new UserModel($username, $password);
      session_start();
      $_SESSION['user'] = $u->to_array();
    } catch (Exception $e) {
      respond(true, $e->getMessage());
    }
  } 
  public function logout($username) {
    try {
      session_start();
      unset($_SESSION['user']);
      session_destroy();
    } catch (Exception $e) {
      respond(true, $e->getMessage);
    }
  }
  public function isLoggedIn($username) {
    return $_SESSION 
      && isset($_SESSION['user'])
      && isset($_SESSION['user']['username'])
      && isset($_SESSION['user']['hashed_password'])
      && $_SESSION['user']['username'] === $username;
  }
  public function changePassword($username, $old_password, $new_password) {
    // TODO :
    // 1) Check if logged in, if not, destroy session and unset everything
    // 2) Check if old === new, if yes then respond(true, 'choose a different password')
    // 3) Check if username exists [being in session doesn't necessarily mean that user is in DB]
    // 4) Finally accept the new password and make changes to database (using only the model functions)

  }
  public function profile($username) {
    try {
      respond(false, 'successfully got the data', (new UserModel($username))->to_array());
    } catch (Exception $e) {
      respond(true, $e->getMessage);
    }
  }
  // create similar API functions to retrieve stuff 
}

