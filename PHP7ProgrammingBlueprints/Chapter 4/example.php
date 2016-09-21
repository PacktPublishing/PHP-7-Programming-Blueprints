<?php
/* 
this example is used to convert any doc format to text
author: Gourav Mehta
author's email: gouravmehta@gmail.com
author's phone: +91-9888316141
*/ 
*/  
require("doc2txt.class.php");

$docObj = new Doc2Txt("test.docx");
//$docObj = new Doc2Txt("test.doc");

$txt = $docObj->convertToText();
echo $txt;
?>