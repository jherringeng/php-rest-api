var users;
var keyAPI = 'wpf0okfhmjoyb3v0gw16';
// var keyAPI = 'gsbsgnsnsfn';

function displayInfo() {
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
  $.ajax({
    url: "http://localhost/php-rest-api/back-end/index.php",
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
        addToConsole("Returned users")
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
  $.ajax({
    url: `http://localhost/php-rest-api/back-end/index.php?id=${id}`,
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
        console.log(result['data']);
        addToConsole(result['data']);

      }

      if (result.status.code == 400) {
        addToConsole(`No user with id = ${id}`);
      }

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

$(document).on('click', '#insert', function () {
  insertUser();
});

function insertUser() {

  const firstName = $('#firstName').val(), surname = $('#surname').val(), dob = $('#dob').val();
  const email = $('#email').val(), phone = $('#phone').val();

  $.ajax({
    url: "http://localhost/php-rest-api/back-end/index.php",
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
        console.log("Added user")
        addToConsole("Added user");
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
        console.log("Updated user");
        addToConsole(`Updated user with id = ${id}`);
      }

      if (result.status.code == 400) {
        addToConsole(`No user with id = ${id}`);
      }

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

// Event listener for # delete
$(document).on('click', '#delete', function () {

  const id = $('#id').val();
  deleteUser(id);

});

function deleteUser(id) {
  $.ajax({
    url: `http://localhost/php-rest-api/back-end/index.php?id=${id}`,
    type: 'DELETE',
    dataType: 'json',
    data: {

    },
    headers: {
      "APIKEY": keyAPI
    },
    success: function(result) {

      if (result.status.name == "ok") {
        console.log("Deleted user")
        addToConsole("Deleted user");
      }

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
    }
  });
}

getUsers();
