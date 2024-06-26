$(document).ready(function() {
    function searchGame(game) {
        $.ajax({
            url: '../logic/get_game_services.php',
            method: 'GET',
            data: { game: game },
            success: function(data) {
                $("#services").html(data);
            }
        });
    }

    function getServiceOffers(categoryId) {
        $.ajax({
            url: '../logic/get_service_offers.php',
            method: 'GET',
            data: { category_id: categoryId },
            success: function(data) {
                $("#offers").html(data);
            }
        });
    }

    $("#search").on("input", function() {
        let query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: '../logic/search.php',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    $("#suggestions").html(data);
                }
            });
        } else {
            $("#suggestions").empty();
        }
    });

    $(document).on("click", ".suggestion", function() {
        let game = $(this).text();
        $("#search").val(game);
        $("#suggestions").empty();
        searchGame(game);
    });

    $(document).on("click", ".category", function() {
        let categoryId = $(this).data('id');
        getServiceOffers(categoryId);
    });

    $("#searchButton").on("click", function() {
        let game = $("#search").val();
        searchGame(game);
    });
});
