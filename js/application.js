// Table Body <tbody>
var tableBody = $('.table-body');
// Current month label
var labelMonth = $('.current_month_label');

// Animation for Jumbotron Text
$('.jumbotron-text').addClass('jumbtron-animation');
$('.jumbotron-text').text('Elegant Calendar');
$('.navbar .form-inline .logout-btn').hide();

// Get Current month and associated name of month
var newDate = new Date();
var month = new Array();
month[0] = "January";
month[1] = "February";
month[2] = "March";
month[3] = "April";
month[4] = "May";
month[5] = "June";
month[6] = "July";
month[7] = "August";
month[8] = "September";
month[9] = "October";
month[10] = "November";
month[11] = "December";

// For our purposes, we can keep the current month in a variable in the global scope
var currentMonth = new Month(2019, newDate.getMonth()); // October 2017

// Change the month when the "next" button is pressed
document.getElementById("previous_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	// alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);

document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	// alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);

updateCalendar();

// This updateCalendar() function only alerts the dates in the currently specified month.  You need to write
// it to modify the DOM (optionally using jQuery) to display the days and weeks in the current month.
function updateCalendar(){
    // Get Events if user logged in
	getEvents();
	
	tableBody.empty();
	labelMonth.text(`${month[currentMonth.month]}, ${currentMonth.year}`);
	var weeks = currentMonth.getWeeks();
	
	for(var w in weeks){
		var days = weeks[w].getDates();
		var trElement = $('<tr></tr>');
		
		// days contains normal JavaScript Date objects.
		for(var i = 0; i < 7; i++){
			trElement.append(`<td>${days[i].getDate()}<ul class='list-group ${days[i].toISOString().split('T')[0]}'></ul></td>`);
			// You can see console.log() output in your JavaScript debugging tool, like Firebug,
			// WebWit Inspector, or Dragonfly.
			// console.log(days[d].toISOString());
		}

		tableBody.append(trElement);
    }

    // Toggle Modal to Create Events
	$("td").on('click', function(e){
		if (e.target !== this){
			return;
		}
		$(this).attr('data-toggle','modal');
		$(this).attr('data-target', '.modal-create-events');
		var dateClass = $(this).find('ul').attr('class').split(' ')[1];
		$('.dateEvent').text(`${dateClass}`);
	});
}

// ***************************************
// ------------ Functions ----------------
// ***************************************

// ------------ Buttons ------------------
let button_register = $('#register-button');
let button_login = $('#login-button');
let button_create_event = $('.createEventButton');
let button_edit_event = $('.editEventButton');
let button_remove_event = $('.removeEventButton');
let button_share_event = $('.shareEventButton');
let button_logout = $('.logout-btn');
$('.toast').toast('hide');

// *****************************************
//            User Register
// *****************************************

function registerUsers(event){
    // Get needed values
    let username = $('.register').val();
    let password = $('.password').val();  

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("register.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success){
                // If user registered, theb toggle Notification
                // Clear inputs
                $('.register').val('');
                $('.password').val('');
                $('#authorization-modal').modal('hide');
                toastSuccess('Registered! Now, you are able to login!');
            }else{
                alert('Error! You could not register!');
            }
        });
}

// *****************************************
//            User Login Event
// *****************************************

function loginUsers(){
    // Get needed values
    let username = $('.login').val();
    let password = $('.login-password').val();  

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("login.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success){
                // Trigger notification
                $('.jumbotron-text').removeClass('jumbtron-animation');
                // clear inputs tag
                $('.login').val('');
                $('.login-password').val('');
                // Store session token in localStorage()
                localStorage.setItem('token', data.token);
                localStorage.setItem('username', data.username);

                // If user logged in, then dismiss authentication modal
                // If an user logged in. Change "Authorization" to "Logout"
                $('#authorization-modal').modal('hide');
                $('.navbar .form-inline .authorization-link').hide();
                $('.navbar .form-inline .logout-btn').show();

                $('.jumbotron-text').addClass('jumbtron-animation');
                $('.jumbotron-text').text(`Welcome "${localStorage.getItem('username')}"`);
                // Animated Toast Notifications
                toastSuccess(`${data.username} you have logged in!`);
                // Get User Events
                getEvents();
            }else{
                // If an error, failed notification pops up!
                $('#authorization-modal').modal('hide');
                toastFailed('You need to register before log in! Otherwise, check password and username');
            }
        });
}

