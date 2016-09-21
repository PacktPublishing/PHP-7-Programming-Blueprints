require('DB.class.php');

$db = new DBClass(‘localhost’,’root’,’password’, ‘databasename’);
We shall now query our database with our database class:


$table = ‘purchases’;
$column = ‘id’; 
$findVal = $_GET[‘purchase_id’];

   $result = $db->read ($table, $column, $findVal);

foreach($item = $result->fetch_assoc()) {
$html .=   "<tr>
		<td>”. $item[‘customer_name’]. “</td>
		<td>” . $item[‘items’] . ”
</tr>";

$total = $items[‘total’]; //let’s save the total in a variable for printing in a new row

}

$html .= ‘<tr><td colspan=”2” align=”right”>TOTAL: ‘ “.$total. ” ’ </td></tr>’;

$html .= <<<EOD
</table>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output(‘customer_invoice.pdf', 'I');

