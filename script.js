jQuery(document).ready(function($) {
    $('.reaction-btn').on('click', function() {
        var button = $(this);
        var reaction = button.data('reaction');
        var post_id = button.closest('.post').attr('id').replace('post-', '');

        $.ajax({
            url: fakebook_reactions.ajax_url,
            method: 'POST',
            data: {
                action: 'fakebook_reactions',
                post_id: post_id,
                reaction: reaction
            },
            success: function(response) {
                if (response.success) {
                    console.log('Reaction saved', response.data);
                }
            }
        });
    });
});
