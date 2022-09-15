<?php
include_once '../config.php';
include '../api/SessionHandler.php';
$getsessioncookie = $_COOKIE['sess_id'];
if(sess_verify($getsessioncookie) == 1) {
$get_user_unique_id = get_sess_user($getsessioncookie);

$query = $db->query("SELECT * FROM users WHERE userid = '{$get_user_unique_id}'",PDO::FETCH_ASSOC);
$dataquery = $query->fetch(PDO::FETCH_ASSOC);
$getusername = $dataquery['username'];
$getuseravatar = $dataquery['avatar'];
$getuserrole = $dataquery['role'];
  
if($getuserrole == "Yönetici") {
$getuserid = htmlentities($_GET['userid']);

if($getuserid == "" || $getuserid == "aliyasin") {
    header("Location: panel.php");
} else {
  
  if (isset($_POST['submit'])) {
    $getnewperm = htmlentities($_POST['perm']);
    
    if($getnewperm == "&Ouml;renci") {
    	$getnewperm = "Öğrenci";
    } else if($getnewperm == "Y&ouml;netici") {
    	$getnewperm = "Yönetici";
    } else {
    	die();
    }
    
    if($getnewperm == "Öğrenci") {
   	 	$getnewrolecolor = "purple";
    } else if($getnewperm == "Yönetici") {
   	 	$getnewrolecolor = "green";
    }
    
    $query57 = $db->prepare("UPDATE users SET
		role = :new_role,
        rolecolor = :new_rolecolor
		WHERE username = :username");
	$updateinfo_run_query = $query57->execute(array(
     	"new_role" => "$getnewperm",
      	"new_rolecolor" => "$getnewrolecolor",
  	 	"username" 	=> "$getuserid"
	));
    if($updateinfo_run_query) {
    	header("Location: users.php");
    } else {
    	echo "Bir veritabanı hatası meydana geldi.";
    }
};
  
}
  
  
} else {
    header("Location: index.php?redir=admin/users.php");
}}
?>

<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Admin Panel | Escort Buluşma Ağı</title>
    <!-- CSS files -->
    <link href="https://egitim.nomee6.xyz/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://egitim.nomee6.xyz/dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="https://egitim.nomee6.xyz/dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="https://egitim.nomee6.xyz/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="https://egitim.nomee6.xyz/dist/css/demo.min.css" rel="stylesheet"/>
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mümkün! Sadece 3 ders ile tüm nesili geleceğe çok iyi hazırlıyoruz." />
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
              <img src="../static/logo.svg" width="110" height="32" alt="EBA" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
              <div class="btn-list">
              </div>
            </div>
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Koyu Temayı ETkinleştir" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Aık Temayı Etkinleştir" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
            </a>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <div class="d-none d-xl-block ps-2">
                  <div><?php echo($getusername) ?></div>
                  <div class="mt-1 small text-muted"><?php echo $getuserrole; ?></div>
                </div>
                <span class="avatar avatar-sm" style="background-image: url(../avatars/<?php echo $getuseravatar; ?>)"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="../profile.php" class="dropdown-item">Profil</a>
                <a href="../account.php" class="dropdown-item">Hesap Yönetimi</a>
                <div class="dropdown-divider"></div>
                <a href="../logout.php" class="dropdown-item">Çıkış Yap</a>
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
                      İçerik Yükle
                    </span>
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu-columns">
                      <div class="dropdown-menu-column">
                        <a class="dropdown-item" href="./upload_video.php" >
                          Video Yükle
                        </a>
                        <a class="dropdown-item" href="./upload_music.php" >
                          Müzik Yükle
                        </a>
                        <a class="dropdown-item" href="./upload_doc.php" >
                          Döküman Yükle
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="./users.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Üyeler
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="./server_stats.php" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="12" rx="1" /><line x1="7" y1="20" x2="17" y2="20" /><line x1="9" y1="16" x2="9" y2="20" /><line x1="15" y1="16" x2="15" y2="20" /><path d="M8 12l3 -3l2 2l3 -3" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Sunucu İstatistikleri
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
                <h2 class="page-title">
                  Üye Yetkisini Güncelle
                </h2>
              </div>
                <form enctype="multipart/form-data" action="" method="POST">
					<div class="card-body">
				        <div class="row">
							<div class="col-xl-4">
				            	<div class="row">
									<div class="col-md-6 col-xl-12">
										<div class="mb-3">
				                		<label class="form-label"><?php echo $getuserid; ?> kullanıcısının Yeni Yetkisi:</label>
                                        <select class="form-select" id="perm" name="perm">
                                            <option value="Öğrenci">Öğrenci</option>
                                            <option value="Yönetici">Yönetici</option>
                                        </select>
				            		</div>
									<div class="input-group">
										<button name="submit" class="btn">Güncelle</button>
									</div>
								    </div>
							    </div>
						    </div>
					    </div>
				    </div>
			    </form>
      </div>
    </div>
    <!-- Tabler Core -->
    <script src="https://egitim.nomee6.xyz/dist/js/tabler.min.js"></script>
    <script src="https://egitim.nomee6.xyz/dist/js/demo.min.js"></script>
  </body>
</html>
