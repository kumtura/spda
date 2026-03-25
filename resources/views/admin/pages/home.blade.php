 @extends('index')

@section('isi_menu')

 <!-- ============================================================== -->
            <div class="container-fluid" style="background:#FFFFFF!important;">
                <!-- *************************************************************** -->
                <!-- Start First Cards -->
                <!-- *************************************************************** -->
                <div class="card-group">
                    
                    <div class="col-md-12" style="margin:20px;">
                        <h2> <b>Dashboard </b> <small> <span style="font-size:18px;"> Dashboard Home </span> </small> </h2>
                        <hr />
                    </div>

                    <div class="col-md-12" style="margin-top:20px;">
                        <div class="row">
                            <div class="col-md-3">

                                <div class="card" style="  background:#d9edf7;">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Total Investor</h5>
                                        <p class="card-text text-center" style="font-size:24px;  color:#04488b;"><?php echo count($usaha); ?></p>
                                        <center><a href="<?php echo url('administrator/data_usaha'); ?>" class="btn btn-primary"><i class="fas fa-eye"></i>  &nbsp;  Cek Detail</a></center>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="card" style=" background:#d9edf7;">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Total Tenaga Kerja</h5>
                                        <p class="card-text text-center" style="font-size:24px;  color:#04488b;"><?php echo $jml_karyawan; ?></p>
                                        <center><a href="<?php echo url('administrator/data_tenagakerja'); ?>" class="btn btn-primary"><i class="fas fa-eye"></i>  &nbsp;  Cek Detail</a></center>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="card" style="background:#d9edf7;">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Total Sumbangan</h5>
                                        <p class="card-text text-center" style="font-size:24px;  color:#04488b;">0</p>
                                        <center><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i>  &nbsp;  Cek Detail</a></center>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3">

                                <div class="card" style="background:#d9edf7;">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Total Iuran</h5>
                                        <p class="card-text text-center"  style="font-size:24px; color:#04488b;"><?php echo format_rupiah($totalpunia); ?></p>
                                        <center><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i>  &nbsp; Cek Detail</a></center>
                                    </div>
                                </div>

                            </div>
                            </div>
                    </div>

                    <div class="col-md-12" style="margin-top:20px;">

                      <div class="col-md-12">

                        <div class="card">
                            <div class="card-header" style="background:linear-gradient(45deg, #044c92 0 50%, #04488b 50% 100%); color:#FFFFFF;">
                                Grafik Sumbangan & Iuran ( <?php echo date("Y"); ?> )
                            </div>
                            <div class="card-body">
                            
                                <div id="containercolumn"></div>

                            </div>
                        </div>

                     </div>

                    </div>

        <script type="text/javascript">
        
        var total_sumbangan = [];
        
        $.ajax({
            type:"GET",
            data:"",
            url:"<?php echo url('administrator/get_danapunia_range'); ?>",
            dataType: "json",
            async:false,
            success:function(data){
                
                var parses = JSON.parse(data.total_punia);
                
                //console.log("punias" , parses[0]);
                
                
                $.each(parses , function(index,element){
                    
                     //console.log("punias" , element.punia);
                     total_sumbangan.push(parseInt(element.punia));
                     
                });
                
            }
        });
        
        console.log("punias" , total_sumbangan);


            Highcharts.chart('containercolumn', {
                chart: {
                    backgroundColor: {
                        linearGradient: [0, 0, 1000, 1000],
                        stops: [
                            [0, 'rgb(255, 255, 255)'],
                            [1, 'rgb(200, 200, 255)']
                        ]
                    },
                    borderWidth: 1,
                    plotBorderWidth: 1,
                    type:'column'
                },
                title: {
                    text: 'Grafik Tahunan ( <?php echo date("Y"); ?> ) Sumbangan & Iuran'
                },
                subtitle: {
                    text: 'Source: danapunia.com'
                },
                xAxis: {
                    categories: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Punia Wajib & Sumbangan (IDR)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Dana Sumbangan',
                    data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

                }, {
                    name: 'Dana Iuran',
                    // data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]
                    data: total_sumbangan
                }
                // }, {
                //     name: 'London',
                //     data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

                // }, {
                //     name: 'Berlin',
                //     data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

                // }
                ]
            });



            </script>



    </div>
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->
</div>

 @stop
