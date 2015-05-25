<?php
require_once ('controller.php');
require_once ('../model/institute_model.php');

class InstituteController extends Controller{
  public function retrieve($_id) {
    try {
      $instituteModel = new InstituteModel($_id);
      $this->respond(false, 'Successfully retrieved ', $instituteModel->to_array());
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
  public function retrieveAll($filters) {
    try {
      $instituteModel = new InstituteModel();
      $institutes = $instituteModel->retrieveAll($filters);
      $this->respond(false, 'Retrieved ' . count($institutes). ' institutes', $institutes);
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
}

