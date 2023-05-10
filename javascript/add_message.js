const messageButton = document.querySelector("#message-form button")
if (messageButton) {
    messageButton.addEventListener('click', submitNewMessage)
}
async function postNewMessage(messageText, ticketID) {
    const json = await postData('../api/api_add_message.php', {
        'message': messageText,
        'ticketID': ticketID,
    })
    return json
}
async function submitNewMessage(event) {
    event.preventDefault()
    
    const messagesList = getIndividualTicketMessagesList()
    const messageInput = document.querySelector('#message-form textarea')
    
    const messageText = messageInput.value
    const ticketID = getIndividualTicketID()
    console.log(messageText)
    messageInput.value = ""

    const json = await postNewMessage(messageText, ticketID)
    console.log(json)
    if (json['error']) {
        console.error(json['error'])
    }
    else if (json['success']) {    
        addMessageToDOM(messagesList, json['text'], json['username'], json['date'])
    }
}

function addMessageToDOM(messagesList, messageText, username, dateText) {
    const newMessage = document.createElement("article")
    newMessage.classList.add("message")
    newMessage.classList.add("self")    // Note: current user can only add messages from himself
    
    const header = document.createElement("header")
    
    const user = document.createElement("span")
    user.classList.add("user")
    user.textContent = username
    header.appendChild(user)
    
    const date = document.createElement("span")
    date.classList.add("date")
    date.textContent = dateText
    header.appendChild(date)

    const text = document.createElement("p")
    text.classList.add("text")
    text.textContent = messageText

    newMessage.appendChild(header)
    newMessage.appendChild(text)
    
    messagesList.appendChild(newMessage)
}

function getIndividualTicketID() {
    return document.querySelector('article#individual-ticket').getAttribute("data-id")
}
function getIndividualTicketMessagesList() {
    return document.querySelector("#messages-list")
}
