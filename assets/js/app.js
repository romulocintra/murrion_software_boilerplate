(function (window, $){
    $('.login-button .trigger').on('click', function(e){
        e.preventDefault();
        $(this).parent().find('.dropdown-menu').toggle();
        // $('#loginUsername').focus();
    })
    
    $('[data-href]').each(function() {
        var $this = $(this);
        var href = $this.data('href');
        $this.on('click', function(e){
            window.location.href = href;
        });
    });
    
    $('.advanced-search-link a').on('click', function(e){
        e.preventDefault();
        
        $('.advanced-search-container').slideToggle();
    });
    
    $('.expand-button').on('click', function(e) {
        var $this = $(this),
            $el = $('#request_div');
            $el2 = $('#response_div');
            
            if ($el.hasClass('span5')) {
                $($el, $el2)
                    .removeClass('span5')
                    .removeClass('span7')
                    .addClass('span12');
                
                // $el.find('.span5')
                //     .removeClass('span5')
                //     .addClass('span6');
                
                $this.addClass('expanded')
            } else {
                $el
                    .removeClass('span12')
                    .addClass('span5');
                    
                $el2
                    .removeClass('span12')
                    .addClass('span7');
                
                // $el.find('.span6')
                //     .removeClass('span6')
                //     .addClass('span5');
                    
                $this.removeClass('expanded')
            }
            
    });
    
    $('#addAnotherFileBtn').on('click', function(e){
        e.preventDefault();
        var $container = $(this).parent().find('.filelist-container'),
            currentCount = $container.find('input').length;
        $container.append('<label class="file-label">File '+(currentCount+1)+':<input type="file" name="file_'+currentCount+'" value="" id="file_'+currentCount+'"></label>');
    });
    
    $('input').placeholder();
})(this, jQuery);