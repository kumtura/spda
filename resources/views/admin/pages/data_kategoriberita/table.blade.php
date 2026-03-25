@extends('index')

@section('isi_menu')

<div class="container-fluid">
                <!-- *************************************************************** -->
                <!-- Start First Cards -->
                <!-- *************************************************************** -->
                <div class="card-group">
                    <div class="card border-right">
                        <div class="card-body">


            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="largeModalLabel"> Data Kategori Berita</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Form</strong>   Kategori Berita
                                                    </div>
                                                    <div class="card-body card-block">

                                                    <form id="form_multi" enctype="multipart/form-data" method="post">
                                                        
                                                            <!--<div class="row form-group">
                                                                <div class="col col-md-3"><label class=" form-control-label">Static</label></div>
                                                                <div class="col-12 col-md-9">
                                                                    <p class="form-control-static">Username</p>
                                                                </div>
                                                            </div>
                                                            -->
                                                            {{ csrf_field() }}


                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label"><b> Kategori Berita </b></label></div>
                                                            </div>

                                                            <hr />

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Kategori</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="emailinput" name="emailinput" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Kategori Berita</small></div>
                                                            </div>


                                                        
                                                    </div>
                                                </div>
                                               
                                            </div>

                                             <button type="reset" class="btn btn-secondary" style="display:none;" id="btn_reset">Reset</button>

                                </form>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                               
                                <button type="button" class="btn btn-primary" onclick="submit_form(); return false;">Confirm</button>
                            </div>
                        </div>
                    </div>
                 </form>
                </div>


                 <div class="modal fade" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="largeModalLabel">Edit Data  Kategori Berita</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Form</strong> Edit  Kategori Berita
                                                    </div>
                                                    <div class="card-body card-block">

                                                    <form id="form_multi_edit" enctype="multipart/form-data" method="POST">

                                                    <input id="iduserinput_edit" name="iduserinput_edit" type="hidden" />
                                                        
                                                        {{ csrf_field() }}

                                                        <div class="row form-group">
                                                            <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Kategori</label></div>
                                                            <div class="col-12 col-md-9"><input type="text" id="emailinput_edit" name="emailinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Email User</small></div>
                                                        </div>


                                                        
                                                    </div>
                                                </div>
                                               
                                            </div>

                                </form>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="submit_edit_form(); return false;">Confirm</button>
                            </div>
                        </div>
                    </div>
                 </form>
                </div>


 <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Table  Kategori Berita</h4>
                               <hr />
                               <button onclick="openModal();" type="button"
                                        class="btn waves-effect waves-light btn-primary"><i class="fas fa-plus"></i> Tambah User </button>
                                        <p></p>
                                <div class="table-responsive">
                                    <table id="ikantable" class="table table-striped table-bordered display no-wrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kategori Berita</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $num = 0;
                                                foreach($kategori as $rows){
                                                $num++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $num; ?></td>
                                                    <td><?php echo $rows->nama_kategori_berita; ?></td>
                                                    <td><a href="javascript:void(0)" onclick="editModal('<?php echo $rows->id_kategori_berita; ?>','<?php echo $rows->nama_kategori_berita; ?>')"> <i class="fa fa-edit"></i> Edit </a> &nbsp; 
                                                    <a href="javascript:void(0)" onclick="deletedata('<?php echo $rows->id_kategori_berita; ?>')"> <i class="fa fa-trash"></i> Delete </a></td>
                                                </tr>
                                                <?php
                                                }
                                            ?>
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Kategori Berita</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
                        </div>
                    </div>
                </div>

    <script type="text/javascript">
    var table = "";

        jQuery(document).ready(function() {

            table = jQuery('#ikantable').DataTable();

            //jQuery('.modal-dialog').draggable();


        } );

        function openModal(){
            $("#btn_reset").click();
            $("#largeModal").modal('show');
        }

        function editModal(index,nama){
            //$("#btn_reset").click();
            $("#emailinput_edit").val(nama);
            $("#iduserinput_edit").val(index);

            $("#editdataModal").modal('show');
            
        }

        function deletedata(value){
            var conn = confirm("Hapus data ?");

            if(conn == true){
                jQuery.ajax({
                    type:"GET",
                    url:url_menu_apis+"/"+"hapus_kategori_berita",
                    data:"id="+value,
                    success:function(data){
                        window.location = url_menu_apis+"/"+"data_kategoriberita";
                    }
                })
            }
        }

        function editdataModal(value){
            
            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_user'); ?>"+"/"+value,
                dataType:"json",
                data:"",
                success:function(data){

                    jQuery("#iduserinput_edit").val(data.id);
                    jQuery("#textinput_edit").val(data.name);
                    jQuery("#emailinput_edit").val(data.email);
                    jQuery("#levelinput_edit").val(data.id_level);
                    jQuery("#nowainput_edit").val(data.no_wa);

                    jQuery("#editdataModal").modal('show');
                }
            });

            
        }

        function submit_edit_form(){
            var serial = jQuery("#form_multi_edit").serialize();
            //alert()

            jQuery.ajax({
                type:"POST",
                url:url_menu_apis+"/"+"post_user_kategori_berita",
                data:serial,
                success:function(data){
                        // jQuery("#largeModal").modal('hide');
                        // table.ajax.reload();
                        window.location = url_menu_apis+"/"+"data_kategoriberita";
                }
            });


        }


        function submit_form(){
            var serial = jQuery("#form_multi").serialize();

            jQuery.ajax({
                type:"POST",
                url:url_menu_apis+"/"+"post_kategori_berita",
                data:serial,
                success:function(data){
                        // jQuery("#largeModal").modal('hide');
                        // table.ajax.reload();
                        window.location = url_menu_apis+"/"+"data_kategoriberita";
                }
            });

        }

    </script>
@stop
