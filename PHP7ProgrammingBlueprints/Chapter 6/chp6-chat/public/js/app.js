var connection = new WebSocket('ws://localhost/chat');

connection.onopen = function (e) {
    console.log('connection established');
};

setTimeout(function() {
    connection.send('ping');
}, 30000);

connection.onclose = function (e) {
    console.error(e);
    setTimeout(function () {
        connection = new WebSocket('ws://localhost/chat');
    }, 5000)
};

connection.onmessage = function (e) {
    if (e.data == 'pong') {
        return;
    }

    var data = JSON.parse(e.data);

    console.log('received message');
    console.log(data);

    appendMessage(data, false);
};

var appendMessage = function (message, sentByMe) {
    var text = sentByMe ? 'Sent at' : 'Received at';
    var html = $('<div class="msg">' + text +' <span class="date"></span> by <span class="author"></span>: <span class="text"></span></div>');

    html.find('.date').text(new Date().toLocaleTimeString());
    html.find('.author').text(message.author);
    html.find('.text').text(message.msg);

    $('#messages').prepend(html);
};

$(document).ready(function () {
    $('#submit').click(function () {
        var message = $('#message').val();
        console.log('sending message');
        connection.send(JSON.stringify({msg: message}));

        appendMessage({
            author: "me",
            msg: message
        }, true);
    })
});