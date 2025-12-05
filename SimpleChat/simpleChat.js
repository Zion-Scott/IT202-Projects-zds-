const chatForm = document.getElementById("chatForm");


const userID = document.getElementById("userID");
const userName = document.getElementById("userName");
const password = document.getElementById("password");
const ChatContent = document.getElementById("ChatContent");


window.onload = function () {
    const chatBox   = document.getElementById("ChatContent");
    const listenBtn = document.getElementById("listenBtn");

    //send chat on every keyup
    chatBox.addEventListener("keyup", sendChat);

    // listen on button click
    listenBtn.addEventListener("click", listenChat);

    //auto-poll every 2 seconds instead of relying on button
    //setInterval(listenChat, 2000);
};

function sendChat() {
    const userName    = document.getElementById("userName").value;
    const password    = document.getElementById("password").value;
    const ChatContent = document.getElementById("ChatContent").value;
    const statusDiv   = document.getElementById("updateStatus");

    if (!userName || !password) {
        statusDiv.textContent = "Enter name and password first.";
        return;
    }

    const params = "userName="    + encodeURIComponent(userName) +
                   "&password="   + encodeURIComponent(password) +
                   "&ChatContent="+ encodeURIComponent(ChatContent);

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const resp = xhr.responseText.trim();
                if (resp === "OK") {
                    statusDiv.textContent = ""; // clear warnings
                } else if (resp === "NO_MATCH") {
                    statusDiv.textContent = "name or password not recognized, nothing updated.";
                } else {
                    statusDiv.textContent = resp; // display any other server message
                }
            } else {
                statusDiv.textContent = "Error contacting server.";
            }
        }
    };
    xhr.open("POST", "updateChat.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(params);
}

function listenChat() {
    const listenName = document.getElementById("listenName").value;
    const listenArea = document.getElementById("listenArea");

    if (!listenName) {
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            listenArea.value = xhr.responseText;
        }
    };
    xhr.open("GET", "getChat.php?listenName=" + encodeURIComponent(listenName), true);
    xhr.send();
}
