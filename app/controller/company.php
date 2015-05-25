<?php
require_once ('controller.php');
require_once ('../model/company_model.php');

class CompanyController extends Controller {
  public function retrieve($_id) {
    try {
      $companyModel = new CompanyModel($_id);
      $this->respond(false, 'Successfully retrieved ', $companyModel->to_array());
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
  public function retrieveAll($filters) {
    try {
      $companyModel = new CompanyModel();
      $companies = $companyModel->retrieveAll($filters);
      $this->respond(false, 'Retrieved ' . count($companies). ' companies', $companies);
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
}

