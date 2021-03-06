var users;
// const keyAPI = 'wpf0okfhmjoyb3v0gw16';

$( document ).ready(function() {
    getUsers();
});

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
  $( "#console" ).append( `<p>${message}</p>`);
}

function getUsers() {
  const keyAPI = $( "#api" ).val();
  const url = "http://localhost/php-rest-api/back-end/index.php";
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
$(document).on('click', '#get', function () {
  getUser();
});

function getUser() {
  const keyAPI = $( "#api" ).val();
  const id = $('#id').val();
  const url = `http://localhost/php-rest-api/back-end/index.php?id=${id}`;
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
$(document).on('click', '#insert', function () {
  insertUser();
});

function insertUser() {

  const keyAPI = $( "#api" ).val();
  const firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();
  const url = "http://localhost/php-rest-api/back-end/index.php";

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
$(document).on('click', '#update', function () {
  updateUser();
});

function updateUser() {
  const id = $('#id').val(), firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();
  const url = `http://localhost/php-rest-api/back-end/index.php?id=${id}`;
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
  const url = `http://localhost/php-rest-api/back-end/index.php?id=${id}`;
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
