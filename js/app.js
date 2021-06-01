$(function () {
    var error = function(errmsg) {
        $('#errmsg').html(errmsg);
        $('#error').show();
    };

    var accept = function (resp) {
        var chunks = resp.split(":", 2);

        if (chunks[0] == "error") {
            error(chunks[1]);
            return;
        }
        if (chunks[1] == "purchase ok") {
            $('#txtAccountName').prop('disabled', false);
            $('#txtPassword').prop('disabled', false);
            $('#btnRegister').prop('disabled', false);
            $("#registration").show();
        }
    };

    // hashcash register form - submit action
    $("#btnRegister").bind("click", function () {
        $.ajax({
            url: "register.php",
            type: "POST",
            data: {
                username: $("#txtAccountName").val(),
                password: $("#txtPassword").val()
            }
        }).done(function (resp) {
            var chunks = resp.split(":");

            if (chunks[0] == "error") {
                error(chunks[1]);
            } else if (chunks[0] == "ok") {
                // alert(chunks[1]);
                window.location = "https://www.hackint.org/transport/tor";
            } else {
                error("Unexpected server response");
            }
        });
    });

    window.setInterval(function () {
        // keep session alive
        jQuery.ajax({
            url: "backend.php",
            type: 'GET',
            data: {
                'action': 'ping'
            }
        });
    }, 60000);

    $.ajax({
        url: "backend.php",
        type: "GET",
        data: {action: "order"}
    }).done(function (resp) {
        console.log(resp);
        var chunks = resp.split(":", 2);

        if (chunks[0] == "error") {
            error(chunks[1]);
            return;
        }

        var parts = chunks[1].split(";");
        var salt = parts[0];
        var payload = parts[1];
        var numRounds = parseInt(parts[2]);

        var workers = [];
        var workerCount = navigator.hardwareConcurrency || 4; // not supported in firefox, fallback to 4 threads
        var workSize = 10**7/2;  // keep in sync with common.php
        var perWorker = workSize / workerCount;

        var onMessageFunction = function (pid) {
            return function (resp) {
                console.log("[#" + pid + "] " + resp.data['event'] + ": " + resp.data['text']);
                if (resp.data["event"] == "finished") {
                    var proof = resp.data["value"].toString();
                    // insert proof
                    $('#proof').val(proof);
                    // hide worker monitor
                    $('#monitor').hide();
                    // enable form
                    $('#txtAccountName').prop('disabled', false);
                    $('#txtPassword').prop('disabled', false);
                    $('#btnRegister').prop('disabled', false);
                    $('#registration').show();
                    jQuery.ajax({
                        url: "backend.php",
                        type: "GET",
                        data: {
                            action: "purchase",
                            secret: proof
                        }
                    }).done(accept);

                    // Terminate Workers
                    for (var i = 0; i < workers.length; i++) {
                        console.log("Terminating Worker #" + i);
                        workers[i].terminate();
                    }
                } else if (resp.data["event"] == "progress") {
                    // Update Worker Progress
                    $('#' + pid).html(resp.data["text"]);
                }
            };
        };

        // Create Workers
        for (var i = 0; i < workerCount; i++) {
            console.log("Starting Worker #" + i);

            // view
            var view = document.createElement('div');

            var title = document.createElement('span');
            title.className = 'worker';
            title.textContent = "Worker #" + i;
            view.appendChild(title);

            var progress = document.createElement('div');
            progress.id = 'progress' + i;
            view.appendChild(progress);

            document.getElementById('workers').appendChild(view);

            // worker
            var worker = new Worker("js/hashcash.js");
            worker.postMessage([salt, payload, perWorker * i, perWorker * (i + 1), numRounds]);
            worker.onmessage = onMessageFunction("progress" + i.toString());
            workers.push(worker);
        }

        $('#monitor').show();
    });
});
