<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indesign Interiors</title>
    <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/images/logo.png">
    <link href="<?php echo base_url('assets/style.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('static/styl.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    
    <script src="<?php echo base_url();?>assets/bootstrap/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>

    </head> 
    
<body style="background: ;">
    
    <div class="container-fluid" style="padding-bottom:100px ;">
        <div class="row">
            <header>

                <div class="navbar navbar-fixed-top navbar-inverse">
                    <div class="navbar-header" style="background:#4fe4e4; min-height:50px ;">
                       <img style="max-height:99px;" src="<?php echo base_url('assets/images/logo.png');?>">
                        <button type="button" class="navbar-toggle " data-target="#head" data-toggle="collapse"></button>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        
                    </div>

            <div class="navbar-collapse collapse" id="head">
                <div class="container">
                     <ul class="nav nav-tabs" >
                <div style="padding:20px ">
                  
                           <li class="pull-right"><a href="<?php echo base_url(); ?>user/logout"><img style="max-height:40px;  " src="<?php echo base_url('assets/images/log.jpg');?>"></a><br>
                                  </li>
                </div>
          
                    </ul>
                </div> 
            </div> 
                </div>
        </header>

             
        </div>
     </div> 
      <div class="container-fluid " style="background-color:#0c4a75; height: 40px; ">
        
        <h4 style=" text-align:left;  color: red;"><?php echo date('Y-m-d H:i:s ');?></h4>
         <h4 style=" text-align:right;  color: purple;"><?php echo $user['email'];?></h4>
      </div>


    


                
            
  
    