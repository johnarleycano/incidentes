<?php
//file_put_contents('graph.png',file_get_contents('http://chart.apis.google.com/chart?chs=600x300&chd=t:21,14,5,47,40,3,2&cht=p&.png'));

header("Content-Type: image/png");
echo file_get_contents("http://chart.apis.google.com/chart?cht=p3&chd=s:hW&chs=250x100&chl=Hello|World");


?> 
