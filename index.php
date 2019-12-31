<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elegant Calendar</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4013cd92ae.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php
        // HTTP-Only Cookies
        ini_set("session.cookie_httponly", 1);
        session_start();
    ?>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand">
        <!-- Calendar Logo  -->
            <img src="img/calendar.svg" width="30" height="30" alt="Logo Calendar">
        </a>
        <!-- Authorization and Logout Buttons -->
        <form class="form-inline">
            <button type="button" class="navbar-brand authorization-link btn" data-toggle="modal" data-target="#authorization-modal">Authorization</button>
            <button type="button" class="navbar-brand ml-2 logout-btn btn">Logout</button>
        </form>
    </nav>

    <!-- Toast Notifications -->
    <div class="position-absolute w-100 mt-1 mr-4 main-toast">
        <div class="toast ml-auto" role="alert" data-delay="900" data-autohide="false">
            <div class="toast-header">
                <strong class="mr-auto toast-info"></strong>
                <button type="button" class="close close btn-close-toast" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="toast-body">
                Now, you are able to log in.
            </div>
        </div>
    </div>

    <!--  Welcoming Jumbotron -->
    <div class="jumbotron jumbotron-fluid shadow">
        <div class="container">
            <h1 class="display-3 text-center jumbotron-text">Elegant Calendar</h1>
        </div>
    </div>

    <!-- Modal Create Events --> 
    <div class="modal fade modal-create-events" tabindex="-1" role="dialog" aria-labelledby="modal-title-id" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Modal Header -->
                    <h5 class="modal-title" id="modal-title-id">Create Events</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Modal Body -->
                    <h2 class="text-center text-info dateEvent">####-##-##</h2>
                    <label><i class="fas fa-file-alt"></i> Title</label>
                    <input type="text" class="form-control" placeholder="Title Event" id="titleEvent">
                    <small class="form-text text-muted">Must be less than 50 characters</small>
                    <label class="mt-3"><i class="fas fa-clock"></i> Time</label>
                    <input type="time" class="form-control" id="timeEvent">
                    <label class="mt-3"><i class="fas fa-sort-amount-down"></i> Priority</label>
                    <select class="form-control" id="priorityEvent">
                        <option value="high">High</option>
                        <option value="low">Low</option> 
                    </select>
                </div>
                <!-- Modal Footer  -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-info btn-sm createEventButton">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Get Specific Event -->
    <div class="modal fade" id="getSpecificEvents" tabindex="-1" role="dialog" aria-labelledby="dateEvent" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- Date of Event -->
                    <h5 class="modal-title text-info" id="dateEvent">####-##-##</h5>
                    <!-- Close Button -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form>
                        <!-- Title of Event -->
                        <div class="form-group row">
                            <label class="col-sm-3 font-weight-bold"><i class="fas fa-file-alt"></i> Title:</label>
                            <div class="col-sm-9">
                                <p class="getEventTitle"></p>
                            </div>
                        </div>
                        <!-- Date of Event -->
                        <div class="form-group row">
                            <label class="col-sm-3 font-weight-bold"><i class="fas fa-calendar-alt"></i> Date:</label>
                            <div class="col-sm-9">
                                <p class="getEventDate"></p>
                            </div>
                        </div>
                        <!-- Time of Event -->
                        <div class="form-group row">
                            <label class="col-sm-3 font-weight-bold"><i class="fas fa-clock"></i> Time:</label>
                            <div class="col-sm-9">
                                <p class="getEventTime"></p>
                            </div>
                        </div>
                        <!-- Priority of Event -->
                        <div class="form-group row">
                            <label class="col-sm-3 font-weight-bold"><i class="fas fa-sort-amount-down"></i> Priority:</label>
                            <div class="col-sm-9">
                                <p class="getEventPriority"></p>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <!-- Edit Event Section -->
                    <button class="btn btn-info btn-block" type="button" data-toggle="collapse" data-target="#editEventContent" aria-expanded="false" aria-controls="editEventContent">
                        <i class="fas fa-edit"></i> Edit Event
                    </button>
                    <!-- Collapse Section when "Edit" button triggered -->
                    <div class="collapse" id="editEventContent">
                        <div class="card card-body">
                            <div class="form-group row">
                                <!-- Title of Event -->
                                <label class="col-sm-3 font-weight-bold">Title:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Title Event" id="editTitleEvent">
                                    <small class="form-text text-muted">Must be less than 50 characters</small>
                                </div>
                            </div>
                            <!-- Time of Event -->
                            <div class="form-group row">
                                <label class="col-sm-3 font-weight-bold">Time:</label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control" id="ediTimeEvent">
                                </div>
                            </div>
                            <!-- Priority of Event -->
                            <div class="form-group row">
                                <label class="col-sm-3 font-weight-bold">Priority:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="editPriorityEvent">
                                            <option value="high">High</option>
                                            <option value="low">Low</option> 
                                    </select>
                                </div>
                                <input type="hidden" class="eventadditional">
                            </div>
                            <!-- Submit Edit Button -->
                            <button class="btn btn-sm btn-info text-center mt-2 editEventButton">Submit</button>
                        </div>
                    </div>
                    <!-- Share Section -->
                    <button class="btn btn-primary btn-block mt-3" type="button" data-toggle="collapse" data-target="#shareEventContent" aria-expanded="false" aria-controls="shareEventContent">
                        <i class="fas fa-share-square"></i> Share Event
                    </button>
                    <!-- Collapse Section when "Share" button triggered -->
                    <div class="collapse" id="shareEventContent">
                        <div class="card card-body">
                            <div class="form-group row">
                                <!-- Shared username -->
                                <label class="col-sm-4 font-weight-bold col-form-label">Username:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" placeholder="Enter shared username" id="shareEventFriend">
                                    <small class="form-text text-muted">Shared user must be registed</small>
                                </div>
                            </div>
                            <!-- Submit button to send event to username -->
                            <button class="btn btn-sm btn-primary text-center mt-2 shareEventButton">Submit</button>
                        </div>
                    </div>
                    <!-- Delete Event Section -->
                    <button type="button" class="btn btn-danger btn-block mt-3 removeEventButton"><i class="fas fa-trash-alt"></i> Delete Event</button>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <!--  Close Modal-->
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Authorization -->
    <div class="modal fade" id="authorization-modal" tabindex="-1" role="dialog" aria-labelledby="authorization-modalLabel" aria-hidden="true">
        <!-- Modal XL size since there are two columns: Login and Register -->
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="authorization-modalLabel">Authorization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mt-4">
                            <!--  Login Form -->
                            <div class="col-lg-5 authorization">
                                <h3 class="text-center">Login</h3>
                                    <!-- Username -->
                                    <div class="form-group">
                                        <label>Login:</label>
                                        <input type="text" name="login" placeholder="Enter login" class="form-control login">
                                    </div>
                                    <!-- Password -->
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" name="loginPassword" class="form-control login-password" placeholder="Enter Password">
                                    </div>
                                    <button id="login-button" class="btn btn-info">Login</button>
                            </div>
                            <div class="col-lg-2"></div>
                            <!-- Register Form -->
                            <div class="col-lg-5 authorization mb-4">
                                <h3 class="text-center">Register</h3>
                                <!-- Logun -->
                                <div class="form-group">
                                    <label>Register:</label>
                                    <input type="text" name="register" placeholder="Enter login" class="form-control register">
                                </div>
                                <!-- Password -->
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input type="password" name="registerPassword" class="form-control password" placeholder="Enter Password">
                                </div>
                                <!-- Submit Register Button -->
                                <button class="btn btn-info" id="register-button">Register</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <!-- Close button -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row ">
            <div class="col-12 col-lg-12 text-center mb-4 monthly">
                <!-- Previous Month Button -->
                <button class="btn btn-info mr-5" id="previous_month_btn">Previous</button>
                <!-- Current Month Text -->
                <p class="current_month_label d-inline lead">Current month</p>
                <!-- Next Mont Button -->
                <button class="btn btn-info ml-5" id="next_month_btn">Next</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-12">
                <table class="table text-center table-bordered shadow">
                    <thead class="thead-dark">
                        <!-- Week names table headers -->
                        <tr>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <!-- All days would be appended here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>

    <!-- Load JQuery first -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/calendar.min.js"></script>
    <script src="js/calendar.js"></script>
    <script src="js/application.js"></script>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>