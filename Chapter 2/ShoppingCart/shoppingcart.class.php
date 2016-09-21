//api-db layer

//inc/shoppingcart.class.php

class ShoppingCart extends DbObject {
	function __construct($connection) {
	    //instantiate $db = new DBObject();
		$this->db = new DbObject($connection);
	}

	//save a bunch of database objects 
	function saveCart($userId, $items) {
		$this->db->save($items);
	}

	//drop the cart (close session)
	function dropCart() {
		
	}
	
	//transform shoppingcart data to orders
	function checkout() {
		$this->db->save($items)
	}

}

