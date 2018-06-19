$(document).ready(function(){

    var api = " http://localhost:8888/smartepics/api/";

    $('body').on('click','.search', function() {

        $('.error-msg').html('');
        $(this).text('Buscando...');
        $(this).attr('disabled','disabled');

        $.ajax({
            'url': api + 'index.php?url=' + $("input[name='url']").val(),
            'type': 'get',
            'dataType': 'json',
            success: function(res) {
                var listImages = '';

                $('.searcher').toggle();
                $('.result').toggle();

                $('.title').html(res.title);
                $('.subtitle').html(res.rates + ' Avaliações, com média de ' + res.average + 'estrelas.');

                for(i=0; res.images.length > i; i++){
                    listImages += '<div class="col-md-2">';
                    listImages += '<div class="card">';
                    listImages += '<img class="card-img-top" src="'+res.images[i]+'" style="height: 150px">';
                    listImages += '</div>';
                    listImages += '</div>';
                }

                $('.gallery').append(listImages);
            },
            error: function(err) {
                $('.error-msg').append('<div class="alert alert-danger" role="alert">'+err.responseText+'</div>');
            },
            complete: function() {
                $('.search').text('Buscar Dados');
                $('.search').removeAttr('disabled');
            }
        });

    });


    $('body').on('click','.back', function() {
        $('.searcher').toggle();
        $('.result').toggle();
    });

});