
<?php
// $data_head = isset($data_head) ? $data_head : null;
$manifest = json_decode(file_get_contents(DOCS_PATH . "manifest.json"));
$tmp = [];
$def_data_head = array(
    'resource' => $resource,
    'extra_js' => isset($extra_js) ? $extra_js : null,
    'bodyid' => isset($bodyid) ? $bodyid : null,
    'bodyAttr' => isset($bodyAttr) ? $bodyAttr : null,
    'extra_css' => isset($extra_css) ? $extra_css : null,
    'includeTiny' => isset($includeTiny) ? $includeTiny : null,
    'loadingAnim' => isset($loading_animation) ? $loading_animation : null,
    'bodyClass' => isset($bodyClass) ? $bodyClass : 'show-spinner',
    'hideSpinner' => isset($hideSpinner) ? $hideSpinner : false,
);
if(isset($data_head) && is_array($data_head)){
    $data_head = $data_head + $def_data_head;
}else{
    $data_head = $def_data_head;
}
$header = isset($header) ? $header : 'head/main';
$footer = isset($footer) ? $footer : 'footer/main';
include_view($header, $data_head);
if (isset($navbar) && !is_array($navbar))
    include_view($navbar, $navbarConf);
if (isset($sidebar) && !is_array($sidebar))
    include_view($sidebar, $sidebarConf);
if (!isset($data_content))
    $data_content = null;

if(!isset($adaBanner)) $adaBanner = true;
?>

<?php if($adaBanner): ?>
<section id="sec_1" class="">
    <!--SLIDER-->
    <div id="slides">
        <div class="slides-container">
            <img src="<?= assets_url('img/background/b1.png') ?>" alt="">
            <img src="<?= assets_url('img/background/b1.png') ?>" alt="">
            <!-- <img src="<?php // echo assets_url('themes/mievent/img/slider/copyright.jpg') ?>" alt=""> -->

        </div>
        <nav class="slides-navigation">
            <a href="#" class="next  fa fa-2x fa-chevron-right"></a>
            <a href="#" class="prev  fa fa-2x fa-chevron-left"></a>
        </nav>
    </div>
    <!--/SLIDER-->
    <div class="home-bg"></div>
    <div class="col-lg-12 landing-text-pos align-center">
        <h1 class="wow animated fadeInDown" data-wow-duration="1s" data-wow-delay="1s"><?= isset($manifest->title) && !empty(isset($manifest->title)) ? $manifest->title : 'Penerbit Ummuna' ?></h1>
        <hr id="title_hr" />
        <p class="wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="1s"><?= isset($manifest->address) && !empty(isset($manifest->address)) ? $manifest->address : '-' ?></p>
        <!-- <a class="learn-more-btn btn-effect wow animated fadeIn" data-wow-duration="0.5s" data-wow-delay="1.5s" href="<?= base_url() ?>">Beranda</a> -->
    </div>
</section>
<?php endif ?>
<?php

if (isset($contentHtml) && !empty($contentHtml)) {
    if (!is_array($contentHtml)) $contentHtml = [$contentHtml];
    foreach ($contentHtml as $k => $c) {
        echo $c;
    }
}
?>
<?php if (isset($content) && !empty($content)) {
    if (!is_array($content)) $content = [$content];
    foreach ($content as $k => $c) {
        include_view($c, $data_content);
    }
}
?>

<?php
$dataFoot = array(
    'resource' => $resource, 
    'adaThemeSelector' => isset($adaThemeSelector) ? $adaThemeSelector : null,
    'extra_js' => isset($extra_js) ? $extra_js : null, 
    'extra_css' => isset($extra_css) ? $extra_css : null
);
include_view($footer, array('resource' => $resource, 'extra_js' => isset($extra_js) ? $extra_js : null, 'extra_css' => isset($extra_css) ? $extra_css : null));