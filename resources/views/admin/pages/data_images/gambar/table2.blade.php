@extends('index')

@section('isi_menu')

<div class="container-fluid">
    <!-- *************************************************************** -->
    <!-- Start First Cards -->
    <!-- *************************************************************** -->


<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Form</strong> Edit User
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
                                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Kategori</label></div>
                                                    <div class="col-12 col-md-9"><input type="text" id="textinput" name="textinput" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama Kategori</small></div>
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
                <h5 class="modal-title" id="largeModalLabel">Tambah Data Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Tambah Kategori
                            </div>
                            <div class="card-body card-block">

                            <form id="form_multi_edit" enctype="multipart/form-data" method="post">

                            <input id="iduserinput_edit" name="iduserinput_edit" type="hidden" />
                                    {{ csrf_field() }}
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Kategori</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="textinput_edit" name="textinput_edit" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Nama Kategori</small></div>
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


<div id="div_detail_berita" style="display:none;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="judul_berita"> Data gambar</h4>
                        <hr />

                        <p>&nbsp;</p>

                        <center>
                            <div class="col-md-6">
                                <img id="img_berita" class="img-fluid" />
                            </div>
                        </center>

                        <p>&nbsp;</p>

                        <div class="col-md-12" id="isi_berita">
                            
                        </div>

                        </div>
                    </div>
                </div>
        </div>
</div>


<div class="content mt-3" id="div_form_berita" style="display:none;">
    <div class="animated fadeIn">
        <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Data Tambah Gambar</strong>
                </div>
                <div class="card-body">

                <div class="col-lg-12">

                <form method="post" enctype="multipart/form-data" id="frm_berita" name="frm_berita">

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
                                <input type="hidden" name="t_idberita" id="t_idberita"  />

                                <div>
                                    <input type="hidden" name="t_aksi_pencarian" id="t_aksi_pencarian" value="" />
                                </div>

                                {{ csrf_field() }}
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Judul Berita</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="textinputan" name="textinputan" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Judul Berita</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Tanggal Berita</label></div>

                                    <div class="col-4 col-md-2">

                                    <select type="text" id="hariinput" name="hariinput" required="required" placeholder="" class="form-control">
                                        <option value="minggu">  Minggu </option>
                                        <option value="senin">  Senin </option>
                                        <option value="selasa">  Selasa </option>
                                        <option value="rabu">  Rabu </option>
                                        <option value="kamis"> Kamis </option>
                                        <option value="jumat">  Jumat </option>
                                        <option value="sabtu">  Sabtu </option>
                                    </select>

                                    <small class="form-text text-muted">Masukkan Hari Berita</small></div>

                                    <div class="col-4 col-md-3"><input type="date" id="tanggalinput" name="tanggalinput" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Tanggal Berita</small></div>

                                    <div class="col-4 col-md-2"><input type="time" id="waktuinput" name="waktuinput" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Waktu Berita</small></div>


                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Kategori Berita</label></div>
                                    <div class="col-12 col-md-9">

                                    <select type="text" id="kategoriinputan" name="kategoriinputan" required="required" placeholder="" class="form-control">
                                        <option value=""> -- Kategori --</option>
                                    </select>

                                    <small class="form-text text-muted">Masukkan Kategori Berita</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Isi Berita</label></div>
                                    <div class="col-12 col-md-9">
                                    <!--<div id="toolbar-container"></div>

                                <!-- This container will become the editable. -->
                                <!--<div id="editor" style="border:1px solid #999999; min-height:400px;">
                                    <p>Input Berita Di sini.</p>
                                </div>-->
                                <textarea name="DSC" class="materialize-textarea"></textarea>
                                <small class="form-text text-muted">Masukkan Isi Berita</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto</label></div>
                                    
                                    <div class="col-3 col-md-2" id="div_foto_berita">
                                        <img id="img_foto_berita" class="img-fluid" />
                                    </div>

                                    <div class="col-9 col-md-4"><input type="file" id="uploadinput" name="uploadinput" placeholder="Text" class="form-control"><small class="form-text text-muted">Upload Foto Berita</small></div>

                                </div>

                            
                        </div>
                    </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="openView()">Cancel</button>
                <button type="reset" class="btn btn-secondary" id="btn_cancel" style="display:none;">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_submit_approve" onclick="tambahdata();">
                    Submit
                </button>
            </div>

        </form>
                                                   
        </div>

        </div>
    </div>
