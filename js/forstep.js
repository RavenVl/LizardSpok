function WaitForStep()
{
    $.ajax({

        type: 'POST',
        url: 'stepComet.php',
        async: true,
        cache: false,
        success: function(data){
            var json = JSON.parse(data);
            $('#time').html(json[0]);

           if(json[0] < json[1]){
               //setTimeout('WaitForStep()',1000);
               WaitForStep();
           }
            else if(json[0] == json[1]){
               let room = $('#room').val();
               $.ajax({
                   method: "POST",
                   url: 'timeover.php',
                   data: {room: room},
                   async: true,
                   cache: false,
                   success: function(data){
                       var json =JSON.parse(data);
                       var games=json.split(';');
                       if(games[0]!='i1' && games[0]!='i2'){
                          
                           var buf = '';
                           for(let i = 0; i<games.length; i++){
                               let game ='<div>'+games[i]+'</div>';
                               buf+=game;

                           }
                           $('.itog').html(buf);
                           WaitForStep();
                       }
                       else{
                           var buf = '';
                           for(let i = 1; i<games.length; i++) {
                               let game = '<div>' + games[i] + '</div>';
                               buf += game;
                           }
                           buf += 'FINISH!!!! WIN Player' + json[1] + '<br>';
                           $('.itog').html(buf);
                       }
                   },

                   error: function(XMLHttpRequest, textStatus, errorThrown){

                       // alert("Error: " + textStatus + "(" + errorThrown +")");
                   }

               });
           }


        },

        error: function(XMLHttpRequest, textStatus, errorThrown){

            // alert("Error: " + textStatus + "(" + errorThrown +")");

            setTimeout('WaitForStep()',15000);
        }

    });


}

function Step() {
    let player = $('#player').val();
    let room = $('#room').val();
    let figur = $('input:checked').val();
    $.ajax({
        type: 'GET',
        url: 'step.php?player='+ player+"&room="+room+"&figur="+figur,
        async: true,
        cache: false,
        success: function(data){
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){
            // alert("Error: " + textStatus + "(" + errorThrown +")");
        }

    });
    // $.ajax({
    //     method: "POST",
    //     url: 'step.php',
    //     data: {player: player, room: room, figur: figur },
    //     async: true,
    //     cache: false,
    //     success: function(data){
    //         let gson = data;
    //         $('#go').fadeOut();
    //     },
    //
    //     error: function(XMLHttpRequest, textStatus, errorThrown){
    //
    //         // alert("Error: " + textStatus + "(" + errorThrown +")");
    //     }
    //
    // });
}