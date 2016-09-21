var messages = [];
 
// connect to the socket server
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
	console.log('Connected to server:', conn);
}
 
conn.onerror = function(e) {
	console.log('Error: Could not connect to server.');
}
 
conn.onclose = function(e) {
	console.log('Connection closed');
}
 
// handle new message received from the socket server
conn.onmessage = function(e) {
	// message is data property of event object
	var message = JSON.parse(e.data);
	console.log('message', message);
 
	// add to message list
	var li = '<li>' + message.text + '</li>';
	$('.message-list').append(li);
}
 
// attach onSubmit handler to the form
$(function() {
	$('.message-form').on('submit', function(e) {
		// prevent form submission which causes page reload
		e.preventDefault();
 
		// get the input
		var input = $(this).find('input');
 
		// get message text from the input
		var message = {
			type: 'message',
			text: input.val()
		};
 
		// clear the input
		input.val('');
 
		// send message to server
		conn.send(JSON.stringify(message));
	});
});
