var users;
var keyAPI = 'wpf0okfhmjoyb3v0gw16';
// var keyAPI = 'gsbsgnsnsfn';

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

function addToConsole(message) {
  $( "#console" ).append( `<p>${message}</p>`);
}

function getUsers() {

  const url = "http://localhost/php-rest-api/back-end/index.php";
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      "APIKEY": keyAPI
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
        addToConsole(`${url} returned users`)
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
  const id = $('#id').val();
  const url = `http://localhost/php-rest-api/back-end/index.php?id=${id}`;
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      "APIKEY": keyAPI
    },
    data: {

    },
    success: function(result) {

      if (result.status.name == "ok") {
        // console.log(result['data'])
        console.log(result);
        addToConsole(`${url} returns ${result['data']}`);
        getUsers();
      }

      if (result.status.code == 400) {
        console.log(result);
        addToConsole(`${url} returns ${result['data']}`);
      }

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

  const firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();
  const url = "http://localhost/php-rest-api/back-end/index.php";

  $.ajax({
    url: url,
    type: 'PUT',
    dataType: 'json',
    headers: {
      "APIKEY": keyAPI
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
      if (result.status.name == "ok") {
        console.log(result)
        addToConsole(`${url} returns ${result['data']}`);
        getUsers();
      }

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

  $.ajax({
    url: `http://localhost/php-rest-api/back-end/index.php?id=${id}`,
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
      "APIKEY": keyAPI
    },
    success: function(result) {

      if (result.status.name == "ok") {
        console.log(result);
        addToConsole(result['data']);
        getUsers();
      }

      if (result.status.code == 400) {
        console.log(result);
        addToConsole(result['data']);
      }

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
  const url = `http://localhost/php-rest-api/back-end/index.php?id=${id}`;
  $.ajax({
    url: url,
    type: 'DELETE',
    dataType: 'json',
    data: {

    },
    headers: {
      "APIKEY": keyAPI
    },
    success: function(result) {

      if (result.status.name == "ok") {
        console.log(result);
        addToConsole(`${url} returns ${result['data']}`);
        getUsers();
      }

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

getUsers();
