<?php

$m = new MongoClient("mongodb://127.0.0.1:27017");
$db = $m->selectDB('collnet');

$userCollection = new MongoCollection($db, 'users'); 

$userCollection->remove();

$userCollection->insert(['name' => 'akanshi sucks ' . rand(). " times"]);
$userCollection->insert(['name' => "chitra"]);

$userCollection->findAndModify(['name' => 'chitra'],['$set' => [
  'age' => 21 + rand()%5, 
  'gender' => 'unknown'
]] );

foreach($userCollection->find() as $doc) {
  echo "The name is ". $doc['name'] . "\n";
  if(in_array('age', array_keys($doc)))echo "  The age is ". $doc['age'] . "\n";
}



