var users;
// const keyAPI = 'wpf0okfhmjoyb3v0gw16';

$( document ).ready(function() {
    getUserForm();
    getUsers();
});


// Event listener for update user form
$(document).on('click', '#getUser', function () {
  getUserForm();
});

// Modify form for updating user
function getUserForm() {
  $( "#userForm" ).html (
    `<form id="get" onsubmit="return false">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputCity">ID#</label>
          <input type="number" class="form-control" id="id" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="api">API Key</label>
          <input type="text" class="form-control" name="api" id="api" value="wpf0okfhmjoyb3v0gw16" required>
        </div>
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4 submit-button">
          <input type="submit" class="btn btn-primary w-100" value="Get User">
        </div>
      </div>
    </form>`
  )
}

// Event listener for insert user form
$(document).on('click', '#insertUser', function () {
  insertUserForm();
});

// Modify form for inserting user
function insertUserForm() {
  $( "#userForm" ).html (
    `<form id="insert" onsubmit="return false">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" name="firstName" id="firstName" pattern="[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z ]*)*" size="50" required>
        </div>
        <div class="form-group col-md-4">
          <label for="surname">Surname</label>
          <input type="text" class="form-control" name="surname" id="surname" pattern="[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z ]*)*" size="50" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="dob">Date of Birth</label>
          <input type="text" class="form-control" name="dob" id="dob" required>
        </div>
        <div class="form-group col-md-4">
          <label for="email">Email</label>
          <input type="text" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group col-md-4">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" name="phone" id="phone" pattern="[0-9 \-\(\)\/\+\s]*" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="api">API Key</label>
          <input type="text" class="form-control" name="api" id="api" value="wpf0okfhmjoyb3v0gw16" size="20" required>
        </div>
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4 submit-button">
          <input type="submit" class="btn btn-success w-100" value="Add User">
        </div>
      </div>
    </form>`
  )
}

// Event listener for update user form
$(document).on('click', '#updateUser', function () {
  updateUserForm();
});

// Modify form for updating user
function updateUserForm() {
  $( "#userForm" ).html (
    `<form id="update" onsubmit="return false">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputCity">ID#</label>
          <input type="number" class="form-control" id="id" required>
        </div>
        <div class="form-group col-md-4">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" name="firstName" id="firstName" pattern="[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z ]*)*" required>
        </div>
        <div class="form-group col-md-4">
          <label for="surname">Surname</label>
          <input type="text" class="form-control" name="surname" id="surname" pattern="[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z ]*)*" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="dob">Date of Birth</label>
          <input type="text" class="form-control" name="dob" id="dob" required>
        </div>
        <div class="form-group col-md-4">
          <label for="email">Email</label>
          <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group col-md-4">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" name="phone" id="phone" pattern="[0-9 \-\(\)\/\+\s]*" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="api">API Key</label>
          <input type="text" class="form-control" name="api" id="api" value="wpf0okfhmjoyb3v0gw16">
        </div>
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4 submit-button">
          <input type="submit" class="btn btn-warning w-100" value="Update User">
        </div>
      </div>
    </form>`
  )
}


// Event listener for update user form
$(document).on('click', '#deleteUser', function () {
  deleteUserForm();
});

// Modify form for updating user
function deleteUserForm() {
  $( "#userForm" ).html (
    `<form id="delete" onsubmit="return false">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputCity">ID#</label>
          <input type="number" class="form-control" id="id" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="api">API Key</label>
          <input type="text" class="form-control" name="api" id="api" value="wpf0okfhmjoyb3v0gw16" required>
        </div>
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4 submit-button">
          <input type="submit" class="btn btn-danger w-100" value="Delete User">
        </div>
      </div>
    </form>`
  )
}

// Display users in the table
function displayInfo() {
  $( "#tableData" ).html('');
  users.forEach(function(user){
    $( "#tableData" ).append( `
      <tr id="user${user.id}" class="user" data-id="${user.id}">
        <td>${user.id}</td>
        <td>${user.first_name}</td>
        <td>${user.surname}</td>
        <td>${user.dob}</td>
        <td>${user.email}</td>
        <td>${user.phone}</td>
      </tr>` );
  });
}

// Add to console log on page
function addToConsole(message) {
  $( "#console" ).html( `<p>${message}</p>`);
}

function getUsers() {
  const keyAPI = $( "#api" ).val();
  const url = "https://jonathanherring.co.uk/back-end/index.php";
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      "Apikey": keyAPI
    },
    data: {

    },
    success: function(result) {

      console.log(result)
      if (result.status.name == "ok") {
        // console.log(result['data'])
        console.log(result['data']);
        users = result['data'];
        displayInfo()
      }

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

// Event listener for get user
$(document).on('submit', '#get', function () {
  getUser();
});

function getUser() {
  const keyAPI = $( "#api" ).val();
  const id = $('#id').val();
  const url = `https://jonathanherring.co.uk/back-end/index.php?id=${id}`;
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      "Apikey": keyAPI
    },
    data: {

    },
    success: function(result) {

      console.log(result);
      addToConsole(`${url} returns ${result['data']}`);
      getUsers();

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

// Event listener for insert user
$(document).on('submit', '#insert', function () {
  insertUser();
});

function insertUser() {

  const keyAPI = $( "#api" ).val();
  const firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();
  const url = "https://jonathanherring.co.uk/back-end/index.php";

  $.ajax({
    url: url,
    type: 'PUT',
    dataType: 'json',
    headers: {
      "Apikey": keyAPI
    },
    data: {
      firstName: firstName,
      surname: surname,
      dob: dob,
      email: email,
      phone: phone
    },
    success: function(result) {
      console.log(result)
      addToConsole(`${url} returns ${result['data']}`);
      getUsers();
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

// Event listener for update button
$(document).on('submit', '#update', function () {
  updateUser();
});

function updateUser() {
  const id = $('#id').val(), firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();
  const url = `https://jonathanherring.co.uk/back-end/index.php?id=${id}`;
  const keyAPI = $( "#api" ).val();

  console.log(email);

  $.ajax({
    url: url,
    type: 'POST',
    dataType: 'json',
    data: {
      id: id,
      firstName: firstName,
      surname: surname,
      dob: dob,
      email: email,
      phone: phone
    },
    headers: {
      "Apikey": keyAPI
    },
    success: function(result) {
      console.log(result);
      addToConsole(`${url} returns ${result['data']}`);
      getUsers();
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

// Event listener for delete user
$(document).on('click', '#delete', function () {

  const id = $('#id').val();
  deleteUser(id);

});

function deleteUser(id) {
  const keyAPI = $( "#api" ).val();
  const url = `https://jonathanherring.co.uk/back-end/index.php?id=${id}`;
  $.ajax({
    url: url,
    type: 'DELETE',
    dataType: 'json',
    data: {

    },
    headers: {
      "Apikey": keyAPI
    },
    success: function(result) {
      console.log(result);
      addToConsole(`${url} returns ${result['data']}`);
      getUsers();
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}
