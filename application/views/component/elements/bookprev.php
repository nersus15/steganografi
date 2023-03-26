<div class="comp-container">
    <div class="mobile-layout">
        <div class="book-cover">
            <img class="book-top" src="<?= $img ?>" alt="book-top" />
            <img class="book-side" src="https://raw.githubusercontent.com/atomic-variable/images-repo/e37f432405904a280858e5437ce1960753bc78a3/book-side.svg" alt="book-side" />
        </div>
        <div class="preface">
            <div class="book-content">
                <div style="text-align: left;">
                    <div class="book-info-header">
                        <div class="book-title" style="cursor: pointer;" data-id="<?= $id ?>"><?= kapitalize($title) ?></div>
                    </div>
                    <div style="margin-bottom: 10px;" class="author">by <?= $author ?></div>
                    <div style="margin-bottom: 10px;" class="author"><?= $isbn ?></div>
                </div>
                <div class="row"style="margin-bottom: 20px;">
                    <div class="col-sm-4"><?= $harga ?></div>
                    <div class="col-sm-4"><?= $dim() ?></div>
                    <div class="col-sm-4"><?= $halaman ?></div>
                </div>
                <div class="body">
                    <p style="text-align: left;"> <?= $sinopsis ?> </p>
                </div>
            </div>
        </div>
    </div>
</div>