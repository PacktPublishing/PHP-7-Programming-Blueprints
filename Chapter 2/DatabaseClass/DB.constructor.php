Class DB {
   //constructor
   public $db;
  function __construct($server, $dbname,$user,$pass) {
    //returns mysqli $link $link = mysqli_connect('');
    return $this->db = mysqli_connect($server, $dbname, $user, $pass);
  }
}
