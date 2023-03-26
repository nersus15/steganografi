
<style>
    tr.selected{
        background-color: white !important;
    }
    .sticky-toolbar{
        position: fixed;
        right: 0;z-index: 99;
        top: 15%;
        background: white;
        border: 1px solid whitesmoke;
        border-radius: 25px;
        padding: 10px 10px;
    }
</style>
<div class="row mb-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body card-no-border">
                <h1 class="card-title ml-4"><?php echo $dtTitle ?></h1>
                <div class="table-responsive container-fluid dt-bootstrap4">
                    <table cellspacing="0" border="1px" class="dataTable table tabeleditor table-nomargin table-condensed table-bordered table-striped table-hover dataTable no-footer dtr-inline" >
                        <thead>
                            <tr>
                                <?php foreach($header as $h):?>
                                <th><?php echo $h ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $k => $v): $v = (array) $v?>
                                <tr>
                                <?php foreach($config as $c): ?>
                                    <?php 
                                        if(is_callable($c))
                                            echo "<td>" . $c((array)$v) . "</td>";
                                        else
                                            echo "<td>" . $v[$c] . "</td>";
                                        
                                    ?>
                                <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>