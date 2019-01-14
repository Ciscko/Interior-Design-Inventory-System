


            <h3>UPLOADED DESIGNS</h3>
            <div>
                <section class="panel">
                  <div class="panel-body " style="align-content:center;">
                      <div style="color:pink ;">
                      <?php $this->session->flashdata('message');?>
                     </div>
                      <form method="post" action="files/upload" enctype="multipart/form-data">
                              <input class="btn " name="zip_file" type="file" >
                              <input type="submit" value="Upload" class="btn btn-success" >

                       </form>
                   </div>           
                </section> 

                 <?php if(!empty($files))
                              {?>
                                   <?php  foreach($files as $frow)
                                      { ?><div class="col-md-3 col-lg-3 col-sm-3" style="height:130px; ">
                                        <img src="<?php echo base_url().'/static/images/4.jpg'?>" style="height:30px; width:170px;">
                                      <h5 style="color:black; font-weight:bold ;" ><?php echo  $frow['upload_name']; ?></h5>
                                     
                                    <a href="<?php echo base_url().'files/downloadz/'.$frow['id']; ?>" class="dwn btn btn-primary">Download</a> 
                                    <a href="<?php echo base_url().'files/delete_file/'.$frow['id']; ?>" class="btn btn-danger">Delete</a>
                                          </div>
                                <?php } 
                              } ?>
            </div>
               
        </div>
     </div>
   </div>
</div>