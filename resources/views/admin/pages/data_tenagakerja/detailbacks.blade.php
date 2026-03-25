@extends('index')

@section('isi_menu')

<div class="container-fluid">
    <!-- *************************************************************** -->
    <!-- Start First Cards -->
    <!-- *************************************************************** -->

<div id="div_detail_berita" style="display:none;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="judul_berita"> Data Detail Berita</h4>
                        <hr />

                        <p>&nbsp;</p>

                        <center>
                            <div class="col-md-6">
                                <img id="img_berita" class="img-fluid" />
                            </div>
                        </center>

                        <p>&nbsp;</p>

                        <!-- <div class="col-md-12" id="isi_berita">
                            
                        </div> -->

                        </div>
                    </div>
                </div>
        </div>
</div>


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    
    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="largeModalLabel">Detail Pembayaran Punia</h4>
                    
        
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5> Pembayaran Bulan : <b><span id="sp_bulan_bayar_detail"></span> <span id="sp_tahun_detail"><?php echo date("Y"); ?></span> </b></h5>
                    
                    <p>&nbsp;</p>
                    
                    <div class="row">
                        <div class="col-md-5" style="position:relative;">
                            <img src="" class="img-fluid" id="img_pembayaran" />
                            <div style="position:absolute; padding:15px; background:rgba(50,50,50,0.5); color:#FFFFFF; top:42%; left:42%;"><i class="fa fa-search"></i></div>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-4"><b>Tgl Pembayaran</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7"><span id="sp_tgl_pembayaran"></span></div>
                            </div>
                            
                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-4"><b>Jml Punia</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-7">
                                    <span id="sp_jml_pembayaran"></span>
                                </div>
                            </div>
                            
                            
                            <div class="row" style="margin-top:15px;">
                                    <div class="col-md-4"><b>Extra Charge</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-7"><span id="sp_extra_pembayaran"></span></div>
                            </div>
                            
                            <div class="row" style="margin-top:15px;">
                                    <div class="col-md-4"><b>Metode Bayar</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-7"><span id="sp_punia_pembayaran"></span></div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    
                </div>
                
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
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
                    <strong class="card-title">Data Tambah Usaha</strong>
                </div>
                <div class="card-body">

                <div class="col-lg-12">

                <form method="post"  id="frm_berita" name="frm_berita" action="<?php echo url("administrator/update_post_add_usaha"); ?>">
                    
                    <input type="hidden" name="_method"  id="_method" value="put" />
                    
                    <ul class="nav nav-tabs mb-3" id="div_tabs">
                        <li class="nav-item" onclick="#"><a href="#detail_usaha" data-toggle="tab" aria-expanded="false" class="nav-link "><i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i><span class="d-none d-lg-block">Detail Usaha</span></a>
                        </li>
                        <li class="nav-item" onclick="#"><a href="#pngg_jawab" data-toggle="tab" aria-expanded="false" class="nav-link "><i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i><span class="d-none d-lg-block">Penanggung Jawab</span></a>
                        </li>
                    </ul>

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
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Judul Usaha</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="textinputan" name="textinputan" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Judul Berita</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Tanggal Usaha</label></div>

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

                                    <small class="form-text text-muted">Masukkan Hari Usaha</small></div>

                                    <div class="col-4 col-md-3"><input type="date" id="tanggalinput" name="tanggalinput" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Tanggal Berita</small></div>

                                    <div class="col-4 col-md-2"><input type="time" id="waktuinput" name="waktuinput" required="required" placeholder="" class="form-control"><small class="form-text text-muted">Masukkan Waktu Berita</small></div>


                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Kategori Usaha</label></div>
                                    <div class="col-12 col-md-9">

                                    <select type="text" id="kategoriinputan" name="kategoriinputan" required="required" placeholder="" class="form-control">
                                        <option value=""> -- Kategori --</option>
                                    </select>

                                    <small class="form-text text-muted">Masukkan Kategori Usaha</small></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Isi Usaha</label></div>
                                    <div class="col-12 col-md-9">
                                    <!--<div id="toolbar-container"></div>

                                <!-- This container will become the editable. -->
                                <!--<div id="editor" style="border:1px solid #999999; min-height:400px;">
                                    <p>Input Berita Di sini.</p>
                                </div>-->
                                <textarea name="DSC" class="materialize-textarea"></textarea>
                                <small class="form-text text-muted">Masukkan Isi Usaha</small></div>
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
                <button type="submit" class="btn btn-primary" id="btn_submit_approve">
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


