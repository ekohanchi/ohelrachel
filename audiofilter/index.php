<?php
error_reporting(0);
/*
 * psudocode:
 * capture all directory names in an array - array1
 * capture all files within a directory in an array - array2
 * display the contents of array1 as a single drop down
 * when any of the items in array1 are selected display the contents of array2 as the next drop down.
 * 
 * (for testing) display the full path of the file as a link
 */

//$audiodirectory="audiosamples";
$audiodirectory="audio";

function cutExtention ($pagenum) {
	return str_replace(".mp3","",$pagenum);
}

function removeSpace ($mas) {
	return str_replace(" ","",$mas);
}

function getDirectoryList ($rootdir) {
	// Read the list of directories into an array
	$masachet=array();
	$m=0;
	if (is_dir($rootdir)) {
		if ($dh=opendir($rootdir)) {
			while (($dir = readdir($dh)) != false) {
				$filetype=filetype("$rootdir/" . $dir);
				if (($filetype=="dir") && ($dir!=".") && ($dir!="..")) {
					$masachet[$m++]=$dir;
				}	
			}
			closedir($dh);
		}
	}
	return $masachet;
}

function getPageNumList ($rootdir, $mas) {
	// For each masachet directory gets the list of page numbers in the directory
	$pagenum=array();
	//$p=0;
	//echo "root dir value: $rootdir";
	//echo "<br> masdir value: $mas[1]<br>";
	$masCount=count($mas);
	for($p=0; $p<$masCount; $p++) {			//mas is an array holding all the masachet directories
		$fulldir="$rootdir/$mas[$p]";
		//echo "full dir value: $fulldir<br>";
		if (is_dir($fulldir)) {	
			if ($dh=opendir($fulldir)) {
				$ppm=0;		//pages per masachet
				while (($file=readdir($dh)) !== false) {
					$filetype=filetype("$fulldir/" . $file);
					if (($file!=".") && ($file!="..")) {
						//echo "the value of file is: $file<br>";
						//echo "the value of masdir[p] is: $mas[$p]<br>";
							//original
						//$pagenum[$mas[$p]]=$file;
						$pagenum[$mas[$p]][$ppm++]=$file;
					}
				}
			}
		}
	}
	return $pagenum;
}

function displayEverything ($audiodirectory, $masachet, $masachet_pagenum) {
	$mpCount=count($masachet_pagenum);
	for($mp=0; $mp<$mpCount; $mp++) { 
		$which_masachet=$masachet[$mp];
		//echo "which masachet value: $which_masachet<br>";
		$ppm_count=count($masachet_pagenum[$which_masachet]);				//pages per masachet
		//echo "ppm_count value is: $ppm_count<br>";
		//echo "value is: $masachet_pagenum[$which_masachet]<br>";
		for($ppm=0; $ppm<$ppm_count; $ppm++) {
			$pagenum=$masachet_pagenum[$which_masachet][$ppm];
			echo "$audiodirectory/$which_masachet/$pagenum<br>";
			//echo "$masachet_pagenum[$which_masachet]";
		}
	}
}

function displayMasachetDD($masachet, $selection) {
	echo "<select name=\"selectMasachetID\">";
	echo "<option value=\"noselection\">Select Masachet</option>";
	for($m=0; $m<count($masachet); $m++) {
		//$masachet_formatted=removeSpace($masachet[$m]);
		if ($masachet[$m]==$selection) {
			echo "<option value=\"$masachet[$m]\" selected>$masachet[$m]</option><br>";
		} else {
			echo "<option value=\"$masachet[$m]\">$masachet[$m]</option><br>";
		}
		
	}
	echo "</select>";
}

