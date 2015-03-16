<?php
/*
echo "counter test:<br><br>";
$mas=$masachet[0];
$val=$masachet_pagenum[$mas][0];	// page number only
echo "value: $val<br>";
$counter=count($masachet_pagenum[$mas]); //pages per masachet
//echo "masachet value: $mas<br>";
echo "counter value: $counter<br>";
echo "<br><br>";

$mpCount=count($masachet_pagenum);
for ($p=0; $p<$mpCount; $p++) {
	$mas=$masachet[$p];
	echo "$audiodirectory$mas/$masachet_pagenum[$mas]<br>";
}
echo "brute force: ";
//$t1=$masachet[0];
//echo "$audiodirectory$t1/$masachet_pagenum[$t1]";
*/

/*

function getFileList ($directory) 
{
	// create an array to hold directory list
	$results = array();
	
	// create a handler for the directory
	$handler = opendir($directory);
	
	// open directory and walk through the filenames
	while ($file = readdir($handler)) {
		// if file isn't this directory or its parent, add it to the results
		if ($file != "." && $file != "..") {
			$results[] = $file;
		}
	}
	
	// tidy up: close the handler
	closedir($handler);
	
	// done!
	return $results;
}




// displaying allt he masachets in the audio dir
echo "<br><br><br>";
$numofmasachet=count($masachet);
foreach ($masachet as $i => $value) {
	echo "$numofmasachet $masachet[$i]<br>";
}

echo "<br>";




//echo "number of directories is: $numofmasachet<br>";

/*foreach $dir in .exec('ls -1 ' .$audiodirectory); do {
	echo $dir;
}*/
/*foreach (exec('ls -1 ' .$audiodirectory) as $key=>$value) {
	echo "key is: $key<br>";
	echo "value is: $value";
}*/

//$masachet=exec('ls -1 ' .$audiodirectory);
//$masachet=exec('for dir in \'ls -1 ' .$audiodirectory .'/*\'; do echo $dir; done');
/*
echo "<br><br>";
echo "audio dir is: $audiodirectory";
echo "<br><br>";
//echo "masachets are: $masachet";
echo "<br><br>";
//echo "i com here now";
*/




/*// Read the list of directories into an array
if (is_dir($audiodirectory)) {
	if ($dh=opendir($audiodirectory)) {
		while (($dir = readdir($dh)) !== false) {
			$filetype=filetype($audiodirectory . $dir);
			if (($filetype=="dir") && ($dir!=".") && ($dir!="..")) {
				$masachet[$m++]=$dir;
				//echo "directory name: $dir. directory type: " . filetype($audiodirectory . $dir) . "<br>";
			}	
		}
		closedir($dh);
	}
}*/
?>