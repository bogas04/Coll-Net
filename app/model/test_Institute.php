<?php
require('college_model.php');

try {
  $u = new InstituteModel(); 
  $u->create([
    'name' => 'Netaji Subhas Institute of technology',
    'location' => 'Dwarka,Delhi',
    'email' => 'info@nsit.ac.in'
  ]);

  echo "\nCREATE: Testing new InstituteModel()\n";
  echo "\n===============================\n";
  print_r($u->to_array());  

  $u = new InstituteModel('info@nsit.ac.in');
  echo "\nRETRIEVE: Testing new UserModel(username)\n";
  echo "\n=========================================\n";
  print_r($u->to_array());  

  $u = new InstituteModel('info@nsit.ac.in');
  $u->name = "DTU";
  $u->location ="Rohini,Delhi";
  $u->update();

  echo "\nUPDATE: Testing new InstituteModel(email)\n";
  echo "\n=================================================\n";
  print_r($u->to_array());  


  $u = new InstituteModel('info@nsit.ac.in');
  echo "\nDELETE: Testing new UserModel(username, password)\n";
  echo "\n=================================================\n";
  $u->delete();
  print_r($u->to_array());
}
catch(Exception $e) {
  echo $e->getMessage(). "\n";
}

