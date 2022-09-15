<?php
include 'config.php';
include 'api/SessionHandler.php';
error_reporting(0);
$getsessioncookie = $_COOKIE['sess_id'];
if (sess_verify($getsessioncookie) == 1) {
    header("Location: panel.php");
} else {

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $getuserip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $getuserip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $getuserip = $_SERVER['REMOTE_ADDR'];
}

if (isset($_POST['submit'])) {
	$tc = hash('sha256', $_POST['tc']);
	$password = md5($_POST['password']);

  
  	$query = $db->query("SELECT * FROM users WHERE tc = '{$tc}' AND password = '{$password}'",PDO::FETCH_ASSOC);
  	$dataquery = $query->fetch(PDO::FETCH_ASSOC);
    $accountcount = $query -> rowCount();
    if( $accountcount > 0 ){
      	$getuseruniqueid = $dataquery['userid'];
      	$getdate = date("d M, Y");
      	if($_POST['rememberme'] == "on") {
        	$getexpiredate = date('Y-m-d', strtotime("+90 days"));
          	$getrememberme = 1;
          	$getcookiedate = 86400 * 90;
        } else {
        	$getexpiredate = date('Y-m-d');
          	$getrememberme = 0;
          	$getcookiedate = 21600;
        }
      	$generatesessionid = openssl_random_pseudo_bytes(32);
        $generatesessionid = bin2hex($generatesessionid);
      	$sess_save_query = $db->prepare("INSERT INTO sessions SET
			sess_token = ?,
			ip = ?,
			platform = ?,
            browser = ?,
            logged_in = ?,
            expire_date = ?,
            last_activity = ?,
            login_date = ?,
            remember_me = ?,
            user_id = ?");
			$run_sess_save_query = $sess_save_query->execute(array(
		     "$generatesessionid", "$getuserip", "$user_os", "$user_browser", "1", "$getexpiredate", "$getdate", "$getdate", "$getrememberme", "$getuseruniqueid"
		));
		if ( $sess_save_query ){
          	setcookie("sess_id", "$generatesessionid", time() + $getcookiedate, "/");
			header("Location: panel.php");
		} else {
        	echo "<script>alert('Bir veritabanı hatası oluştu!')</script>";
        }
    } else {
		echo "<script>alert('Bir hata oluştu! TC Kimlik no veya şifre yanlış.')</script>";
	}
}};

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NOMEE6 EĞİTİM">
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mümkün! Sadece 3 ders ile tm nesili geleceğe çok iyi hazırlıyoruz." />
    <meta name="keywords" content="eba, eğitim, egitim, nomee6, nomee6xyz, nomee6.xyz, nomee6 xyz, torbaci, hseyin, huseyın, torbaci huseyin, torbaci hüseyin, nomee6 egitim, eğlenceli eğitim, nomee6 eğitim, online, online egitim, online eğitim, online eğitim nomee6, eğitim nomee6 online">
	<link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
	<?php
		echo("
		<!-- Matomo -->
		  <script>
			var _paq = window._paq = window._paq || [];
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			_paq.push(['enableHeartBeatTimer']);
			(function() {
				var u=\"https://matomo.aliyasin.org/\";
			  _paq.push(['setTrackerUrl', u+'matomo.php']);
			  _paq.push(['setSiteId', '12']);
			  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			  g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		  </script>
		  <!-- End Matomo Code -->
		");
	?>
	<title>Giriş Yap | NOMEE6 EĞİTİM</title>
</head>
<body  class=" border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="" method="POST" autocomplete="off">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Hesabına Giriş Yap</h2>
            <div class="mb-3">
              <label class="form-label">TC Kimlik No</label>
              <input type="text" name="tc" class="form-control" placeholder="TC Kimlik No" maxlength="11" required>
            </div>
            <div class="mb-2">
              <label class="form-label">
                Şifre
                <span class="form-label-description">
                  <a href="forgot-password.php">Şifremi Unuttum</a>
                </span>
              </label>
              <div class="input-group input-group-flat">
                <input type="password" name="password" id="password" class="form-control" placeholder="ifre" autocomplete="off" required>
                <span class="input-group-text">
                  <a id="togglePassword" class="link-secondary" title="Şifreyi Göster" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                  </a>
                </span>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-check">
                <input name="rememberme" type="checkbox" class="form-check-input"/>
                <span class="form-check-label">Beni bu cihazda hatırla</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" name="submit" class="btn btn-primary w-100">Giriş Yap</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
          Henüz hesabın yok mu? <a href="register.php" tabindex="-1">Kayıt Ol</a>
        </div>
      </div>
    </div>
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
    <script src="./dist/js/demo.min.js"></script>
  	<script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
        });
    </script>
  </body>
</html>
