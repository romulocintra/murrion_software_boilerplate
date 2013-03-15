<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>assets/css/<?php echo $style = ($this->config->item('site_style') == 'default') ? 'bootstrap.css' : 'styles/'.$this->config->item('site_style').'.css';?>" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url(); ?>assets/css/app.css" rel="stylesheet">
    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


    <title><?php echo isset($title) ? $title . " - " . $this->config->item("site_name") : $this->config->item("site_name") ?></title>

    <script type="text/javascript">var homeurl = "<?php echo site_url() ?>"</script>
	<?php echo link_tag(base_url()."assets/js/jquery-ui-1.8.18.custom/css/ui-lightness/jquery-ui-1.8.18.custom.css") ?>
</head>

<body>

<?php $this->load->view("public/layout/nav") ?>
