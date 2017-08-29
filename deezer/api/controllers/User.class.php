<?
  class User {
    public function __construct() {
      echo 'This is the User class';
    }

    public function get ($id) {
      echo 'This is User '. $id;
    }
  }
