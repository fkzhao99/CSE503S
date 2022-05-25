// Here begins the part for the calendar(left part of the page)
const months = {0: "January", 1: "February", 2: "March", 3: "April", 4: "May",5: "June",
                6: "July", 7: "August", 8: "September", 9: "October", 10: "November", 11: "December"};

let year = document.getElementById("year");
let month = document.getElementById("month");
let days = document.getElementById("days");
let prev = document.getElementById("prev");
let next = document.getElementById("next");
next.addEventListener("click", nextMonth, false);
prev.addEventListener("click", prevMonth, false);

let curDate = new Date();
let curMonth = new Month(curDate.getFullYear(), curDate.getMonth());

const selectDate = document.getElementById("selectDate");
selectDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + curDate.getDate();

const showCate = document.getElementById("showCate");

const eventDate = document.getElementById("eventDate");
eventDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + curDate.getDate();

const editDate = document.getElementById("editDate");
// show today's events
function showToday() {
    let curDate = new Date();
    let curMonth = new Month(curDate.getFullYear(), curDate.getMonth());

    const selectDate = document.getElementById("selectDate");
    selectDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + curDate.getDate();

    const eventDate = document.getElementById("eventDate");
    eventDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + curDate.getDate();
}

// show the calendar
function showCalendar() {
    days.innerHTML = '';
    year.innerHTML = curMonth.year;
    month.innerHTML = months[curMonth.month];

    let weeks = curMonth.getWeeks();
    let count = 0;
    for (let week of weeks) {
        // remove dates that does not belong to the month
        for(let date of week.getDates()) {
            if(count <= 6 && date.getDate() > 7) {
                let li = document.createElement('li');
                days.append(li);
            }
            else if(count >= 28 && date.getDate() < 7) {
                let li = document.createElement('li');
                days.append(li);
            }
            else {
                let li = document.createElement('li');
                li.append(date.getDate());
                li.style.cursor = 'pointer';
                li.addEventListener("click", function(){
                    eventDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + date.getDate();
                    selectDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + date.getDate();
                    editDate.innerHTML = curMonth.year + '-' + (curMonth.month+1) + '-' + date.getDate();
                }, false);
                li.addEventListener("click", getEvents, false);
                days.append(li);
                count += 1;
            }
        }
    }
}

// show next month
function nextMonth() {
    curMonth = curMonth.nextMonth();
    showCalendar();
}

// show previous month
function prevMonth(){
    curMonth = curMonth.prevMonth();
    showCalendar();
}

