<h2>Record</h2>
<?
	echo "<h1>".$record['label']."</h1>";
	echo "<h2>".$record['uri']."</h2>";

?>
<h2>Categories</h2>
<?
	if (is_array($categories) ==true) {
		foreach ($categories as $c) {
			echo $c['label'].'<br />';
		}
	}

?>


<h2>SameAs Concepts</h2>
<?
	if (is_array($sameAs) == true) {
		foreach ($sameAs as $s) {
			echo $s['uri'].'<br />';
		}
	}

?>

<h2>Suggested SameAs Concepts</h2>
<?
if (is_array($sameAsSuggestions) == true) {
	foreach ($sameAsSuggestions as $suggestion) {
		echo '<a href="/lca/addSameAs?ft_id='.str_replace("http://footprinted.org/rdfspace/lca/","",$record['uri']).'&opencyc_id='.str_replace("http://sw.opencyc.org/concept/", "", $suggestion['uri']) . '" target="_blank">'.$suggestion['label'].'</a><br />';
	}
}
?>
<h2>Suggested Categories</h2>
<?
	foreach ($categorySuggestions as $suggestion) {
		echo '<a href="/lca/addCategory?ft_id='.str_replace("http://footprinted.org/rdfspace/lca/","",$record['uri']).'&opencyc_id='.str_replace("http://sw.opencyc.org/concept/", "", $suggestion['uri']) . '" target="_blank">'.$suggestion['label'].'</a><br />';
	}

echo '<br /><br /><a href="/admin/assignCategory/'.$next.'" />Next</a>';
?>