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

$('.btn-get-followers').on('click', function () {
    let user_id = $(this).parent().attr('data-id');

    let html = `<div class="container"><ul id="followers-list" class="list-group list-group-flush"></ul></div>`;
    let clone = `<li class="list-group-item"><div class="d-flex align-items-center justify-content-between"><div class="me-2"><div class="d-flex align-items-center"><div class="me-2"><img id="user-avatar" class="border rounded-circle" src="" width="40" height="40" loading="lazy"></div><div class="text-break"><h7 id="fullname" class="text-body"></h7><p id="username" class="mb-0 small fw-light"></p></div></div></div><div><a id="link" href="" class="btn btn-primary btn-sm">Show profile</a></div></div></li>`;
    const d = new Dialog('Followers', html);
    d.show('', true);
    const modal = d.clone;
    const target = modal.find('#followers-list')
    modal.find('.modal-body').addClass('p-2');
    modal.find('.modal-dialog').addClass('modal-dialog-scrollable');
    modal.find('.modal-footer').remove();


    $.get('/api/users/followers?id=' + user_id, 
        function (data) {
            if (data.message == true && data.followers.length > 0) {
                for (let count = 0; count < data.followers.length; count++) {
                    let ud = data.followers[count];
                    let username = ud.username;
                    let fullname = ud.fullname;
                    let avatar = ud.avatar;
                    target.append(clone);
                    target.find('#username').text('@' + username);
                    target.find('#username').attr('id', 'username' + count);
                    target.find('#fullname').text(fullname);
                    target.find('#fullname').attr('id', 'fullname' + count);
                    target.find('#user-avatar').attr('src', avatar);
                    target.find('#user-avatar').attr('id', 'user-avatar' + count);
                    target.find('#link').attr('href', '/profile/' + username);
                    target.find('#link').attr('id', 'link' + count);
                }
            } else {
                $('<h5 class="text-center my-5"><i class="bi bi-exclamation-triangle me-2"></i>No followers found</h5>').prependTo(modal.find('.modal-body').empty())
            }
        }).fail(function () {
            showToast("Photogram", "Just Now", "Can't get followers for user");
        });
});

$('.btn-get-followings').on('click', function () {
    let user_id = $(this).parent().attr('data-id');

    let html = `<div class="container"><ul id="followings-list" class="list-group list-group-flush"></ul></div>`;
    let clone = `<li class="list-group-item"><div class="d-flex align-items-center justify-content-between"><div class="me-2"><div class="d-flex align-items-center"><div class="me-2"><img id="user-avatar" class="border rounded-circle" src="" width="40" height="40" loading="lazy"></div><div class="text-break"><h7 id="fullname" class="text-body"></h7><p id="username" class="mb-0 small fw-light"></p></div></div></div><div><a id="link" href="" class="btn btn-primary btn-sm">Show profile</a></div></div></li>`;
    const d = new Dialog('Followings', html);
    d.show('', true);
    const modal = d.clone;
    const target = modal.find('#followings-list')
    modal.find('.modal-body').addClass('p-2');
    modal.find('.modal-dialog').addClass('modal-dialog-scrollable');
    modal.find('.modal-footer').remove();

    $.get('/api/users/followings?id=' + user_id, function (data) {
        if (data.message == true && data.followings.length > 0) {
            for (let count = 0; count < data.followings.length; count++) {
                let ud = data.followings[count];
                let username = ud.username;
                let fullname = ud.fullname;
                let avatar = ud.avatar;
                target.append(clone);
                target.find('#username').text('@' + username);
                target.find('#username').attr('id', 'username' + count);
                target.find('#fullname').text(fullname);
                target.find('#fullname').attr('id', 'fullname' + count);
                target.find('#user-avatar').attr('src', avatar);
                target.find('#user-avatar').attr('id', 'user-avatar' + count);
                target.find('#link').attr('href', '/profile/' + username);
                target.find('#link').attr('id', 'link' + count);
            }
        } else {
            $('<h5 class="text-center my-5"><i class="bi bi-exclamation-triangle me-2"></i>No followings found</h5>').prependTo(modal.find('.modal-body').empty())
        }
    }).fail(function () {
        console.error("Can't get followings for user: " + user_id);
    });
});
