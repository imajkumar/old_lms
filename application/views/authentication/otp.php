echo "error";die();
<?php $this->load->view('authentication/includes/head.php'); ?>
<body class="login_admin"<?php if(is_rtl()){ echo ' dir="rtl"'; } ?>>
 <div class="container">
  <div class="row">
   <div class="col-md-4 col-md-offset-8 authentication-form-wrapper">
    <div class="company-logo">
      <?php //get_company_logo(); ?>
	  <br><br>
	  <br><br>
	  <br><br>
    </div>
    <div class="mtop40">
      <h1 style="text-align:left;color:#000"><?php echo _l('admin_auth_login_heading'); ?></h1>
		echo "error";die();
      <form action="otp1" method="post">
	  
	  <div class="form-group">
        <input type="email" id="email" name="email" placeholder="enter email" class="form-control" autofocus="1">
      </div>
	  <div class="form-group">
        <input type="text"  name="otp" placeholder="mobile number" class="form-control" autofocus="1">
      </div>
      
        
       <div class="form-group">
        <button type="submit" class="btn btn-info btn-block">submit</button>
      </div>
	  </form>
      <div class="form-group">
        <a href="<?php echo site_url('authentication/forgot_password'); ?>"><?php echo _l('admin_auth_login_fp'); ?></a>
      </div>
      <?php if(get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != ''){ ?>
      <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
      <?php } ?>
      <?php do_action('before_admin_login_form_close'); ?>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
</div>
</body>
</html>
