<?php 
    if(!isset($active)) $active = null;
?>
<!--HEADER-->
<div class="header header-hide">
    <div class="container">
        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url() ?>"><img src="<?= assets_url('img/logo/logo5.png') ?>" alt="logo" /></a>
            </div>
            <div class="collapse navbar-collapse" id="example-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="<?= $active == 'beranda' ? 'active' : null ?>"><a href="<?= base_url() ?>">Beranda</a></li>
                    <li class="<?= $active == 'terbitan' ? 'active' : null ?>"><a href="<?= base_url('terbitan') ?>">Daftar Buku</a></li>
                    <li class="<?= $active == 'kirimnaskah' ? 'active' : null ?>"><a href="<?= base_url('home/kirimnaskah') ?>">Kirim Naskah</a></li>
                    <!-- <li class="<?php // echo $active == 'artikel' ? 'active' : null ?>"><a href="<?php // ARTIKEL_URL ?>">Artikel</a></li> -->
                    <li class="<?= $active == 'faq' ? 'active' : null ?>"><a href="<?= base_url('home/faq') ?>">FAQ</a></li>
                    <li><a href="<?= base_url('admin/login') ?>">Admin</a></li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--/HEADER-->
