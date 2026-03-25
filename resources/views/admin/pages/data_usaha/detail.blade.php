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
                        <h4 class="card-title" id="judul_berita"> Data Detail Usaha</h4>
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

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    
    <form id="form_maintenance_pembayaran" name="form_maintenance_pembayaran" enctype="multipart/form-data" method="post" onSubmit="submit_pembayaran_form(this); return false;">
        
        {{ csrf_field() }}
        
       
    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="largeModalLabel">Form Bayar Tagihan</h4>
                    
                     <input type="hidden" name="t_hidden_usaha" id="t_hidden_usaha" value="<?php echo $rows->id_usaha; ?>" />
        
                     <input type="hidden" name="t_bulan_pembayaran" id="t_bulan_pembayaran" />
        
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5> Pembayaran Bulan : <b><span id="sp_bulan_bayar"></span> <?php echo date("Y"); ?></b></h5>
                    
                    <p>&nbsp;</p>
                    <div class="col-md-12" style="margin-top:20px;">
                        <div class="row">
                            <div class="col-md-3"><b>Nama Usaha</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8"><span id="sp_usaha_new"></span></div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-3"><b>Minimal Punia</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8">
                                <span id="sp_pembayaran_new">
                                    Rp. <?php echo $rows->minimal_bayar; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-3"><b>Input Pembayaran</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8">
                                <input type="number" value="<?php echo $rows->minimal_bayar; ?>" min="<?php echo $rows->minimal_bayar; ?>" name="teks_input_pembayarans" />
                             </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-3"><b>Jenis Pembayaran</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8">Manual Transfer</div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-3"><b>Tanggal Pembayaran</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8"><input type="date" name="tanggal_bukti_pembayaran" id="tanggal_bukti_pembayaran" value="<?php echo date('Y-m-d'); ?>" /></div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="row">
                            <div class="col-md-3"><b>Upload Bukti Pembayaran</b></div>
                            <div class="col-md-1">:</div>
                            <div class="col-md-8"><input type="file" name="f_bukti_pembayaran" id="f_bukti_pembayaran" /> <br /> <small><i>format file *jpg , png</i></small></div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            
            </div>
            
            
            
        </div>
        
        
    </form>
        
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


