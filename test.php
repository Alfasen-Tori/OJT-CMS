<?php
$host = 'smtp.gmail.com';
$port = 465;

$connection = @fsockopen($host, $port, $errno, $errstr, 10);

if (!$connection) {
    echo "Connection failed: [$errno] $errstr";
} else {
    echo "✅ Connection successful to $host:$port";
    fclose($connection);
}
?>
