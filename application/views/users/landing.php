 

<section id="content" >
    <section class="main padder">
        <div class="container" >
              <div class="row" style="padding-top: 20px;">
                <?php if($user['level']=='admin' || $user['level']=='hr'){?>
                   <a href="<?php echo base_url('people');?>" class=" col-lg-4  col-md-4 col-sm-6 col-xs-12 "><br><br><br>
                        
                         
                          <img style=" height:200px; width: 100%;"  src="<?php echo base_url('static/images/hr2.jpg');?>">
                        <h1  style=" position: absolute; top: 50%; left: 50%;transform: translate(-50%, -50%); color:black;">
                               HUMAN RESOURCE  
                          </h1>
                      

                </a>
                  <?php }?>
                  <?php if($user['level']=='admin' || $user['level']=='accounts'){?>
                  <a  href="<?php echo base_url('inventory');?>" class="col-lg-4  col-md-4 col-sm-6 col-xs-12 "><br><br><br>
                     
                    <img style=" height:200px; width: 100%;"  src="<?php echo base_url('static/images/port5.jpg');?>">
                  <h1 style=" position: absolute; top: 50%; left: 50%;transform: translate(-50%, -50%); color:black;">
                         ACCOUNTS
                    </h1>
                  
                  </a>
                  <?php }?>
                    <?php if($user['level']=='admin' || $user['level']=='designs'){?>
                   <a href="<?php echo base_url('files');?>" class="col-lg-4  col-md-4 col-sm-6  col-xs-12 "><br><br><br>
                    
                    <img style=" height:200px; width: 100%;"  src="<?php echo base_url('static/images/hero.jpg');?>">
                  <h1 style=" position: absolute; top: 50%; left: 50%;transform: translate(-50%, -50%); color:black;">
                        DESIGNS
                    </h1>
                    
                  </a>
                   <?php }?>

              
                    <?php if($user['level']=='admin' || $user['level']=='social'){?>
             
                   <div class="col-lg-4   col-md-4 col-sm-6 col-xs-12 "><br><br><br>
                    <img style=" height:200px; width: 100%;"  src="<?php echo base_url('static/images/3.jpg');?>">
                  <h1 style=" position: absolute; top: 50%; left: 50%;transform: translate(-50%, -50%); color:black;">
                        SOCIAL
                    </h1>
                  </div>
                  <?php }?>
                    <?php if($user['level']=='admin' || $user['level']=='projects'){?>

                  <div class="col-lg-4  col-md-4  col-sm-6 col-xs-12 "><br><br><br>
                    <img style=" height:200px; width: 100%;"  src="<?php echo base_url('static/images/b.jpg');?>">
                  <h1 style=" position: absolute; top: 50%; left: 50%;transform: translate(-50%, -50%); color:black;">
                         PROJECTS
                    </h1>
                  </div>
                  <?php }?>
                    
            </div>
        </div>
             
              
      
    </section>
  </section>


 <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>&copy;2017 Indesign Interiors </small><br><br>
        <a href="#" class="btn btn-xs btn-circle btn-twitter"><i class="fa fa-twitter"></i></a>
        <a href="#" class="btn btn-xs btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
        <a href="#" class="btn btn-xs btn-circle btn-gplus"><i class="fa fa-instagram"></i></a>
      </p>
    </div>
  </footer>
                     
  
</body>
</html>