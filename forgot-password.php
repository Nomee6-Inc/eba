<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifremi Unuttum</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta property="og:title" content="Nomee6 Eğitim" />
    <meta property="og:url" content="https://nomee6.xyz" />
    <meta property="og:image" content="https://nomee6.xyz/assets/A.png" />
    <meta property="og:description" content="Daha iyi bir eğitim NOMEE6 Eğitim ile mümkün! Sadece 3 ders ile tüm nesili geleceğe çok iyi hazırlıyoruz." />
    <meta name="keywords" content="eba, eğitim, egitim, nomee6, nomee6xyz, nomee6.xyz, nomee6 xyz, torbaci, hüseyin, huseyın, torbaci huseyin, torbaci hüseyin, nomee6 egitim, eğlenceli eğitim, nomee6 eğitim, online, online egitim, online eğitim, online eğitim nomee6, eğitim nomee6 online">
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
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="forgot-password.php" method="POST" autocomplete="">
                    <h2 class="text-center">Şifremi Unuttum</h2>
                    <p class="text-center">E-posta adresinizi girin. Epostanın ulaşması 5 dakikayı bulabilir. Eğer eposta almıyorsanız bizimle iletişime geçiniz.</p>
                    <?php
                        if(count($errors) > 0){
                            ?>
                            <div class="alert alert-danger text-center">
                                <?php 
                                    foreach($errors as $error){
                                        echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Devam">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>