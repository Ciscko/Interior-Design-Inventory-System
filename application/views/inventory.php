
   
        
        <a href=""></a>
        <h3>INVENTORY</h3>
        <br />
        <button class="btn btn-success" onclick="add_inventory()"><i class="glyphicon glyphicon-plus"></i> Add Inventory</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <button class="btn btn-danger" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>
        <a class="btn btn-primary" href="<?php echo base_url('export/export_table/inventorys');?>" ><i class="glyphicon glyphicon-download-alt"></i> Excel Export</a>
         <a class="btn btn-success" onclick="import_table()"><i class="glyphicon glyphicon-circle-arrow-up"></i> Import Excel</a>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                     <th>Inventory ID</th>
                    <th>Details</th>
                    <th>Purchased Qty</th>
                    <th>Price/ Unit</th>
                    <th>Stock Qty</th>
                    <th>Stock Worth</th>
                    <th>Reorder Level</th>
                    <th>Reorder Quantity</th>
                    <th>Quantity Sold</th>
                    <th>Discontinued Product</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                <th></th>
                   <th>Inventory ID</th>
                    <th>Details</th>
                    <th>Purchased Qty</th>
                    <th>Price/ Unit</th>
                    <th>Stock Qty</th>
                    <th>Stock Worth</th>
                    <th>Reorder Level</th>
                    <th>Reorder Quantity</th>
                    <th>Quantity Sold</th>
                    <th>Discontinued Product</th>
                <th>Action</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
</div>
<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('inventory/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },

        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });


    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });

});

function import_table(){
  
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form3').modal('show'); // show bootstrap modal
    $('.modal-title').text('Import Excel File'); 

}

function add_inventory()
{
    save_method = 'add';
    $('#form2')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form2').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Inventory'); // Set Title to Bootstrap modal title

}

function edit_inventory(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('inventory/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="detail"]').val(data.detail);
            $('[name="qtypurchased"]').val(data.qtypurchased);
            $('[name="pricePer"]').val(data.pricePer);
            $('[name="qtyStock"]').val(data.qtyStock);
            $('[name="stockWorth"]').val(data.stockWorth);
            $('[name="reorderL"]').val(data.reorderL);
            $('[name="reorderQty"]').val(data.reorderQty);
            $('[name="qtySold"]').val(data.qtySold);
            $('[name="discoPrdct"]').val(data.discoPrdct);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Inventory'); // Set title to Bootstrap modal title


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('inventory/ajax_add')?>";
    } else {
        url = "<?php echo site_url('inventory/ajax_update')?>";
    }

     if(save_method == 'add')
    {
         var formData = new FormData($('#form2')[0]);
    }
    else
    {
        var formData = new FormData($('#form')[0]);
    }

    // ajax adding data to database
   
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

           if(data.status) //if success close modal and reload ajax table
            {   
                if(save_method == 'add')
                 {
                    $('#modal_form2').modal('hide');
                 }
                else {
                        $('#modal_form').modal('hide');
                     }
                reload_table();
            }
            
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_inventory(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('inventory/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function bulk_delete()
{
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "<?php echo site_url('inventory/ajax_bulk_delete')?>",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reload_table();
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Inventory Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Inventory ID</label>
                            <div class="col-md-9">
                                <input name="id" placeholder="Site Log ID" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Product Details</label>
                            <div class="col-md-9">
                                 <input name="detail" placeholder="Product Details" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity Purchased</label>
                            <div class="col-md-9">
                                <input name="qtypurchased" placeholder="Item" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Price / Unit</label>
                            <div class="col-md-9">
                                <input name="pricePer" placeholder="Price / Unit" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                       
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity in Stock</label>
                            <div class="col-md-9">
                                <input name="qtyStock" placeholder="Quantity in Stock" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Stock Worth</label>
                            <div class="col-md-9">
                                <input name="stockWorth" placeholder="Stock Worth" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3">Reorder Level</label>
                            <div class="col-md-9">
                               <input name="reorderL" placeholder="Reorder Level" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Reorder Quantity</label>
                            <div class="col-md-9">
                               <input name="reorderQty" placeholder="Reorder Quantity" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity Sold</label>
                            <div class="col-md-9">
                               <input name="qtySold" placeholder="Quantity Sold" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Discontinued Product</label>
                            <div class="col-md-9">
                               <input name="discoPrdct" placeholder="Discontinued Product" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modal_form2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Inventory Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Details</label>
                            <div class="col-md-9">
                                 <input name="detail" placeholder="Product Details" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Purchased Qty</label>
                            <div class="col-md-9">
                                <input name="qtypurchased" placeholder="Quantity Purchased" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Price/Unit</label>
                            <div class="col-md-9">
                                <input name="pricePer" placeholder="Price / Unit" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                       
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Stock Qty</label>
                            <div class="col-md-9">
                                <input name="qtyStock" placeholder="Quantity in Stock" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3">Stock Worth</label>
                            <div class="col-md-9">
                                <input name="stockWorth" placeholder="Stock Worth" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-3">Reorder Level</label>
                            <div class="col-md-9">
                               <input name="reorderL" placeholder="Reorder Level" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Reorder Quantity</label>
                            <div class="col-md-9">
                               <input name="reorderQty" placeholder="Reorder Quantity" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity Sold</label>
                            <div class="col-md-9">
                               <input name="qtySold" placeholder="Quantity Sold" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Discontinued Product</label>
                            <div class="col-md-9">
                               <input name="discoPrdct" placeholder="Discontinued Product" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="modal_form3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Upload</h3>
            </div>
            <div class="modal-body form">
               <?php
                $output = '';
                $output .= form_open_multipart('inventory/save');
                $output .= '<div class="row">';
                $output .= '<div class="col-lg-12 col-sm-12"><div class="form-group">';
                $output .= form_label('Import Data', 'image');
                $data = array(
                    'name' => 'userfile',
                    'id' => 'userfile',
                    'class' => 'form-control filestyle',
                    'value' => '',
                    'data-icon' => 'false'
                );
                $output .= form_upload($data);
                $output .= '</div> <span style="color:red;">*Please choose an Excel file(.xls or .xlxs) as Input</span></div>';
                $output .= '<div class="col-lg-4 col-sm-4"><div class="form-group text-right">';
                $data = array(
                    'name' => 'importfile',
                    'id' => 'importfile-id',
                    'class' => 'btn btn-primary',
                    'value' => 'Import',
                );
                $output .= form_submit($data, 'Import Data');
                $output .= '</div></div>
                                        ';
                $output .= form_close();
                echo $output;
                ?>
                        
                        
                       
                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>