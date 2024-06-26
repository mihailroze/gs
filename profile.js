$(document).ready(function() {
    $('#proposeServiceButton').click(function() {
        $('#proposeServiceForm').toggle();
    });

    $('#game').change(function() {
        let gameId = $(this).val();
        if (gameId) {
            $.ajax({
                url: '../logic/get_categories.php',
                method: 'GET',
                data: { game_id: gameId },
                success: function(data) {
                    $('#category').html(data);
                    $('#category').prop('disabled', false);
                }
            });
        } else {
            $('#category').html('<option value="">Select a category</option>');
            $('#category').prop('disabled', true);
        }
    });
});
