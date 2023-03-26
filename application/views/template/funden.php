<?php
// $data_head = isset($data_head) ? $data_head : null;
$data_head = array(
    'resource' => $resource,
    'extra_js' => isset($extra_js) ? $extra_js : null,
    'extra_css' => isset($extra_css) ? $extra_css : null,
    'includeTiny' => isset($includeTiny) ? $includeTiny : null,
    'loading_animation' => isset($loading_animation) ? $loading_animation : true,
	'bodyClass' => isset($bodyClass) ? $bodyClass : 'show-spinner',
    'hideSpinner' => isset($hideSpinner) ? $hideSpinner : false,
);
$head = isset($head) ? $head : 'head/main';
$foot = isset($foot) ? $foot : 'footer/main';
include_view($head, $data_head);
if (isset($navbar) && !is_array($navbar))
    include_view($navbar, $navbarConf);
if (isset($sidebar) && !is_array($sidebar))
    include_view($sidebar, $sidebarConf);
if (!isset($data_content))
    $data_content = null;
$manifest = json_decode(file_get_contents(DOCS_PATH . "manifest.json"));

?>

	<!--====== Preloader ======-->
	<div id="preloader">
		<div id="loading-center">
			<div id="loading-center-absolute">
				<div class="object" id="object_one"></div>
				<div class="object" id="object_two"></div>
				<div class="object" id="object_three"></div>
				<div class="object" id="object_four"></div>
			</div>
		</div>
	</div>

	<!--====== Header Start ======-->
	<header class="site-header sticky-header transparent-header topbar-transparent">
		<div class="header-topbar d-none d-sm-block">
			<div class="container">
				<div class="row justify-content-between align-items-center">
					<div class="col-auto">
						<ul class="contact-info">
							<li><a href="#"><i class="far fa-envelope"></i> <a href="mailto:ta.futsal@m.com">ta.futsal@m.com</a> </a></li>
							<li><a href="#"><i class="far fa-map-marker-alt"></i> Jl. Ismail Marzuki, No. 40, Mataram, NTB</a></li>
						</ul>
					</div>
					<div class="col-auto d-none d-md-block">
						<!-- <ul class="social-icons">
							<li><a href="#"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#"><i class="fab fa-youtube"></i></a></li>
							<li><a href="#"><i class="fab fa-behance"></i></a></li>
							<li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
						</ul> -->
					</div>
				</div>
			</div>
		</div>
		<div class="navbar-wrapper">
			<div class="container">
				<div class="navbar-inner">
					<div class="site-logo">
						<a href="<?= base_url()?>"><img style="width: 50px; margin-right: 10px" src="<?= base_url($manifest->image) ?>" alt="TA Futsal"><span style="font-size: 20px; font-weight: bold">TA Futsal</span></a>
					</div>
					<div class="nav-menu">
						<ul>
							<li class="current">
								<a href="<?= base_url()?>">Home</a>
							</li>
							<li>
								<?php if(!is_login()): ?>
									<a id="#login" href="<?= base_url("auth/login") ?>">Login</a>
								<?php elseif(is_login('admin') || is_login('pimpinan')): ?>
									<a id="#login" href="<?= base_url("dashboard") ?>">Dashboard</a>
								<?php elseif(is_login('member')): ?>
									<a id="#login" href="<?= base_url("member") ?>">Member Area</a>
								<?php endif ?>
							</li>
						</ul>
					</div>
					<div class="navbar-extra d-flex align-items-center">
						<?php if(!is_login('admin')):?>
							<a href="#booking" class="main-btn nav-btn d-none d-sm-inline-block">
								Booking Now <i class="far fa-arrow-right"></i>
							</a>
						<?php endif ?>
						<a href="#" class="nav-toggler">
							<span></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="mobile-menu-panel">
			<div class="panel-logo">
				<a href="<?= base_url() ?>"><img style="width: 50px; margin-right: 10px" src="<?= base_url($manifest->image) ?>" alt="TA Futsal"><span style="font-size: 20px; font-weight: bold">TA Futsal</span></a>
			</div>
			<ul class="panel-menu">
				<li class="current">
					<a href="<?= base_url() ?>">Home</a>
				</li>
				<li>
				<?php if(!is_login()): ?>
						<a id="#login" href="<?= base_url("auth/login") ?>">Login</a>
					<?php elseif(is_login('admin') || is_login('pimpinan')): ?>
						<a id="#login" href="<?= base_url("dashboard") ?>">Dashboard</a>
					<?php elseif(is_login('member')): ?>
						<a id="#login" href="<?= base_url("member") ?>">Member Area</a>
					<?php endif ?>
				</li>

			</ul>
			<div class="panel-extra">
				<?php if(!is_login('admin')): ?>
					<a href="#booking" class="main-btn btn-white">
						Booking Now <i class="far fa-arrow-right"></i>
					</a>
				<?php endif ?>
			</div>
			<a href="#" class="panel-close">
				<i class="fal fa-times"></i>
			</a>
		</div>
	</header>
	<!--====== Header End ======-->

	<!--====== Hero Area Start ======-->
	<section class="hero-area-one">
		<div class="hero-text">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-xl-10">
						<span class="tagline wow fadeInUp" data-wow-delay="0.3s">Futsal field booking</span>
						<h1 class="title wow fadeInUp" data-wow-delay="0.4s">play and enjoy time with friends</h1>
						<a href="project-1.html" class="main-btn wow fadeInUp" data-wow-delay="0.5s">
							<!-- Explore Projects <i class="far fa-arrow-right"></i> -->
						</a>
					</div>
				</div>
			</div>
			<div class="hero-shapes">
				<div class="hero-line-one">
					<img src="<?= base_url('public/assets/themes/funden') ?>/img/hero/hero-line.png" alt="Line">
				</div>
				<div class="hero-line-two">
					<img src="<?= base_url('public/assets/themes/funden') ?>/img/hero/hero-line-2.png" alt="Line">
				</div>
				<div class="dot-one"></div>
				<div class="dot-two"></div>
			</div>
		</div>
		<div class="hero-images">
			<div style="width: 25%" class="hero-img image-small fancy-bottom wow fadeInLeft" data-wow-delay="0.6s">
				<img src="<?= base_url('public/assets/themes/funden') ?>/img/landing/f1.jpg" alt="Image">
			</div>
			<div style="width: 50%" class="hero-img main-img wow fadeInUp" data-wow-delay="0.5s">
				<img src="<?= base_url('public/assets/themes/funden') ?>/img/landing/f3.jpg" alt="Image">
			</div>
			<div style="width: 25%" class="hero-img image-small fancy-top wow fadeInRight" data-wow-delay="0.7s">
				<img src="<?= base_url('public/assets/themes/funden') ?>/img/landing/f2.jpg" alt="Image">
			</div>
		</div>
	</section>
	<!--====== Hero Area End ======-->

	<!--====== Categories Section Start ======-->
	<!-- <section class="popular-categories section-gap">
		<div class="container">
			<div class="categories-header">
				<div class="row align-items-center justify-content-between">
					<div class="col-auto">
						<div class="common-heading mb-30">
							<span class="tagline">
								<i class="fas fa-plus"></i> what we do
								<span class="heading-shadow-text">Category</span>
							</span>
							<h2 class="title">Popular Categories</h2>
						</div>
					</div>
					<div class="col-auto">
						<a href="project-1.html" class="main-btn mb-30">View All Category <i class="far fa-angle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="row justify-content-center fancy-icon-boxes">
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-reading-book"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Education</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0.1s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-stethoscope"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Medical & Health</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0.2s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-tshirt-1"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Clothes</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0.3s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-video-camera"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Video & Films</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0.4s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-project-management"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Technology</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6 col-sm-10 wow fadeInUp" data-wow-delay="0.5s">
					<div class="fancy-box-item mt-30">
						<div class="icon">
							<i class="flaticon-salad"></i>
						</div>
						<div class="content">
							<h4 class="title"><a href="project-details.html">Organic Foods</a></h4>
							<p>School, Collage & University</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->
	<!--====== Categories Section End ======-->

	<!--====== About Section Start ======-->
	<section class="about-section-one section-gap">
		<div class="container">
			<div class="row align-items-center justify-content-lg-start justify-content-center">
				<div class="col-xl-6 col-lg-7 col-md-9">
					<div class="about-img">
						<img src="<?= base_url('public/assets/themes/funden') ?>/img/landing/f1.jpg" alt="Image">
					</div>
				</div>
				<div class="col-xl-4 col-lg-5 col-md-10 offset-xl-1">
					<div class="about-text mt-md-70 mb-md-50">
						<div class="common-heading mb-30">
							<span class="tagline">
								<i class="fas fa-plus"></i> who we are
								<span class="heading-shadow-text">Abouit Us</span>
							</span>
							<h2 class="title">Why Choose Us</h2>
						</div>
						<p>
							Sedut perspiciatis unde omnis iste natus voluptatem accusantium dolore dantiumy totam rem apeam, eaque ipsa quaventore veritatis quasi architecto beatae.
						</p>
						<div class="author-note wow fadeInUp">
							<ul>
								<li><i class="far fa-check"></i> We have a large schedule that can be booked with more than one field </li>
								<li><i class="far fa-check"></i> The price we offer is much cheaper than other places </li>
								<li><i class="far fa-check"></i> You can order from anywhere through our site </li>
							</ul>
							<div class="author-info">
								<div class="author-img">
									<img src="<?= base_url('public/assets/themes/funden') ?>/img/author-thumbs/01.jpg" alt="Image">
								</div>
								<h5 class="name">Michel H. Heart</h5>
								<span class="title">CEO & Founder</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--====== About Section End ======-->

	<!--====== Emergency Project & CTA Start ======-->
	<section class="emergency-project-with-cta" style="margin-bottom: 20%">
		
		<div class="emergency-project-slider">
			<div class="container">
				<div class="common-heading text-center mb-60">
					<span class="tagline">
						<i class="fas fa-plus"></i> Price
						<!-- <span class="heading-shadow-text">Donate</span> -->
					</span>
					<h2 class="title">Explore Our Price</h2>
				</div>
				<div class="row project-slider-two project-items project-style-four">
					<div class="col">
						<div class="project-item">
							<div class="thumb" style="background-image: url(<?= base_url('public/assets/themes/funden') ?>/img/landing/f4.jpg);"></div>
							<div class="content">
								<div class="cats">
									<a href="#">Member</a>
								</div>

								<h5 class="title">
									<a href="project-details.html">Our Member Get 30% Dicount for 3 booking in month</a>
								</h5>
								
							</div>
						</div>
					</div>
					<div class="col">
						<div class="project-item">
							<div class="thumb" style="background-image: url(<?= base_url('public/assets/themes/funden') ?>/img/landing/f5.webp);"></div>
							<div class="content">
								<div class="cats">
									<a href="#">Non Member</a>
								</div>
								<h5 class="title">
									<a href="project-details.html">we offer the best price for our field in every schedule </a>
								</h5>								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if(!is_login('admin')):?>
			<div id="booking" class="container">
				<!-- Call to Action -->
				<div class="cta-box cta-double-content" style="background-image: url(<?= base_url('public/assets/themes/funden') ?>/img/cta/01.jpg);">
					<form action="<?= is_login('member') ? base_url('member/add_booking') : base_url('ws/booking') ?>" method="POST" id="form-booking">
						<div class="row">
							<div class="col-xl-8 col-lg-6 col-md-6 col-sm-12">
								<h4 class="text-white">Booking</h4>
								<div class="content mt-5" style="text-align: left">
									<div class="form-group">
										<label class="text-white" for="">Tanggal</label>
										<input data-rule-required="true" type="date" name="tanggal" id="tanggal" class="form-control">
									</div>
									<div class="form-group">
										<label class="text-white" for="">Lapangan</label>
										<select class="form-control" data-rule-required="true" name="lapangan" id="lapangan">
											<?php foreach($lapangan as $v): ?>
												<option value="<?= $v->id ?>"><?= $v->id . "(". $v->jenis .") - " . $v->tempat ?></option>
											<?php endforeach?>
										</select>
									</div>
									<div class="form-group">
										<label class="text-white" for="">Jam</label>
										<select class="form-control" data-rule-required="true" name="jadwal" id="jadwal">
											<?php foreach($jadwal as $v): ?>
												<option data-lapangan="<?= $v->lapangan ?>" value="<?= $v->id ?>"><?= $v->mulai . " - " . $v->selesai . "(". rupiah_format($v->tarif) .")" ?></option>
											<?php endforeach?>
										</select>
										<label style="display: none;" id="jadwal-err" class="text-danger" for=""><small>Sudah dibooking, silahkan piling jam atau tanggal lain</small></label>
									</div>
									<div class="form-group">
										<label class="text-white" for="">Penanggung Jawab</label>
										<input type="text" data-rule-required="true" name="penanggung_jawab" id="wakil" class="form-control">
									</div>
									<div class="form-group">
										<label class="text-white" for="">Tim</label>
										<input type="text" name="tim" id="tim" class="form-control">
									</div>
									<div class="form-group">
										<label class="text-white" for=""><small>Sudah menjadi member? jika sudah menjadi member, silahkan booking melalui <a href="<?= base_url('member/booking') ?>">Member Area</a></small></label>
									</div>
									<div style="text-align: center">
										<button type="submit" class="btn btn-sm btn-primary">Booking</button>
									</div>
									<div class="form-group">
										<label style="display: none" id="alert_danger" class="text-danger" for=""><small></small></label>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
								<h4 class="text-white">Lihat Status Booking</h4>
								<div class="content mt-5" style="text-align: left">
									<div class="form-group">
										<label class="text-white" for="">ID Booking</label>
										<input type="text" id="bid" class="form-control">
									</div>
								</div>
								<div style="text-align: center">
									<button id="lihat-booking" type="button" class="btn btn-sm btn-primary">Lihat</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		<?php endif ?>
	</section>
	<!--====== Emergency Project & CTA End ======-->
	<style>
		form .error{
			color: red;
		}
	</style>

			
    <?php
$dataFoot = array(
    'resource' => $resource, 
    'adaThemeSelector' => isset($adaThemeSelector) ? $adaThemeSelector : null,
    'extra_js' => isset($extra_js) ? $extra_js : null, 
    'extra_css' => isset($extra_css) ? $extra_css : null
);
include_view($foot, array('resource' => $resource, 'extra_js' => isset($extra_js) ? $extra_js : null, 'extra_css' => isset($extra_css) ? $extra_css : null));