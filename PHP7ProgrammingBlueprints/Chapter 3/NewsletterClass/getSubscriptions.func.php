

public function getSubscriptions($member_id) {
  $queryDb = $db->query("SELECT * FROM subscriptions, newsletters WHERE subscriptions.member_id ='". $member_id."'");
  if($queryDb->num_rows() > 0) {
      while ($row = $result->fetch_assoc()) {
          return array( 
            $row->newsletter_name, 
            $row->newsletter_count,
            $row->member_id, 
            $row->active
         );

      }
  } 
}

