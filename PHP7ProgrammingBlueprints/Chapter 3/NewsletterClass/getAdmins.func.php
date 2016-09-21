//get admins = marketers
public function get_admins ($newsletter_id) {
  $query = “SELECT * FROM newsletter_admins LEFT JOIN marketers ON marketers.id = newsletter_admins.admin_id.WHERE newsletters_admins.newsletter_id = ‘”.$newsletter_id.”’”;
  $this->db->query($query);
}

