Class DB {
   //constructor
   public $db;
  public function __construct($server, $dbname,$user,$pass) {
    //returns mysqli $link $link = mysqli_connect('');
    return $this->db = mysqli_connect($server, $dbname, $user, $pass);
  }

 public function query($sql) {
 $results =   $this->db->query($sql);
 return $results;
}

public function read($table, $key, $value){
         $query  = SELECT * FROM $table WHERE `”. $key . “` =  “ . $value;
     return $this->db->query($query);
}

public function select_all($table){
         $query  = “SELECT * FROM “ . $table;
     return $this ->query($query);
}

public function delete($table, $key, $value){
         $query  = DELETE FROM $table WHERE `”. $key . “` =  “ . $value;
     return $this->query($query);
}
function update($table, $updateSetArray, $where){
     Foreach($updateSetArray as $key => $value) {
         $update_fields .= $key . “=” . $value . “,”;
     }
      //remove last comma from the foreach loop above
     $update_fields = substr($update_fields,0, str_len($update_fields)-1);
    $query  = “UPDATE “ . $table. “ SET “ . $updateFields . “ WHERE “ $where; //the where
    return $this->query($query);
}

public function first_of($results) {
  return reset($results);
}


public function last_of($results) {
  Return end($results);
}


public function iterate_over($prefix, $postfix, $items) {
    $ret_val = ‘’;
    foreach($items as $item) {
        $ret_val .= $prefix. $item . $postfix;
    }
    return $ret_val;
}


}
