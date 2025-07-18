<style>
  @font-face {
   font-family: myFirstFont;
   src: url(sansation_light.woff);
}

* {
   font-family: myFirstFont;
   font-size: 16px;
}
</style>

<!-- Navbar -->
<div class="w3-top">
    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
        <a href="#" class="w3-bar-item w3-button w3-padding-medium w3-hover-white" title="Connect Logo" style="cursor:pointer">FORUM</a>
    </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="#about" class="w3-bar-item w3-button w3-padding-large">About</a>
    <a href="#features" class="w3-bar-item w3-button w3-padding-large">Features</a>
    <a href="#contact" class="w3-bar-item w3-button w3-padding-large">Contact</a>
    <a href="login.php" class="w3-bar-item w3-button w3-padding-large">Login</a>
    <a href="register.php" class="w3-bar-item w3-button w3-padding-large">Register</a>
</div>

<header class="w3-bar w3-hide-medium w3-hide-large w3-card w3-theme-d2">
  <button class="w3-bar-item w3-button w3-xxlarge w3-hover-theme" onclick="openSidebar()">&#9776;</button>
  <h1 class="w3-bar-item"><a href="#"><img class="w3-circle" src="logo.png" alt="Connect" style="width:100%"></a></h1>
</header>


<script>
closeSidebar();
function openSidebar() {
  document.getElementById("mySidebar").style.display = "block";
}

function closeSidebar() {
  document.getElementById("mySidebar").style.display = "none";
}
</script>

	<script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({
				pageLanguage: 'en',
				layout: google.translate.TranslateElement.InlineLayout.SIMPLE
			}, 'google_translate_element');
		}
	</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
