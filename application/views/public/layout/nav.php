<header class="site-header container">

<?php if (!$this->user_model->is_logged_in()) : ?>
	<?php echo anchor("", $this->config->item("site_name"), "title='Go back to ".$this->config->item("site_name")." homepage.'") ?>
  <div class="btn-group login-button">
    <button class="btn btn-primary trigger">Login</button>
    <button class="btn btn-primary trigger dropdown-toggle">
      <span class="caret"></span>
    </button>
    <div class="dropdown-menu" id="login-dropdown-form">
      
      <?php echo form_open("user/login", array("class" => "mini-login", "accept-charset" => "utf-8")) ?>
        <p>
          <label for="loginUsername" class="hide">Email</label>
          <input name="user_email" type="text" id="loginUsername" placeholder="Email address" />
        </p>
        <p>
          <label for="loginPassword" class="hide">Password</label>
          <input name="user_password" type="password" id="loginPassword" placeholder="Password" />
        </p>
      
        <p class="submit-container">
          <input name="submit_login" type="submit" class="btn btn-primary" value="Login &raquo;" />
        </p>

        <hr>

        <hr>
        <aside>
          <div>Forgot your password? <?php echo anchor("user/forgot", "Recover it here") ?>.</div>
          <div>New user? <?php echo anchor("user/register", "Register now") ?>.</div>
        </aside>
      <?php echo form_close() ?>
      
    </div>
  </div>
<?php else : ?>
  <div class="row">
    <div class="span5">
      <?php echo anchor("", $this->config->item("site_name"), "class='logo ir' title='Go back to ".$this->config->item("site_name")." homepage.'") ?>
    </div>
    <div class="span7">
      <div class="row account-details-box">
        <div class="span5">
          <div>Logged in as: <strong><?php echo $this->user_model->get_user_field("user_name") . " " . $this->user_model->get_user_field("user_last_name") ?></strong></div>
          
        </div>
        <div class="span2">
          <div><i class="icon-user"></i> <?php echo anchor("profile", "My Profile") ?></div>
          <div><i class="icon-off"></i> <?php echo anchor("user/logout", "Log Out") ?></div>
        </div>
      </div>
    </div>
<?php endif ?>
</header>

<div class="container site-container">

<?php if (!isset($skip_messages)) : ?>
	<?php if ($this->session->flashdata("message")) : ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata("message") ?></div>
    <?php elseif ($this->session->flashdata("error")) : ?>
        <div class="alert alert-error"><?php echo $this->session->flashdata("error") ?></div>
    <?php elseif ($this->session->flashdata("info")) : ?>
        <div class="alert alert-message"><?php echo $this->session->flashdata("info") ?></div>
    <?php elseif (isset($error)) : ?>
        <div class="alert alert-error"><?php echo $error ?></div>
    <?php endif ?>
<?php endif ?>