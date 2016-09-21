<html>
<!doctype html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<?php

foreach($results as $item) {
  	echo profile_template($item->name, $item->age, $item->country);
}
?>
</body>
</html>
