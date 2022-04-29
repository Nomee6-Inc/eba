<?php 

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
	$tc = hash('sha256', $_POST['tc']);
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$cpassword = md5($_POST['cpassword']);

	if ($password == $cpassword) {
		$sql = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);
		if (!$result->num_rows > 0) {
			$sql = "INSERT INTO users (tc, username, email, password)
					VALUES ('$tc', '$username', '$email', '$password')";
			$result = mysqli_query($conn, $sql);
			if ($result) {
				echo "<script>alert('Kayıt İşlemi Başarılı.')</script>";
				$tc = "";
				$username = "";
				$email = "";
				$_POST['password'] = "";
				$_POST['cpassword'] = "";
			} else {
				echo "<script>alert('Bir hata oluştu.')</script>";
			}
		} else {
			echo "<script>alert('Mail adresi zaten kullanılmakta.')</script>";
		}
		
	} else {
		echo "<script>alert('Şifreler eşleşmiyor.')</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mümkün! Sadece 3 ders ile tüm nesili geleceğe çok iyi hazırlıyoruz." />
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
	<title>Kayıt Ol</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Kayıt Ol</p>
			<div class="input-group">
				<input type="text" placeholder="TC Kimlik No" name="tc" maxlength="11" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Kullanıcı Adı" name="username" value="<?php echo $username; ?>" required>
			</div>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Şifre" name="password" required>
            </div>
            <div class="input-group">
				<input type="password" placeholder="Tekrar Şifre" name="cpassword" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Kayıt Ol</button>
			</div>
			<p class="login-register-text">Zaten bir hesabın var mı? <a href="index.php">Giriş Yap</a>.</p>
		</form>
	</div>
</body>
</html>