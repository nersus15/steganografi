configTabel["<?= $id ?>"] = [
    { 
        mData: null,
    },
    {
        data: 'username'
    },
    {
        data: 'nama'
    },
    {
        data: 'hp'
    },
    {
        data: 'email'
    }, 
    {
        mRender: function(row, type, data){
            return data.photo ? '<img style="max-width: 150px" src="' + path + '/public/assets/img/profile/' + data.photo + '">' : 'Tidak Ada Photo'; 
        }
    }
];
