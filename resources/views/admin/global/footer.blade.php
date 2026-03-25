
<script src="<?php echo url('assets/src/'); ?>/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="<?php echo url('assets/src/'); ?>/dist/js/app-style-switcher.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/dist/js/feather.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/dist/js/sidebarmenu.js"></script>
    <script src="<?php echo url('assets'); ?>/dist/js/jq-prompt.min.js"></script>
    
    <!--Custom JavaScript -->
    <script src="<?php echo url('assets/src/'); ?>/dist/js/custom.min.js"></script>
    
    <!--This page JavaScript -->
    <script src="<?php echo url('assets/src/'); ?>/assets/extra-libs/c3/d3.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/extra-libs/c3/c3.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>

    <script src="<?php echo url('assets/src/'); ?>/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo url('assets/src/'); ?>/dist/js/pages/datatable/datatable-basic.init.js"></script>

    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.9/adapters/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js" integrity="sha512-j7/1CJweOskkQiS5RD9W8zhEG9D9vpgByNGxPIqkO5KrXrwyDAroM9aQ9w8J7oRqwxGyz429hPVk/zR6IOMtSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script type="text/javascript">
        var url_menu_apis  = "{{ config('myconfig.devUrl') }}";
        var url_menu_asset = "{{ config('myconfig.assetsUrl') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //alert(url_menu_apis);
        
        $(document).ready(function() {

        	/* This is basic - uses default settings */
        	
        	$("a#single_image").fancybox();
	
        });

        $.ajax({
            type:"GET",
            dataType:"json",
            url:"<?php echo url('administrator/ambil_listmenu'); ?>",
            success:function(data){
                $("#side_menu_member").html("");

                $.each(data , function(index,element){
                    var menus = "<?php echo url('administrator/datamenu'); ?>"+"/"+element.id_menu_member;
                    var datas = '<a href="" class="list-group-item list-group-item-action bg-dark text-white">';
                    datas += '<span class="menu-collapsed"><i class="fas fa-image"></i> &nbsp; Gbr '+element.menu+'</span></a>';
                    
                    $("#side_menu_member").append(datas);
                });
            }
        });

    </script>

   
