<?php
session_start();
include_once 'config.php';
include 'api/SessionHandler.php';
$getsessioncookie = $_COOKIE['sess_id'];
if(sess_verify($getsessioncookie) == 1) {
$get_user_unique_id = get_sess_user($getsessioncookie);

$query = $db->query("SELECT * FROM users WHERE userid = '{$get_user_unique_id}'",PDO::FETCH_ASSOC);
$dataquery = $query->fetch(PDO::FETCH_ASSOC);
$getusername = $dataquery['username'];
$getuseravatar = $dataquery['avatar'];
$getuserrole = $dataquery['role'];
$getuserabout = $dataquery['about'];
$getuseremail = $dataquery['email'];
  
if (isset($_POST['submit'])) {
$generateppnametoken = openssl_random_pseudo_bytes(30);
$generateppnametoken = bin2hex($generateppnametoken);

if ($_FILES['ppupload']['tmp_name']!='') {
$target_dir = "avatars/";
$target_file = $target_dir . basename($_FILES["ppupload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file2 = $target_dir . $generateppnametoken.".$imageFileType";

$check = getimagesize($_FILES["ppupload"]["tmp_name"]);
if($check !== false) {
  $uploadOk = 1;
} else {
  echo "Yüklemeye çalıştığınız dosya bir resim değil.";
  $uploadOk = 0;
}
if($imageFileType != "png") {
  echo "Üzgünüz, sadece PNG formatları desteklenmektedir.";
  $uploadOk = 0;
}

if ($uploadOk == 0) {
    //
} else {
  if (move_uploaded_file($_FILES["ppupload"]["tmp_name"], $target_file2)) {
    $newaboutme = htmlentities($_POST['about']);
$query = $db->prepare("UPDATE users SET
	about = :new_about_me,
    avatar = :avatar
	WHERE userid = :userid");
$updateinfo_run_query = $query->execute(array(
     "new_about_me" => "$newaboutme",
  	 "avatar" 	=> "$generateppnametoken.$imageFileType",
     "userid" => "$get_user_unique_id"
));
if ( $updateinfo_run_query ){
  echo "<script>alert(\"Bilgileriniz başarıyla güncellendi.\")</script>";
  header("Refresh:0");
} else {
  echo "<script>alert(\"Bir veritabanı hatası meydana geldi.\")</script>";
}
  } else {
    echo "Dosya yüklenirken bir hata olutu.";
  }
}
} else {
  $newaboutme = htmlentities($_POST['about']);
  $query = $db->prepare("UPDATE users SET
about = :new_about_me
WHERE userid = :userid");
$updateaboutme_run_query = $query->execute(array(
     "new_about_me" => "$newaboutme",
     "userid" => "$get_user_unique_id"
));
if ( $updateaboutme_run_query ){
  echo "<script>alert(\"Bilgileriniz başarıyla güncellendi.\")</script>";
  header("Refresh:0");
} else {
  echo "<script>alert(\"Bir veritabanı hatası meydana geldi.\")</script>";
}
}
};
  
  
if(isset($_POST['sess_close'])) {
$get_close_session = $_POST['sess_close'];
$query = $db->prepare("UPDATE sessions SET
logged_in = :new_logged_in
WHERE sess_token = :sesstoken");
$sessclosequery = $query->execute(array(
     "new_logged_in" => "0",
     "sesstoken" => "$get_close_session"
));
if ( $sessclosequery ){
  echo "<script>alert(\"lem Başarılı\")</script>";
} else {
  echo "Bir hata oluştu!";
}}
  
if(isset($_POST['sess_delete'])) {
$get_del_session = $_POST['sess_delete'];
$query = $db->prepare("DELETE FROM sessions WHERE sess_token = :id");
$delete_session_query = $query->execute(array(
   'id' => $get_del_session
));
if ( $delete_session_query ){
  echo "<script>alert(\"İşlem Başarılı\")</script>";
} else {
  echo "Bir hata oluştu!";
}};
  
  
} else {
    header("Location: index.php?redir=account.php");
}

?>

<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Hesap Yönetimi | Escort Buluşma Ağı</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" href="https://nomee6.xyz/assets/pp.png" />
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js');
    } else {
        console.log("Service worker bu taraycıda desteklenmiyor.");
    }
    </script>
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mmkn! Sadece 3 ders ile tm nesili geleceğe çok iyi hazırlıyoruz." />
<?php 
echo("
<!-- Matomo -->
  <script>
	var _paq = window._paq = window._paq || [];
	_paq.push(['trackPageView']);
	_paq.push(['enableLinkTracking']);
	_paq.push(['setUserId', '$getusername']);
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
  </head>
  <body>
    <div class="wrapper">
      <header class="navbar navbar-expand-md navbar-light d-print-none">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href=".">
              <img src="./static/logo.svg" width="110" height="32" alt="EBA" class="navbar-brand-image">
            </a>
          </h1>
          <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
              <form action="api/search.php" method="POST">
                <div class="input-icon">
                  <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                  </span>
                  <input id="search_query" name="search_query" type="text" class="form-control w-100" placeholder="Ara…">
                </div>
              </form>
           </div>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
              <div class="btn-list">
                  <a href="https://github.com/Nomee6-Inc/eba" class="btn" target="_blank" rel="noreferrer">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" /></svg>
                  Kaynak Kodu
                </a>
              </div>
            </div>
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Koyu Temayı Etkinleştir" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Açık Temayı Etkinleştir" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
            </a>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <div class="d-none d-xl-block ps-2">
                  <div><?php echo $getusername; ?></div>
                  <div class="mt-1 small text-muted"><?php echo $getuserrole; ?></div>
                </div>
                <span class="avatar avatar-sm" style="background-image: url(./avatars/<?php echo $getuseravatar; ?>)"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="profile.php" class="dropdown-item">Profil</a>
                <a href="account.php" class="dropdown-item">Hesap Yönetimi</a>
                <?php
                if($getuserrole == "Yönetici") {
                    echo "<a href=\"admin/\" class=\"dropdown-item\">Yönetici Paneli</a>";
                } else {
                    //
                }
                
                ?>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item">Çıkış Yap</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      <div class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="navbar navbar-light">
            <div class="container-xl">
              <ul class="navbar-nav">
                <li class="nav-item dropdown">
                  <a class="nav-link" href="panel.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Ana Sayfa
                    </span>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                    </span>
                    <span class="nav-link-title">
                      İçerikler
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="./videos.php" >
                          Video
                        </a>
                        <a class="dropdown-item" href="./tests.php" >
                           Test
                        </a>
                        <a class="dropdown-item" href="./game.php" >
                          Oyun
                        </a>
                        <a class="dropdown-item" href="./music.php" >
                          Müzik
                        </a>
                        <a class="dropdown-item" href="./docs.php" >
                          Döküman
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="./popular.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Popüler
                    </span>
                  </a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link dropdown-toggle" href="#navbar-layout" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="6" height="5" rx="2" /><rect x="4" y="13" width="6" height="7" rx="2" /><rect x="14" y="4" width="6" height="7" rx="2" /><rect x="14" y="15" width="6" height="5" rx="2" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Ayarlar
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="./account.php" >
                          Hesap Ayarları
                        </a>
                        <a class="dropdown-item" href="./privacy.php" >
                          Gizlilik
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="./community.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10a6 6 0 0 0 -6 -6h-3v2a6 6 0 0 0 6 6h3" /><path d="M12 14a6 6 0 0 1 6 -6h3v1a6 6 0 0 1 -6 6h-3" /><line x1="12" y1="20" x2="12" y2="10" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Topluluk
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="page-wrapper">
        <div class="container-xl">
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Ayarlar
                </div>
                <h2 class="page-title">
                  Hesap Yönetimi
                </h2>
              </div>

			  <form enctype="multipart/form-data" action="" method="POST">
					<div class="card-body">
				        <div class="row">
							<div class="col-xl-4">
				            	<div class="row">
									<div class="mb-3">
                            		<div class="form-label">Profil Fotoğrafı</div>
										<input type="file" name="ppupload" id="ppupload" class="form-control">
									</div>
									<div class="mb-3">
                            		<div class="form-label">Hakkımda</div>
										<textarea rows="4" type="text" name="about" id="about" class="form-control" maxlength="700"><?php echo $getuserabout; ?></textarea>
									</div>
									<div class="input-group">
										<button name="submit" class="btn">Kaydet</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
              
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Oturum Açılmış Cihazlar</h3>
                  </div>
                  <div class="table-responsive">
                    <table
		class="table table-vcenter table-mobile-md card-table">
                      <thead>
                        <tr>
                          <td>Platform</td>
                          <td>Giriş Tarihi</td>
                          <td>Durum</td>
                          <td class="w-1"></td>
                        </tr>
                      </thead>
                      <tbody>
                        <form action="" method="POST">
                        <?php
                        $getsessionssql = "SELECT * FROM sessions WHERE user_id = '$get_user_unique_id'";
                        $getsessionsresult = mysqli_query($conn, $getsessionssql);
                        while($row = mysqli_fetch_array($getsessionsresult)){
                        $getplatform = $row['platform'];
                        $getbrowser = $row['browser'];
                        $getlogindate = $row['login_date'];
                        $getsessionstatusdb = $row['logged_in'];
                        $getsess_token = $row['sess_token'];
                        if($getsessionstatusdb == 1) {
                        	$getsessionstatus = '<td class=\"text-muted\" data-label=\"Durum\" >
                            Oturum Açık
                          </td>';
                        } else {
                        	$getsessionstatus = '<td class=\"text-orange\" data-label=\"Durum\" >
                            Oturum Kapatldı
                          </td>';
                        }
                          if(strstr($getplatform, "Windows")) {
                          	$getplatformicon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.8 20l-12 -1.5c-1 -.1 -1.8 -.9 -1.8 -1.9v-9.2c0 -1 .8 -1.8 1.8 -1.9l12 -1.5c1.2 -.1 2.2 .8 2.2 1.9v12.1c0 1.2 -1.1 2.1 -2.2 1.9z" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="4" y1="12" x2="20" y2="12" /></svg>';
                          } else if(strstr($getplatform, "Mac") || strstr($row['platform'], "iPhone") || strstr($row['platform'], "iPod") || strstr($row['platform'], "iPad")) {
                          	$getplatformicon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7c-3 0 -4 3 -4 5.5c0 3 2 7.5 4 7.5c1.088 -.046 1.679 -.5 3 -.5c1.312 0 1.5 .5 3 .5s4 -3 4 -5c-.028 -.01 -2.472 -.403 -2.5 -3c-.019 -2.17 2.416 -2.954 2.5 -3c-1.023 -1.492 -2.951 -1.963 -3.5 -2c-1.433 -.111 -2.83 1 -3.5 1c-.68 0 -1.9 -1 -3 -1z" /><path d="M12 4a2 2 0 0 0 2 -2a2 2 0 0 0 -2 2" /></svg>';
                          } else if(strstr($getplatform, "Ubuntu") || strstr($row['platform'], "Linux")){
                          	$getplatformicon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="5" r="2" /><path d="M17.723 7.41a7.992 7.992 0 0 0 -3.74 -2.162m-3.971 0a7.993 7.993 0 0 0 -3.789 2.216m-1.881 3.215a8 8 0 0 0 -.342 2.32c0 .738 .1 1.453 .287 2.132m1.96 3.428a7.993 7.993 0 0 0 3.759 2.19m3.998 -.003a7.993 7.993 0 0 0 3.747 -2.186m1.962 -3.43a8.008 8.008 0 0 0 .287 -2.131c0 -.764 -.107 -1.503 -.307 -2.203" /><circle cx="5" cy="17" r="2" /><circle cx="19" cy="17" r="2" /></svg>';
                          } else if(strstr($getplatform, "Android")) {
                         	$getplatformicon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="10" x2="4" y2="16" /><line x1="20" y1="10" x2="20" y2="16" /><path d="M7 9h10v8a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-8a5 5 0 0 1 10 0" /><line x1="8" y1="3" x2="9" y2="5" /><line x1="16" y1="3" x2="15" y2="5" /><line x1="9" y1="18" x2="9" y2="21" /><line x1="15" y1="18" x2="15" y2="21" /></svg>';
                          } else {
                          	$getplatformicon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><line x1="12" y1="19" x2="12" y2="19.01" /></svg>';
                          };
                            echo "<tr>
                          <td data-label=\"Platform\" >
                            <div class=\"d-flex py-1 align-items-center\">
                              <span class=\"avatar me-2\">$getplatformicon</span>
                              <div class=\"flex-fill\">
                                <div class=\"font-weight-medium\">$getplatform</div>
                                <div class=\"text-muted\"><a class=\"text-reset\">$getbrowser</a></div>
                              </div>
                            </div>
                          </td>
                          <td data-label=\"Giriş Tarihi\" >
                            <div>$getlogindate</div>
                          </td>
                          $getsessionstatus
                          <td>
                            <div class=\"btn-list flex-nowrap\">
                              <div class=\"dropdown\">
                                <button class=\"btn dropdown-toggle align-text-top\" data-bs-toggle=\"dropdown\">
                                  Eylemler
                                </button>
                                <div class=\"dropdown-menu dropdown-menu-end\">
                                  <button class=\"dropdown-item\" name=\"sess_close\" value=\"$getsess_token\">
                                    Oturumu Kapat
                                  </button>
                                  <button class=\"dropdown-item\" name=\"sess_delete\" value=\"$getsess_token\">
                                    Oturumu Sil
                                  </button>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>";
                        };
                        ?>
                          </form>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <br>
<small class="form-hint">Not: Oturumu kapatırsanız o cihazdan tekrar oturum açana kadar erişim sağlayamazsınız. Fakat oturum ile ilgili bilgiler sunucularımızda saklanmaya devam eder. Oturumu silerseniz tamamen yok olur.</small>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                    <a href="https://discord.gg/bserKPkHCM" target="_blank" class="link-secondary" rel="noopener">
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="12" r="1" /><circle cx="15" cy="12" r="1" /><path d="M7.5 7.5c3.5 -1 5.5 -1 9 0" /><path d="M7 16.5c3.5 1 6.5 1 10 0" /><path d="M15.5 17c0 1 1.5 3 2 3c1.5 0 2.833 -1.667 3.5 -3c.667 -1.667 .5 -5.833 -1.5 -11.5c-1.457 -1.015 -3 -1.34 -4.5 -1.5l-1 2.5" /><path d="M8.5 17c0 1 -1.356 3 -1.832 3c-1.429 0 -2.698 -1.667 -3.333 -3c-.635 -1.667 -.476 -5.833 1.428 -11.5c1.388 -1.015 2.782 -1.34 4.237 -1.5l1 2.5" /></svg>
                      Discord
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js"></script>
    <script src="./dist/js/demo.min.js"></script>
  </body>
</html>
