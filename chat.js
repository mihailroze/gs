$(document).ready(function() {
    function loadChat() {
        let serviceId = $("#chat").data("service-id");
        let chatSessionId = $("#chat").data("chat-session-id");
        $.ajax({
            url: '../logic/load_chat.php',
            method: 'GET',
            data: { service_id: serviceId, chat_session_id: chatSessionId },
            success: function(data) {
                $("#chat").html(data);
            }
        });
    }

    $("#chatForm").on("submit", function(e) {
        e.preventDefault();
        let message = $("#message").val();
        let serviceId = $("#chat").data("service-id");
        let chatSessionId = $("#chat").data("chat-session-id");
        $.ajax({
            url: '../logic/send_message.php',
            method: 'POST',
            data: {
                service_id: serviceId,
                chat_session_id: chatSessionId,
                message: message
            },
            success: function() {
                $("#message").val('');
                loadChat();
            }
        });
    });

    loadChat();
    setInterval(loadChat, 5000); // Обновление чата каждые 5 секунд
});
