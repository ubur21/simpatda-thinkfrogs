<style type="text/css">
/* menu styles */
#jsddm
{	margin: 0;
	padding: 0}

	#jsddm li
	{	float: left;
		list-style: none;
		font: 12px Tahoma, Arial}

	#jsddm li a
	{	display: block;
		background: #324143;
		padding: 5px 12px;
		text-decoration: none;
		border-right: 1px solid white;
		width: 70px;
		color: #EAFFED;
		white-space: nowrap}

	#jsddm li a:hover
	{	background: #24313C}
		
		#jsddm li ul
		{	margin: 0;
			padding: 0;
			position: absolute;
			visibility: hidden;
			border-top: 1px solid white}
		
			#jsddm li ul li
			{	float: none;
				display: inline}
			
			#jsddm li ul li a
			{	width: auto;
				background: #A9C251;
				color: #24313C}
			
			#jsddm li ul li a:hover
			{	background: #8EA344}
</style>

<script type="text/javascript">
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

function jsddm_open()
{	jsddm_canceltimer();
	jsddm_close();
	ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');}

function jsddm_close()
{	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{	closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{	if(closetimer)
	{	window.clearTimeout(closetimer);
		closetimer = null;}}

$(document).ready(function()
{	$('#jsddm > li').bind('mouseover', jsddm_open);
	$('#jsddm > li').bind('mouseout',  jsddm_timer);});

document.onclick = jsddm_close;
</script>
<!-- menu -->
<ul id="jsddm">
    <li><a href="#">Do</a>
        <ul>
        <?php if(!b_logged()) { echo '<li><a href=".">Login</a></li>';}
        else { echo '<li><a href="logout.php">Logout</a></li>'.
            '<li><a href="/admin/">Administration</a></li>';}
        ?>
        </ul>
    </li> 
    <?php if ($menus) { foreach($menus as $m) { echo '<li><a href="#" >'.$m[0].'</a>';
        echo '<ul>';
        for ($i = 1; $i < count($m); $i ++) { 
            echo '<li><a href="'.$m[$i][0].'">'.$m[$i][1].'</a></li>';
        }
        echo '</ul>';
        echo '</li>';
        } } ?>
</ul>

</script>
