 
                    <!--outter-wp-->
                        
               </div>
            </div>
                <!--//content-inner-


            <!--/sidebar-menu-->
                <div class="sidebar-menu">
                    <header class="logo">
                    <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="index.html"> <span id="logo"> <h1>Augment</h1></span> 
                    <!--<img id="logo" src="" alt="Logo"/>--> 
                  </a> 
                </header>
            <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
            <!--/down-->
            <div class="down">	
									  <a href="index.html"><img src="images/admin.jpg"></a>
									  <a href="index.html"><span class=" name-caret">Jasmin Leo</span></a>
									 <p>System Administrator in Company</p>
									<ul>
									<li><a class="tooltips" href="index.html"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
										<li><a class="tooltips" href="index.html"><span>Settings</span><i class="lnr lnr-cog"></i></a></li>
										<li><a class="tooltips" href="index.html"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
										</ul>
									</div>
                          
            <div class="menu">
                <ul id="menu" >
                    <li><h4 href="index.html"><i class="fa fa-tachometer"></i> <span>Dashboard</span></h4></li>
                     <li id="menu-academico" ><a href="#"><i class="fa fa-table"></i> <span> ACCOUNTS</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                       <ul id="menu-academico-sub" >
                        <li id="menu-academico-avaliacoes" ><a href="tabs.html"> Cashbooks</a></li>
                        <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>receipt">Receipts</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>invoice">Invoices</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>">Bank Statements</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>inventory">Inventories</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>sitelog">Sitelogs</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>site">Sites</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="<?php echo base_url(); ?>payroll">Payroll</a></li>
                      </ul>
                    </li>
                     <li id="menu-academico" ><a href="#"><i class="fa fa-file-text-o"></i> <span>HUMAN RESOURCE</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                         <ul id="menu-academico-sub" >
                           
                            <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>worker">Employees</a></li>
                            <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>
                            user2">App-Users</a></li>
                            <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>leave">Leave Tracker</a></li>
                            <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>people">Team</a></li>
                            <li id="menu-academico-boletim" ><a href="<?php echo base_url(); ?>supplier">Supplier</a></li>

                          </ul>
                     </li>
                     <li><a href="<?php echo base_url(); ?>files"><i class="lnr lnr-pencil"></i> <span>Designs</span></a></li>  
              </ul>
            </div>
                              </div>
                              <div class="clearfix"></div>      
                            </div>

                            <script>
                            var toggle = true;
                                        
                            $(".sidebar-icon").click(function() {                
                              if (toggle)
                              {
                                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                                $("#menu span").css({"position":"absolute"});
                              }
                              else
                              {
                                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                                setTimeout(function() {
                                  $("#menu span").css({"position":"relative"});
                                }, 400);
                              }
                                            
                                            toggle = !toggle;
                                        });
                            </script>
<!--js -->
    <script src="<?php echo base_url()?>assets/dash/js/jquery.nicescroll.js"></script>
    <script src="<?php echo base_url()?>assets/dash/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
   <script src="<?php echo base_url()?>assets/dash/js/bootstrap.min.js"></script>
    <script type="text/javascript">
                 $('.main-search').hide();
                $('button').click(function (){
                    $('.main-search').show();
                    $('.main-search text').focus();
                }
                );
                $('.close').click(function(){
                    $('.main-search').hide();
                });
            </script>

                <script type="text/javascript">

            function DropDown(el) {
                this.dd = el;
                this.placeholder = this.dd.children('span');
                this.opts = this.dd.find('ul.dropdown > li');
                this.val = '';
                this.index = -1;
                this.initEvents();
            }
            DropDown.prototype = {
                initEvents : function() {
                    var obj = this;

                    obj.dd.on('click', function(event){
                        $(this).toggleClass('active');
                        return false;
                    });

                    obj.opts.on('click',function(){
                        var opt = $(this);
                        obj.val = opt.text();
                        obj.index = opt.index();
                        obj.placeholder.text(obj.val);
                    });
                },
                getValue : function() {
                    return this.val;
                },
                getIndex : function() {
                    return this.index;
                }
            }

            $(function() {

                var dd = new DropDown( $('#dd') );

                $(document).click(function() {
                    // all dropdowns
                    $('.wrapper-dropdown-3').removeClass('active');
                });

            });

        </script>
