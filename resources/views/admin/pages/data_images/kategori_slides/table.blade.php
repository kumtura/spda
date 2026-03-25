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
                                <h5 class="modal-title" id="largeModalLabel">Edit Data Banjar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="form_multi" method="post" action="<?php echo url("administrator/post_data_banjar"); ?>">
                             
                            <div class="modal-body">
                                                
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Tambah</strong> Data Banjar
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
                                                            <input type="hidden" name="t_id_banjar" id="t_id_banjar"  value="" />


                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label"><b> Form Data </b></label></div>
                                                            </div>

                                                            <hr />

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Banjar</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="t_nama_banjar" name="t_nama_banjar" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama Banjar</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label" required="required">Alamat Banjar</label></div>
                                                                <div class="col-12 col-md-9"><textarea rows="4" name="t_alamat_banjar" id="t_alamat_banjar" class="form-control"></textarea></div>
                                                            </div>


                                                        
                                                    </div>
                                                </div>
                                               
                                            </div>

                                             <button type="reset" class="btn btn-secondary" style="display:none;" id="btn_reset">Reset</button>


                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                               
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>

                            </form>

                        </div>
                    </div>
                 </form>
                </div>


                 <div class="modal fade" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="largeModalLabel">Tambah Data User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                 <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>Form</strong> Tambah Banjar
                                                    </div>
                                                    <div class="card-body card-block">

                                                    <form id="form_multi_edit" enctype="multipart/form-data" method="post">

                                                    <input id="iduserinput_edit" name="iduserinput_edit" type="hidden" />
                                                        
                                                            <!--<div class="row form-group">
                                                                <div class="col col-md-3"><label class=" form-control-label">Static</label></div>
                                                                <div class="col-12 col-md-9">
                                                                    <p class="form-control-static">Username</p>
                                                                </div>
                                                            </div>
                                                            -->
                                                            {{ csrf_field() }}


                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label"><b> Credential User </b></label></div>
                                                            </div>

                                                            <hr />

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Email User</label></div>
                                                                <div class="col-12 col-md-9"><input type="email" id="emailinput_edit" name="emailinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Email User</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Password User</label></div>
                                                                <div class="col-12 col-md-9"><input type="password" id="passwordinput_edit" name="passwordinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Password User</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label"><b> Detail User </b></label></div>
                                                            </div>
                                                            <hr />

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama User</label></div>
                                                                <div class="col-12 col-md-9"><input type="text" id="textinput_edit" name="textinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama User</small></div>
                                                            </div>


                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">No Telp/Wa</label></div>
                                                                <div class="col-12 col-md-9"><input type="number" id="nowainput_edit" name="nowainput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan No Telp/Wa</small></div>
                                                            </div>

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Level</label></div>
                                                                <div class="col-12 col-md-9">
                                                                <select  id="levelinput_edit" name="levelinput_edit" placeholder="" class="form-control">
                                                                <option value=""> - Level - </option>
                                                                <option value="1"> - Admin - </option>
                                                                <option value="2"> - Wartawan - </option>
                                                                <option value="3"> - Pimpinan Redaksi - </option>
                                                                <option value="4"> - Editor - </option>
                                                                </select>
                                                                <small class="form-text text-muted">Masukkan Level User</small></div>
                                                            </div>

                                                            

                                                            <div class="row form-group">
                                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto</label></div>
                                                                <div class="col-12 col-md-9"><input type="file" id="uploadinput_edit" name="uploadinput_edit" placeholder="Text" class="form-control"><small class="form-text text-muted">Masukkan Foto User</small></div>
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
                                <h4 class="card-title">Table Kategori Slides</h4>
                               <hr />
                               <button onclick="openModal();" type="button"
                                        class="btn waves-effect waves-light btn-primary"><i class="fas fa-plus"></i> Tambah Data </button>
                                        <p>&nbsp;</p>
                                <div class="table-responsive">
                                    <table id="ikantable" class="table table-striped table-bordered display no-wrap datatable"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kategori </th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $no = 1;
                                                foreach($datalist as $values){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo $values->nama_kategori; ?></td>
                                                        <td> <span style="cursor:pointer;" onclick="openModal_edit('<?php echo $values->id_kategori_slides; ?>','<?php echo $values->nama_kategori; ?>');"> <i class="fas fa-edit"></i>  </span>&nbsp; <span style="cursor:pointer;" onclick="openDelete_data('<?php echo $values->id_kategori_slides; ?>');"> <i class="fas fa-trash"></i> </span> </td>
                                                    </tr>
                                                    <?php
                                            $no++;
                                                }

                                            ?>
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kategori</th>
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

    $('#ikantable').DataTable();

        // jQuery(document).ready(function() {

        //     table = jQuery('#ikantable').DataTable( {
        //         "ajax": {
        //             "url": "<?php echo url('administrator/ambil_listbanjar'); ?>",
        //             "dataSrc": ""
        //         },
        //         columns: [
        //             { "data": 'no' },
        //             { "data": 'nama_banjar' },
        //             { "data": 'alamat' },
        //             { "data": 'aksi' }
        //         ]
        //     } );

        //     //jQuery('.modal-dialog').draggable();


        // } );

        function openModal(){
            $("#btn_reset").click();
            $("#t_id_banjar").val("");

            $("#largeModal").modal('show');
        }

        function openModal_edit(id,nama,alamat){
            $("#btn_reset").click();

            $("#t_id_banjar").val(id);
            $("#t_nama_banjar").val(nama);
            $("#t_alamat_banjar").val(alamat);

            $("#largeModal").modal('show');
        }

        function openDelete_data(value){
            var conn = confirm("Hapus data ?");

            if(conn == true){
                jQuery.ajax({
                    type:"POST",
                    url:"<?php echo url('administrator/hapusbanjar'); ?>",
                    data:"id="+value,
                    success:function(data){
                        //table.ajax.reload();
                        window.location = "<?php echo url('administrator/databanjar'); ?>";
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

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('updateuser'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#editdataModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }


        function submit_form(){
            var serial = jQuery("#form_multi").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('post_user'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#largeModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

    </script>
@stop