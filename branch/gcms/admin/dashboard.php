<?php
include('lastrss.php');
$orss = new lastRSS; 
  
// setup transparent cache
// $rss->cache_dir = './cache'; 
$orss->cache_time = 3600; // one hour
  
// load some RSS file
if ($ars = $orss->get('http://bdg.bedeng.com/category/news/feed/')) {
  	// here we can work with RSS fields
?>
<table class="table_admin2">
<tr>
	<td><h1>News</h1></td>
</tr>
<?php 
for($ii=0;$ii<$ars['items_count'];$ii++) { 
?>
	<tr>
		<td align="left">
		<? echo $ars['items'][$ii]['pubDate'] ?><br>
		<font size="+1"><b><a href="<? echo $ars['items'][$ii]['link'] ?>" target="_blank" style="text-decoration: none;"><? echo $ars['items'][$ii]['title'] ?></b></font></a>
		<p align="justify"><? echo $ars['items'][$ii]['description'] ?></p>		
		</td>
	</tr>
<?php 
} 
?>
</table>
<?php
} else {
  	//die ("Internet connection error");
}
?>