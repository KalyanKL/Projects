<!DOCTYPE html>
<html>
<head>
    <title>Real-Time Chat Application</title>
</head>
<body>
    <div id="app">
        <h1>Welcome to the Chat Room!</h1>
        
        <div class="chat-box">
            <div class="message-container">
                <div class="message" v-for="message in messages">
                    <strong>@{{ message.username }}:</strong> @{{ message.text }}
                </div>
            </div>
            
            <div class="input-box">
                <input type="text" v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type your message">
                <button @click="sendMessage">Send</button>
            </div>
        </div>
    </div>

    <!-- Include Vue.js from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    
    <!-- Include PieSocket JavaScript library -->
    <script src="https://cdn.piesocket.com/piesocket-0.2.1.min.js"></script>
    
    <!-- Include JavaScript to handle chat logic -->
    <script>
        new Vue({
            el: '#app',
            data: {
                messages: [],
                newMessage: '',
            },
            mounted() {
                // Initialize PieSocket with your API key and channel name
                const pie = new PieSocket('gWqbcVbPX9DECrT862I4lTCIfqlSgBcM6FvLsiwP', 'ChatApp');
                
                // Subscribe to the chat channel
                const channel = pie.subscribe();
                
                // Handle incoming messages
                channel.onMessage(message => {
                    this.messages.push({ text: message.text, username: message.username });
                });
            },
            methods: {
                sendMessage() {
                    if (this.newMessage.trim() === '') return;
                    
                    // Emit the new message to the WebSocket channel
                    const message = {
                        text: this.newMessage,
                        username: 'You', // Replace with the actual username
                    };
                    
                    // Send the message using PieSocket
                    channel.send(message);
                    
                    // Add the message locally
                    this.messages.push(message);
                    
                    // Clear the input
                    this.newMessage = '';
                }
            }
        });
    </script>
</body>
</html>
