<?php

class Controller {
  public function respond($err, $msg, $data = []) {
    die(json_encode(['error' => $err, 'message' => $msg, 'data' => $data]));
  }
}
