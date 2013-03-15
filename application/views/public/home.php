<?php $this->load->view("public/layout/header") ?>

<div id="gmap1" class="gmap_list" style="width: auto; height: 400px; border: 1px solid #AAAAAA; margin: 1em 0;"></div>
<input type="hidden" size="50" id="gmap1_coordinates" name="gmap1_coordinates" value="53.225768,-8.437500" />
<input type="hidden" size="50" id="gmap1_infowindow" name="gmap1_infowindow" value="<?php echo addslashes("Marker") ?>" />

<?php $this->load->view("public/layout/footer") ?>