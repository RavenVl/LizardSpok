var timestap = 0;

function WaitForRoom() {
    $.ajax({

        type: 'GET',
        url: 'roomComet.php?timestamp=' + timestap,
        async: true,
        cache: false,
        success: function (data) {
            var json = data;
            $('#rooms').empty();
            var rooms = JSON.parse(json).split(";");
            for (var i = 0; i < rooms.length - 1; i++) {
                let roomId = rooms[i].split(' ')[0];
                var formEnterRoom = `<form action="../php/gamerun2.php" method="post">
                                         <input type="hidden" name="idroom" value=${roomId}>
                                         <button class="vhod">Enter</button>
                                       </form>`;
                var temp = `<li><span class="text">` + rooms[i] + '</span>' + formEnterRoom + `</li>`;
                //var temp = `<li>`+'22' + `</li>`;
                $('#rooms').append(temp);
            }

            timestap = 1;
            setTimeout('WaitForRoom()', 1000);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForRoom()', 15000);
        }

    });
}