<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <form id="form_maintenance_usaha" name="form_maintenance_usaha" enctype="multipart/form-data" method="post" action="<?php echo url("administrator/submit_post_add_tenagakerja"); ?>">
    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Form Maintenance Tenaga Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Form</strong> Input Tenaga Kerja
                        </div>
                        
                        
        <div class="card-body card-block">
                            
                        <!--<ul class="nav nav-tabs mb-3" id="div_tabs">-->
                        <!--    <li class="nav-item" onclick="#"><a href="#detail_usaha" data-toggle="tab" aria-expanded="false" class="nav-link active"><i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i><span class="d-none d-lg-block">Detail Usaha</span></a></li>-->
                        <!--    <li class="nav-item" onclick="#">-->
                        <!--    <a href="#pngg_jawab" data-toggle="tab" aria-expanded="false" class="nav-link "><i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i><span class="d-none d-lg-block">Penanggung Jawab</span></a>-->
                        <!--    </li>-->
                        <!--</ul>-->
                        
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Detail Karyawan</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Data Skill</a>
                    
                  </div>
                </nav>
                
                <p>&nbsp;</p>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                        <div id="detail_usaha">

                       
                                {{ csrf_field() }}

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Karyawan :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_title_new" name="text_title_new" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Email Karyawan :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_email_usaha_new" name="text_email_usaha_new" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">No. WA:</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_telpkantor_new" name="text_telpkantor_new" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Umur :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_notelp_was" name="text_notelp_was" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>


                                <div class="row form-group">

                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Alamat  :</label></div>

                                        <div class="col-12 col-md-9">
                                            <textarea class="col-12 col-md-12" name="t_alamat_usaha" class="form-control"></textarea>
                                            <small class="form-text text-muted">Masukkan alamat </small>
                                        </div>
                                
                                </div>
                                
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Jenis Kelamin :</label></div>
                                    <div class="col-12 col-md-9"><input type="radio" id="rdb_jk_karyawan" name="rdb_jk_karyawan" checked="checked" value="1" /> &nbsp; Laki - Laki &nbsp; <input type="radio" id="rdb_jk_karyawan" name="rdb_jk_karyawan"  value="0" /> &nbsp; Perempuan</div>
                                </div>

                                <div class="row form-group"  style="margin-top:30px;">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto Profile :</label></div>
                                    <div class="col-12 col-md-9"><input type="file" id="f_upload_gambar_mobile" name="f_upload_gambar_mobile" required="required" class="form-control"><small class="form-text text-muted">Masukkan Foto Profile</small></div>
                                </div>

                                <div class="row form-group"  style="margin-top:30px;">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Foto Ijazah :</label></div>
                                    <div class="col-12 col-md-9"><input type="file" id="f_foto_ijasah" name="f_foto_ijasah" required="required" class="form-control"><small class="form-text text-muted">Masukkan Foto Ijazah</small></div>
                                </div>

                                </div>
                                
                      
                    </div>
                  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                      
                      
                        <div id="pngg_jawab">
                            <div class="row">

                            <?php
                            foreach($tenaga_kerja as $rows_tek){
                            ?>
                                <div class="col-md-6" style="padding:10px; box-sizing:border-box;"> 
                                    <input type="checkbox" name="chk_tenaga_kerja[]" id="chk_tenaga_kerja[]" value="<?php echo $rows_tek->id_skill_tenaga_kerja; ?>" style="transform: scale(1.5);" /> &nbsp; &nbsp; <?php echo $rows_tek->nama_skill; ?>
                                </div>
                            <?php
                            }
                            ?>
                                

                            </div>
                        </div>
                            
                  </div>
                  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
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

            </div>
        </div>
    </form>

