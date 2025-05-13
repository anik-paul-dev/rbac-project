$(document).ready(function() {
    console.log('RBAC System loaded.');

    // Handle user approval
    $(document).on('click', '.approve-user', function() {
        let userId = $(this).data('id');
        $.post('approve_user.php', { id: userId, action: 'approve' }, function(response) {
            if (response.success) {
                $('tr[data-user-id="' + userId + '"]').remove();
                alert('User approved successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });

    // Handle user rejection
    $(document).on('click', '.reject-user', function() {
        let userId = $(this).data('id');
        $.post('approve_user.php', { id: userId, action: 'reject' }, function(response) {
            if (response.success) {
                $('tr[data-user-id="' + userId + '"]').remove();
                alert('User rejected successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });
});