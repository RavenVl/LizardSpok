var timestap = 0;

function WaitForUser2()
{
    $.ajax({

        type: 'GET',
        url: 'user2Comet.php?timestamp='+ timestap,
        async: true,
        cache: false,
        success: function(data){
            var json = data;
            let nameUser2 = JSON.parse(json).split(" ")[2];
            $('#user2').val(nameUser2);
            $('#run').css('display','block');
            timestap = 1;
            setTimeout('WaitForUser2()',1000);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForUser2()',15000);
        }

    });
}