showCalendar();
getEvents();
// register function
function registerAjax(event) {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const data = { 'username': username, 'password': password };
    fetch('register.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => alert(data.success ? "Successfully registered!" : `Register failed! ${data.message}`))
    .catch(error => console.error('Error:',error));
}

// log in function
function loginAjax(event) {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const data = { 'username': username, 'password': password };
    fetch('login.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => loginCallback(data))
    .catch(error => console.error('Error:',error));
}

// login callback
function loginCallback(data) {
    if (data.success == true) {
        showLogin(data);
        const token = document.getElementById("token");
        token.value = data.token;
    }
    else {
        alert(data.message);
    }
}

// change the panel when user has logged in
function showLogin(data) {
    const loginDiv = document.getElementsByClassName("login")[0];
    const logoutDiv = document.getElementsByClassName("logout")[0];
    loginDiv.style.display = 'none';
    logoutDiv.style.display = 'block';
    const greet = document.getElementById("greet");
    greet.innerHTML = 'Welcome, ' + data.username + '!';

    const midDiv = document.getElementsByClassName("mid")[0];
    const rightDiv = document.getElementsByClassName("right")[0];
    midDiv.style.visibility="visible";
    rightDiv.style.visibility="visible";
    showToday();
    getEvents();
    showNewEvent();
}

// change the panel when user has not logged in
function showGuest() {
    const loginDiv = document.getElementsByClassName("login")[0];
    const logoutDiv = document.getElementsByClassName("logout")[0];
    loginDiv.style.display = 'block';
    logoutDiv.style.display = 'none';
    const greet = document.getElementById("greet");
    greet.innerHTML = 'Welcome, Guest!';

    const midDiv = document.getElementsByClassName("mid")[0];
    const rightDiv = document.getElementsByClassName("right")[0];
    midDiv.style.visibility="hidden";
    rightDiv.style.visibility="hidden";
}

// determine if the user is logged in
function checkLogin() {
    fetch('checkLogin.php', {
        method: 'POST',
        body: JSON.stringify(),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success == true) {
            showLogin(data);
            const token = document.getElementById("token");
            token.value = data.token;
        }
        else {
            showGuest();
        }
    })
    .catch(error => console.error('Error:',error));
}

// log out function
function logoutAjax(event) {
    fetch("logout.php", {
        method: 'POST',
        body: JSON.stringify(),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        checkLogin(data);
        const token = document.getElementById("token");
        token.value = '';
        console.log(token.value);
    })
    .catch(error => console.error('Error:',error));
}

document.getElementById("loginBtn").addEventListener("click", loginAjax, false);
document.getElementById("regBtn").addEventListener("click", registerAjax, false);
document.getElementById("logoutBtn").addEventListener("click", logoutAjax, false);
// When the page is loaded, check if logged in
document.addEventListener("DOMContentLoaded", checkLogin, false);


// ------------------------------------------------
// Here begins the part of showing events(mid part of the page)
const showEvents = document.getElementById('showEvents');
function getEvents() {
    const showDate = selectDate.innerHTML;
    const data = {'showDate': showDate};
    fetch('getEventArray.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if(!data.hasOwnProperty("success")){
            showEvents.innerHTML = '';
            for(let singleEvent in data) {
                let eventTitle=document.createElement("h3");
                eventTitle.innerHTML = data[singleEvent].title;
                if(data[singleEvent].isGroup) {
                    eventTitle.innerHTML += ' (Group)'
                }
                let li = document.createElement("li");
                li.setAttribute('id', 'e' + data[singleEvent].id);
                li.style.cursor = 'pointer';
                li.append(eventTitle);  
                li.addEventListener("click", showEventDetail, false);
                showEvents.append(li);  
            }
        }
        else {
            console.log('Need to log in!');
        }
    })
    .catch(error => console.error('Error:',error));

    $("#showCate").val("0");
}

const editBtn = document.getElementById('edit')
editBtn.addEventListener("click", showEditEvent, false);

// ------------------------------------------------
// Here begins the part of adding or editing events(right part of the page)
function showNewEvent() {
    const newEvent = document.getElementsByClassName("newEvent")[0];
    const editEvent = document.getElementsByClassName("editEvent")[0];
    const eventDetail = document.getElementsByClassName("eventDetail")[0];
    editEvent.style.display = 'none';
    eventDetail.style.display = 'none';
    newEvent.style.display = 'block';
}

// add options for the 'time' select boxes
const eventHour = document.getElementById("eventHour");
const eventMinute = document.getElementById("eventMinute");
for(let hh = 0; hh < 24; hh++) {
    newHour = document.createElement("option");
    newHour.value = hh;
    if(hh < 10) {
        hh = '0' + hh.toString();
    }
    newHour.text = hh;
    eventHour.options.add(newHour);
}
for(let mm = 0; mm < 60; mm++) {
    newMinute = document.createElement("option");
    newMinute.value = mm;
    if(mm < 10) {
        mm = '0' + mm.toString();
    }
    newMinute.text = mm;
    eventMinute.options.add(newMinute);
}

// if the 'group' checkbox is selected, show users
const isGroup = document.getElementById("isGroup");
const users = document.getElementById("users");
users.style.visibility="hidden";
const userArray = document.getElementById("userArray");

function showUserArray(){
    users.style.visibility="visible";
    fetch('getUserArray.php', {
    method: 'POST',
    body: JSON.stringify(),
    headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        userArray.innerHTML = '';
        for(let singleUser in data) {
            let checkBox=document.createElement("input");  
            checkBox.setAttribute("type","checkbox");
            checkBox.setAttribute("id", data[singleUser].id);
            checkBox.setAttribute("name", "groupUsers");
            let li = document.createElement("li");
            li.append(checkBox);  
            li.innerHTML += data[singleUser].username; 
            userArray.append(li);  
        }
    })
    .catch(error => console.error('Error:',error));
}

isGroup.addEventListener("click", function(){
    if(isGroup.checked){
        showUserArray();
    }
    else{
        users.style.visibility="hidden";
    }
    
}, false);

