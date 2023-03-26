<!DOCTYPE html>
<html lang="id">
<?php 
    $manifest = json_decode(file_get_contents(DOCS_PATH . "manifest.json"));
    
?>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($manifest->favicon) && !empty($manifest->favicon)): ?>
        <link rel="icon" type="image/png" href="<?= assets_url($manifest->favicon)?>"/>
    <?php endif ?>
    <meta property="og:type" content="website" >
    <meta name="description" content="<?php echo isset($desc) ? $desc : $manifest->description?>">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <?php if(!empty($manifest->keywords)):
        foreach($manifest->keywords as $key):?>
            <meta name="keywords" content="<?= $key ?>">
    <?php endforeach;endif?>
    <meta property="og:title" content="<?php echo isset($konten) ? $konten : $manifest->title ?>">
    <meta property="og:description" content="<?php echo isset($desc) ? $desc : $manifest->description?>">
    <meta property="og:url" content="<?php echo base_url() ?>">
    <?php if(isset($thumb) || isset($manifest->image)): ?>
        <meta property="og:image" content="<?php echo isset($thumb) ? $thumb : base_url($manifest->image) ?>">
    <?php endif?>
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />
    <?php if(isset($manifest->image)): ?>
    <link rel="icon" type="image/gif" href="<?php echo !empty($manifest) && isset($manifest->image) ? base_url( $manifest->image ) : null ?>">
    <?php endif?>
    <title><?php echo isset($title) ? $title : $manifest->title; ?></title>
        
    <?php
    $removed = isset($removedFromGroup) ? $removedFromGroup : [];
    $bodyAttrText = "";
    if(isset($bodyAttr) && is_array($bodyAttr)){
        foreach($bodyAttr as $k => $v){
            $bodyAttrText .= $k . "='" . $v . "'";
        }
    }
    if(!isset($bodyid)) $bodyid = 'app-container';
    if (isset($resource) && !empty($resource)) {
        echo addResourceGroup($resource, $removed, null, 'head');     
    }
    if (isset($extra_js) && !empty($extra_js)) {
        foreach ($extra_js as $js) {
            if(!isset($js['attr']))
                $js['attr'] = null;
                
            if ($js['pos'] == 'head' && $js['type'] == 'file')
                echo '<script src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif ($js['pos'] == 'head' && $js['type'] == 'cache')
                echo '<script type="application/javascript" src="' . base_url('public/assets/' . $js['src']) . '"></script>';
            elseif ($js['pos'] == 'head' && $js['type'] == 'inline') {
                echo '<script>' . $js['script'] . '</script>';
            }
            elseif($js['pos'] == 'head' && $js['type'] == 'cdn')
                echo '<script src="' . $js['src'] . '"'. $js['attr'] .'></script>';
        }
    }

    if (isset($extra_css) && !empty($extra_css)) {
        foreach ($extra_css as $css) {
            if(!isset($css['attr']))
                $css['attr'] = null;

            if ($css['pos'] == 'head' && $css['type'] == 'file')
                echo '<link rel="stylesheet" href="' . base_url('public/assets/' . $css['src']) . '"></link>';
            elseif ($css['pos'] == 'head' && $css['type'] == 'inline') {
                echo '<style>' . $css['style'] . '</style>';
            }
            elseif($css['pos'] == 'head' && $css['type'] == 'cdn')
                echo '<link rel="stylesheet" href="' .  $css['src'] . '" '. $css['attr'] .'></link>';

        }
    }
    if(isset($hideSpinner) && !empty($bodyClass))
        $bodyClass = str_replace("show-spinner", "", $bodyClass);
    ?>
    <script>
        var path = "<?= base_url() ?>";
    </script>
</head>

<body id="<?= $bodyid ?>" <?= $bodyAttrText ?> class="<?php echo isset($sembunyikanSidebar) && $sembunyikanSidebar ? 'menu-hidden' : 'menu-default';?> <?= $bodyClass ?>">
   <?php if(isset($loadingAnim) && $loadingAnim === TRUE):?>
        <div class="c-overlay">
            <div class="c-overlay-text">Loading</div>
        </div>
    <?php endif ?>