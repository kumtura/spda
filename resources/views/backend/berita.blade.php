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


         <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="largeModalLabel">Tambah Data Berita</h5>
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
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Berita</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="textinput" name="textinput" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama Berita</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Harga</label></div>
                                                                <div class="col-12 col-md-9">
                                                                <div id="toolbar-container"></div>

                                                            <!-- This container will become the editable. -->
                                                            <div id="editor" style="border:1px solid #999999;">
                                                                <p>Input Berita Di sini.</p>
                                                            </div>
                                                            <small class="form-text text-muted">Masukkan Harga Ikan</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto</label></div>
                                                                <div class="col-12 col-md-9"><input type="file" id="uploadinput" name="uploadinput" placeholder="Text" class="form-control"><small class="form-text text-muted">Masukkan Foto Ikan</small></div>
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
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Ikan</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="textinput_edit" name="textinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama Ikan</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Harga</label></div>
                                                                <div class="col-12 col-md-9"><input type="number" id="hargainput_edit" name="hargainput_edit" placeholder="0" class="form-control"><small class="form-text text-muted">Masukkan Harga Ikan</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto</label></div>
                                                                <div class="col-12 col-md-9"><input type="file" id="uploadinput" name="uploadinput" placeholder="Text" class="form-control"><small class="form-text text-muted">Masukkan Foto Ikan</small></div>
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
                                <strong class="card-title">Data Table Berita</strong>
                            </div>
                            <div class="card-body">

                        <!--    
                            <button type="button" class="btn btn-primary mb-1" onclick="launch()">
                                + Tambah Data
                            </button>
                        -->
                        <a href="<?php echo url('admin_perikanan/berita/tambah'); ?>">    <button type="button" class="btn btn-primary mb-1">
                                + Tambah Data
                            </button>
                        </a>

                            <p>&nbsp;</p>

                                <table id="ikantable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Isi</th>
                                            <th>Foto</th>
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

        /*var modals = jQuery('#largeModal');
        modals.find('.modal-content').resizable({
            minWidth: 625,
            minHeight: 600,
            handles: 'n, e, s, w, ne, sw, se, nw',
          })
          .draggable({
            handle: '.modal-header'
          });
          */

        var launch = function () {
          modals.modal();
        }
        



        jQuery(document).ready(function() {
            table = jQuery('#ikantable').DataTable( {
                "ajax": {
                    "url": "<?php echo url('ambil_listberita'); ?>",
                    "dataSrc": ""
                },
                columns: [
                    { "data": 'id_berita' },
                    { "data": 'judul_berita' },
                    { "data": 'isi_berita' },
                    { "data": 'gambar' },
                    { "data": 'aksi' }
                ]
            } );

            jQuery('.modal-dialog').draggable();


        } );

        function editdataModal(value){
            
            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_ikan'); ?>"+"/"+value,
                dataType:"json",
                data:"",
                success:function(data){

                    jQuery("#textinput_edit").val(data.nama);
                    jQuery("#hargainput_edit").val(data.harga);
                    jQuery("#idikaninput").val(data.id_ikan);

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
                url:"<?php echo url('post_ikan'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#largeModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

        function submit_edit_form(){
            var serial = jQuery("#form_multi_edit").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('updateikan'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#editdataModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

</script>

<script>
    DecoupledEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
            const toolbarContainer = document.querySelector( '#toolbar-container' );

            toolbarContainer.appendChild( editor.ui.view.toolbar.element );
        } )
        .catch( error => {
            console.error( error );
        } );
</script>


   @stop