Class Authorization {
     public function verify($email, $password) {
    	//check for the $email and password encrypted with bcrypt
	$bcrypt_options = [
            ‘cost’ => 12,
            ‘salt’ => ‘secret’
        ];
	$password_hash = password_hash($password, PASSWORD_BCRYPT, $bcrypt_options);
	
	$q= “SELECT * FROM users WHERE email = ‘”. $email. “’ AND password = ‘”.$password_hash. ”’”;
		if($result = $this->db->query($q)) {
	while ($obj = results->fetch_object()) {
		$user_id = $obj->id;
       }
		
        } else {
	$user_id = null;
}
		$result->close();
		$this->db->close();
		return $user_id;
 	
    }
}

