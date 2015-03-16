<?php
/*
 * psudocode:
 * capture all directory names in an array - array1
 * capture all files within a directory in an array - array2
 * display the contents of array1 as a single drop down
 * when any of the items in array1 are selected display the contents of array2 as the next drop down.
 * 
 * (for testing) display the full path of the file as a link
 */

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
	echo "<b>Masachet:</b> $singleM<br>";
	echo "<b>Page #:</b> $singleP_formatted<br>";
	echo "<a href=\"$audiodir/$singleM/$singleP\"> Download </a>";
}

$masachet=getDirectoryList($audiodirectory);
$masachet_pagenum=getPageNumList($audiodirectory, $masachet);
//displayEverything($audiodirectory, $masachet, $masachet_pagenum);
?>
<html>
<head>
<title>Ohel Rachel - Daf Yomi</title>
<script language="JavaScript">
function GetSelectedItem() {
	len = document.dy.selectMasachetID.length
	i = 0
	chosen = "none"

	for (i = 0; i < len; i++) {
		if (document.dy.selectMasachetID[i].selected) {
		chosen = document.dy.selectMasachetID[i].value
		}
	}

	//return chosen
	alert(chosen)	
}
</script>

</head>
<body>

Select Daf by Masachet and Page#: <br/>
<form name="f1" action="index.php" method='post'>

<?php
//$testMasachet=$masachet[0];
//$testPage=0;
if (isset($_POST['selectMasachetID']) && $_POST['selectMasachetID']!="noselection") {
	echo "yes i come here in form f1<br>";
	$masSetValue=$_POST['selectMasachetID'];
	$showbutton=false;
} else {
	$masSetValue="";
	$showbutton=true;
}
displayMasachetDD($masachet,$masSetValue);
//displayPagesDD($masachet_pagenum, $testMasachet);
if ($showbutton) {
?>
<input type="submit" value="Next"/>
<?php 
}
?>
</form>

<?php
if(isset($_POST['selectMasachetID']) || isset($_POST['selectPageID'])) {
	$masSel=$_POST['selectMasachetID'];
	$pageSel=$_POST['selectPageID'];
	//echo "i come here 1. masSel value: $masSel<br>";
	if ($masSel != "noselection") {
		//echo "i come here 2<br>";
		?>
		<form name="f2" action="index.php?selectMasachetID='<?php echo $masSel; ?>'" method='post'>
		<?php
		if (isset($_POST['selectPageID']) && $_POST['selectPageID']!="noselection") {
			$pageSetValue=$_POST['selectPageID'];
			$showbutton2=false;
		} else {
			$pageSetValue="";
			$showbutton2=true;
		}
		displayPagesDD($masachet_pagenum, $masSel, $pageSetValue);
		if ($showbutton2) {
		?>
		<input type="submit" value="Submit"/>
		<?php 
		}
		?>
		</form>
		<?php
		/*if ($pageSel != "noselection") {
			echo "the value of the first DD is $masSel<br>";
			echo "the value of the second DD is $pageSel<br>";
		}*/
	}
}

echo "the value of the first DD is $masSel<br>";
echo "the value of the second DD is $pageSel<br>";

displayDownloadLink($audiodirectory, $testMasachet, $masachet_pagenum[$testMasachet][$testPage]);

?>
</body>
</html>