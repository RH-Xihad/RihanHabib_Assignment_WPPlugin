jQuery(document).ready(function($) {
    // Handle image upload buttons
    $('.upload-image-button').click(function(e) {
        e.preventDefault();
        
        var target = $(this).data('target');
        var frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#' + target).val(attachment.url);
            
            // Update preview
            $('#' + target).siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto;">');
        });
        
        frame.open();
    });
});