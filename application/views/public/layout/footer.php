</div>



    <footer class="site-footer">

      <div class="container">

        <div class="footer-links">

        </div>

        <div class="copyright">

          Copyright &copy; <?php echo date("Y") ?> <?php echo $this->config->item("site_name") ?>.

        </div>

      </div>



    <?php if ($this->config->item("development")) : ?>

        <p><?php var_dump($this->session->userdata) ?> {elapsed_time}</p>

        <?php $this->output->enable_profiler() ?>

    <?php endif ?>



    </footer>

    <!-- Le javascript

    ================================================== -->

    <!-- Placed at the end of the document so the pages load faster -->

	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-1.8.18.custom/js/jquery-1.7.1.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>

    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/javascript.js"></script>
    
    <?php if (isset($include_gmaps)) : ?>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&region=uk&language=en"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/gmaps.js"></script>
    <?php endif ?>
  </body>

</html>