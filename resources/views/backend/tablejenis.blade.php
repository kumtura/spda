@extends('backend.layouts')

@section('isi_menu')


        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Table</a></li>
                            <li class="active">Data table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <form id="form_multi" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mediumModalLabel">Tambah Data Jenis Ikan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Tambah</strong> Data
                                                    </div>
                                                    <div class="card-body card-block">
                                                        
                                                            <!--<div class="row form-group">
                                                                <div class="col col-md-3"><label class=" form-control-label">Static</label></div>
                                                                <div class="col-12 col-md-9">
                                                                    <p class="form-control-static">Username</p>
                                                                </div>
                                                            </div>
                                                            -->
                                                            {{ csrf_field() }}

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jenis Ikan</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="textinput" name="textinput" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Jenis Ikan</small></div>
                                                            </div>

                                                        
                                                    </div>
                                                </div>
                                               
                                            </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="submit_form(); return false;">Confirm</button>
                            </div>
                        </div>
                    </div>
                 </form>
                </div>


        <div class="modal fade" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <form id="form_multi_edit" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mediumModalLabel">Edit Data Ikan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Edit</strong> Data
                                                    </div>
                                                    <div class="card-body card-block">
                                                        
                                                            <!--<div class="row form-group">
                                                                <div class="col col-md-3"><label class=" form-control-label">Static</label></div>
                                                                <div class="col-12 col-md-9">
                                                                    <p class="form-control-static">Username</p>
                                                                </div>
                                                            </div>
                                                            -->

                                                            <input type="hidden" id="idikaninput" name="idikaninput" />

                                                            {{ csrf_field() }}
                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jenis Ikan</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="textinput_edit" name="textinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Jenis Ikan</small></div>
                                                            </div>

                                                            

                                                        
                                                    </div>
                                                </div>
                                               
                                            </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="submit_edit_form(); return false;">Confirm</button>
                            </div>
                        </div>
                    </div>
                 </form>
                </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Data Table Jenis Ikan</strong>
                            </div>
                            <div class="card-body">

                            <button type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#mediumModal">
                                + Tambah Data
                            </button>

                            <p>&nbsp;</p>

                                <table id="ikantable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script type="text/javascript">

        var table = "";

        jQuery(document).ready(function() {

            table = jQuery('#ikantable').DataTable( {
                "ajax": {
                    "url": "<?php echo url('ambil_listjenis'); ?>",
                    "dataSrc": ""
                },
                columns: [
                    { "data": 'id_jenis_ikan' },
                    { "data": 'nama_jenis' },
                    { "data": 'aksi' }
                ]
            } );

        } );

        function editdataModal(value){
            
            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_jenisikan'); ?>"+"/"+value,
                dataType:"json",
                data:"",
                success:function(data){

                    jQuery("#textinput_edit").val(data.nama_jenis);
                    jQuery("#idikaninput").val(data.id_jenis_ikan);

                    jQuery("#editdataModal").modal('show');
                }
            });

            
        }

        function deletedataikan(value){
            var conn = confirm("Hapus data ?");

            if(conn == true){
                jQuery.ajax({
                    type:"GET",
                    url:"<?php echo url('hapusikan/'); ?>",
                    data:"id_ikan="+value,
                    success:function(data){
                        table.ajax.reload();
                    }
                })
            }
        }

        function submit_form(){
            var serial = jQuery("#form_multi").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('post_jenisikan'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#mediumModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

        function submit_edit_form(){
            var serial = jQuery("#form_multi_edit").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('update_jenisikan'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#editdataModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

</script>


   @stop