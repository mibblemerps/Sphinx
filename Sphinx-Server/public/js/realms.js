// realms.js

function createRealm(name, owner, doneCallback) {
    // Send request to create Realm.
    var request = $.post(window.sphinx.dashboardUrl + "/ajax/create_realm", {
        name: name,
        owner: owner,
        _token: window.sphinx.csrfToken
    });

    request.done(function (data) {
        if (typeof doneCallback !== "undefined") {
            doneCallback(true);
        }

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
    $(".realm-edit-btn").click(function () {
        alert("Realm editing is incomplete.");
    });

    $(".realm-remove-btn").click(function () {
        alert("Realm deletion is incomplete.");
    });

    $("#realm-create-submit").click(function () {
        // Disable create Realm text fields.
        $(".form-create-realm input").each(function () {
            $(this).attr("disabled", true);
        });

        createRealm(
            $("#realm-create-name").val(),
            $("#realm-create-owner").val(),
            function (success, error) {
                if (success) {
                    window.location.reload();
                } else {
                    // Error occured!
                    if (error == "bad_username") {
                        alert("Unknown username! Please check it's correct.");
                    } else if (error == "request_failed") {
                        alert("Create Realm AJAX request failed.");
                    } else {
                        alert("Unknown error: " + error);
                    }
                }

                // Finished creating Realm - re-enable text fields.
                $(".form-create-realm input").each(function () {
                    $(this).attr("disabled", false);
                });
            }
        );
    });
});
