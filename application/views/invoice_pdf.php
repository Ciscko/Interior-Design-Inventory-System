<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="<?php echo base_url() ?>/pdf/assets/style.css" />
        <link href="<?php echo base_url('assets/style.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    </head>
    <body style="font-size:20px;">
        <div class="main">
            <div class="header_area">
                <img style="max-height:81px;" src="<?php echo base_url('assets/images/logo.png');?>">
                <h1 align="center"><strong>INVOICE</strong></h1>

            </div>
            <div class="voucher_address">
                <div class="customer_address">
                    <strong><h2 align="left">Invoice No: <?php echo $pdf_data->invoiceNo; ?> </h2>
                    <h2 align="left"><?php echo 'Date : '.$pdf_data->date; ?></h2></strong>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                    <th>Gross Amount</th>
                                    <th>TAX %</th>
                                    <th>NET  Amount</th>
                                    <th>Site Name</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <tr>
                              <td><?php echo 'Sh.'.$pdf_data->gross; ?></td> 
                              <td><?php echo $pdf_data->tax.' %'; ?></td> 
                              <td><?php echo 'Sh.'.$pdf_data->net; ?></td>
                              <td><?php echo $pdf_data->siteName; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
               
            </div>
       
            <div class="footer_area">
                <p align="center"></p>
            </div>

        </div>
    </body>
</html>
