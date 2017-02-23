var timestap = 0;

function WaitForUser() {
    $.ajax({

        type: 'GET',
        url: 'userComet.php?timestamp=' + timestap,
        async: true,
        cache: false,
        success: function (data) {
            var json = data;
            $('#users').empty();
            var users = JSON.parse(json).split(";");
            for (var i = 0; i < users.length - 1; i++) {
                var temp = `<li>` + users[i] + `</li>`;
                $('#users').append(temp);
            }

            timestap = 1;
            setTimeout('WaitForUser()', 1000);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForUser()', 15000);
        }

    });
}
