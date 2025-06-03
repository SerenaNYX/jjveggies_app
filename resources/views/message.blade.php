@extends('layouts.app')

@section('content')

<!-- IS THIS PAGE STILL NEEDED??? -->

<div class="container">
    <h1 class="text-center">Messages</h1>
    <p class="section-description text-center">Chat with our customer service.</p>
</div>

    <div id="messages">
        <!-- Messages will be loaded here dynamically 
    </div>
    <form id="sendMessageForm">
        @csrf
        <input type="hidden" name="receiver_id" value="/* Receiver ID */">
        <textarea name="message" placeholder="Type your message here..." required></textarea>
        <button type="submit">Send</button>
    </form>-->
</div>

<script>
document.getElementById('sendMessageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/messages/send', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Message sent:', data);
        // Add the new message to the messages list dynamically
    })
    .catch(error => console.error('Error:', error));
});

function loadMessages() {
    fetch('/messages')
    .then(response => response.json())
    .then(data => {
        const messagesContainer = document.getElementById('messages');
        messagesContainer.innerHTML = '';
        data.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.textContent = `${message.sender.name}: ${message.message}`;
            messagesContainer.appendChild(messageElement);
        });
    })
    .catch(error => console.error('Error:', error));
}

// Load messages when the page loads
document.addEventListener('DOMContentLoaded', loadMessages);
</script>
@endsection
