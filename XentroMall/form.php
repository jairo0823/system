<!DOCTYPE html>
<html>
<head>
    <title>PHP SMS</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("smsForm");
            const messageBox = document.getElementById("responseMessage");

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                messageBox.textContent = "";
                messageBox.style.color = "";

                const formData = new FormData(form);

                fetch("send.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        messageBox.style.color = "green";
                    } else {
                        messageBox.style.color = "red";
                    }
                    messageBox.textContent = data.message;
                })
                .catch(error => {
                    messageBox.style.color = "red";
                    messageBox.textContent = "Error sending message.";
                });
            });
        });
    </script>
</head>
<body>

    <h1>PHP SMS Notification</h1>
    <form id="smsForm" method="post" action="send.php">
        <label for="number">Number</label>
        <input type="text" name="number" id="number" placeholder="+639XXXXXXXXX" required pattern="^\+639\d{9}$" title="Enter a valid Philippine mobile number starting with +639" />
        
        <label for="message">Message</label>
        <textarea name="message" id="message" placeholder="Type your message here..." required></textarea>
        
        <fieldset>
            <legend>Provider</legend>
            <label>
                <input type="radio" name="provider" value="infobip" /> Infobip
            </label>
            <br />
            <label>
                <input type="radio" name="provider" value="twilio" checked /> Twilio
                <small style="color: blue; display: block;">Using Twilio for SMS notification</small>
            </label>
        </fieldset>
        <button type="submit">Send</button>
    </form>

    <div id="responseMessage" style="margin-top: 1em; font-weight: bold;"></div>

</body>
