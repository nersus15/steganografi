<?php
    $manifest = json_decode(file_get_contents(DOCS_PATH . "manifest.json"));
?>

<section class="text-center section-padding contact-wrap" style="margin-top: 50px;" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-8 wow animated fadeInLeft align-center" data-wow-duration="1s" data-wow-delay="0.3s">
                <h1 class="arrow">Kontak Kami</h1>
                <hr>
                <!-- <p>Lorem ipsum dolor sit amet, alia honestatis an qui, ne soluta nemore eripuit sed. Falli exerci
                    aperiam ut his, prompta feugiat usu minimum delicata.</p> -->
            </div>
        </div>
        <div class="row contact-details">
            <div class="col-md-3 wow animated fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
                <div class="light-box box-hover">
                    <h2><span>Alamat</span></h2>
                    <p><?= isset($manifest->address) ? $manifest->address : null ?></p>
                </div>
            </div>
            <div class="col-md-3 wow animated fadeInDown" data-wow-duration="1s" data-wow-delay="0.7s">
                <div class="light-box box-hover">
                    <h2><span>Whatsapp</span></h2>
                    <p><?= isset($manifest->wa) ? $manifest->wa : null ?></p>
                </div>
            </div>
            <div class="col-md-3 wow animated fadeInDown" data-wow-duration="1s" data-wow-delay="0.9s">
                <div class="light-box box-hover">
                    <h2><span>Email</span></h2>
                    <p><?= isset($manifest->email) ? $manifest->email : null ?></p>
                </div>
            </div>
            <div class="col-md-3 wow animated fadeInDown" data-wow-duration="1s" data-wow-delay="0.9s">
                <div class="light-box box-hover">
                    <h2><span>Waktu Pelayanan</span></h2>
                    <p>Senin – Sabtu <br> (Pukul 08.00 – 17.00)</p>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <a id="get_directions" class="learn-more-btn btn-effect" href="#"><i class="fa fa-map-marker"></i><span>Get Directions</span></a>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <ul class="social-buttons">
                    <?php if(isset($manifest->dribbble)): ?>
                        <li><a  target="_blank" href="<?= $manifest->dribbble ?>" class="social-btn"><i class="fab fa-dribbble"></i></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->twitter)): ?>
                        <li><a  target="_blank" href="<?= $manifest->twitter ?>" class="social-btn"><i class="fab fa-twitter"></i></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->facebook)): ?>
                        <li><a  target="_blank" href="<?= $manifest->facebook ?>" class="social-btn"><i class="fab fa-facebook"></i></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->instagram)): ?>
                        <li><a  target="_blank" href="<?= $manifest->instagram ?>" class="social-btn"><i class="fab fa-instagram"></i>></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->linkedin)): ?>
                        <li><a  target="_blank" href="<?= $manifest->linkedin ?>" class="social-btn"><i class="fab fa-linkedin"></i></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->youtube)): ?>
                        <li><a  target="_blank" href="<?= $manifest->youtube ?>" class="social-btn"><i class="fab fa-youtube"></i></a></li>
                    <?php endif ?>
                    <?php if(isset($manifest->github)): ?>
                        <li><a  target="_blank" href="<?= $manifest->github ?>" class="social-btn"><i class="fab fa-github"></i></a></li>
                    <?php endif ?>
                    <?php if(false && isset($manifest->wa)): ?>
                        <li><a target="_blank" href="<?= 'https://web.whatsapp.com/send?phone=' . str_replace('+', '', $manifest->wa)  . '&text='?>" class="social-btn"><i class="fab fa-whatsapp"></i></a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- /CONTACT -->

<!--FOOTER-->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6 align-center">
                <ul class="legals">
                    <li><button class="md-trigger " data-modal="modal-11">Disclaimer</button></li>
                    <li><a href="http://multia.in/">nahnumedia.com</a></li>
                </ul>
            </div>
            <div class="md-modal md-effect-9" id="modal-11">
                <div class="md-content padding-none">
                    <div class="folio">
                        <div class="sp-name disclaimer"><strong>Disclaimer</strong></div>
                        <div class="sp-dsc disclaim-border">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            <br /><br />
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                            nulla pariatur. Sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                            mollit anim id est laborum.
                        </div>
                        <button class="md-close"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="md-overlay"></div>
        </div>
    </div>
</footer>
<!-- /FOOTER -->

<?php
    $removed = isset($removedFromGroup) ? $removedFromGroup : [];
    if (isset($resource) && !empty($resource)) {
        echo addResourceGroup($resource, $removed, null, 'body:end');
        
    }
    if(isset($extra_js) && !empty($extra_js)){
        foreach($extra_js as $js){
            if(!isset($js['attr']))
                $js['attr'] = null;

            if($js['pos'] == 'body:end' && $js['type'] == 'file')
                echo '<script src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif($js['pos'] == 'body:end' && $js['type'] == 'cache')
                echo '<script type="application/javascript" src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif($js['pos'] == 'body:end' && $js['type'] == 'inline'){
                echo '<script async type="application/javascript">' . $js['script'] . '</script>';
            }
            elseif($js['pos'] == 'body:end' && $js['type'] == 'cdn')
                echo '<script src="' . $js['src'] . '"'. $js['attr'] .'></script>';
        }
    }

    if(isset($extra_css) && !empty($extra_css)){
        foreach($extra_css as $css){
            if(!isset($css['attr']))
                $css['attr'] = null;
                
            if($css['pos'] == 'body:end' && $css['type'] == 'file')
                echo '<link rel="stylesheet" href="' . base_url('public/assets/' . $css['src']) . '"></link>';
            elseif($css['pos'] == 'body:end' && $css['type'] == 'inline'){
                echo '<style>' . $css['style'] . '</style>';
            }
            elseif($css['pos'] == 'body:end' && $css['type'] == 'cdn')
                echo '<link rel="stylesheet" href="' . $css['src'] . '"'. $css['attr'] .'></link>';
        }
    }
?>
</body>

</html>