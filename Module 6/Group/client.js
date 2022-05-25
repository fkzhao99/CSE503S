let socketio = io.connect();

// Log in request and response
const loginBtn = document.getElementById("loginBtn");
loginBtn.addEventListener("click", loginReq, false);
let curRoom = '';
const roomsDiv = document.getElementsByClassName("rooms")[0];
roomsDiv.style.display = 'none';
const chatroomDiv = document.getElementsByClassName("chatroom")[0];
chatroomDiv.style.display = 'none';

function loginReq() {
    let username = document.getElementById("username").value;
    if(username == '') {
        alert("Username must not be null!");
    }
    else {
        socketio.emit("loginReq", {message: username});
    }
}

socketio.on("login",function(data) {
    document.getElementsByClassName("login")[0].style.display = 'none';
    document.getElementsByClassName("rooms")[0].style.display = 'inline-block';
    document.getElementById("curUser").innerHTML = data['message'];
 });

socketio.on("loginError",function(data) {
    alert(data['message']);
});

// Create a chatroom
newroomBtn = document.getElementById("newroomBtn");
newroomBtn.addEventListener("click", newroomReq, false);
function newroomReq() {
    let newroom = document.getElementById("newroom").value;
    if(newroom == '') {
        alert("Room name must not be null!");
    }
    else {
        let username = document.getElementById("curUser").innerHTML;
        let newpwd = document.getElementById("newpwd").value;
        document.getElementById("newroom").value = '';
        document.getElementById("newpwd").value = '';
        socketio.emit("newroomReq", {message: [newroom, newpwd, username]});
    }
}

socketio.on("newroomError", function(data) {
    alert(data['message']);
})

// Show room list
socketio.on("showRooms", function(data) {
    const rooms = data['message'];
    const roomlist = document.getElementById("roomlist");
    roomlist.innerHTML = '';
    for(let roomname in rooms) {
        let li = document.createElement("li");
        li.setAttribute('id', 'room_' + roomname);
        li.innerHTML = roomname;
        li.style.cursor = 'pointer';
        li.addEventListener("click", joinRoomReq, false);
        roomlist.append(li);
    }
})

// Show user list
function showUsers(curUsers, creator) {
    const userlist = document.getElementsByClassName("userlist")[0];
    userlist.innerHTML = '';
    // If user is the creator of the room, show kick and ban button
    if(creator == document.getElementById("curUser").innerHTML) {
        for(let curUser in curUsers) {
            let div = document.createElement("div");
            div.setAttribute("id", "user_" + curUsers[curUser])
            chatBtn = document.createElement("span");
            chatBtn.innerHTML = curUsers[curUser];
            div.appendChild(chatBtn);
            if(curUsers[curUser] != document.getElementById("curUser").innerHTML){
                chatBtn.style.cursor = 'pointer';
                chatBtn.addEventListener("click", chatUser, false);
                let kickBtn = document.createElement("button");
                kickBtn.innerHTML = "Kick";
                kickBtn.addEventListener("click", kickUser, false);
                let banBtn = document.createElement("button");
                banBtn.innerHTML = "Ban";
                banBtn.addEventListener("click", banUser, false);
                div.appendChild(kickBtn);
                div.appendChild(banBtn);
            }
            userlist.append(div);
        }
    }
    else {
        for(let curUser in curUsers) {
            let div = document.createElement("div");
            div.setAttribute("id", "user_" + curUsers[curUser])
            chatBtn = document.createElement("span");
            chatBtn.innerHTML = curUsers[curUser];
            div.appendChild(chatBtn);
            if(curUsers[curUser] != document.getElementById("curUser").innerHTML){
                chatBtn.style.cursor = 'pointer';
                chatBtn.addEventListener("click", chatUser, false);
            }
            userlist.append(div);
        }
    }
    const whoToSend = document.getElementById("select");
    whoToSend.innerHTML = '';
    let newOption = document.createElement("option");
    newOption.value = "0";
    newOption.text = "All";
    whoToSend.options.add(newOption);
    for(let curUser in curUsers) {
        if(curUsers[curUser] != document.getElementById("curUser").innerHTML){
            let newOption = document.createElement("option");
            newOption.value = 'sendTo_' + curUsers[curUser];
            newOption.text = curUsers[curUser];
            whoToSend.options.add(newOption);
        }
    }
}

// join a room
function joinRoomReq(e) {
    const roomname = e.currentTarget.id.substring(5);
    if(curRoom != roomname){
        let username = document.getElementById("curUser").innerHTML;
        socketio.emit("joinRoomReq", {message: [roomname, username]});
    }

}

// Banned from joining the room
socketio.on("hasBeenBanned", function(data) {
    alert("You have been banned from joining that room!");
})

// Enter the password to join a room
socketio.on("showPwd", function(data) {
    let roomname = data['message'];
    let inputPwd = prompt("Please enter password to join this room", "");
    let username = document.getElementById("curUser").innerHTML;
    socketio.emit("inputPwd", {message: [roomname, inputPwd, username]});
})

socketio.on("reject", function(data) {
    alert("Wrong Password!");
})

// Allowed to join the room
socketio.on("roomIn", function(data) {
    const chatroom = document.getElementsByClassName("chatroom")[0];
    chatroom.style.display = 'inline-block';
    const roomname = data['message'][0];
    const curUsers = data['message'][1];
    const chatlog = data['message'][2];
    const creator = data['message'][3];
    showLogs(chatlog);
    showUsers(curUsers, creator);
    curRoom = roomname;
    document.getElementById("roomname").innerHTML = roomname;
})

