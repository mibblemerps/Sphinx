// users.js

function createUser(username, email, password, confirmPassword, doneCallback) {
    // Send request to create Realm.
    var request = $.post(window.sphinx.dashboardUrl + "/ajax/create_user", {
        username: username,
        email: email,
        password: password,
        confirm_password: confirmPassword,
        _token: window.sphinx.csrfToken
    });

    request.done(function (data) {
        if (data.success) {
            // Successful.
            doneCallback(true);
        } else {
            // Server returned some kind of error.
            doneCallback(false, data.errors);
        }
    });
    request.fail(function () {
        // Request failed.
        if (typeof doneCallback !== "undefined") {
            doneCallback(false, "request_failed");
        }
    });
}

function deleteUser(id, doneCallback) {
    // Send request to delete Realm.
    var request = $.post(window.sphinx.dashboardUrl + "/ajax/delete_user", {
        userid: id,
        _token: window.sphinx.csrfToken
    });

    request.done(function (data) {
        if (data.success) {
            // Successful.
            doneCallback(true);
        } else {
            // Server returned some kind of error.
            doneCallback(false, data.error);
        }
    });
    request.fail(function () {
        // Request failed.
        if (typeof doneCallback !== "undefined") {
            doneCallback(false, "request_failed");
        }
    });
}

$(document).ready(function () {
    $("#user-create-submit").click(function () {
        // Disable create Realm text fields.
        $(".form-create-user input").each(function () {
            $(this).attr("disabled", true);
        });

        createUser(
            $("#user-create-username").val(),
            $("#user-create-email").val(),
            $("#user-create-password").val(),
            $("#user-create-password-confirm").val(),
            function (success, errors) {
                if (success) {
                    window.location.reload();
                } else {
                    // Error occured!
                    if (errors == "request_failed") {
                        alert("Create Realm AJAX request failed.");
                    } else {
                        // Show errors alert.
                        $("#user-create-error").fadeIn();
                        $("#user-create-errors").html("");

                        // Render errors.
                        Object.keys(errors).forEach(function (field) {
                            for (var i = 0; i < errors[field].length; i++) {
                                var error = errors[field][i];

                                // Create and insert new error list item.
                                var errorLi = document.createElement("li");
                                errorLi.innerHTML = error;
                                $("#user-create-errors").append(errorLi);
                            }
                        });
                    }
                }

                // Finished creating Realm - re-enable text fields.
                $(".form-create-user input").each(function () {
                    $(this).attr("disabled", false);
                });
            }
        );
    });

    $(".user-remove-btn").click(function () {
        // Delete user button pressed.
        var userid = $(this).attr("data-userid");
        var username = $(this).attr("data-username");

        // Disable delete button.
        $(this).attr("disabled", true);

        if (confirm("Please confirm you wish to remove this user (" + username + ").\nThis action cannot be undone.")) {
            deleteUser(userid, function (success, error) {
                if (success) {
                    // Success!
                    window.location.reload();
                } else {
                    // Sever returned an error!
                    if (error == "request_failed") {
                        alert("AJAX request to delete user failed!");
                    } else {
                        alert("Unknown error occured: " + error);
                    }
                }
            });
        } else {
            // Aborted - enable button.
            $(this).attr("disabled", false);
        }
    });

    $(".form-create-user input").keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == 13) {
            $("#user-create-submit").click();
        }
    });
});
