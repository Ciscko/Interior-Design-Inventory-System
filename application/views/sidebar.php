
<div class="container-fluid" style="background-color: #f2eded ;">

      <div class="row ">
            <div class="col-lg-2 col-md-2 col-sm-12">
                
                  <div class="panel-group" style="padding-top:60px;"  id="accordion" >
                          
                    <?php if($user['level']=='admin' || $user['level']=='hr'){?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a href="#panelB1" data-toggle="collapse" data-parent="#accordion" >Human Resource</a>
                                            </h4>
                                        </div>
                                        <div id="panelB1" class="panel-collapse ">
                                            <div  class="panel-body" >
                                                <ul>
                                                   <a class="pan" href="<?php echo base_url().'people';?>">TEAM</a>
                                                   <a class="pan" href="<?php echo base_url().'supplier';?>">SUPPLIERS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'worker';?>">WORKERS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'contractor';?>">CONTRACTORS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'leave';?>">LEAVES</a><br>
                                                    <a class="pan" href="<?php echo base_url().'user2';?>">APP-USERS</a><br>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                    <?php if($user['level']=='admin' || $user['level']=='design'){?>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a href="#panelB3" data-toggle="collapse" data-parent="#accordion" >Design Archives</a>
                                            </h4>
                                        </div>
                                        <div id="panelB3" class="panel-collapse ">
                                            <div class="panel-body" >
                                                <ul>
                                                    <a class="pan" href="<?php echo base_url().'files';?>">ARCHIVES</a>
                                                   
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                             <?php }?>
                              <?php if($user['level']=='admin' || $user['level']=='accounts'){?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a href="#panelB2" data-toggle="collapse" data-parent="#accordion" >Accounts</a>
                                            </h4>
                                        </div>
                                        <div id="panelB2" class="panel-collapse ">
                                            <div class="panel-body" >
                                                <ul>
                                                   <a class="pan" href="<?php echo base_url().'invoice';?>">INVOICES</a><br>
                                                    <a class="pan" href="<?php echo base_url().'receipt';?>">RECEIPTS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'cashbook';?>">CASHBOOK</a><br>
                                                    <a class="pan" href="<?php echo base_url().'';?>">STATEMENTS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'';?>"> PAYROLL</a><br>
                                                    <a class="pan" href="<?php echo base_url().'inventory';?>">INVENTORIES</a><br>
                                                    <a class="pan" href="<?php echo base_url().'sitelog';?>">SITE LOGS</a><br>
                                                    <a class="pan" href="<?php echo base_url().'site';?>">SITES</a>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                    
                            
                         </div>

                  
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 ">



