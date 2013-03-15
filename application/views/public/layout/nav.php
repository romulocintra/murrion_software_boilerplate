<header class="container">

<h1><?php echo anchor("", $this->config->item("site_name"), "title='Go back to ".$this->config->item("site_name")." homepage.'") ?></h1>

<?php if (!$this->user_model->is_logged_in()) : ?>
    <a href="<?php echo site_url("user/login") ?>" class="btn btn-primary">Login</a><br />
    Forgot your password? <?php echo anchor("user/forgot", "Recover it here") ?><br />
    New user? <?php echo anchor("user/register", "Register now") ?>
<?php else : ?>
    Logged in as: <strong><?php echo $this->user_model->get_user_field("user_name") . " " . $this->user_model->get_user_field("user_last_name") ?></strong><br />
    <?php echo anchor("user/password", "Change password") ?><br />
    
    <?php echo anchor("user/logout", "Log Out") ?>
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