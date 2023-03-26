$(document).ready(function(){
    var form_data = <?= $form_data ?>;
    var edited_data = <?= $form_cache ?>;
    var formid = form_data.formid;
    var components = {
        form: $("#" + formid),
        method: $("#method"),
        judul: $('#judul'),
        penulis: $('#penulis'),
        id: $('#id'),
        panjang: $('#panjang'),
        lebar: $('#lebar'),
        harga: $('#harga'),
        desc: $('#desc'),
        halaman: $('#halaman'),
        harga: $('#harga'),
        cover: $('#old-cover'),
    };

    _persiapan_data().then(data => {
        _add_event_listener(data);
        _persiapan_nilai(data);
    
    });
   



    async function _persiapan_data(){
        var data = {};
       
        return data;
    }


    function _add_event_listener(data){

    }


    function _persiapan_nilai(data){
        if(data.lapangan){
            Object.keys(data.lapangan).forEach(k => {
                if(k == 'type') return;
                components.lapangan.append("<option value='" + data.lapangan[k].id + "'>" + data.lapangan[k].id + " - " + data.lapangan[k].jenis +"</option>");
            });
        }

        if(form_data.mode == 'edit' && edited_data){
            components.method.val('update');
            components.id.val(edited_data.id);
            components.desc.val(edited_data.deskripsi);
            components.penulis.val(edited_data.penulis);
            components.judul.val(edited_data.judul);
            components.panjang.val(edited_data.panjang);
            components.lebar.val(edited_data.lebar);
            components.halaman.val(edited_data.halaman);
            components.harga.val(edited_data.harga);
            components.cover.val(edited_data.cover);
        }else if(form_data.mode == 'baru'){
            components.method.val('POST');
        }
    }

});