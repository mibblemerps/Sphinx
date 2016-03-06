// realms.js

function createRealm(name, owner, doneCallback) {
    // Send request to create Realm.
    var request = $.post(window.sphinx.dashboardUrl + "/ajax/create_realm", {
        name: name,
        owner: owner,
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

function deleteRealm(id, doneCallback) {
    // Send request to delete Realm.
    var request = $.post(window.sphinx.dashboardUrl + "/ajax/delete_realm", {
        serverid: id,
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
    $(".realm-edit-btn").click(function () {
        alert("Realm editing is incomplete.");
    });

    $(".realm-remove-btn").click(function () {
        var serverid = $(this).attr("data-serverid");
        var servername = $(this).attr("data-servername");

        $(this).attr("disabled", true); // disable remove button

        if (confirm("Please confirm you wish to remove this Realm (" + servername + ").\nThis action cannot be undone.")) {
            // Delete Realm.
            deleteRealm(serverid, function (success, error) {
                if (success) {
                    // Successfully deleted Realm.
                    window.location.reload(); // reload page.
                } else {
                    // Sever returned an error!
                    if (error == "request_failed") {
                        alert("Delete Realm AJAX request failed.");
                    } else {
                        alert("Unknown error: " + error);
                    }

                    $(this).attr("disabled", false);
                }
            });
        } else {
            // Aborted.
            $(this).attr("disabled", false); // enable remove button
        }
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

    // Submit create Realm form when enter key is pressed in Realm name or Realm owner text boxes.
    $("#realm-create-name, #realm-create-owner").keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == 13) {
            $("#realm-create-submit").click();
        }
    });

    $("#realm-create-owner").keyup(function () {
        var realmName = $("#realm-create-name").val();
        if (realmName == "" || realmName.substring(realmName.length - "'s Realm".length) == "'s Realm") {
            var owner = $("#realm-create-owner").val();
            if (owner == "") {
                // No owner entered.
                $("#realm-create-name").val("");
            } else {
                // Owner specified, generate Realm name.
                $("#realm-create-name").val($("#realm-create-owner").val() + "'s Realm");
            }
        }
    });
});
