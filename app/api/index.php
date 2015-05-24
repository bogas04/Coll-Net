<?php 

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
case 'loginStatus': 
  die(json_encode([
    'error' => false, 
    'msg' => 'User is logged in', 
    'data' => [
      '_id' => 1, 
      'name' => 'Divjot Singh',
      'email' => 'bogas04@gmail.com',
      'username' => 'bogas04',
      'image' => 'assets/img/divjot.jpg',
      'educationHistory' => [
        [
          'id' => 523,
          'name' => "St. Mary's School, Dwarka",
          'location' => 'New Delhi, India',
          'rating' => 4.3,
          'duration' => '2003-2012',
          'course' => 'C.B.S.E Boards'
        ],
        [
          'id' => 756,
          'name' => 'Netaji Subhas Institute Of Technology',
          'location' => 'New Delhi, India',
          'rating' => 4.5,
          'duration' => '2012-Present',
          'course' => 'Computer Engineering, B.E.'
        ]
      ],
      'workHistory' => [
        [
          'id' => 123,
          'name' => 'Frrole',
          'title' => 'Frontend Web Development Intern',
          'duration' => 'Dec, 2013 - Jan, 2014',
          'description' => 'Bla bla bla'
        ],
        [
          'id' => 234,
          'name' => 'Refiral',
          'title' => 'Product Developer',
          'duration' => 'Oct, 2013 - Oct, 2013',
          'description' => 'Bla bol bla'
        ],
        [
          'id' => 634,
          'name' => 'Rise Consulting',
          'title' => 'Software Developer',
          'duration' => 'Jan, 2015 - March, 2015',
          'description' => 'bla bla bla'
        ]
      ],
      'social' => [
        'facebook' => 'https://facebook.com/divjot94'
      ]
    ]
  ])); 
  break;  

case 'createUser': die(createUser($_POST)); break;
case 'getUser':    die(getUser($_GET)); break;
case 'updateUser': die(updateUser($_POST)); break;
case 'deleteUser': die(deleteUser($_GET)); break;

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
function createUser($details) {
  return json_encode([
    'error' => false,
    'msg' => 'Successfully created!',
    'data' => $details
  ]);
}
function getUser($details) {
  return json_encode([
    'error' => false,
    'msg' => 'User found',
    'data' => [
      'name' => 'Divjot Singh',
      'educationHistory' => [
        [
          'id' => 523,
          'name' => "St. Mary's School, Dwarka",
          'location' => 'New Delhi, India',
          'rating' => 4.3,
          'duration' => '2003-2012',
          'course' => 'C.B.S.E Boards'
        ],
        [
          'id' => 756,
          'name' => 'Netaji Subhas Institute Of Technology',
          'location' => 'New Delhi, India',
          'rating' => 4.5,
          'duration' => '2012-Present',
          'course' => 'Computer Engineering, B.E.'
        ]
      ],
      'workHistory' => [
        [
          'id' => 123,
          'name' => 'Frrole',
          'description' => 'Bla bla bla'
        ],
        [
          'id' => 234,
          'name' => 'Refiral',
          'description' => 'Bla bol bla'
        ],
        [
          'id' => 634,
          'name' => 'Rise Consulting',
          'description' => 'bla bla bla'
        ],
      ]
    ]
  ]);
}
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