// *****************************************
//            User Logout
// *****************************************

function logoutUsers(){
    // Get needed values
    let usernameOut = 'logout';

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': usernameOut };

    fetch("logout.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success){
                // notification
                $('.jumbotron-text').removeClass('jumbtron-animation');
                updateCalendar();
                localStorage.clear();
                toastSuccess('You have logged out!');

                // If an user logs out. Change "Logout" to "Authorization"
                $('.navbar .form-inline .authorization-link').show();
                $('.navbar .form-inline .logout-btn').hide();
                $('.jumbotron-text').addClass('jumbtron-animation');
                $('.jumbotron-text').text(`Elegant Calendar`);
            }
        });
}

// Toast Success!
function toastSuccess(bodyMessage){
    $('.toast-info').removeClass('text-danger');
    $('.toast-info').addClass('text-success');
    $('.toast-info').html('Successful!');
    $('.toast-body').text(bodyMessage);
    $('.toast').addClass('testing_toasts');
    $('.toast').toast('show');
}

// Toast Failed!
function toastFailed(bodyMessage){
    $('.toast-info').removeClass('text-success');
    $('.toast-info').addClass('text-danger');
    $('.toast-info').html('Failed!');
    $('.toast-body').text(bodyMessage);
    $('.toast').addClass('testing_toasts');
    $('.toast').toast('show');
}

// *****************************************
//           Get Events
// *****************************************

function getEvents(){
    $.ajax({
        type:'POST',
        url:'events.php',
        success:function(data){
            if(data.length > 0){
                $('.navbar .form-inline .authorization-link').hide();
                $('.navbar .form-inline .logout-btn').show();
                $('.jumbotron-text').text(`Welcome "${localStorage.getItem('username')}"`);
                // Convert data from server side data to json and loop through
                var json = $.parseJSON(data);
                // Append each event with associated title, date, time and priority
                json.map( d => {
                    if(d.priority == 'high'){
						$(`.${d.date}`).append(`<li class='list-group-item list-group-item-danger' data-id="${d.event_id}" data-date="${d.date}" data-time="${d.time}" data-priority="${d.priority}">${d.title}</li>`);
                    }else{
						$(`.${d.date}`).append(`<li class='list-group-item list-group-item-info' data-id="${d.event_id}" data-date="${d.date}" data-time="${d.time}" data-priority="${d.priority}">${d.title}</li>`);
                    }
                });
                
                //  -------------------------------
                //  Specific Event Modal Toggle
                //  --------------------------------

				$("li").on('click', function(){
					$(this).attr('data-toggle','modal');
					$(this).attr('data-target', '#getSpecificEvents');
					let eventTitleModal = $(this).text();
					let eventTimeModal = $(this).attr("data-time");
					let eventPriorityModal = $(this).attr("data-priority");
					let eventDateModal = $(this).attr("data-date");
					let eventID = $(this).attr("data-id");

					// Get Events
					$('#dateEvent').text(eventDateModal);
					$('.getEventTitle').text(eventTitleModal)
					$('.getEventDate').text(eventDateModal);
					$('.getEventTime').text(eventTimeModal);
					$('.getEventPriority').text(eventPriorityModal);

					// Get value for Edit Events
					$('#editTitleEvent').val(eventTitleModal);
					$('#ediTimeEvent').val(eventTimeModal);
					$('#editPriorityEvent').val(eventPriorityModal);
					$('.eventadditional').val(eventID);
				});
            }
		}
	});
}