// add a new event
function addEvent() {
    const addTitle = document.getElementById("eventTitle").value;
    const addDate = document.getElementById("eventDate").innerHTML;
    const addHour = document.getElementById("eventHour").value;
    const addMinute = document.getElementById("eventMinute").value;
    const addCate = document.getElementById("eventCate").value;
    const addIsGroup = isGroup.checked;
    const groupUsers = document.getElementsByName("groupUsers");
    const token = document.getElementById("token").value;
    let selectUsers = new Array();
    for(let i = 0; i < groupUsers.length; i++){
        if(groupUsers[i].checked)
        selectUsers.push(groupUsers[i].id);
    }

    if(addTitle == '' || addDate == '' || addHour == '' || addMinute == '' || addCate == ''){
        alert("Add event failed!");
        return;
    }
    const data = { 'addTitle': addTitle, 'addDate': addDate, 'addHour': addHour,
     'addMinute': addMinute, 'addCate': addCate, 'addIsGroup': addIsGroup, 'selectUsers': selectUsers, 'token': token };
    fetch('addEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        getEvents();
    })
    .catch(error => console.error('Error:',error));
}

function clearForm() {
    const addTitle = document.getElementById("eventTitle");
    const addHour = document.getElementById("eventHour");
    const addMinute = document.getElementById("eventMinute");
    const addCate = document.getElementById("eventCate");
    const shareCode = document.getElementById("inputCode");
    addTitle.value = '';
    addHour.value = '0';
    addMinute.value = '0';
    addCate.value = '1';
    isGroup.checked = false;
    users.style.visibility="hidden";
    shareCode.value = '';
    userArray.innerHTML = '';
}

const addEventBtn = document.getElementById("addEvent");
addEventBtn.addEventListener("click", function() {
    addEvent();
    clearForm();
    getEvents();
}, false);

const clear = document.getElementById("clear");
clear.addEventListener("click", clearForm, false);

// When clicking the 'back' button, back to add event panel
const backBtn = document.getElementById("back");
const editBackBtn = document.getElementById("editBack");
editBackBtn.addEventListener("click", showNewEvent, false);
backBtn.addEventListener("click", showNewEvent, false);

// When clicking an event, show its detail
function showEventDetail(e) {
    const newEvent = document.getElementsByClassName("newEvent")[0];
    const editEvent = document.getElementsByClassName("editEvent")[0];
    const eventDetail = document.getElementsByClassName("eventDetail")[0];
    editEvent.style.display = 'none';
    eventDetail.style.display = 'block';
    newEvent.style.display = 'none';

    const eventId = e.currentTarget.id;
    const data = {'eventId': eventId};
    fetch('getEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        const detailTitle = document.getElementById("detailTitle");
        const detailDate = document.getElementById("detailDate");
        const detailTime = document.getElementById("detailTime");
        const detailCate = document.getElementById("detailCate");
        const detailIsGroup = document.getElementById("detailIsGroup");
        const curEventId = document.getElementById("curEventId");
        detailTitle.innerHTML = data.title;
        detailDate.innerHTML = data.date;
        if(data.hour < 10) {
            data.hour = '0' + data.hour;
        }
        if(data.minute < 10) {
            data.minute = '0' + data.minute;
        }
        detailTime.innerHTML = data.hour + ' : ' + data.minute;
        detailCate.innerHTML = getCateName(data.cateId);
        detailIsGroup.innerHTML = (data.isGroup == 1);
        curEventId.value = data.id;
    })
}

// get category name by id
function getCateName(id) {
    if(id == 1) {
        return 'Study';
    }
    else if(id == 2) {
        return 'Work';
    }
    else if(id == 3) {
        return 'Sport';
    }
    else if(id == 4) {
        return 'Play';
    }
    else {
        return 'Other';
    }
}

// show edit panel
function showEditEvent() {
    const newEvent = document.getElementsByClassName("newEvent")[0];
    const editEvent = document.getElementsByClassName("editEvent")[0];
    const eventDetail = document.getElementsByClassName("eventDetail")[0];
    editEvent.style.display = 'block';
    eventDetail.style.display = 'none';
    newEvent.style.display = 'none';
    
    const editTitle = document.getElementById("editTitle");
    const editDate = document.getElementById("editDate");
    const editHour = document.getElementById("editHour");
    const editMinute = document.getElementById("editMinute");
    const editCate = document.getElementById("editCate");
    const editIsGroup = document.getElementById("editIsGroup");
    editIsGroup.style.display = 'none';

    for(let hh = 0; hh < 24; hh++) {
        newHour = document.createElement("option");
        newHour.value = hh;
        if(hh < 10) {
            hh = '0' + hh.toString();
        }
        newHour.text = hh;
        editHour.options.add(newHour);
    }
    for(let mm = 0; mm < 60; mm++) {
        newMinute = document.createElement("option");
        newMinute.value = mm;
        if(mm < 10) {
            mm = '0' + mm.toString();
        }
        newMinute.text = mm;
        editMinute.options.add(newMinute);
    }

    const curEventId = document.getElementById("curEventId");
    const eventId = 'e' + curEventId.value;
    const data = {'eventId': eventId};
    fetch('getEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        editTitle.value = data.title;
        editDate.innerHTML = data.date;
        editHour.value = data.hour;
        editMinute.value = data.minute;
        editCate.value = data.cateId;
        if(data.isGroup == 1){
            editIsGroup.checked = true;
        }
    })
}

