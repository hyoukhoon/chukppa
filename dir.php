<?php
echo "<pre>";
$path = "C:/xampp";
echo "path=>".$path."<br>";
    $dh = opendir($path);
    
        while(($file = readdir($dh)) !== false) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $fname = $file;
            
            echo $fname."<br>";
        }
        closedir($dh);
    

?>