</div>

      </div>
    </div><!-- .animated -->
</div><!-- .content -->

 <div class="row" id="view_berita_div">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
             
             <h4 class="card-title">Data Gambar</h4>

             <hr />

             <?php
               // if(Session::get('level') == "2"){
             ?>

                 <button onclick="openModal();" type="button"
                        class="btn waves-effect waves-light btn-primary"><i class="fas fa-plus"></i> Tambah Gambar 
                 </button>

             <?php
              //  }
             ?>
            
             <p></p>

             <p>&nbsp;</p>

            <div>

                <ul class="nav nav-tabs mb-3" id="div_tabs">
                    
                </ul>

                <div class="row form-group">
                     <div class="col col-md-3" style="margin-top:30px;"></div>


                    <br clear="all" />
                    <p></p>

                    <div class="col col-md-3" style="margin-top:30px;"></div>

                    <!-- <div class="col-9 col-md-9" style="margin-top:20px;">
                      <input type="submit"  id="btn_submit_cari" name="btn_submit_cari" value="Cari Data" class="btn-primary" /> 
                    </div> -->

                    <div class="col-12 col-md-9" style="margin-top:20px;">
                        <div id="ditemukan_berita"></div>
                    </div>

            </div>


            <div id="div_container_isi">
                                
                    <div class="col-lg-3 col-md-6" style="float:left;">
                        <!-- Card -->
                        <div class="card">
                            <img class="card-img-top img-fluid" src="../assets/images/big/img1.jpg"
                                alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title">Card title</h4>
                                <p class="card-text">Some quick example text to build on the card title and make
                                    up the bulk of the card's content.</p>
                                <a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <!-- column -->
                    <!-- column -->
                    <div class="col-lg-3 col-md-6" style="float:left;">
                        <!-- Card -->
                        <div class="card">
                            <img class="card-img-top img-fluid" src="../assets/images/big/img2.jpg"
                                alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title">Card title</h4>
                                <p class="card-text">Some quick example text to build on the card title and make
                                    up the bulk of the card's content.</p>
                                <a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <!-- column -->
                    <!-- column -->
                    <div class="col-lg-3 col-md-6" style="float:left;">
                        <!-- Card -->
                        <div class="card">
                            <img class="card-img-top img-fluid" src="../assets/images/big/img3.jpg"
                                alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title">Card title</h4>
                                <p class="card-text">Some quick example text to build on the card title and make
                                    up the bulk of the card's content.</p>
                                <a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <!-- column -->
                    <!-- column -->
                    <div class="col-lg-3 col-md-6 img-fluid" style="float:left;">
                        <!-- Card -->
                        <div class="card">
                            <img class="card-img-top img-fluid" src="../assets/images/big/img4.jpg"
                                alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title">Card title</h4>
                                <p class="card-text">Some quick example text to build on the card title and make
                                    up the bulk of the card's content.</p>
                                <a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>

                    <div class="col-lg-3 col-md-6 img-fluid" style="float:left;">
                        <!-- Card -->
                        <div class="card">
                            <img class="card-img-top img-fluid" src="../assets/images/big/img4.jpg"
                                alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title">Card title</h4>
                                <p class="card-text">Some quick example text to build on the card title and make
                                    up the bulk of the card's content.</p>
                                <a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>

            </div>

    </div>

                        </div>
                    </div>
                </div>

                </div>
                       

    <script type="text/javascript">
    var table = "";
    var active_id = "";

    var level_user = "<?php echo Session::get('level'); ?>";

        jQuery(document).ready(function() {

            /*table = jQuery('#ikantable').DataTable( {
                "ajax": {
                    "url": "<?php //echo url('ambil_listkategori'); ?>",
                    "dataSrc": ""
                },
                columns: [
                    { "data": 'no' },
                    { "data": 'name' },
                    { "data": 'aksi' }
                ]
            } );*/

            $('#textinputancari').on('keypress', function (e) {
                
                 if(e.which === 13){
                    //alert("click");
                    ambil_beritaparam(active_id , $('#textinputancari').val(), $("#chk_cari").val());
                 }

            });

            $('#btn_submit_cari').on('click', function (e) {
                 //if(e.which === 13){
                    //alert("click");
                
                    //alert($(".chk_cari:checked").val());

                    ambil_beritaparam(active_id , $('#textinputancari').val(), $(".chk_cari:checked").val());
                // }
            });

            jQuery('textarea[name="DSC"]').ckeditor();

            $("#div_container_isi").html("");

            $("#div_tabs").html("");

            // $.ajax({
            //     type:"get",
            //     url:"<?php //echo url('ambil_listberita'); ?>",
            //     data:"",
            //     dataType:"json",
            //     success:function(data){
            //     //console.log("databerita" , data);
            //     $.each(data,function(index , element){

            //         var sudah_update = '<a href="javascript:void(0)" onclick="open_detail('+element.id_berita+')" class="btn btn-warning"><i class="fas fa-bullseye"></i>  </a> </div><div style="float:right;"><a href="javascript:void(0)" class="btn btn-success" onclick="editModal('+element.id_berita+')"><i class="fas fa-pencil-alt"></i> Edit</a> &nbsp; <a href="javascript:void(0)" class="btn btn-primary"> <i class="fas fa-trash"></i> Hapus</a></div>';

            //         if(element.sudah_update == "1" && level_user != 3){
            //             sudah_update = "<i class='fa fa-check' style='color:green;'></i> Berita ini sudah diupdate oleh editor";
            //         }

            //         if(element.approved == "1"){
            //             sudah_update = "<i class='fa fa-check' style='color:green;'></i> Di Setujui";
            //         }

            //         var isi_berita = '<div class="col-lg-6 col-md-6" style="float:left; margin-top:30px; height:800px; overflow:auto;"><div class="card"><img class="card-img-top img-fluid" src="'+element.urlfoto+'" alt="Card image cap"><div class="card-body"><h4 class="card-title">'+element.judul_berita+'</h4><h5 class="card-title" style="text-align:right;"><small>'+element.tanggal+'</small></small></h5><p class="card-text">'+element.isi_berita+'</p><br clear="all" /><h4 class="card-title" style="text-align:left;">'+element.kode_wartawan+'<br clear="all" /> <p></p></h4> <div style="float:left;">'+sudah_update+'</h4></div></div></div>';

            //         $("#div_container_isi").append(isi_berita);

            //     });

            //   }
            // });

            $.ajax({
                type:"get",
                url:"<?php echo url('administrator/ambil_listkategori_slides'); ?>",
                data:"",
                dataType:"json",
                success:function(data){
                //console.log("databerita" , data);
                var len = data.length;
                var a = 1;
                $.each(data,function(index , element){

                var active = "";

                if(a == 1){
                    active = "active";
                    active_id = element.id_kategori_slides;
                }

                

                var kategori = '<li class="nav-item" onclick="ambil_berita('+element.id_kategori_slides+');"><a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link '+active+'"><i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i><span class="d-none d-lg-block">'+element.nama_kategori+'</span></a></li>';

                a++;

                $("#div_tabs").append(kategori);

                });

              }
            });


            

            //jQuery('.modal-dialog').draggable();


        } );

        function open_detail(id){

            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_berita'); ?>"+"/"+id,
                dataType:"json",
                data:"",
                success:function(data){

                    $("#view_berita_div").slideUp();
            
                    $("#div_detail_berita").slideDown();

                    jQuery("#judul_berita").html('<a onclick="openViewdetail()" style="cursor:pointer;"> <i class="fas fa-undo"></i> </a> &nbsp;'+" "+data.judul_berita);
                    jQuery("#isi_berita").html(data.isi_berita);

                    jQuery("#img_berita").prop("src" , "<?php echo url('public/berita/foto/'); ?>"+"/"+data.foto);

                    

                    //jQuery("#editdataModal").modal('show');
                }
            });

        }

        

        function ambil_berita(id){

            $("#div_container_isi").html("");
            active_id = id;

            $('#textinputancari').val("");
            $("#ditemukan_berita").html("");


            $.ajax({
                type:"get",
                url:"<?php echo url('ambil_listberita_kategori'); ?>",
                data:"id="+id,
                dataType:"json",
                success:function(data){
                //console.log("databerita" , data);
                $.each(data,function(index , element){

                var sudah_update = '<a href="javascript:void(0)" onclick="open_detail('+element.id_berita+')" class="btn btn-warning"><i class="fas fa-bullseye"></i> </a> </div><div style="float:right;"><a href="javascript:void(0)" class="btn btn-success" onclick="editModal('+element.id_berita+')"><i class="fas fa-pencil-alt"></i> Edit</a> &nbsp; <a href="javascript:void(0)" class="btn btn-primary"> <i class="fas fa-trash"></i> Hapus</a></div>';

                if(element.sudah_update == "1"){
                    sudah_update = "<i class='fa fa-check' style='color:green;'></i> Berita ini sudah diupdate oleh editor";
                }

                if(element.approved == "1"){
                        sudah_update = "<i class='fa fa-check' style='color:green;'></i> Di Setujui";
                }

                var isi_berita = '<div class="col-lg-4 col-md-6" style="float:left; margin-top:30px; height:800px; overflow:auto;"><div class="card"><img class="card-img-top img-fluid" src="'+element.urlfoto+'" alt="Card image cap"><div class="card-body"><h4 class="card-title">'+element.judul_berita+'</h4><h5 class="card-title" style="text-align:right;"><small>'+element.tanggal+'</small></small></h5><p class="card-text">'+element.isi_berita+'</p><br clear="all" /><h4 class="card-title" style="text-align:left;">'+element.kode_wartawan+'<br clear="all" /> <p></p></h4> <div style="float:left;">'+sudah_update+'</h4></div></div></div>';

                $("#div_container_isi").append(isi_berita);

                });

              }
            });

        }

        function ambil_beritaparam(id,cari,is_update){

            //alert(is_update+" & "+no_update);

            $("#div_container_isi").html("");
            active_id = id;


            $.ajax({
                type:"get",
                url:"<?php echo url('ambil_listberita_kategori'); ?>",
                data:"id="+id+"&cari="+cari+"&status_update="+is_update,
                dataType:"json",
                success:function(data){
                //console.log("databerita" , data);
                $("#ditemukan_berita").html("");

                if(cari != ""){

                if(data.length > 0){
                    $("#ditemukan_berita").html("Ditemukan <b>"+data.length+"</b> judul berita dengan kata kunci '<b>"+cari+"</b>'");
                }
                else{
                    $("#ditemukan_berita").html("Tidak ditemukan judul berita dengan kata kunci '<b>"+cari+"</b>'");
                }

                }
                

                $.each(data,function(index , element){
                    
                    var sudah_update = '<a href="javascript:void(0)" onclick="open_detail('+element.id_berita+')" class="btn btn-warning"><i class="fas fa-bullseye"></i> View </a> </div><div style="float:right;"><a href="javascript:void(0)" class="btn btn-success" onclick="editModal('+element.id_berita+')"><i class="fas fa-pencil-alt"></i> Edit</a> &nbsp; <a href="javascript:void(0)" class="btn btn-primary"> <i class="fas fa-trash"></i> Hapus</a></div>';

                    if(element.sudah_update == "1"){
                        sudah_update = "<i class='fa fa-check' style='color:green;'></i> Berita ini sudah diupdate oleh editor";
                    }

                    if(element.approved == "1"){
                        sudah_update = "<i class='fa fa-check' style='color:green;'></i> Di Setujui";
                    }

                    var isi_berita = '<div class="col-lg-4 col-md-6" style="float:left; margin-top:30px; height:800px; overflow:auto;"><div class="card"><img class="card-img-top img-fluid" src="'+element.urlfoto+'" alt="Card image cap"><div class="card-body"><h4 class="card-title">'+element.judul_berita+'</h4><h5 class="card-title" style="text-align:right;"><small>'+element.tanggal+'</small></small></h5><p class="card-text">'+element.isi_berita+'</p><br clear="all" /><h4 class="card-title" style="text-align:left;">'+element.kode_wartawan+'<br clear="all" /> <p></p></h4> <div style="float:left;">'+sudah_update+'</div></div></div>';

                    $("#div_container_isi").append(isi_berita);

                });

              }
            });

        }

        function append_kategori(){

            $("#kategoriinputan").html("");

            $.ajax({
                type:"get",
                url:"<?php echo url('ambil_listkategori_awal'); ?>",
                data:"",
                dataType:"json",
                success:function(data){
                //console.log("databerita" , data);
                var len = data.length;
                var a = 1;
                $.each(data,function(index , element){

                    var kategori = '<option value='+element.id_kategori_berita+'>'+element.name+'</option></li>';
                    a++;
                    $("#kategoriinputan").append(kategori);



                });

              }
            });

        }

        function openModal(){

            append_kategori();

            $("#btn_cancel").click();

            $("#view_berita_div").slideUp();
            
            $("#div_form_berita").slideDown();

            $("#div_foto_berita").hide();

            $("#div_video_berita").hide();

            CKEDITOR.instances['DSC'].setData("");

            jQuery("#t_aksi_pencarian").val("");

            $("#t_idberita").val("");


        }

        function editModal(id){

            append_kategori();

            if(level_user == "3"){
                $("#btn_submit_approve").html("Submit & Approve");
            }
            else{
                $("#btn_submit_approve").html("Submit");
            }

            $("#view_berita_div").slideUp();
            
            $("#div_form_berita").slideDown();



            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_berita'); ?>"+"/"+id,
                dataType:"json",
                data:"",
                async:false,
                success:function(data){

                    $("#view_berita_div").slideUp();
            
                    $("#div_form_berita").slideDown();

                    $("#div_foto_berita").show();

                    $("#div_video_berita").show();

                    var tgl = data.tanggal_berita;
                    var sp_tgl = tgl.split(" ");

                    jQuery("#t_idberita").val(data.id_berita);

                    jQuery("#textinputan").val(data.judul_berita);
                    jQuery("#hariinput").val(data.hari);
                    jQuery("#tanggalinput").val(sp_tgl[0]);
                    jQuery("#waktuinput").val(sp_tgl[1]);
                    jQuery("#t_aksi_pencarian").val("edit");

                    CKEDITOR.instances['DSC'].setData(data.isi_berita);

                    $("#img_foto_berita").prop("src" , "<?php echo url('public/berita/foto/'); ?>"+"/"+data.foto);

                    //jQuery("#img_berita").prop("src" , "<?php echo url('public/berita/foto/'); ?>"+"/"+data.foto);

                    

                    //jQuery("#editdataModal").modal('show');
                }
            });



        }

        function openView(){

            $("#view_berita_div").slideDown();
            
            $("#div_form_berita").slideUp();

        }

        function openViewdetail(){

            $("#view_berita_div").slideDown();
            
            $("#div_detail_berita").slideUp();

        }

        function deletedata(value){
            var conn = confirm("Hapus data ?");

            if(conn == true){
                jQuery.ajax({
                    type:"GET",
                    url:"<?php echo url('hapuskategori/'); ?>",
                    data:"id="+value,
                    success:function(data){
                        table.ajax.reload();
                    }
                })
            }
        }

        function editdataModal(value){
            
            jQuery.ajax({
                type:"GET",
                url:"<?php echo url('ambil_kategori'); ?>"+"/"+value,
                dataType:"json",
                data:"",
                success:function(data){

                    jQuery("#iduserinput_edit").val(data.id_kategori_berita);
                    jQuery("#textinput_edit").val(data.nama_kategori_berita);

                    jQuery("#editdataModal").modal('show');
                }
            });

            
        }

        function submit_edit_form(){
            var serial = jQuery("#form_multi_edit").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('updatekategori'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#editdataModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

        function tambahdata(){

            var file_data =  $('#uploadinput').prop('files')[0]; 
            //var file_video = $('#videoinput').prop('files')[0]; 

            var form_data = new FormData();          

            form_data.append('uploadinput', file_data);
            // form_data.append('videoinput', file_video);
            form_data.append('hari', $("#hariinput").val());
            form_data.append('tanggal', $("#tanggalinput").val());
            form_data.append('waktu', $("#waktuinput").val());
            form_data.append('judul', $("#textinputan").val());
            form_data.append('kategori', $("#kategoriinputan").val());
            form_data.append('DSC', CKEDITOR.instances['DSC'].getData());
            form_data.append('_token', "<?php echo csrf_token(); ?>");
            form_data.append('t_idberita', $("#t_idberita").val());

            var url = "<?php echo url('tambahberita'); ?>";

            if($('#t_aksi_pencarian').val() == "edit"){
                url = "<?php echo url('updateberita'); ?>";
            }

            //alert(form_data);                             
            $.ajax({
                url: url, // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                   // alert(php_script_response); // display response from the PHP script, if any
                    ambil_berita(response);

                    openView();
                }
             });


        }


        function submit_form(){
            var serial = jQuery("#form_multi").serialize();

            jQuery.ajax({
                type:"POST",
                url:"<?php echo url('post_kategori'); ?>",
                data:serial,
                success:function(data){
                        jQuery("#largeModal").modal('hide');
                        table.ajax.reload();
                }
            });

        }

    </script>
@stop