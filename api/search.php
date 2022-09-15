<?php
$get_search_query = htmlentities($_POST['search_query']);
header("Location: ../search.php?q=$get_search_query")
?>
