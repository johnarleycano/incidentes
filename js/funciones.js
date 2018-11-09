function ajax(url, datos, tipo_respuesta, async=false){
    //Variable de exito
    var exito;

    // Esta es la petición ajax que llevará 
    // a la interfaz los datos pedidos
    $.ajax({
        url: url,
        data: datos,
        type: "POST",
        dataType: tipo_respuesta,
        async: async,
        success: function(respuesta){
            //Si la respuesta no es error
            if(respuesta){
                //Se almacena la respuesta como variable de éxito
                exito = respuesta;
            } else {
                //La variable de éxito será un mensaje de error
                exito = 'error';
            } //If
        },//Success
        error: function(respuesta){
            //Variable de exito será mensaje de error de ajax
            exito = respuesta;
        }//Error
    });//Ajax

    //Se retorna la respuesta
    return exito;
}// ajax