// *****************************************
//            Edit Events
// *****************************************

function editEvents(){
    // Get needed values
	let titleEvent = $('#editTitleEvent').val();
    let timeEvent = $('#ediTimeEvent').val();
    let priorityEvent = $('#editPriorityEvent').val();
	let dateEvent = $('#dateEvent').text();
    let eventID = $('.eventadditional').val();
    let tokenID = localStorage.getItem('token');

    // Make a URL-encoded string for passing POST data:
    const data = {'titleEvent': titleEvent, 'timeEvent': timeEvent, 'priorityEvent': priorityEvent, 'dateEvent': dateEvent, 'idEvent': eventID, 'tokenID': tokenID};

    fetch("editevent.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                // If event is edited then updateCalendar()
                updateCalendar();
                // Dismiss Specific Modal Event
                $('#getSpecificEvents').modal('hide');
                toastSuccess(`You changed an event!`);
            }
        });
}

// *****************************************
//            Share Event WIth User
// *****************************************

function shareEvents(){
    // Get needed values
	let titleEvent = $('#editTitleEvent').val();
    let timeEvent = $('#ediTimeEvent').val();
    let priorityEvent = $('#editPriorityEvent').val();
	let dateEvent = $('#dateEvent').text();
	let usernameToBeShared = $('#shareEventFriend').val();

    // Make a URL-encoded string for passing POST data:
    const data = {'titleEvent': titleEvent, 'timeEvent': timeEvent, 'priorityEvent': priorityEvent, 'dateEvent': dateEvent, 'usernameShare': usernameToBeShared};

    fetch("shareevent.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                // If event is sent to username then dismiss Specific Event Modal
                $('#getSpecificEvents').modal('hide');
                toastSuccess(`You shared an event with ${data.usernameShare}`);
            }
        });
}

// *****************************************
//            Remove Event
// *****************************************

function removeEvents(){
    // Get needed values
    let eventID = $('.eventadditional').val();
    let tokenID = localStorage.getItem('token');

	// Make a URL-encoded string for passing POST data:
	const data = {'idEvent': eventID, 'tokenID':tokenID};

	fetch("removevent.php", {
			method: 'POST',
			body: JSON.stringify(data),
			headers: {'Content-Type': 'application/json'}
		})
		.then(response => response.json())
		.then(data => {
			if(data.success){
                // When event is deleted then call updateCalendar()
                updateCalendar();
                // DIsmiss Specific Event Modal
                $('#getSpecificEvents').modal('hide');
                toastSuccess(`You removed an event!`);
			}
		});
}

// *****************************************
//            Create Event
// *****************************************

function createEvents(){
    // Get needed values
    let titleEvent = $('#titleEvent').val();
    let timeEvent = $('#timeEvent').val();
    let priorityEvent = $('#priorityEvent').val();
    let dateEvent = $('.dateEvent').text();

    // Make a URL-encoded string for passing POST data:
    const data = {'titleEvent': titleEvent, 'timeEvent': timeEvent, 'priorityEvent': priorityEvent, 'dateEvent': dateEvent };

    fetch("createvent.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                // If event is created then call updateCalendar()
                updateCalendar();
                // Dismiss Creating Modal Event
                $('.modal-create-events').modal('hide');
                // Animated Toast Notifications
                toastSuccess(`You created an event for date: ${data.date}`);
            }else{
                $('.modal-create-events').modal('hide');
                toastFailed('You need to log in to create an event!')
            }
        });
}
// *****************************************
//            Calling Buttons
// *****************************************

button_register.click(registerUsers);
button_login.click(function(){
    loginUsers()
});
button_logout.click(logoutUsers);
button_create_event.click(createEvents);
button_edit_event.click(editEvents);
button_remove_event.click(removeEvents);
button_share_event.click(shareEvents);
