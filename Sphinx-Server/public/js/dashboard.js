// dashboard.js

/**
 * Update stats table.
 */
function updateStats()
{
    $.get(window.sphinx.dashboardUrl + "/ajax/stats", function (stats) {
        Object.keys(stats).forEach(function (stat) {
            var value = stats[stat];

            if (stat == "nodeOnline") {
                // Special case.
                if (value) {
                    $("#stat-nodeOnline").html('<span style="color:green;">Online</span>');
                } else {
                    $("#stat-nodeOnline").html('<span style="color:red;">Offline</span>');
                }
                return;
            }

            // Update stat field.
            $("#stat-" + stat).html(value);
        });
    });
}

$(document).ready(function () {
    // Set timer for updating stats.
    setInterval(updateStats, 5000); // every 5 seconds
});