// edit event
function editEvent() {
    const editId = document.getElementById("curEventId").value;
    const editTitle = document.getElementById("editTitle").value;
    const editDate = document.getElementById("editDate").innerHTML;
    const editHour = document.getElementById("editHour").value;
    const editMinute = document.getElementById("editMinute").value;
    const editCate = document.getElementById("editCate").value;
    const editIsGroup = document.getElementById("editIsGroup").checked;

    const token = document.getElementById("token").value;

    if(editTitle == '' || editDate == '' || editHour == '' || editMinute == '' || editCate == ''){
        alert("Edit event failed!");
        return;
    }
    const data = {'editId': editId, 'editTitle': editTitle, 'editDate': editDate, 'editHour': editHour,
     'editMinute': editMinute, 'editCate': editCate, 'editIsGroup': editIsGroup, 'token': token };
    fetch('editEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        getEvents();
    })
    .catch(error => console.error('Error:',error));
}

const sumbitEdit = document.getElementById("sumbitEdit");
sumbitEdit.addEventListener("click", function(){
    editEvent();
    getEvents();
    showNewEvent();
} , false);

// delete event
function deleteEvent() {
    const deleteId = document.getElementById("curEventId").value;
    const token = document.getElementById("token").value;

    const data = {'deleteId': deleteId, 'token': token};
    fetch('deleteEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        getEvents();
        alert(data.message);
    })
    .catch(error => console.error('Error:',error));
    getEvents();
    showNewEvent();
}
const deleteBtn = document.getElementById("delete");
deleteBtn.addEventListener("click", function() {
    deleteEvent();
    getEvents();
    showNewEvent();
} , false);

// share event: get sharing code
function shareEvent() {
    const shareId = 'e' + document.getElementById("curEventId").value;
    const data = {'eventId': shareId};
    fetch('getEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert('Please save the sharing code: ' + data.shareCode);
    })
    .catch(error => console.error('Error:',error));
}
const shareBtn = document.getElementById("share");
shareBtn.addEventListener("click", shareEvent, false);

// share event: input code and get the event
function getEventByCode() {
    const shareCode = document.getElementById("inputCode").value;
    const data = {'shareCode' : shareCode};
    fetch('getSharedEvent.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success == false) {
            alert("Invalid code!");
        }
        else {
            alert("Success!");
            selectDate.innerHTML = data.date;
            getEvents();
            showNewEvent();
        }
    })
    .catch(error => console.error('Error:',error));
}

const getShared = document.getElementById("submitGet");
getShared.addEventListener("click", function(){
    getEventByCode();
    getEvents();
    clearForm();
    showNewEvent();
}, false);

// show certain category
function getEventsByCate() {
    const showCate = document.getElementById("showCate").value;
    const showDate = selectDate.innerHTML;
    const data = {'showCate' : showCate, "showDate": showDate};
    fetch('getEventByCate.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if(!data.hasOwnProperty("success")){
            showEvents.innerHTML = '';
            for(let singleEvent in data) {
                let eventTitle=document.createElement("h3");
                eventTitle.innerHTML = data[singleEvent].title;
                if(data[singleEvent].isGroup) {
                    eventTitle.innerHTML += ' (Group)'
                }
                let li = document.createElement("li");
                li.setAttribute('id', 'e' + data[singleEvent].id);
                li.style.cursor = 'pointer';
                li.append(eventTitle);  
                li.addEventListener("click", showEventDetail, false);
                showEvents.append(li);  
            }
        }
        else {
            console.log('Need to log in!');
        }
    })
    .catch(error => console.error('Error:',error));
}
$('#showCate').change(function() {
    getEventsByCate();
})