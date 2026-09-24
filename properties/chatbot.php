<!-- Chatbot Toggle Button -->
<button class="chatbot-toggle" id="chatbotToggle">
    <i class="fas fa-comment-dots"></i>
    <span class="chatbot-toggle-text">Chat with us</span>
    <span class="chatbot-badge">1</span>
</button>

<!-- Chatbot Window -->
<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chatbot-header-text">
                <strong>Triple K Assistant</strong>
                <span class="chatbot-status online">
                    <span class="status-dot"></span> Online
                </span>
            </div>
        </div>
        <div class="chatbot-header-actions">
            <button class="chatbot-minimize" id="chatMinimize">
                <i class="fas fa-minus"></i>
            </button>
            <button class="close-btn" id="chatClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <div class="chatbot-messages" id="chatMessages">
        <!-- Messages will be populated by JavaScript -->
    </div>
    
    <div class="chatbot-typing" id="chatTyping">
        <span></span>
        <span></span>
        <span></span>
    </div>
    
    <div class="chatbot-quick-replies" id="quickReplies">
        <button class="quick-reply" data-message="Show me properties">🏠 Properties</button>
        <button class="quick-reply" data-message="What's the price range?">💰 Prices</button>
        <button class="quick-reply" data-message="Where are your properties located?">📍 Locations</button>
        <button class="quick-reply" data-message="Contact Triple K">📞 Contact</button>
    </div>
    
    <div class="chatbot-input">
        <input type="text" id="chatInput" placeholder="Type your message..." autocomplete="off">
        <button id="chatSendBtn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatbotWindow = document.getElementById('chatbotWindow');
    const chatClose = document.getElementById('chatClose');
    const chatMinimize = document.getElementById('chatMinimize');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const chatTyping = document.getElementById('chatTyping');
    const quickReplies = document.getElementById('quickReplies');

    let isOpen = false;
    let isMinimized = false;
    let isProcessing = false;

    // Get time-based greeting
    function getTimeGreeting() {
        const hour = new Date().getHours();
        if (hour >= 5 && hour < 12) {
            return 'Good morning';
        } else if (hour >= 12 && hour < 17) {
            return 'Good afternoon';
        } else if (hour >= 17 && hour < 21) {
            return 'Good evening';
        } else {
            return 'Hello';
        }
    }

    // Get random welcome message
    function getWelcomeMessage() {
        const greetings = [
            `👋 ${getTimeGreeting()}! Welcome to Triple K Properties. How can I assist you today?`,
            `🏠 ${getTimeGreeting()}! I'm your Triple K Properties assistant. Looking for a property?`,
            `✨ ${getTimeGreeting()}! Welcome! I'm here to help you find your dream property.`,
            `🌟 ${getTimeGreeting()}! Ready to explore our premium properties across Kenya?`
        ];
        return greetings[Math.floor(Math.random() * greetings.length)];
    }

    // Display initial welcome
    function showInitialWelcome() {
        const welcomeMsg = getWelcomeMessage();
        addMessage('bot', welcomeMsg);
    }

    // Add message to chat
    function addMessage(type, text, isHTML = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${type}`;
        
        if (isHTML) {
            messageDiv.innerHTML = text;
        } else {
            // Preserve line breaks
            messageDiv.textContent = text;
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Show typing indicator
    function showTyping() {
        chatTyping.classList.add('active');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Hide typing indicator
    function hideTyping() {
        chatTyping.classList.remove('active');
    }

    // Send message to server
    async function sendMessage(message) {
        if (!message.trim() || isProcessing) return;

        isProcessing = true;
        
        // Add user message
        addMessage('user', message);
        chatInput.value = '';
        chatInput.disabled = true;
        chatSendBtn.disabled = true;

        // Show typing
        showTyping();

        try {
            const formData = new FormData();
            formData.append('message', message);
            formData.append('session_id', getSessionId());

            const response = await fetch('includes/chatbot-handler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            // Hide typing
            hideTyping();

            if (data.success) {
                addMessage('bot', data.response);
            } else {
                addMessage('bot', 'I apologize, but I\'m having trouble connecting. Please try again later.');
            }
        } catch (error) {
            hideTyping();
            console.error('Chatbot Error:', error);
            addMessage('bot', 'I apologize, but I\'m having trouble connecting. Please try again later.');
        } finally {
            isProcessing = false;
            chatInput.disabled = false;
            chatSendBtn.disabled = false;
            chatInput.focus();
        }
    }

    // Get or create session ID
    function getSessionId() {
        let sessionId = localStorage.getItem('chat_session_id');
        if (!sessionId) {
            sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('chat_session_id', sessionId);
        }
        return sessionId;
    }

    // Toggle chat window
    function toggleChat() {
        isOpen = !isOpen;
        chatbotWindow.classList.toggle('active', isOpen);
        chatbotToggle.classList.toggle('active', isOpen);
        
        if (isOpen) {
            const badge = document.querySelector('.chatbot-badge');
            if (badge) badge.remove();
            chatInput.focus();
        }
    }

    // Minimize chat
    function minimizeChat() {
        isMinimized = !isMinimized;
        chatbotWindow.classList.toggle('minimized', isMinimized);
        const icon = document.querySelector('.chatbot-header-actions .chatbot-minimize i');
        if (isMinimized) {
            icon.className = 'fas fa-expand';
        } else {
            icon.className = 'fas fa-minus';
        }
    }

    // Event listeners
    chatbotToggle.addEventListener('click', toggleChat);

    chatClose.addEventListener('click', function() {
        isOpen = false;
        chatbotWindow.classList.remove('active');
        chatbotToggle.classList.remove('active');
    });

    chatMinimize.addEventListener('click', minimizeChat);

    chatSendBtn.addEventListener('click', function() {
        sendMessage(chatInput.value);
    });

    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage(chatInput.value);
        }
    });

    // Quick reply buttons
    document.querySelectorAll('.quick-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            sendMessage(this.dataset.message);
        });
    });

    // Show initial welcome
    showInitialWelcome();

    // Auto-open after 3 seconds if not previously opened
    if (!localStorage.getItem('chat_opened')) {
        setTimeout(() => {
            if (!isOpen) {
                toggleChat();
                localStorage.setItem('chat_opened', 'true');
            }
        }, 3000);
    }
});
</script>