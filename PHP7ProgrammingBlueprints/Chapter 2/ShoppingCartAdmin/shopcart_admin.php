<?php
//create an html variable for printing the html of our page:
$html = ‘<!DOCTYPE html><html><body>’;

$table = ‘purchases’;
$results = $db->select_all($table);

//start a new table structure and set the headings of our table:
$html .= ‘<table><tr>
    <th>Customer name</th>
    <th>Email</th>
    <th>Address</th>
    <th>Total Purchase</th>
</tr>’;

//loop through the results of our query:
while($row = $results->fetch_assoc()){
    $html .= ‘<tr><td><a href="' . $row[''] .'">’$row[‘customer_name’] . '</a></td>';
    $html .= ‘<td>’$row[‘email’] . '</td>';
    $html .= ‘<td>’$row[‘address’] . '</td>';
    $html .= ‘<td>’$row[‘purchase_date’] . '</td>';
    $html .= ‘</tr>’;
}

$html .= ‘</table>’;
$html .= ‘</body></html>;

//print out the html
echo $html;
