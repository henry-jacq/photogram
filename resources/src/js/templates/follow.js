// Toggle follow button status
function toggleFollow(selector) {
    if (selector.find('i').hasClass('bi-person-add')) {
        selector.html('<i class="bi-person-check me-1"></i>Following');
    } else {
        selector.html('<i class="bi-person-add me-1"></i>Follow');
    }
}

// Follow user
$('.btn-follow').on('click', function () {
    let selector = $(this);
    let follow_id = $(this).attr('data-id');
    toggleFollow(selector);

    $.post('/api/users/follow',
        {
            follower_id: follow_id
        }).fail(function () {
            toggleFollow(selector);
            console.error("Can't follow user: " + follow_id);
        });
});