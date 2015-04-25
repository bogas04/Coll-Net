<?php
require('company_model.php');

try {
  $u = new CompanyModel(); 
  $u->create([
    'name' => 'Google',
    'headquarter' => 'Hyderabad',
    'branch' => 'Bangalore'
  ]);

  echo "\nCREATE: Testing new CompanyModel\n";
  echo "\n===============================\n";
  print_r($u->to_array());  

  $u = new CompanyModel('google@gmail.com');
  echo "\nRETRIEVE: Testing new CompanyModel\n";
  echo "\n=========================================\n";
  print_r($u->to_array());  
  $u->update();

  echo "\nUPDATE: Testing new CompanyModel\n";
  echo "\n=================================================\n";
  print_r($u->to_array());  

  echo "\nDELETE: Testing new CompanyModel\n";
  echo "\n=================================================\n";
  $u->delete();
  print_r($u->to_array());
}
catch(Exception $e) {
  echo $e->getMessage(). "\n";
}

