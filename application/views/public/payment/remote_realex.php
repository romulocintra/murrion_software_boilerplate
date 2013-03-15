<?php $this->load->view('public/layout/header'); ?>

<body class="track-booking-page provisional">
  
  <div class="background">
  
    <div class="wrapper row">
      <div class="container twelve columns">
      
        <header class="row">
          <h1 class="logo four columns">
          	<span>
          	</span>
          </h1>
            <h2>Payment</h2>
          <div class="eight columns availability">        	
         </div>
        </header>
      
        <div class="row">
          <div class="twelve columns">
            <hr>
          </div>
        </div>

          <div class="eight columns">
          	
			<div class="details">
              
			<p>Please make an advance payment of &euro;<?php echo number_format($price, 2); ?>.</p>
			
			 <?php if($error_message) : ?>
		   		<div style="color: #B20000;text-align:center"><p><strong><?php echo $error_message; ?></strong></p></div>
			 <?php endif ?>
	   
	   		<?php if($show_form) : ?>
				<?php $this->load->view("public/payment/form") ?>
			<?php endif ?>
		</div>
            
          </div>
        </div>
      
        <div class="row">
          <div class="twelve columns">
            
          </div>
        </div>
      
      </div>
    </div>
    <?php $this->load->view('public/layout/footer'); ?>
  </div>

<script type="text/javascript">var base_url = '<?php echo base_url() ?>';</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/payment.js") ?>"></script>

</body></html>