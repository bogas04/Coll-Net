<?php
require('user_model.php');

try {
  $u = new UserModel(); 
  $u->create([
    'username' => 'divjot94',
    'password' => 'hallo',
    'email' => 'bogas04@gmail.com'
  ]);

  echo "\nCREATE: Testing new UserModel()\n";
  echo "\n===============================\n";
  print_r($u->to_array2());  

  $u = new UserModel('divjot94');
  echo "\nRETRIEVE: Testing new UserModel(username)\n";
  echo "\n=========================================\n";
  print_r($u->to_array2());  

  $u = new UserModel('divjot94', 'hallo');
  $u->name = "Divjot";
  $u->location = [
    'city' => 'new delhi',
    'state' => 'new delhi',
    'country' => 'india'
  ];
  $u->update();

  echo "\nUPDATE: Testing new UserModel(username, password)\n";
  echo "\n=================================================\n";
  print_r($u->to_array2());  


  $u = new UserModel('divjot94', 'hallo');
  echo "\nDELETE: Testing new UserModel(username, password)\n";
  echo "\n=================================================\n";
  $u->delete();
  print_r($u->to_array2());
}
catch(Exception $e) {
  echo $e->getMessage(). "\n";
}

