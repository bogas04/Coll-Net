<?php
require_once ('controller.php');
require_once ('../model/post_model.php');

class PostController extends Controller {
  public function retrieve($_id) {
    try {
      $postModel = new PostModel($_id);
      $this->respond(false, 'Successfully retrieved ', $postModel->to_array());
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
  public function retrieveAll($filters) {
    try {
      $postModel = new PostModel();
      $posts = $postModel->retrieveAll($filters);
      $this->respond(false, 'Retrieved ' . count($posts). ' posts', $posts);
    } catch (Exception $e) {
      $this->respond(true, $e->getMessage());
    }
  }
}


