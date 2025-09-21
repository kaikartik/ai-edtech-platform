// --- Simple AI Chatbot ---
const chatLog = document.getElementById("chat-log");
const chatInput = document.getElementById("chat-input");
const sendBtn = document.getElementById("send-btn");

// Replace with your Gemini API key
const API_KEY = "AIzaSyBXHHKlrqOou8lnvDHJ16oH9zfiau2OaLc";
const TEXT_API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${API_KEY}`;

// --- Markdown Cleanup Function ---
function cleanMarkdown(text) {
    return text
        .replace(/#+\s/g, "")                  // remove headings (#, ##, ###)
        .replace(/\*\*(.*?)\*\*/g, "$1")       // remove bold markers
        .replace(/\*(.*?)\*/g, "$1")           // remove italic markers
        .replace(/`{1,3}(.*?)`{1,3}/g, "$1")  // remove inline and block code markers
        .replace(/_/g, "");                    // remove underscores
}

// --- Add message to chat ---
function addMessage(text, className, isMarkdown = false) {
    const messageDiv = document.createElement("div");
    messageDiv.className = className;

    // Clean markdown for bot messages
    if (className === "bot-message") {
        text = cleanMarkdown(text);
    }

    // Render markdown if needed
    messageDiv.innerHTML = isMarkdown ? marked.parse(text) : text;

    chatLog.appendChild(messageDiv);
    chatLog.scrollTop = chatLog.scrollHeight;
}

// --- Send message to Gemini API ---
async function sendMessage() {
    const userMessage = chatInput.value.trim();
    if (!userMessage) return;

    // Add user message to chat
    addMessage("You: " + userMessage, "user-message");
    chatInput.value = "";
    sendBtn.disabled = true;

    // Show loading spinner
    document.getElementById("loading-spinner").classList.remove("hidden");

    try {
        const payload = { contents: [{ parts: [{ text: userMessage }] }] };

        const response = await fetch(TEXT_API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        console.log(result); // Check API response structure

        const botReply =
            result?.candidates?.[0]?.content?.parts?.[0]?.text ||
            "Sorry, I didn’t get that.";

        addMessage("Bot: " + cleanMarkdown(botReply), "bot-message");
    } catch (error) {
        console.error("Error:", error);
        addMessage("⚠ Something went wrong. Try again later.", "bot-message");
    } finally {
        // Hide spinner and re-enable send button
        document.getElementById("loading-spinner").classList.add("hidden");
        sendBtn.disabled = false;
    }
}

// --- Event listeners ---
sendBtn.addEventListener("click", sendMessage);
chatInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") sendMessage();
});

// chat.js (loaded inside chat.html iframe)
document.addEventListener('DOMContentLoaded', () => {
    // Back button: hide overlay and redirect parent
    const backBtn = document.querySelector('#back');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            if (window.parent) {
                // Hide the overlay iframe in parent
                const overlay = window.parent.document.querySelector('#overlaySite');
                if (overlay) overlay.style.display = 'none';

                // Redirect parent to edu.html
                window.parent.location.href = 'edu.html';
            }
        });
    }

    // Home/Icon button: just redirect parent
    const iconBtn = document.querySelector('#icon');
    if (iconBtn) {
        iconBtn.addEventListener('click', () => {
            if (window.parent) {
                window.parent.location.href = 'edu.html';
            }
        });
    }
});


