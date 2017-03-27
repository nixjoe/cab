<?php
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Transfer-Encoding: binary');
header('Content-length: '.$filesize);
header('Content-Type: '.$filetype);
header('Content-Disposition: attachment; filename='.$filename);
print $value;
exit();
?>