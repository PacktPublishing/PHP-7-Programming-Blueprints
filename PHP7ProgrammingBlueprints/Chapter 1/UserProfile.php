<?php

class UserProfile {

    private $table = 'user_profiles';
  

    public function fetch_one($id) {
        $link = mysqli_connect('');
        $query = "SELECT * from ". $this->table . " WHERE `id` =' " .  $id "'";
        $results = mysqli_query($link, $query);
    }

    public function fetch_all() {
        $link = mysqli_connect('127.0.0.1', 'root','apassword','my_database' );
        $query = "SELECT * from `". $this->table . "`";
        $results = mysqli_query($link, $query);
    }

    public function insert_profile($values)  {

        $link =  mysqli_connect('127.0.0.1', 'username','password', 'databasename');   

         $q = " INSERT INTO " . $this->table . " VALUES ( '". $values['name']."', '".$values['age'] . "' ,'".      $values['country']. "')";
   
        return mysqli_query($q);

 }

}

