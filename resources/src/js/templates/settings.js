// Remove user session
$('.btn-remove-session').on('click', function () {
    let session_id = $(this).parent().attr('data-session-id');
    let isArchived = $(this).attr('data-archived');
    let $this = $(this);
    let spinner = `<div class="spinner-border spinner-border-sm me-2" role="status"></div>`;
    let message = "<p>Are you sure you want to remove this session? <br>This action cannot be undone.</p>";

    let d = new Dialog('<i class="bi bi-trash me-2"></i>Remove Session', message);
    d.setButtons([
        {
            'name': "Cancel",
            "class": "btn-secondary",
            "onClick": function (event) {
                $(event.data.modal).modal('hide');
            }
        },
        {
            'name': "Remove",
            "class": "btn-danger",
            "onClick": function (event) {
                let removeBtn = $(event.target);
                removeBtn.prop('disabled', true);
                removeBtn.html(spinner + "Removing...");

                $.ajax({
                    url: '/api/users/settings/remove_session',
                    type: 'POST',
                    data: {
                        id: session_id
                    },
                    success: function (data, textStatus) {
                        $this.closest('.list-group-item').remove();
                        $(event.data.modal).modal('hide');
                        if (data.redirect !== undefined) {
                            location.reload();
                        }
                        showToast("Photogram", "Just Now", "Session removed successfully.");
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        showToast("Photogram", "Just Now", errorThrown);
                    }
                });
            }
        }
    ]);
    d.show();
});
