var timestap = 0;

function WaitForGame()
{
    $.ajax({

        type: 'GET',
        url: 'gameComet.php?timestamp='+ timestap,
        async: true,
        cache: false,
        success: function(data){
            var json = data;
            let nameUser2 = JSON.parse(json).split(" ")[2];
            $('#user2').val(nameUser2);
            $('#run').css('display','block');
            timestap = 1;
            setTimeout('WaitForGame()',1000);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForGame()',15000);
        }

    });
}
