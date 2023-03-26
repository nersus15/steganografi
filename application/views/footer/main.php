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

<script>
    var adaThemeSelector = <?php echo !empty($adaThemeSelector) ? boolval($adaThemeSelector) : 'false' ?>;
    var notifBtn = $("#notificationButton");
    $(document).ready(function(){
        var notif = <?= isset($this->notifikasi) ? json_encode($this->notifikasi) : '{}' ?>;
        if(adaThemeSelector && themeSelector && typeof(themeSelector) === "function")
            themeSelector();
        else{
            try {
                $("body").dore();
            } catch (error) {
                
            }
        }
        function renderNotifikasi(){
            var notifItem = "";
            
            notif.forEach((n, i) => {
                var link = n.link ? path + n.link : '#';
                if(i == 15){
                    // notifItem += '<div style="width: 100%; background: whitesmoke; justify-content: center" class="d-flex flex-row mt-3">' +
                    //             '<div class="pl-3 pr-2">' +
                    //                 '<a href="'+ path +'notifcenter" class="btn">Baca Semua</a>'
                    //             '</div>' +
                    //         '</div>';
                }else if(i < 15){
                    if(!n.dibaca){
                        notifItem += '<div class="d-flex flex-row mb-3 pb-3 border-bottom">' +
                                '<div class="pl-3 pr-2">' +
                                    '<a class="nitem" data-id="'+n.id+'" href="'+ link +'">' +
                                        '<p class="font-weight-medium mb-1">'+ n.pesan +'</p>' +
                                        '<p class="text-muted mb-0 text-small">'+ n.dibuat +'</p>' +
                                    '</a>' +
                                '</div>' +
                            '</div>';
                    }else{
                        notifItem += '<div class="d-flex flex-row mb-3 pb-3 border-bottom">' +
                                '<div class="pl-3 pr-2">' +
                                    '<b><a class="text-primary nitem" data-id="'+ n.id +'" href="'+ link +'">' +
                                        '<p class="font-weight-medium mb-1">'+ n.pesan +'</p>' +
                                        '<p class="text-muted mb-0 text-small">'+ n.dibuat +'</p>' +
                                    '</a></b>' +
                                '</div>' +
                            '</div>';
                    }
                }
                
            });
            
            var unreadNotif = notif.filter(n =>!n.dibaca);
            $(notifBtn).find('span.count').text(unreadNotif.length);
            $("#notificationDropdown").empty();
            $("#notificationDropdown").append(notifItem);
            $("#notificationDropdown").find('a.nitem').click(function(e){
                e.preventDefault();
                var link = $(this).attr('href');
                var nid = $(this).data('id');
                var notifItem = notif.filter(n => n.id == nid);
                if(notifItem.length > 0)
                    notifItem = notifItem[0];
                    
                if(!notifItem.dibaca){
                    $.post(path + 'ws/baca_notif/' + nid).then(res => {
                        notif.forEach((n, i) => {
                            if(n.id == nid)
                                notif[i].dibaca = res.dibaca;
                        });
                    });
                }
                
                if(link != '#')
                    window.location.href = link;
                else{
                    notifikasi(notifItem.pesan, {});
                }

            });
        };

        if(notifBtn.length > 0){
            renderNotifikasi();
            setInterval(renderNotifikasi, 10000);
        }
           
    });
</script>
</body>

</html>