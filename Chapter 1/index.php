<?php
require('definitions.php');
class UserProfile {
  function fetch_all() {
       //…same lines ...
      
       $results = $results ??  NO_RESULTS_MESSAGE;
       return $message;   
  }

  function profile_template( $name, $age, $country ) {
      $name = $name ?? null;
      $age = $age ?? null;
      if($name == null || $age === null) {
          return 'Name or Age need to be set'; 
      } else {

          return '<div>

               <li>Name: ' . $name . ' </li>

               <li>Age: ' . $age . '</li>

               <li>Country:  ' .  $country . ' </li>

          </div>';
      }
  }
}
