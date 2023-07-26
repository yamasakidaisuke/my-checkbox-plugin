jQuery(document).ready(function($) {
    $('.my-checkbox').on('change', function() {
        var checkbox_id = this.id;
        var checkbox_state = this.checked ? 'checked' : 'unchecked';
        var post_id = ajax_object.post_id;  // Get the post ID from localized object

        $.post(ajax_object.ajax_url, {
            action: 'save_checkbox_state',
            checkbox_id: checkbox_id,
            checkbox_state: checkbox_state,
            post_id: post_id  // Pass the post ID to the AJAX handler
        });
    });
});