function displayPagesDD($masachet_pagenum, $singleMasachet, $selection) {
	echo "<select name=\"selectPageID\">";
	echo "<option value=\"noselection\">Select Page #</option>";
	$ppm_count=count($masachet_pagenum[$singleMasachet]);
	for ($ppm=0; $ppm<$ppm_count; $ppm++) {
		$pagenum=$masachet_pagenum[$singleMasachet][$ppm];
		$pagenum_formatted=cutExtention($pagenum);
		if ($pagenum_formatted==$selection) {
			$selected="selected";
		} else {
			$selected="";
		}
		echo "<option value=\"$pagenum_formatted\" $selected>$pagenum_formatted</option><br>";
	}
	echo "</select>";
}

function displayDownloadLink($audiodir, $singleM, $singleP) {
	$singleP_formatted=cutExtention($singleP);
	//echo "singleP value: $singleP. singleP_formatted value: $singleP_formatted";
	echo "<br><br><b>Masachet:</b> $singleM<br>";
	echo "<b>Page #:</b> $singleP_formatted<br>";
	if($singleP != "") {
		echo "<a href=\"$audiodir/$singleM/$singleP\"> Download </a>";
	}
}

function findKeyInPageNumArray($masachet_pagenum, $singleMasachet, $seed) {
	$mpnArray_size=count($masachet_pagenum[$singleMasachet]);
	for ($kc=0; $kc<$mpnArray_size; $kc++) {
		$pos=strpos($masachet_pagenum[$singleMasachet][$kc], $seed);
		if ($pos===0) {
			return $kc;
		}
	}
}

$masachet=getDirectoryList($audiodirectory);
$masachet_pagenum=getPageNumList($audiodirectory, $masachet);
//displayEverything($audiodirectory, $masachet, $masachet_pagenum);
?>
<html>
<head>
<title>Ohel Rachel - Daf Yomi</title>
</head>
<body>

Select Daf by Masachet and Page#: <br/>
<?php
$pageSetValue="";
if ((isset($_POST['selectMasachetID']) && $_POST['selectMasachetID']!="noselection") || (isset($_GET['selectMasachetID']) && $_GET['selectMasachetID']!="noselection")) {
	if (isset($_POST['selectMasachetID'])) {
		$masSetValue=$_POST['selectMasachetID'];
	} elseif (isset($_GET['selectMasachetID'])) {
		$masSetValue=str_replace("'","",stripcslashes($_GET['selectMasachetID']));
	}
	displayMasachetDD($masachet,$masSetValue);
	//display form f2
	if (isset($_POST['selectPageID']) && $_POST['selectPageID']!="noselection") {
		$pageSetValue=$_POST['selectPageID'];
		displayPagesDD($masachet_pagenum, $masSetValue, $pageSetValue);
		?>
		<br/><input type="button" value="Start Over" onclick="window.location.href='index.php'">
		<?php
	} else {
		$pageSetValue="";
		?>
		<form name="f2" action="index.php?selectMasachetID='<?php echo $masSetValue; ?>'" method='post'>
			<?php displayPagesDD($masachet_pagenum, $masSetValue, $pageSetValue); ?>
			<input type="submit" value="Submit"/>
		</form>
		<?php
	}
} else {
	$masSetValue="";
	?>
	<form name="f1" action="index.php" method='post'>
		<?php displayMasachetDD($masachet,$masSetValue); ?>
	<input type="submit" value="Next"/>
	</form>
	<?php
}

$mpnKey=findKeyInPageNumArray($masachet_pagenum, $masSetValue, $pageSetValue);
displayDownloadLink($audiodirectory, $masSetValue, $masachet_pagenum[$masSetValue][$mpnKey]);

//$testMasachet=$masachet[0];
//$testPage=0;
//displayMasachetDD($masachet,$masSetValue);
//displayPagesDD($masachet_pagenum, $testMasachet);

// DEBUG
/*
echo "<br><br>the value of the first DD is <b>$masSetValue</b><br>";
echo "the value of the second DD is <b>$pageSetValue</b><br>";
echo "the value of the key in masachet_pagenum array is: $mpnKey<br>";
*/



?>
</body>
</html>