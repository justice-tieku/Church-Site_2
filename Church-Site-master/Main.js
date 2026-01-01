// Set current year in footer
document.getElementById('currentYear').textContent = new Date().getFullYear();

// Welcome message typing animation
let currentMessage = 1;
const totalMessages = 4;
let messageInterval;
let typingTimeout;
let isTyping = false;

const messages = [
    {
        title: "Welcome to Cross Passion International Church",
        subtitle: "Where faith meets community and love transforms lives"
    },
    {
        title: "Join Our Spiritual Journey",
        subtitle: "Experience God's presence through worship, fellowship, and service"
    },
    {
        title: "Discover Your Purpose",
        subtitle: "Find meaning, hope, and direction in our vibrant church family"
    },
    {
        title: "Connect with God Daily",
        subtitle: "Access our weekly messages and grow in your relationship with Christ"
    }
];

function typeText(element, text, speed = 50) {
    return new Promise(resolve => {
        let i = 0;
        element.textContent = '';
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                typingTimeout = setTimeout(type, speed);
            } else {
                resolve();
            }
        }
        type();
    });
}

async function showMessage(messageNum) {
    if (isTyping) return;
    isTyping = true;

    const messageData = messages[messageNum - 1];
    const titleElement = document.querySelector(`#message${messageNum} h1`);
    const subtitleElement = document.querySelector(`#message${messageNum} p`);

    // Update indicators
    document.querySelectorAll('.indicator').forEach(ind => {
        ind.classList.remove('active');
    });
    document.querySelector(`.indicator[data-message="${messageNum}"]`).classList.add('active');

    // Hide all messages
    document.querySelectorAll('.welcome-message').forEach(msg => {
        msg.classList.remove('active');
    });

    // Show current message
    document.getElementById(`message${messageNum}`).classList.add('active');

    // Type the title
    await typeText(titleElement, messageData.title, 80);
    // Type the subtitle
    await typeText(subtitleElement, messageData.subtitle, 40);

    isTyping = false;
    currentMessage = messageNum;
}

function nextMessage() {
    if (isTyping) return;
    currentMessage = currentMessage >= totalMessages ? 1 : currentMessage + 1;
    showMessage(currentMessage);
}

function startMessageRotation() {
    messageInterval = setInterval(nextMessage, 6000); // Change every 6 seconds
}

function stopMessageRotation() {
    clearInterval(messageInterval);
    clearTimeout(typingTimeout);
}

// Add click handlers for indicators
document.addEventListener('DOMContentLoaded', function() {
    fetchMessages();

    // Initialize welcome messages if they exist
    if (document.querySelector('.welcome-section')) {
        // Start with first message
        showMessage(1).then(() => {
            startMessageRotation();
        });

        document.querySelectorAll('.indicator').forEach(indicator => {
            indicator.addEventListener('click', function() {
                stopMessageRotation();
                const messageNum = parseInt(this.dataset.message);
                showMessage(messageNum).then(() => {
                    startMessageRotation(); // Restart rotation
                });
            });
        });
    }
});

function fetchMessages() {
    fetch('api/messages.php')
        .then(response => response.json())
        .then(data => displayMessages(data))
        .catch(error => {
            console.error('Error:', error);
            // Fallback to mock data if API fails
            const mockMessages = [{
                    id: 1,
                    title: "The Power of Faith",
                    speaker: "Pastor John Smith",
                    date: "2023-06-12",
                    description: "Exploring how faith can move mountains in our daily lives.",
                    filename: "message1.mp3"
                },
                {
                    id: 2,
                    title: "Love Thy Neighbor",
                    speaker: "Reverend Sarah Johnson",
                    date: "2023-06-05",
                    description: "Understanding the biblical command to love one another.",
                    filename: "message2.mp3"
                }
            ];
            displayMessages(mockMessages);
        });
}

function displayMessages(messages) {
    const container = document.getElementById('messagesContainer');

    if (messages.length === 0) {
        container.innerHTML = '<p>No messages available yet. Please check back later.</p>';
        return;
    }

    container.innerHTML = messages.map(message => `
        <div class="message-card">
            <div class="message-info">
                <h3>${message.title}</h3>
                <p class="speaker">By: ${message.speaker}</p>
                <p class="date">Date: ${formatDate(message.date)}</p>
                <p class="description">${message.description}</p>
            </div>
            <div class="message-actions">
                <audio controls>
                    <source src="uploads/${message.filename}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <a href="uploads/${message.filename}" download class="download-btn">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    `).join('');
}

// Handle contact form submission
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Simple form validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !subject || !message) {
                alert('Please fill in all fields.');
                return;
            }

            // In a real implementation, send to server
            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
        });
    }
});

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}