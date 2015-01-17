  
<!--	<style type="text/css">
html {
	overflow-Y: scroll;
}
body {
	font: 10px normal Arial, Helvetica, sans-serif;
	margin: 0;
	padding: 0;
	line-height: 1.7em;
}
*, * focus {
	outline: none;
	margin: 0;
	padding: 0;
}

.container {
	width: 500px;
	margin: 0 auto;
}
h1 {
	font: 4em normal Georgia, 'Times New Roman', Times, serif;
	text-align:center;
	padding: 20px 0;
	color: #aaa;
}
h1 span { color: #666; }
h1 small{
	font: 0.3em normal Verdana, Arial, Helvetica, sans-serif;
	text-transform:uppercase;
	letter-spacing: 1.5em;
	display: block;
	color: #666;
}
h2.trigger {
	padding: 0 0 0 50px;
	margin: 0 0 5px 0;
	background: url(h2_trigger_a.gif) no-repeat;
	height: 46px;
	line-height: 46px;
	width: 450px;
	font-size: 2em;
	font-weight: normal;
	float: left;
}
h2.trigger a {
	color: #fff;
	text-decoration: none;
	display: block;
}
h2.trigger a:hover {
	color: #ccc;
}
h2.active {background-position: left bottom;}
.toggle_container {
	margin: 0 0 5px;
	padding: 0;
	border-top: 1px solid #d6d6d6;
	background: #f0f0f0 url(toggle_block_stretch.gif) repeat-y left top;
	overflow: hidden;
	font-size: 1.2em;
	width: 500px;
	clear: both;
}
.toggle_container .block {
	padding: 20px;
	background: url(toggle_block_btm.gif) no-repeat left bottom;
}
.toggle_container .block p {
	padding: 5px 0;
	margin: 5px 0;
}
.toggle_container h3 {
	font: 2.5em normal Georgia, "Times New Roman", Times, serif;
	margin: 0 0 10px;
	padding: 0 0 5px 0;
	border-bottom: 1px dashed #ccc;
}
.toggle_container img {
	float: left;
	margin: 10px 15px 15px 0;
	padding: 5px;
	background: #ddd;
	border: 1px solid #ccc;
}
</style>-->

<script type="text/javascript">
$(document).ready(function(){
	
	$(".toggle_container").hide();

	$("h2.trigger").click(function(){
		$(this).toggleClass("active").next().slideToggle("slow");
	});

});
</script>
<div class="container">

	<h2 class="trigger"><a href="#">Web Design &amp; Development</a></h2>
	<div class="toggle_container">
		xxxxx
	</div>
	
	<h2 class="trigger"><a href="#">Logo / Corporate Identity</a></h2>

	<div class="toggle_container">
		yyyyy

	</div>
	
	<h2 class="trigger"><a href="#">Seach Engine Optimization</a></h2>
	<div class="toggle_container">
		<div class="block">
			<h3>Need to be Heard?</h3>
			<img src="thumbnail.gif" alt="" />
			<p>Consequat te olim letalis premo ad hos olim odio olim indoles ut venio iusto. Euismod, sagaciter diam neque antehabeo blandit, jumentum transverbero luptatum. Lenis vel diam praemitto molior facilisi facilisi suscipere abico, ludus, at. Wisi suscipere nisl ad capto comis esse, autem genitus. Feugiat immitto ullamcorper hos luptatum gilvus eum. Delenit patria nunc os pneum acsi nulla magna singularis proprius autem exerci accumsan. </p>

			
			<p>Praesent duis vel similis usitas camur, nostrud eros opes verto epulae feugiat ad. Suscipit modo magna letalis amet et tego accumsan facilisi, meus. Vindico luptatum blandit ulciscor mos caecus praesent sed meus velit si quis lobortis praemitto, uxor. </p>
		</div>
	</div>
	
	<h2 class="trigger"><a href="#">eCommerce</a></h2>
	<div class="toggle_container">
		<div class="block">
			<h3>Have Product to Sell?</h3>

			<img src="thumbnail.gif" alt="" />
			<p>Consequat te olim letalis premo ad hos olim odio olim indoles ut venio iusto. Euismod, sagaciter diam neque antehabeo blandit, jumentum transverbero luptatum. Lenis vel diam praemitto molior facilisi facilisi suscipere abico, ludus, at. Wisi suscipere nisl ad capto comis esse, autem genitus. Feugiat immitto ullamcorper hos luptatum gilvus eum. Delenit patria nunc os pneum acsi nulla magna singularis proprius autem exerci accumsan. </p>
			
			<p>Praesent duis vel similis usitas camur, nostrud eros opes verto epulae feugiat ad. Suscipit modo magna letalis amet et tego accumsan facilisi, meus. Vindico luptatum blandit ulciscor mos caecus praesent sed meus velit si quis lobortis praemitto, uxor. </p>
		</div>
	</div>


</div>

