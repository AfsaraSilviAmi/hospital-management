function sendMessage() {
    const input = document.getElementById("userInput").value;

    fetch("http://127.0.0.1:5000/chat", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ message: input })
    })
    .then(res => res.json())
    .then(data => {
        const chatlog = document.getElementById("chatlog");

        // Create user message
        const userMsg = document.createElement("p");
        userMsg.innerHTML = `<strong>You:</strong> ${input}`;
        chatlog.appendChild(userMsg);

        // Create bot message
        const botMsg = document.createElement("p");
        botMsg.innerHTML = `<strong>Bot:</strong> ${data.response}`; // this allows <a> to work
        chatlog.appendChild(botMsg);

        // Clear input
        document.getElementById("userInput").value = '';
    });
}
