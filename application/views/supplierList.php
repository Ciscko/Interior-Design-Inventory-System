
   
        <h1 style="font-size:20pt">SUPPLIERS</h1>

       
        <br />
        <button class="btn btn-success" onclick="add_supplier()"><i class="glyphicon glyphicon-plus"></i> Add Supplier</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <button class="btn btn-danger" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>
        <a class="btn btn-primary" href="<?php echo base_url('export/export_table/suppliers');?>" ><i class="glyphicon glyphicon-download-alt"></i> Excel Export</a>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Supplier ID</th>
                    <th>Supplier Person</th>
                    <th>Supplier Company Name</th>
                    <th>Supplier VAT NO</th>
                    <th>Supplier PIN NO</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                <th></th>
                    <th>Supplier ID</th>
                    <th>Supplier Person</th>
                    <th>Supplier Company Name</th>
                    <th>Supplier VAT NO</th>
                    <th>Supplier PIN NO</th>
                    <th style="width:150px;">Action</th
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
            "url": "<?php echo site_url('supplier/ajax_list')?>",
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



function add_supplier()
{
    save_method = 'add';
    $('#form2')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form2').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Supplier'); // Set Title to Bootstrap modal title

}

function edit_supplier(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('supplier/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="personId"]').val(data.personId);
            $('[name="name"]').val(data.name);
            $('[name="company"]').val(data.company);
            $('[name="vatNo"]').val(data.vatNo);
            $('[name="pinNo"]').val(data.pinNo);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Supplier'); // Set title to Bootstrap modal title


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
        url = "<?php echo site_url('supplier/ajax_add')?>";
    } else {
        url = "<?php echo site_url('supplier/ajax_update')?>";
    }

    // ajax adding data to database
    if(save_method == 'add')
    {
         var formData = new FormData($('#form2')[0]);
    }
    else
    {
        var formData = new FormData($('#form')[0]);
    }
   
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

function delete_supplier(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('person/ajax_delete')?>/"+id,
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
                url: "<?php echo site_url('supplier/ajax_bulk_delete')?>",
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
                <h3 class="modal-title">Supplier Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group" >
                            <label class="control-label col-md-3">Supplier Person ID</label>
                            <div class="col-md-9" id="slist">
                                 <input name="personId" placeholder="Supplier Person ID" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier Person Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Supplier Person Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier Company Name</label>
                            <div class="col-md-9">
                            <input name="company" placeholder="Supplier Company Name" class="form-control" type="text">
                                <span class="help-block"></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3"> Supplier VAT NO</label>
                            <div class="col-md-9">
                                <textarea name="vatNo" placeholder="VAT NO" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier PIN NO</label>
                            <div class="col-md-9">
                                <input name="pinNo" placeholder="PIN NO" class="form-control " type="text">
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
                <h3 class="modal-title">Supplier Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group" >
                            <label class="control-label col-md-3">Supplier Person ID</label>
                            <div class="col-md-9" id="slist">
                             <select name="personId" class="form-control">
                                 <?php foreach ($persons as $key => $value ) {?>
                                    <option value="<?php echo $value->personId;?>"><?php echo $value->personId.'  --  '.$value->name;?></option>
                                 <?php } ?>
                             </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier Person Name</label>
                            <div class="col-md-9">
                                 <select name="name" class="form-control">
                                 <?php foreach ($persons as $key => $value ) {?>
                                    <option value="<?php echo $value->name;?>"><?php echo'-- '. $value->name.' --';?></option>
                                 <?php } ?>
                             </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier Company Name</label>
                            <div class="col-md-9">
                            <input name="company" placeholder="Supplier Company Name" class="form-control" type="text">
                                <span class="help-block"></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3"> Supplier VAT NO</label>
                            <div class="col-md-9">
                                <textarea name="vatNo" placeholder="VAT NO" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Supplier PIN NO</label>
                            <div class="col-md-9">
                                <input name="pinNo" placeholder="PIN NO" class="form-control " type="text">
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
<!-- End Bootstrap modal -->
</body>
</html>