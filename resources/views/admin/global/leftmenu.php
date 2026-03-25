<aside class="left-sidebar" data-sidebarbg="skin6" style="background:linear-gradient(45deg, #044c92 0 50%, #04488b 50% 100%);">
            <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="<?php echo url('administrator/'); ?>"
                        aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                            class="hide-menu">Dashboard</span></a></li>
                <li class="list-divider" style="background:#FFFFFF;"></li>
                <li class="nav-small-cap"><span class="hide-menu" style="color:gold;">Master</span></li>
                <!-- <li class="nav-small-cap"><span class="hide-menu">Master</span></li>
                    <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo url('administrator/userprofile'); ?>"
                        aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                            class="hide-menu">User Profile
                        </span></a>
                </li> -->

                
                <?php
                if(Session::get('level') == "1"){
                ?>
                <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo url('administrator/datauser'); ?>"
                        aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
                            class="hide-menu">Data User
                        </span></a>
                </li>

                    <!-- <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo url('administrator/datakategori'); ?>"
                        aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                            class="hide-menu">Kategori Berita
                        </span></a>
                </li> -->
                <?php
                }
                ?>

                <!-- <li class="sidebar-item"> <a class="sidebar-link" href="<?php //echo url('administrator/databerita'); ?>"
                        aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                            class="hide-menu">Data Gambar
                        </span></a>
                </li> -->

                <li class="sidebar-item"> 

                    <a class="sidebar-link" href="#submenu1" data-toggle="collapse">
                        <i data-feather="settings" class="feather-icon"></i>
                        <span class="hide-menu" >Settings &nbsp; <i data-feather="chevron-down" class="feather-icon"></i></span>
                    </a>

                </li>

                <div id='submenu1' class="collapse sidebar-submenu" style="font-size:14px;">
                        <a href="<?php echo url('administrator/databanjar'); ?>" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed"><i data-feather="tag" class="feather-icon"></i> &nbsp; Data Banjar</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed"><i data-feather="tag" class="feather-icon"></i> &nbsp; Data General</span>
                        </a>
                        <a href="<?php echo url('administrator/datamenu'); ?>" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed"><i data-feather="tag" class="feather-icon"></i> &nbsp; Data Member Menu</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                            <span class="menu-collapsed"><i data-feather="tag" class="feather-icon"></i> &nbsp; Data Social Media</span>
                        </a>
                        <li class="sidebar-item"> 

                            <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="#submenu2" data-toggle="collapse">
                            <!-- <a href="#" href="#submenu2" data-toggle="collapse" class="list-group-item list-group-item-action bg-dark text-white"> -->
                                <span class="menu-collapsed"><i data-feather="image" class="feather-icon"></i> &nbsp; Data Gambar &nbsp; <i data-feather="chevron-down" class="feather-icon"></i></span>
                            </a>

                        </li>
                        <div id='submenu2' class="collapse sidebar-submenu" style="font-size:14px;">
                            <li class="sidebar-item"> 

                                <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="#submenu3" data-toggle="collapse">
                                    <span class="menu-collapsed"  style="margin-left:10px;"><i data-feather="image" class="feather-icon"></i> &nbsp; Gbr HomePage &nbsp; <i data-feather="chevron-down" class="feather-icon"></i> </span>
                                </a>

                            </li>

                            <div id='submenu3' class="collapse sidebar-submenu" style="font-size:14px;">

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/datakategori_slides"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="settings" class="feather-icon"></i> &nbsp; Kategori</span>
                                    </a>
                                <li>

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/datagambar_slides"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="image" class="feather-icon"></i> &nbsp; Data Gambar</span>
                                    </a>
                                </li>


                            </div>


                            <li class="sidebar-item" id="side_menu_member"> 

                                <!-- <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="#" data-toggle="collapse">
                                    <span class="menu-collapsed"  style="margin-left:10px;"><i data-feather="tag" class="feather-icon"></i> &nbsp; Gbr Sumbangan &nbsp; <i data-feather="chevron-down" class="feather-icon"></i> </span>
                                </a> -->

                            </li>

                        </div>
                       
                </div>

                
                <li class="list-divider" style="background:#FFFFFF;"></li>

                <li class="nav-small-cap"><span class="hide-menu" style="color:gold;">Secondary Master</span></li>
                
                <li class="sidebar-item"> 
                    <a class="sidebar-link" href="<?php echo url('administrator/data_usaha'); ?>"
                        aria-expanded="false"><i data-feather="file" class="feather-icon"></i><span
                            class="hide-menu">Data Usaha
                        </span>
                    </a>
                </li>

                <li class="sidebar-item"> 

                    <a class="sidebar-link" href="#submenu5" data-toggle="collapse">
                        <i data-feather="settings" class="feather-icon"></i>
                        <span class="hide-menu" >Data Blog &nbsp; <i data-feather="chevron-down" class="feather-icon"></i></span>
                    </a>

                </li>

                <div id='submenu5' class="collapse sidebar-submenu" style="font-size:14px;">

                        <li class="sidebar-item"> 
                            <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/databerita"); ?>">
                                <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; Data Blog</span>
                            </a>
                        <li>

                        <li class="sidebar-item"> 
                            <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/data_kategoriberita"); ?>">
                                <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; Kategori Blog</span>
                            </a>
                        </li>

                </div>
                
                <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo url('administrator/datapunia_wajib'); ?>"
                        aria-expanded="false"><i data-feather="heart" class="feather-icon"></i><span
                            class="hide-menu">Data Punia Wajib
                        </span></a>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link" href="<?php echo url('administrator/datasumbangan'); ?>"
                        aria-expanded="false"><i data-feather="heart" class="feather-icon"></i><span
                            class="hide-menu">Data Sumbangan
                        </span></a>
                </li>

                <!-- <li class="sidebar-item"> 
                    <a class="sidebar-link" href="<?php //echo url('administrator/data_tenagakerja'); ?>"
                        aria-expanded="false"><i data-feather="file" class="feather-icon"></i><span
                            class="hide-menu">Data Tenaga Kerja
                        </span>
                    </a>
                </li> -->

                <!-- <li class="sidebar-item"> 

                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="#submenu4" data-toggle="collapse">
                        <span class="menu-collapsed"><i data-feather="settings" class="feather-icon"></i> Data Tenaga Kerja &nbsp; <i data-feather="chevron-down" class="feather-icon"></i> </span>
                    </a>

                </li> -->

                <li class="sidebar-item"> 

                    <a class="sidebar-link" href="#submenu4" data-toggle="collapse">
                        <i data-feather="settings" class="feather-icon"></i>
                        <span class="hide-menu" >Tenaga Kerja &nbsp; <i data-feather="chevron-down" class="feather-icon"></i></span>
                    </a>

                </li>

                <div id='submenu4' class="collapse sidebar-submenu" style="font-size:14px;">

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/data_tenagakerja"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; Data Pekerja</span>
                                    </a>
                                <li>

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/data_tenagakerja_skill"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; Skill Pekerja</span>
                                    </a>
                                </li>

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/data_tenagakerja_interview"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; InterView</span>
                                    </a>
                                </li>

                                <li class="sidebar-item"> 
                                    <a class="sidebar-link list-group-item list-group-item-action bg-dark text-white" href="<?php echo url("administrator/data_tenagakerja_approve"); ?>">
                                        <span  style="margin-left:20px;"><i data-feather="file-text" class="feather-icon"></i> &nbsp; Diterima </span>
                                    </a>
                                </li>


                </div>
                
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="<?php echo url('logoutadmin'); ?>"
                        aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                            class="hide-menu">Logout</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>