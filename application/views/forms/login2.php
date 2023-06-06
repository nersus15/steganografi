	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('<?= assets_url('img/background/login.jpg') ?>');">
			<div class="wrap-login100">
				<form id="form-login" action="<?= base_url('ws/login') ?>" method="POST" class="login100-form validate-form">
					<span class="login100-form-logo">
						<img style="width: 125px;height: 125px;border-radius: 100%;border: 4px white solid;" src="<?= assets_url('img/background/login.jpg') ?>">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Masukkan username atau email">
						<input class="input100" type="text" name="user" placeholder="Email atau username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"> <i class="fa fas-user"></i> </span>
					</div>
					<p id="alert_danger" class="text-white" style="display: none;"></p>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					<p class="text-white mt-4">Belum punya akun ? <a class="text-info" href="#" id="register">Klik disini</a> untuk membuat akun</p>
				</form>
			</div>
		</div>
	</div>