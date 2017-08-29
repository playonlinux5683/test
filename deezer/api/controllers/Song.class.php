<?
  class Song {
    public function __construct() {
      echo 'This is the Song class';
    }

    public function get ($sid) {
      echo 'This is Song '. $sid;
    }

  }