// Broadcast someone leaves the room
socketio.on("userLeave", function(data) {
    const leaveUser = data['message'][0];
    const curUsers = data['message'][1];
    const logs = data['message'][2];
    const creator = data['message'][3];
    showUsers(curUsers, creator);
    showLogs(logs);
})

// Broadcast someone joins the room
socketio.on("userJoin", function(data) {
    const joinUser = data['message'][0];
    const curUsers = data['message'][1];
    const logs = data['message'][2];
    const creator = data['message'][3];
    showUsers(curUsers, creator);
    showLogs(logs);
})

// Send message
const inputBtn = document.getElementById("inputBtn");
inputBtn.addEventListener("click", sendMsg, false);
function sendMsg(){
    let inputText = document.getElementById("inputText").value;
    let username = document.getElementById("curUser").innerHTML;
    let curRoom = document.getElementById("roomname").innerHTML;
    document.getElementById("inputText").value = '';
    if(inputText == ''){
        alert("Message cannot be empty!");
        return;
    }
    let whoToSend = document.getElementById("select").value;
    // Send public message
    if(whoToSend == 0) {
        socketio.emit("sendMsgReq", {message: [inputText, username, curRoom]});
    }
    // Send private message
    else {
        whoToSend = whoToSend.substring(7);
        socketio.emit("privateMsgReq", {message: [inputText, username, curRoom, whoToSend]});
    }
}

// Update chat log when someone send a message
socketio.on("updateLog", function(data) {
    logs = data['message'];
    showLogs(logs);
})

function showLogs(logs){
    const chatlog = document.getElementById("chatlog");
    chatlog.innerHTML = '';
    for(let i=0; i<logs.length; i++) {
        let msg = logs[i];
        if(msg.length == 3){
            const inputText = msg[0];
            const username = msg[1];
            const sendTime = msg[2];
            let line1 = username + '  ' + sendTime;
            let line2 = inputText;
            let line1li = document.createElement("li");
            line1li.setAttribute('id', 'userInfo');
            line1li.textContent = line1;
            let line2li = document.createElement("li");
            line2li.setAttribute('id', 'userChat');
            line2li.textContent = line2;
            if(username == document.getElementById("curUser").innerHTML) {
                line1li.style.textAlign = 'right';
                line2li.style.textAlign = 'right';
            }
            chatlog.appendChild(line1li);
            chatlog.appendChild(line2li);
        }
        else if(msg.length == 4) {
            const inputText = msg[0];
            const username = msg[1];
            const sendTime = msg[2];
            const whoToSend = msg[3];
            if(whoToSend == document.getElementById("curUser").innerHTML || username == document.getElementById("curUser").innerHTML){
                let line1 = username + '  ' + sendTime + ' (Private)';
                let line2 = inputText;
                let line1li = document.createElement("li");
                line1li.setAttribute('id', 'userInfo');
                line1li.textContent = line1;
                let line2li = document.createElement("li");
                line2li.setAttribute('id', 'userChat');
                line2li.textContent = line2;
                if(username == document.getElementById("curUser").innerHTML) {
                    line1li.style.textAlign = 'right';
                    line2li.style.textAlign = 'right';
                }
                chatlog.appendChild(line1li);
                chatlog.appendChild(line2li);
            }
        }
        else{
            const inputText = msg[0];
            let line1 = inputText;
            let line1li = document.createElement("li");
            line1li.setAttribute('id', 'notice');
            line1li.textContent = line1;
            chatlog.appendChild(line1li);
        }
    }
    chatlogDiv = document.getElementsByClassName("chatlog")[0];
    chatlogDiv.scrollTop = chatlogDiv.scrollHeight;
}

// Chat with a user
function chatUser(e){
    const user = e.currentTarget.parentElement.id.substring(5);
    let curRoom = document.getElementById("roomname").innerHTML;
    console.log("chat with " + user);
}

// Kick a user
function kickUser(e){
    const username = e.currentTarget.parentElement.id.substring(5);
    let curRoom = document.getElementById("roomname").innerHTML;
    socketio.emit("kickUserReq", {message: [username, curRoom]});
}

socketio.on("kicked", function(data) {
    roomname = data['message'];
    const chatroom = document.getElementsByClassName("chatroom")[0];
    chatroom.style.display = 'none';
    document.getElementById("roomname").innerHTML = '';
    curRoom = '';
    alert("You have been kicked from room " + roomname);
})

socketio.on("userKicked", function(data) {
    const kickedUser = data['message'][0];
    const curUsers = data['message'][1];
    const logs = data['message'][2];
    const creator = data['message'][3];
    showUsers(curUsers, creator);
    showLogs(logs);
})


// Ban a user
function banUser(e){
    const username = e.currentTarget.parentElement.id.substring(5);
    let curRoom = document.getElementById("roomname").innerHTML
    socketio.emit("banUserReq", {message: [username, curRoom]});
}

socketio.on("banned", function(data) {
    roomname = data['message'];
    const chatroom = document.getElementsByClassName("chatroom")[0];
    chatroom.style.display = 'none';
    document.getElementById("roomname").innerHTML = '';
    curRoom = '';
    alert("You have been banned from room " + roomname);
})

socketio.on("userBanned", function(data) {
    const bannedUser = data['message'][0];
    const curUsers = data['message'][1];
    const logs = data['message'][2];
    const creator = data['message'][3];
    showUsers(curUsers, creator);
    showLogs(logs);
})