</div>


<div class="row" id="view_berita_div">
 <div class="col-12">
   <div class="card">
    <div class="card-body">
            
        <h4 class="card-title">Data Karyawan</h4>

        <hr />

        <?php
        // if(Session::get('level') == "2"){
        ?>

            <button onclick="openModal_Edit();" style="float:right;" type="button"
                class="btn waves-effect waves-light btn-primary"><i class="fas fa-edit"></i> Edit Data Usaha
            </button>
            
            <br clea"all" />

        <?php
        //  }
        ?>

        <p></p>
            
    <div>

    <div class="row form-group">

        <div class="col col-md-3" style="margin-top:30px;"></div>
        <br clear="all" />
        <div class="col col-md-3" style="margin-top:10px;"></div>


    <div class="col-12 col-md-12" style="margin-top:20px;">
        
        <div class="row">
            <div class="col col-md-4">
                <div class="col-md-12" style="position:relative;">
                    <input type="file" name="f_upload" id="f_upload" style="display:none;" onchange="upload_gambar('<?php echo $rows->id_tenaga_kerja; ?>');" />
                <div id="logo_usaha_loader" style="position:absolute; top:-25px; left:0; background:rgba(50,50,50,0.8); width:100%; height:110%; z-index:999999; display:none;">
                    <center>
                        <div class="lds-ring"  style="margin-top:30%;"><div></div><div></div><div></div><div></div></div>
                    </center>
                </div>
                <div style="position:absolute; top:-20px; padding:0 10px; background:rgba(50,50,50,0.7); color:#FFFFFF; border-radius:5px; right:10px; font-size:21px; cursor:pointer;" onclick="$('#f_upload').click();">
                    <i class="fas fa-camera"></i>
                </div>
                <a id="single_image" href="<?php echo url('public/usaha/icon/'.$rows->logo); ?>"><img src="<?php echo url('public/karyawan/'.$rows->foto_profile); ?>" id="logo_img_icon" class="img-fluid" /></a> <p></p>
                </div>
                <div class="col-md-12">
                <center>
                    <h2 style="font-size:21px;"><b><span id="div_usaha_nama"><?php echo $rows->nama; ?></span></b></h2>
                    <h4 style="font-size:14px;"><?php echo $rows->email_karyawan; ?></h4>
                    <h4 style="font-size:14px;"><?php echo $rows->no_wa; ?></h4>
                </center>
                </div>
            </div>
            
            <div class="col col-md-8">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Contacs ...</div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
            </div>
                
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home-tab" role="tab" aria-controls="nav-home" aria-selected="false">Detail Karyawan</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Skill</a>
                    <a class="nav-item nav-link" id="nav-punia-tab" data-toggle="tab" href="#nav-punia" role="tab" aria-controls="nav-punia" aria-selected="false">Status Kerja</a>
                    
                  </div>
                </nav>
                
                <p>&nbsp;</p>
                <div class="tab-content" id="nav-tabContents" style="border:1px solid #DDDDDD;">
                    
                    <div class="tab-pane fade show active" id="nav-home-tab" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                        <div id="detail_usaha" style="padding:20px;">

                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Alamat</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->alamat; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Jenis kelamin</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo Config::get('myconfig.jk_tenaga')[$rows->jenis_kelamin]; //$jk_tenaga($rows->jenis_kelamin); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Umur</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->umur; ?> Tahun
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Status</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo Config::get('myconfig.status_tenaga')[$rows->status]; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top:40px;">
                                    <div class="row">
                                        Download Ijazah & CV :  &nbsp;  &nbsp; 
                                        <button class="btn btn-success"> &nbsp; <i class="fa fa-download"></i> Download CV & Ijazah</button>
                                    </div>
                                </div>
                            </div>
                        
                            
                        </div>
                            
                    <div class="tab-pane fade" id="nav-punia" role="tabpanel" aria-labelledby="nav-punia-tab">
                        
                        <div id="datas_usaha" style="padding:20px 20px 40px 20px;">
                            
                            Berikut adalah beberapa daftar / List Skill yang dimiliki calon pekerja : 
                            
                            
                        </div>
                        
                    </div>
                    
                    <div class="tab-pane fade" id="nav-punia" role="tabpanel" aria-labelledby="nav-punia-tab">
                        
                        Berikut adalah beberapa daftar / List Skill yang dimiliki calon pekerja : 
                            

                    </div>
                    
                    
                    </div>
                    
                </div>
                
            </div>
        </div>
            
    </div>

    </div>

    <div id="div_container_isi">
                    

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
    
    $('#ikantable').DataTable();
    
    function upload_gambar(index){
        
        if($('#f_upload')[0].files[0] != ""){
            var data = new FormData();
            data.append('file', $('#f_upload')[0].files[0]);
            data.append("_token" , "<?php echo csrf_token(); ?>");
            
            $("#logo_usaha_loader").show();
            
            $.ajax({
                  url: url_menu_apis+"/"+"upload_gambar_usaha/"+index,
                  type: "POST",
                  data: data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,
                  success:function(data){
                      
                      $("#logo_img_icon").prop("src" , data);
                      $("#logo_usaha_loader").fadeOut("slow");
                      
                      $('#f_upload').val("");
                      
                  }// tell jQuery not to set contentType
            });
        }
    }
    
    function bayar_tagihan(index,bulan){
        
        $('#largeModal').modal('show');
        
        var parseBulan = parseInt(index);
        
        var format_bulan = index;
        
        if(parseBulan <= 9){
            format_bulan = "0"+index;
        }
        
        var today = new Date(); 
        var dd = String(today.getDate());
        
        var dateNow = $("#pilih_tahun_subs").val() + "-" + format_bulan + "-" + dd;
        
        $("#tanggal_bukti_pembayaran").val(dateNow);
        
        $("#sp_bulan_bayar").html(bulan);
        $("#t_bulan_pembayaran").val(index);
        $("#sp_usaha_new").html($("#div_usaha_nama").html());
       // $("#sp_pembayaran_new").html("Rp. 15.000,-");
        
    }
    
    function open_detail_pembayaran(index){
        
        $.ajax({
            type:'GET',
            data:"id="+index,
            url:url_menu_apis+"/"+"get_pembayaran_detail/"+index,
            dataType:"json",
            success:function(data){
                $("#detailModal").modal("show");
                
                var bulan = ["bulan","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                
                $("#sp_bulan_bayar_detail").html(bulan[data[0].bulan]);
                $("#sp_tahun_detail").html(data[0].tahun);
                
                $("#img_pembayaran").prop("src",url_menu_asset+"/"+"bukti_pembayaran/"+data[0].bukti_pembayaran);
                
                $("#sp_tgl_pembayaran").html(data[0].tanggal_pembayaran);
                $("#sp_jml_pembayaran").html("Rp. "+data[0].jumlah_dana);
                $("#sp_extra_pembayaran").html("Rp. "+data[0].charge);
                $("#sp_punia_pembayaran").html(data[0].metode);
                
            }
        })
        
    }

    function submit_pembayaran_form(form){

        var formData = new FormData(document.getElementById('form_maintenance_pembayaran'));
        formData.append("urutan_id" , active_id);

        //console.log("formdata" , formData);

        //console.log("submits" , );
        $.ajax({
            data:formData,
            url:url_menu_apis+"/"+"post_pembayaran_baru/"+"manual",
            type:"post",
            processData: false,
            contentType: false,
            dataType:"json",
            cache: false,
            success:function(data){
                //$("#register_user_new").val();
                var scc = '<div class="col-md-12" style="text-align:right; margin-top:-15px;">'+
                    '<b>Sudah Bayar</b> <br />'+ data[0].tanggal_pembayaran +
                    '<br />'+data[0].metode+'</div>'
                //if(data == "success"){
                    $("#largeModal").modal("hide");
                   // $("#btn_reset").click();
                   $("#div_status_pembayaran_"+data[0].bulan).html(scc);
                   $("#checks_data_berhasil_"+data[0].bulan).html("<i class='fa fa-check' style='color:green;'></i>")
                //}
            }
        });

        return false;

    }

    function submit_edit_gambar_form(form){

        var formData = new FormData(document.getElementById('form_multi_edit'));
        formData.append("urutan_id" , active_id);

        //console.log("formdata" , formData);

        //console.log("submits" , );
        $.ajax({
            data:formData,
            url:url_menu_apis+"/"+"post_gambar_baru_edit",
            type:"post",
            processData: false,
            contentType: false,
            cache: false,
            success:function(data){
                //$("#register_user_new").val();
                if(data == "success"){
                    $("#largeModal").modal("hide");
                    $("#btn_reset").click();
                }
            }
        });

        return false;

        }

    jQuery(document).ready(function() {


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

            //$("#div_tabs").html("");

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


            //jQuery('.modal-dialog').draggable();


        } );

        function open_detail(id){

            jQuery.ajax({
                type:"GET",
                url:url_menu_apis+"/"+"ambil_listslides",
                dataType:"json",
                data:"posisi="+id,
                success:function(data){

                   // $("#view_berita_div").slideUp();
            
                    //$("#div_detail_berita").slideDown();

                    //jQuery("#judul_berita").html('<a onclick="openViewdetail()" style="cursor:pointer;"> <i class="fas fa-undo"></i> </a> &nbsp;'+" "+data.judul_berita);
                    //jQuery("#isi_berita").html(data.isi_berita);

                    jQuery("#img_berita").prop("src" , "<?php echo url('public/GambarSlides/'); ?>"+"/"+data.image_name);

                    

                    //jQuery("#editdataModal").modal('show');
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
            $("#largeModal").modal('show');
        }

        function openModal_Edit(){
            
            $("#largeModal_edit").modal('show');
            
        }
        
        function editedModal(index){
            $("#editedModal").modal('show');

            $.ajax({
                type:"GET",
                data:"id="+index,
                url:url_menu_apis+"/get_gambar_slide",
                dataType:"json",
                success:function(data){
                    //alert(data.alt);
                    $("#edit_text_title_new").val(data.title);
                    $("#edit_text_desc_new").val(data.alt);

                    $("#edit_hidden_textfield").val(data.id_gambar_home);
                    
                    $("#edit_desktop_image").prop("src",url_menu_asset+"/GambarSlides/"+data.image_name);

                    $("#edit_mobile_image").prop("src",url_menu_asset+"/GambarSlides/"+data.image_name_mobile);
                    
                   
                }
            });
            
             $("#editedModal").modal('hide');

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

            //var file_data =  $('#uploadinput').prop('files')[0]; 
            //var file_video = $('#videoinput').prop('files')[0]; 

            var form_data = new FormData(); 

            console.log("formdata" , form_data);        

            return false; 

            // form_data.append('uploadinput', file_data);
            // // form_data.append('videoinput', file_video);
            // form_data.append('hari', $("#hariinput").val());
            // form_data.append('tanggal', $("#tanggalinput").val());
            // form_data.append('waktu', $("#waktuinput").val());
            // form_data.append('judul', $("#textinputan").val());
            // form_data.append('kategori', $("#kategoriinputan").val());
            // form_data.append('DSC', CKEDITOR.instances['DSC'].getData());
            // form_data.append('_token', "<?php //echo csrf_token(); ?>");
            // form_data.append('t_idberita', $("#t_idberita").val());

            // var url = "<?php //echo url('tambahberita'); ?>";

            // if($('#t_aksi_pencarian').val() == "edit"){
            //     url = "<?php //echo url('updateberita'); ?>";
            // }

            // //alert(form_data);                             
            // $.ajax({
            //     url: url, // point to server-side PHP script 
            //     dataType: 'text',  // what to expect back from the PHP script, if anything
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     data: form_data,                         
            //     type: 'post',
            //     success: function(response){
            //        // alert(php_script_response); // display response from the PHP script, if any
            //         ambil_berita(response);

            //         openView();
            //     }
            //  });


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