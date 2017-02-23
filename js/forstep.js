function WaitForStep() {
    $.ajax({

        type: 'POST',
        url: 'stepComet.php',
        async: true,
        cache: false,
        success: function (data) {
            var json = JSON.parse(data);
            $('#time').html(json[0]);

            if (json[0] < json[1]) {
                setTimeout('WaitForStep()', 100);
                // WaitForStep();
            }
            else if (json[0] == json[1]) {
                let room = $('#room').val();
                $.ajax({
                    method: "POST",
                    url: 'timeover.php',
                    data: {room: room},
                    async: true,
                    cache: false,
                    success: function (data) {
                        var json = JSON.parse(data);
                        var games = json.split(';');
                        if (games[0] != 'i1' && games[0] != 'i2') {

                            var buf = '';
                            for (let i = 0; i < games.length; i++) {
                                let game = '<div>' + strOut(games[i]) + '</div>';
                                buf += game;

                            }
                            $('.itog').html(buf);
                            WaitForStep();
                        }
                        else {
                            var buf = '';
                            for (let i = 1; i < games.length; i++) {
                                let game = '<div>' + strOut(games[i]) + '</div>';
                                //let game = '<div>' + games[i] + '</div>';
                                buf += game;
                            }
                            if (json[1] == 1) {
                                buf += 'FINISH!!!!  YOU WIN!!! ' + '<br>';
                            }
                            else {
                                buf += 'FINISH!!!!  YOU LOSE!!! ' + '<br>';
                            }
                            $('.itog').html(buf);
                        }
                    },

                    error: function (XMLHttpRequest, textStatus, errorThrown) {

                        // alert("Error: " + textStatus + "(" + errorThrown +")");
                    }

                });
            }


        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForStep()', 15000);
        }

    });


}

function Step() {
    let player = $('#player').val();
    let room = $('#room').val();
    let figur = $('input:checked').val();
    $.ajax({
        type: 'GET',
        url: 'step.php?player=' + player + "&room=" + room + "&figur=" + figur,
        async: true,
        cache: false,
        success: function (data) {
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            // alert("Error: " + textStatus + "(" + errorThrown +")");
        }

    });

}

function Hint() {
    $.ajax({
        method: "POST",
        url: 'hint.php',
        async: true,
        cache: false,
        success: function (data) {
            var json = JSON.parse(data);
            $('#hintout').append('Competitor did step figur:  ' + json);
            $('#hint').css('display', 'none');
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {

            // alert("Error: " + textStatus + "(" + errorThrown +")");
        }

    });
}

function strOut(str) {
    if (str === '') return '';
    var mas = str.split(" ");

    rez = `Step: ${mas[0]}  You made figure: ${mas[1]}    Your oponet made figure: ${mas[2]}`;
    if (mas[3] == 1) {
        rez += " You  WIN!!!";
    }
    else {
        rez += " You  LOSE!!!";
    }
    return rez;
}