<?php 

include 'config.php';

session_start();

error_reporting(0);

if (isset($_SESSION['username'])) {
    header("Location: panel.php");
}

if (isset($_POST['submit'])) {
	$tc = hash('sha256', $_POST['tc']);
	$password = md5($_POST['password']);

	$sql = "SELECT * FROM users WHERE tc='$tc' AND password='$password'";
	$result = mysqli_query($conn, $sql);
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_assoc($result);
		$_SESSION['username'] = $row['username'];
		$_SESSION['email'] = $row['email'];
		header("Location: panel.php");
	} else {
		echo "<script>alert('Bir hata oluştu! TC Kimlik no veya şifre yanlış.')</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NOMEE6 EĞİTİM">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mümkün! Sadece 3 ders ile tüm nesili geleceğe çok iyi hazırlıyoruz." />
    <meta name="keywords" content="eba, eğitim, egitim, nomee6, nomee6xyz, nomee6.xyz, nomee6 xyz, torbaci, hüseyin, huseyın, torbaci huseyin, torbaci hüseyin, nomee6 egitim, eğlenceli eğitim, nomee6 eğitim, online, online egitim, online eğitim, online eğitim nomee6, eğitim nomee6 online">
	<link rel="stylesheet" type="text/css" href="style.css">
	<?php 
		$username = $_SESSION['username'];
		echo("
		<!-- Matomo -->
		  <script>
			var _paq = window._paq = window._paq || [];
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			_paq.push(['setUserId', '$username']);
			_paq.push(['enableHeartBeatTimer']);
			(function() {
				var u=\"https://matomo.aliyasin.org/\";
			  _paq.push(['setTrackerUrl', u+'matomo.php']);
			  _paq.push(['setSiteId', '']);
			  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			  g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		  </script>
		  <!-- End Matomo Code -->
		");
	?>
	<title>Giriş Yap</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Giriş Yap</p>
			<div class="input-group">
				<input type="text" placeholder="TC Kimlik No" name="tc" maxlength="11" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Şifre" name="password" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Giriş Yap</button>
			</div>
			<p class="login-register-text">Henüz hesabın yok mu? <a href="register.php">Kayıt Ol</a>.</p>
			<p class="login-register-text">Şifreni mi unuttun? <a href="forgot-password.php">Şifremi Unuttum</a>.</p>
		</form>
	</div>
</body>
</html>