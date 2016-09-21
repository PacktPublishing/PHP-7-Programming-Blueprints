 <?php
   require('definitions.php');
   require('db.php'); //our database class
 
   function paginate ($base_url, $rows_per_page, $total_rows) {
  
      $pagination_links = array(); //instantiate an array to hold our html pagelinks
      
      //we can use null coalesce to check if the inputs are  null

      ( $total_rows || $rows_per_page) ?? exit('Error: no rows per page and total rows');
      //we exit with an error message if this function is called incorrectly
      $pages =  $total_rows % $rows_per_page;
      $i= 0;
      $pagination_links[$i] =  '<a href="http://'. $base_url  .   '?pagenum='. $pagenum.'&rpp='.$rows_per_page. '">'  . $pagenum . '</a>';
 
       return $pagination_links;
         }


   function display_pagination($links) {
      $display = '<div class="pagination">
                  <table><tr>';
      foreach ($links as $link) {
               echo "<td>" . $link . "</td>";
      }
       
       $display .= '</tr></table></div>';
      
       return $display;
    }

//paginate the results
$mysqli = mysqli_connect('localhost','<username>','<password>',  '<dbname>');
    $limit = $_GET['rpp'] ?? 10;    //how many items to show per page -  default 10;
    $pagenum = $_GET['pagenum'];  //what page we are on
    if($pagenum)
      $start = ($pagenum - 1) * $limit; //first item to display on this page
    else
      $start = 0;      //if no page var is given, set start to 0
   
/*Display records here*/
   $sql = "SELECT * FROM userprofiles LIMIT $start, $limit ";
   $rs_result = mysqli_query ($sql); //run the query
   while($row = mysqli_fetch_assoc($rs_result) {
 ?>
<tr>
    <td><?php echo $row['country']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['age']; ?></td>
</tr>

 <?php }
   /* Let's show our page */
   /* get number of records through  */
  $record_count = $db->count_rows('userprofiles');
  $pagination_links =  paginate('listprofiles.php' , $limit, $rec_count);
  
  echo display_pagination($paginaiton_links);
?>









 ?>