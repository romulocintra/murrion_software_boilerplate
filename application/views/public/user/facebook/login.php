 <html>
    <head>    
      <title>My Facebook Login Page</title>
    </head>
    <body>

      <div id="fb-root"></div>
      <script>
	  	setTimeout(function() {
			window.location.reload();
		}, 6000);

        window.fbAsyncInit = function() {
          FB.init({
            appId      : <?php echo $this->config->item("appId", "facebook") ?>, // App ID
            channelUrl : "<?php echo site_url("facebook_login/login_done") ?>", // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
          });
          // Additional initialization code here
		  
			FB.Event.subscribe('auth.login', function(response) {
				window.location.reload();
			});
        };
        // Load the SDK Asynchronously
        (function(d){
           var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           ref.parentNode.insertBefore(js, ref);
         }(document));
      </script>

	<p>Wait while the facebook login is performed...</p>

    </body>
 </html>