@extends('layouts.app')

@section('title', 'Elliana D - Virtual Nurse Assistant')

@section('content')
<div class="container-fluid py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <i class="fa fa-user-nurse text-pink-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Elliana D</h1>
                        <p class="text-pink-100">Your Virtual Nurse Assistant</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Online
                    </span>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Chat Messages Area -->
            <div id="chatMessages" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900 scroll-smooth">
                <!-- Welcome Message -->
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa fa-user-nurse text-white text-sm"></i>
                    </div>
                    <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                        <p class="text-gray-700 dark:text-gray-200">
                            👋 Hello! I'm <strong>Elliana D</strong>, your virtual nurse assistant. I'm here to help you with:
                        </p>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                            <li>✅ Booking appointments</li>
                            <li>✅ Answering medical questions</li>
                            <li>✅ General inquiries</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            How can I assist you today?
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chat Input Area -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
                <form id="chatForm" class="flex space-x-2">
                    @csrf
                    <input 
                        type="text" 
                        id="messageInput" 
                        name="message"
                        placeholder="Type your message here..." 
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        autocomplete="off"
                    >
                    <button 
                        type="submit" 
                        id="sendButton"
                        class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fa fa-paper-plane mr-2"></i>
                        Send
                    </button>
                </form>
                
                <!-- Quick Actions -->
                <div class="mt-3 flex flex-wrap gap-2 items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        <button 
                            onclick="sendQuickMessage('I want to book an appointment')"
                            class="px-3 py-1 text-xs bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300 rounded-full hover:bg-pink-200 dark:hover:bg-pink-800 transition"
                        >
                            📅 Book Appointment
                        </button>
                        <button 
                            onclick="sendQuickMessage('I have a medical question')"
                            class="px-3 py-1 text-xs bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition"
                        >
                            🏥 Medical Question
                        </button>
                        <button 
                            onclick="sendQuickMessage('What are your operating hours?')"
                            class="px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition"
                        >
                            ⏰ Hours
                        </button>
                    </div>
                    <button 
                        onclick="clearChat()"
                        class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                        title="Clear chat history"
                    >
                        🗑️ Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let isLoading = false;

// Send quick message
function sendQuickMessage(message) {
    document.getElementById('messageInput').value = message;
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
}

// Add message to chat
function addMessage(message, isUser = true) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start space-x-3 ${isUser ? 'flex-row-reverse space-x-reverse' : ''}`;
    
    const avatar = document.createElement('div');
    avatar.className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${
        isUser 
            ? 'bg-gradient-to-br from-blue-400 to-blue-600' 
            : 'bg-gradient-to-br from-pink-400 to-purple-500'
    }`;
    avatar.innerHTML = isUser 
        ? '<i class="fa fa-user text-white text-sm"></i>'
        : '<i class="fa fa-user-nurse text-white text-sm"></i>';
    
    const content = document.createElement('div');
    content.className = `flex-1 rounded-lg p-4 shadow-sm ${
        isUser 
            ? 'bg-blue-500 text-white' 
            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'
    }`;
    
    // Format message (preserve line breaks, basic markdown)
    const formattedMessage = formatMessage(message);
    content.innerHTML = `<div class="whitespace-pre-wrap">${formattedMessage}</div>`;
    
    // Add timestamp
    const timestamp = document.createElement('div');
    timestamp.className = `text-xs mt-2 ${isUser ? 'text-blue-100' : 'text-gray-400 dark:text-gray-500'}`;
    timestamp.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    content.appendChild(timestamp);
    
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(content);
    chatMessages.appendChild(messageDiv);
    
    // Scroll to bottom with smooth animation
    setTimeout(() => {
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
    }, 100);
}

// Format message text (basic markdown support)
function formatMessage(message) {
    // Escape HTML first
    let formatted = message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    // Bold (**text**)
    formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    
    // Italic (*text*)
    formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
    
    // Line breaks
    formatted = formatted.replace(/\n/g, '<br>');
    
    // Bullet points (- item)
    formatted = formatted.replace(/^[-•]\s+(.+)$/gm, '<li>$1</li>');
    formatted = formatted.replace(/(<li>.*<\/li>)/s, '<ul class="list-disc ml-4 mt-2">$1</ul>');
    
    return formatted;
}

// Clear chat
function clearChat() {
    if (confirm('Are you sure you want to clear the chat history?')) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-user-nurse text-white text-sm"></i>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <p class="text-gray-700 dark:text-gray-200">
                        👋 Hello! I'm <strong>Elliana D</strong>, your virtual nurse assistant. I'm here to help you with:
                    </p>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-300">
                        <li>✅ Booking appointments</li>
                        <li>✅ Answering medical questions</li>
                        <li>✅ General inquiries</li>
                    </ul>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        How can I assist you today?
                    </p>
                </div>
            </div>
        `;
    }
}

// Show typing indicator
function showTyping() {
    const chatMessages = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.className = 'flex items-start space-x-3';
    typingDiv.innerHTML = `
        <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa fa-user-nurse text-white text-sm"></i>
        </div>
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
            <div class="flex space-x-1">
                <div class="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Remove typing indicator
function removeTyping() {
    const typing = document.getElementById('typingIndicator');
    if (typing) {
        typing.remove();
    }
}

// Handle form submission
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (isLoading) return;
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message
    addMessage(message, true);
    input.value = '';
    
    // Show typing indicator
    showTyping();
    isLoading = true;
    document.getElementById('sendButton').disabled = true;
    
    try {
        const response = await fetch('{{ route("ai.elliana-d.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        removeTyping();
        
        if (data.success) {
            addMessage(data.data.response, false);
            
            // Handle actions (like appointment booking confirmation)
            if (data.data.actions && data.data.actions.appointment_id) {
                // Show success notification
                setTimeout(() => {
                    alert('✅ Appointment booked successfully! Check your appointments page for details.');
                }, 500);
            }
        } else {
            addMessage('I apologize, but I encountered an error. Please try again.', false);
        }
    } catch (error) {
        console.error('Error:', error);
        removeTyping();
        addMessage('I apologize, but I encountered a connection error. Please try again.', false);
    } finally {
        isLoading = false;
        document.getElementById('sendButton').disabled = false;
        input.focus();
    }
});

// Enable Enter key to send (Shift+Enter for new line)
document.getElementById('messageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
});

// Focus input on load
document.getElementById('messageInput').focus();
</script>
@endpush
@endsection

