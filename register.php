<?php 
include 'config.php';
include 'api/SessionHandler.php';
error_reporting(1);
$getsessioncookie = $_COOKIE['sess_id'];
if (sess_verify($getsessioncookie) == 1) {
    header("Location: panel.php");
} else {
if (isset($_POST['submit'])) {
$tc = hash('sha256', $_POST['tc']);
$username = htmlentities($_POST['username']);
$email = $_POST['email'];
$password = md5($_POST['password']);
  
$query = $db->query("SELECT * FROM users WHERE email = '{$email}' OR username = '{$username}'",PDO::FETCH_ASSOC);
$dataquery = $query->fetch(PDO::FETCH_ASSOC);
$accountcount = $query -> rowCount();
if( $accountcount < 0 || $accountcount == 0){
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
if(!$tc || !$username || !$email || !$password) {
	echo "Lütfen tüm gerekli alanlar doldurun!";
} else {
$generate_new_unique_id = openssl_random_pseudo_bytes(30);
$generate_new_unique_id = bin2hex($generate_new_unique_id);
 
$save_user = $db->prepare("INSERT INTO users SET
tc = ?,
username = ?,
email = ?,
password = ?,
status = ?,
code = ?,
about = ?,
role = ?,
rolecolor = ?,
note = ?,
avatar = ?,
userid = ?,
look_later = ?");
$save_user_insert = $save_user->execute(array(
     $tc, $username, $email, $password, "0", "0", "Ben NOMEE6 Eğitim kullanıyorum.", "Öğrenci", "purple", "9", " ", $generate_new_unique_id, ""
));
if($save_user_insert) {
	echo "<script>alert('Kayıt işlemi başarılı!')</script>";
} else {
	echo "<script>alert('Bir veritabanı hatası meydana geldi.')</script>";
}

}
} else {
	echo "<script>alert('E-Posta adresi geçersiz.')</script>";
}
} else {
	echo "<script>alert('E-Posta veya kullanıcı adı zaten kullanılmakta.')</script>";
}}};

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mmkn! Sadece 3 ders ile tüm nesili geleceğe çok iyi hazırlıyoruz." />
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
	<title>Kayıt Ol | NOMEE6 EĞİTİM</title>
</head>
<body class="border-top-wide border-primary d-flex flex-column">
      <div class="page page-center">
      <div class="container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="" method="POST">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Hesap Oluştur</h2>
            <div class="mb-3">
              <label class="form-label">TC Kimlik No</label>
              <input type="text" name="tc" class="form-control" maxlength="11" placeholder="TC Kimlik Numaranızı girin" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Kullanıcı Adı</label>
              <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı girin" required>
            </div>
            <div class="mb-3">
              <label class="form-label">E-Posta adresi</label>
              <input type="email" name="email" class="form-control" placeholder="E-Posta adresi girin" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Şifre</label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" name="password" class="form-control" placeholder="Şifre" autocomplete="off" required>
                <span class="input-group-text">
                  <a id="togglePassword" class="link-secondary" title="Şifreyi Göster" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                  </a>
                </span>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-check">
                <input type="checkbox" class="form-check-input" required/>
                <span class="form-check-label">Bu siteye kayıt olarak <a href="https://nomee6.xyz/privacy" tabindex="-1">NOMEE6 Gizlilik Politikasını</a> kabul ediyorum.</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" name="submit" class="btn btn-primary w-100">Hesap Oluştur</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
          Zaten hesabın var mı? <a href="index.php" tabindex="-1">Giriş Yap</a>
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
