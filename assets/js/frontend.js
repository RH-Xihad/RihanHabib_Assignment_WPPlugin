jQuery(document).ready(function($) {
    $('.member-contact-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);

        // Remove previous messages and add preloader
        form.find('.response-message, .form-preloader').remove();
        const preloader = $('<div class="form-preloader">Sending message...</div>');
        form.append(preloader);

        const data = {
            action: 'submit_member_contact',
            member_contact_nonce: memberContactAjax.nonce,
            member_id: form.find('[name="member_id"]').val(),
            full_name: form.find('[name="full_name"]').val(),
            email: form.find('[name="email"]').val(),
            message: form.find('[name="message"]').val()
        };

        $.post(memberContactAjax.mtm_ajax_url, data, function(response) {
            preloader.remove(); // Remove preloader

            const msg = $('<div class="response-message"></div>').text(response.data.message);
            if (response.success) {
                msg.addClass('contact-success');
            } else {
                msg.addClass('contact-failed');
            }

            form.append(msg);

            // Hide message after 3 seconds
            setTimeout(function() {
                msg.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 3000);
        });
    });
});