<div class="modal fade" id="largeModal_edit" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <form id="form_maintenance_usaha" name="form_maintenance_usaha" enctype="multipart/form-data" method="post" action="<?php echo url("administrator/update_post_add_usaha"); ?>">
        
        @method('PUT')
    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Form Maintenance Usaha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Form</strong> Input Usaha
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
                      
                    <a class="nav-item nav-link active" id="input-nav-home-tab" data-toggle="tab" href="#input-nav-home" role="tab" aria-controls="input-nav-home" aria-selected="true">Detail Usaha</a>
                    <a class="nav-item nav-link" id="input-nav-profile-tab" data-toggle="tab" href="#input-nav-profile" role="tab" aria-controls="input-nav-profile" aria-selected="false">Penanggung Jawab</a>
                    <a class="nav-item nav-link" id="nav-credentials-tab" data-toggle="tab" href="#nav-credentials" role="tab" aria-controls="nav-credentials-tab" aria-selected="false">Credentials</a>
                    
                  </div>
                </nav>
                
                <p>&nbsp;</p>
                <div class="tab-content" id="nav-tabContent">
                    
                    <div class="tab-pane fade" id="nav-credentials" role="tabpanel" aria-labelledby="nav-credentials-tab">
                    
                        <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Username :</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="text_username_new" name="text_username_new" readonly="readonly" class="form-control" value="<?php echo $rows->username; ?>" />
                                    <small class="form-text text-muted">Username Login Investor ( email investor )</small>
                                </div>
                        </div>

                        <div class="row form-group">
                            <div class="col col-md-3"><label for="text-input" class=" form-control-label">New Password :</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="password" id="text_password_new" name="text_password_new" required="required" class="form-control">
                                    <small class="form-text text-muted">Masukkan Password Investor Baru</small>
                                </div>
                        </div>

                    </div>

                    <div class="tab-pane fade show active" id="input-nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                        <div id="detail_usaha">

                       
                                {{ csrf_field() }}
                                <input type="hidden" name="tb_hidden_usaha" id="tb_hidden_usaha" value="<?php echo $rows->id_usaha; ?>" />
                                
                                <input type="hidden" name="tb_hidden_detail_usaha" id="tb_hidden_detail_usaha" value="<?php echo $rows->id_detail_usaha; ?>" />
                                
                                <input type="hidden" name="tb_hidden_pngg_usaha" id="tb_hidden_pngg_usaha" value="<?php echo $rows->id_penanggung_jawab; ?>" />

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Usaha :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_title_new" name="text_title_new" value="<?php echo $rows->nama_usaha; ?>" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                
                                <div class="row form-group">
                                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Kategori Usaha</label></div>
                                                <div class="col-12 col-md-9">
                                        
                                        <select id="cmb_kategori_usaha" name="cmb_kategori_usaha" required="required" class="form-control">
                                                <option value="">- Pilih Kategori Usaha -</option>
                                        <?php
                                        foreach($kategori as $rows_cat){
                                        $selected = "";
                                            
                                        if($rows->id_jenis_usaha == $rows_cat->id_kategori_usaha){
                                        $selected = 'selected    ="selected"';
                                        }
                                            ?>
                                            <option <?php echo $selected; ?> value="<?php echo $rows_cat->id_kategori_usaha; ?>"><?php echo $rows_cat->nama_kategori_usaha; ?></option>
                                            <?php
                                        }
                                        ?>
                                        </select>
                                                
                                                <small class="form-text text-muted">Pilih Kategori Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Minimal Pembayaran :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_minimal_pembayaran" name="text_minimal_pembayaran" value="<?php echo $rows->minimal_bayar; ?>" required="required" class="form-control"><small class="form-text text-muted">Masukkan Inputan Minimal Pembayaran</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Email Usaha :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_email_usaha_new" name="text_email_usaha_new" value="<?php echo $rows->email_usaha; ?>" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">No.Telp Kantor Usaha :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_telpkantor_new" name="text_telpkantor_new" value="<?php echo $rows->no_telp; ?>" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">No.Telp WA :</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="text_notelp_was" name="text_notelp_was" value="<?php echo $rows->no_wa; ?>" required="required" class="form-control"><small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>

                                <!--<div class="row form-group">-->
                                <!--    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Banjar :</label></div>-->
                                <!--    <div class="col-12 col-md-9"><input type="text" id="text_desc_new" name="text_desc_new" required="required"  class="form-control"><small class="form-text text-muted">Masukkan Nama Banjar</small></div>-->
                                <!--</div>-->
                                
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama Banjar :</label></div>
                                    <div class="col-12 col-md-9">
                                        
                                        <select class="form-control" id="text_desc_new" name="text_desc_new" required="required">
                                            <option value="">- Pilih Banjar -</option>
                                            <?php
                                                foreach($banjar as $rows_banjar){
                                        $selected = "";
                                            
                                        if($rows->id_banjar == $rows_banjar->id_data_banjar){
                                        $selected = 'selected    ="selected"';
                                        }
                                                    ?>
                                        <option <?php echo $selected; ?> value="<?php echo $rows_banjar->id_data_banjar; ?>"><?php echo $rows_banjar->nama_banjar; ?></option>
                                                    <?php
                                                }
                                                ?>
                                        </select>
                                        <small class="form-text text-muted">Masukkan Nama Banjar</small></div>
                                </div>

                                <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Alamat Usaha :</label></div>
                                <div class="col-12 col-md-9">
                                    <textarea class="col-12 col-md-12" name="t_alamat_usaha" class="form-control"><?php echo $rows->alamat_banjar; ?></textarea>
                                    <small class="form-text text-muted">Masukkan Nama Usaha</small></div>
                                </div>
                                
                                <div class="row form-group">
                                    
                                <div class="col col-md-12" style="margin-top:30px;">
                                    
                                    <div class="row">
                                    
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Facebook Url :</label>
                                        </div>
                                            
                                        <div class="col-12 col-md-9">
                                            <input type="text" name="cmb_social_facebook" id="cmb_social_facebook" class="form-control" value="<?php echo $rows->facebook_url; ?>" />
                                        </div>
                                    
                                    </div>
                                
                                </div>
                                
                                <div class="col col-md-12" style="margin-top:30px;">
                                    
                                    <div class="row">
                                
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Twitter Url :</label>
                                        </div>
                                            
                                        <div class="col-12 col-md-9">
                                            <input type="text" name="cmb_social_twitter" id="cmb_social_twitter" class="form-control" value="<?php echo $rows->twitter_url; ?>" />
                                        </div>
                                    
                                    </div>
                                
                                </div>
                                
                                <div class="col col-md-12" style="margin-top:30px;">
                                    
                                    <div class="row">
                                
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Website Url :</label>
                                        </div>
                                            
                                        <div class="col-12 col-md-9">
                                            <input type="text" name="cmb_social_website" id="cmb_social_website" class="form-control" value="<?php echo $rows->website_url; ?>" />
                                        </div>
                                    
                                    </div>
                                
                                </div>
                                    
                                </div>
                                
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Google Maps :</label></div>
                                    <div class="col-12 col-md-9"><textarea id="text_google_maps" name="text_google_maps" required="required" class="form-control">
                                        <?php echo trim($rows->google_maps); ?>
                                        </textarea>
                                        <small class="form-text text-muted">Masukkan Embed Link Google Maps</small></div>
                                </div>

                                
                                
                                </div>
                                
                      
                    </div>
                  <div class="tab-pane fade" id="input-nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                      
                      
                        <div id="pngg_jawab">
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Nama  :</label></div>
                                
                                <div class="col-12 col-md-9">
                                <input type="text" id="text_namapngg_new" name="text_namapngg_new" required="required" class="form-control" value="<?php echo $rows->nama; ?>"><small class="form-text text-muted">Masukkan Nama Penanggung Jawab</small></div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Status  :</label></div>
                                
                                <div class="col-12 col-md-9">
                                <input type="text" id="text_statuspngg_new" name="text_statuspngg_new" required="required" class="form-control" value="<?php echo $rows->status_penanggung_jawab; ?>" /><small class="form-text text-muted">Masukkan Status Penanggung Jawab</small></div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Alamat  :</label></div>
                                
                                <div class="col-12 col-md-9">
                                <input type="text" id="text_alamat_pngg_new" name="text_alamat_pngg_new" required="required" class="form-control" value="<?php echo $rows->alamat_usaha; ?>" /><small class="form-text text-muted">Masukkan Alamat Penanggung Jawab</small></div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Email  :</label></div>
                                
                                <div class="col-12 col-md-9">
                                <input type="text" id="text_email_usaha_new" name="text_email_pngg_new" required="required" class="form-control" value="<?php echo $rows->email; ?>"><small class="form-text text-muted">Masukkan Email </small></div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">No.Telp  :</label></div>
                                
                                <div class="col-12 col-md-9">
                                <input type="text" id="text_notelp_pngg_new" name="text_notelp_pngg_new" required="required" class="form-control" value="<?php echo $rows->no_wa_pngg; ?>" /><small class="form-text text-muted">Masukkan No.Telp Penanggung Jawab</small></div>
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
             
             <h4 class="card-title">Data Usaha</h4>

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

                    <!-- <div class="col-9 col-md-9" style="margin-top:20px;">
                      <input type="submit"  id="btn_submit_cari" name="btn_submit_cari" value="Cari Data" class="btn-primary" /> 
                    </div> -->

    <div class="col-12 col-md-12" style="margin-top:20px;">
        
        <div class="row">
            <div class="col col-md-4">
                <div class="col-md-12" style="position:relative;">
                    <input type="file" name="f_upload" id="f_upload" style="display:none;" onchange="upload_gambar('<?php echo $rows->id_detail_usaha; ?>');" />
                <div id="logo_usaha_loader" style="position:absolute; top:-25px; left:0; background:rgba(50,50,50,0.8); width:100%; height:110%; z-index:999999; display:none;">
                    <center>
                        <div class="lds-ring"  style="margin-top:30%;"><div></div><div></div><div></div><div></div></div>
                    </center>
                </div>
                <div style="position:absolute; top:-20px; padding:0 10px; background:rgba(50,50,50,0.7); color:#FFFFFF; border-radius:5px; right:10px; font-size:21px; cursor:pointer;" onclick="$('#f_upload').click();">
                    <i class="fas fa-camera"></i>
                </div>
                <a id="single_image" href="<?php echo url('storage/usaha/icon/'.$rows->logo); ?>"><img src="<?php echo url('storage/usaha/icon/'.$rows->logo); ?>" id="logo_img_icon" class="img-fluid" /></a> <p></p>
                </div>
                <div class="col-md-12">
                <center>
                <?php
                    $no_wa = $rows->no_wa;
                    $fwa = substr($rows->no_wa , 0 , 1);

                    if($fwa == "0"){
                        $no_wa = "+62".substr($rows->no_wa , 1 , strlen($rows->no_wa)-1);
                    }

                    $ex_dt = explode(" " , $rows->created_at);
                ?>
                    <h2 style="font-size:21px;"><b><span id="div_usaha_nama" style="color:#333333;"><?php echo $rows->nama_usaha; ?></span></b></h2>
                    <h4 style="font-size:14px;"><?php echo $rows->email_usaha; ?></h4>
                    <h4 style="font-size:14px;"><i class="bi bi-whatsapp" style="font-size:21px; color:green;"></i> &nbsp; <?php echo $no_wa; ?></h4>
                    <p></p>
                    <h4 style="font-size:14px;">&nbsp; <small> Tanggal Bergabung : </small> <br /> <b> <?php echo tgl_indo($rows->tanggal_daftar); ?> </b> </h4>
                </center>
                </div>
            </div>
            
            <div class="col col-md-8">
                
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Detail</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Penanggung Jawab</a>
                    <a class="nav-item nav-link" id="nav-punia-tab" data-toggle="tab" href="#nav-punia" role="tab" aria-controls="nav-punia" aria-selected="false">Punia</a>
                    <a class="nav-item nav-link" id="nav-sumbangan-tab" data-toggle="tab" href="#nav-sumbangan-panel" role="tab" aria-controls="nav-sumbangan-tab" aria-selected="false">Sumbangan</a>
                    <a class="nav-item nav-link" id="nav-tenaga-tab" data-toggle="tab" href="#nav-tenaga-panel" role="tab" aria-controls="nav-tenaga-tab" aria-selected="false">Tenaga Kerja</a>
                    
                  </div>
                </nav>
                
                <p>&nbsp;</p>
                <div class="tab-content" id="nav-tabContent" style="border:1px solid #DDDDDD;">

                    <div class="tab-pane fade" id="nav-sumbangan-panel" role="tabpanel" aria-labelledby="nav-sumbangan-tab">
                        <div id="list_usaha" style="padding:20px;">
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                <h5 style="color:#333333;"><b> Data Sumbangan </b> </h5> <hr />
                            </div>

                            <p></p>
                            <div class="col-md-12" style="margin-top:30px;">

                                <div class="row">
                                    <div class="col-md-5">
                                        <small> Tanggal Awal : </small> <br />
                                        <input type="date" name="t_date_awal" id="t_date_awal" class="form-control" />
                                    </div>
                                    <div class="col-md-5">
                                    <small> Tanggal Akhir : </small> <br />
                                        <input type="date" name="t_date_akhir" id="t_date_akhir" class="form-control" />
                                    </div>
                                    <div class="col-md-2"  style="margin-top:23px:">
                                        <div style="visibility:hidden;"> Aksi </div>
                                        <button type="button" name="btn_search_date" id="btn_search_date" class="form-control btn-success"> <i class="fa fa-search"></i> </button>
                                    </div>
                                </div>

                            </div>

                            <p>&nbsp;</p>
                            <?php
                                foreach($sumbangan as $sumb_rows){
                                ?>
                                <div class="col-md-12" style="margin-top:20px;">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5 style="color:#333333;"><b><?php echo tgl_indo($sumb_rows->tanggal); ?></b></h5>
                                            <small> Manual Transfer / <b> <?php echo $sumb_rows->nama_bank; ?></b></small><br />
                                            <small stye="font-size:11px;"> <i class="fa fa-edit"></i> <?php echo $sumb_rows->deskripsi; ?> </small>
                                        </div>

                                        <div class="col-md-4" style="text-align:right;">
                                            <h5 style="color:#333333;"><b>Rp. <?php echo format_rupiah($sumb_rows->nominal); ?></b></h5>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                }
                            ?>
                            <p>&nbsp;</p>
                            <hr />
                            <div class="col-md-12" style="margin-top:20px; color:#222222;">
                                <div class="row">
                                    <div class="col-md-8"> <b> Total : </b> </div>
                                    <div class="col-md-4 text-right"><b> Rp. <?php echo format_rupiah($total_usaha); ?></b></div>
                                </div>
                            </div>

                        </div>       
                    </div>

                    <div class="tab-pane fade" id="nav-tenaga-panel" role="tabpanel" aria-labelledby="nav-tenaga-tab">

                        <div id="list_karyawan" style="padding:20px;">
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                <h5 style="color:#333333;"><b> Data Tenaga Kerja </b> </h5> <hr />
                            </div>

                            <p></p>
                            <div class="col-md-12" style="margin-top:30px;">

                                <div class="row">
                                    <div class="col-md-10">
                                        <small> Nama Tenaga Kerja : </small> <br />
                                        <input type="text" name="text_nama_karyawan" id="text_nama_karyawan" class="form-control" />
                                    </div>
                                    <div class="col-md-2"  style="margin-top:23px:">
                                        <div style="visibility:hidden;"> Aksi </div>
                                        <button type="button" name="btn_search_karyawan" id="btn_search_karyawan" class="form-control btn-success"> <i class="fa fa-search"></i> </button>
                                    </div>
                                </div>

                            </div>

                                <p>&nbsp;</p>
                                <?php
                                    foreach($usaha_karyawan as $rowspr){
                                    ?>
                                    <div class="col-md-12" style="margin-top:20px;">

                                        <div class="row">
                                            <div class="col-md-3 col-6">
                                            <?php
                                                    if($rowspr->foto_profile == ""){
                                                        ?>
                                                            <img src="<?php echo url('assets/noprofile.png'); ?>" class="img-fluid" style="object-fit:cover; border-radius:50%;" />
                                                        <?php
                                                    }
                                                    else{
                                                        ?>
                                                            <img src="<?php echo url('storage/karyawan/'.$rowspr->foto_profile); ?>" class="img-fluid" style="height:115px; object-fit:cover;  width:100%;  border-radius:50%;" />
                                                        <?php
                                                    }
                                                ?>
                                            </div>

                                            <div class="col-md-9 col-6">
                                                    <h5 style="color:#222222;"><b> <?php echo $rowspr->nama; ?> </b></h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-envelope"></i> <?php echo $rowspr->email_karyawan; ?> </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-whatsapp"></i> <?php echo $rowspr->no_wa; ?> </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-house"></i> <?php echo $rowspr->alamat; ?> </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-person"></i> <?php echo $rowspr->umur; ?> Tahun</small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-person"></i> <?php echo Config::get('myconfig.jk_tenaga')[$rowspr->jenis_kelamin]; ?> </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small><i class="bi bi-eye"></i> <a href="<?php echo url('administrator/detail_tenaga_kerja/'.$rowspr->id_tenaga_kerja); ?>" target="_blank"> <b>Detail</b> </a></small>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                }
                            ?>
                            <p>&nbsp;</p>
                            <hr />
                            <div class="col-md-12" style="margin-top:20px; color:#222222;">
                                <!-- <div class="row">
                                    <div class="col-md-8"> <b> Total : </b> </div>
                                    <div class="col-md-4 text-right"><b> Rp. <?php //echo format_rupiah($total_usaha); ?></b></div>
                                </div> -->
                            </div>

                        </div>   


                    </div>
                    
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                        <div id="detail_usaha" style="padding:20px;">
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Banjar</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->nama_banjar; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Alamat Banjar</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->alamat_banjar; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="row">
                                    
                                <div class="col-md-3"><b>No. Telp</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-8"><?php echo $rows->no_telp; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Alamat Usaha</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->alamat_banjar; ?>
                                    </div>
                                </div>
                            </div>
                        
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="row">
                                    <div class="col-md-3"><b>Facebook</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->facebook_url; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="row">
                                    
                                <div class="col-md-3"><b>Instagram</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-8"><?php echo $rows->twitter_url; ?>
                                </div>
                            </div>
                        </div>
                            
                        <div class="col-md-12" style="margin-top:10px;">
                                <div class="row">
                                    
                                <div class="col-md-3"><b>Website</b></div>
                                <div class="col-md-1">:</div>
                                <div class="col-md-8"><?php echo $rows->website_url; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="margin-top:40px;">
                            
                            <div style="overflow:auto;"><?php echo $rows->google_maps; ?></div>
                            
                        </div>
                            
                        </div>
                            
                        </div>
                        
                    
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        
                        <div id="datas_usaha" style="padding:20px 20px 40px 20px;">
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Nama</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->nama; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Status</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->status_penanggung_jawab; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:10px;">
                                    <div class="row">
                                        
                                    <div class="col-md-3"><b>Email</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->email; ?>
                                    </div>
                                </div>
                            </div>
                            
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
                                        
                                    <div class="col-md-3"><b>No. WA</b></div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-8"><?php echo $rows->no_wa_pngg; ?>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                    </div>
                    
                    <div class="tab-pane fade" id="nav-punia" role="tabpanel" aria-labelledby="nav-punia-tab">
                        
                        <div id="det_punia_usaha" style="padding:20px 20px 40px 20px;">
                            
                        <div class="col-md-12">
                             Pilih Tahun : <br />
                             
                            <input type="number" id="pilih_tahun_subs" class="form-control" value="<?php echo date("Y"); ?>" />
                            
                        </div>
                        
                        <p>&nbsp;</p>
                        <?php
                        $index_bln = 1;
                        $bln = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                        
                        $index_dana = 0;
                        
                        foreach($bln as $arrays){
                            $active = "";
                            
                            if($index_bln == date("m")){
                                $active = "background:#DDDDDD;";
                            }
                            
                            $sts_byr = $arr_sts_punia[($index_bln-1)];
                            
                            $checks = "";
                            
                            if($sts_byr == "y"){
                                $checks = "<i class='fa fa-check' style='color:green;'></i>";
                            }

                            $daftar = $rows->tanggal_daftar;

                            $dis = "";

                            $months = $index_bln;

                            if($index_bln <= 9){
                                $months = "0".$index_bln;
                            }

                            $nowss = date("Y")."-".$months."-31";

                            if($nowss < $daftar){
                                $dis = "disabled='disabled'";
                            }
                            
                           // print_r($arr_sts_punia);
                        ?>
                        
                            <div class="col-md-12" style="margin-top:25px;">
                                
                                 <div class="row">
                                     
                                  <div class="col-md-3">
                                      <b><?php echo $arrays." &nbsp; <span id='checks_data_berhasil_".$index_bln."'>".$checks; ?></span></b>
                                  </div>
                                  
                                  <div class="col-md-9" id="div_status_pembayaran_<?php echo $index_bln; ?>">
                                  
                                  <?php
                                  if($sts_byr == "y"){
                                      ?>
                                      <div class="col-md-12" style="text-align:right; margin-top:-15px;">
                                          <b>Sudah Bayar</b> <br />
                                          <?php echo $arr_danas[$index_dana]->tanggal_pembayaran; ?> <br />
                                          <?php echo $arr_danas[$index_dana]->metode; ?> <br />
                                          <button class="btn btn-success" onclick="open_detail_pembayaran('<?php echo $arr_danas[$index_dana]->id_dana_punia; ?>');"><i class="fa fa-eye"></i> Detail</button>
                                      </div>
                                      <?php
                                      $index_dana++;
                                      //$index_dana++;
                                  }
                                  else{
                                      ?>
                                      <div class="col-md-12" style="text-align:right;">
                                          <Button class="btn btn-primary" onclick="bayar_tagihan('<?php echo $index_bln; ?>','<?php echo $arrays; ?>');" <?php echo $dis; ?> ><i class="fa fa-eye"></i> Bayar </Button>
                                      </div>
                                      <?php
                                  }
                                  ?>
                                  
                                  </div>
                                  
                                  
                                 
                                </div>
                                
                                
                            </div>
                            
                            <div style="border-bottom:1px solid #E5E5E5; height:10px;"></div>
                            
                        
                        <?php
                        $index_bln++;
                        }
                        ?>
                            
                        </div>
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

                    jQuery("#img_berita").prop("src" , "<?php echo url('storage/GambarSlides/'); ?>"+"/"+data.image_name);

                    

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

                    $("#img_foto_berita").prop("src" , "<?php echo url('storage/berita/foto/'); ?>"+"/"+data.foto);

                    //jQuery("#img_berita").prop("src" , "<?php echo url('storage/berita/foto/'); ?>"+"/"+data.foto);

                    

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
