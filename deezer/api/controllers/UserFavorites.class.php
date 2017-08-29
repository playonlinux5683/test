<?
  class UserFavorites {
    public function __construct() {
      echo 'This is the UserFavorites class';
    }

    public function get ($uid, $sid) {
      echo 'This is User '. $uid;
      echo 'This is Song '. $sid;
    }

    public function post ($uid, $sid) {
      echo 'This is User '. $uid;
      echo 'This is Song '. $sid;
    }

    public function delete ($uid, $sid) {
      echo 'This is User '. $uid;
      echo 'This is Song '. $sid;
    }
  }
