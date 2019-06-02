function init() {
    var idEncuesta = $("#idEncuesta").val();
    if (idEncuesta != null) {
        Generar(idEncuesta);
    }

    $("#FormularioEncuesta").on("submit", function (e) {
        RegistroEncuesta(e);
    });
}

function Generar(idEncuesta) {
    $.post("/Encuesta/RecuperarEncuestaCompleta", {
        "idEncuesta": idEncuesta
    }, function (data, status) {

        data = JSON.parse(data);
        if (data.EncuestaNombre == null) {
            $("#FormularioEncuesta").hide();
            $("#ErrorEncuesta").show();
        } else {
            $("#ErrorEncuesta").hide();
            $("#FormularioEncuesta").show();
            var Encuesta = "";
            var TituloEncuesta = data.EncuestaNombre;
            var Detalle = data.EncuestaDetalle;
            $("#tituloEncuesta").empty();
            $("#tituloEncuesta").text(TituloEncuesta);
            $("#DetalleEncuesta").empty();
            $("#DetalleEncuesta").text(Detalle);
            var ArregloPreguntas = data.Preguntas.split("&");
            ArregloPreguntas.pop();

            for (var i = 0; i < ArregloPreguntas.length; i++) {
                var Pregunta = ArregloPreguntas[i].split("|");
                Encuesta = AgregarTipoPregunta(Pregunta, Encuesta);
            }
            Encuesta = Encuesta + "<hr>";
            $("#CuerpoEncuesta").html(Encuesta);
        }

    });
}

function AgregarTipoPregunta(Pregunta, Encuesta) {
    Encuesta = Encuesta + ' <div class="row Titulopregunta center_element">' +
        '<div class="col-10 col-12-xsmall">' +
        '<p class="texto-12px" align="justify">' + Pregunta[1] + '</p>' +
        '</div>' +
        ' </div>';
    if (Pregunta[2] == 1) {
        Encuesta = Encuesta + '<div class="row Opcion Titulopregunta center_element m-5" data-tipo="1" data-contador="' + Pregunta[3] + '">' +
            '<div class="col-2 col-2-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion1-' + Pregunta[0] + '" name="satisfaccion-' + Pregunta[0] + '" value="1" >' +
            '<label class="texto-12px" for="opcion1-' + Pregunta[0] + '">1</label>' +
            '</div>' +
            '<div class="col-2 col-2-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion2-' + Pregunta[0] + '" name="satisfaccion-' + Pregunta[0] + '" value="2">' +
            '<label class="texto-12px"  for="opcion2-' + Pregunta[0] + '">2</label>' +
            '</div>' +
            '<div class="col-2 col-2-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion3-' + Pregunta[0] + '" name="satisfaccion-' + Pregunta[0] + '" value="3">' +
            '<label class="texto-12px"  for="opcion3-' + Pregunta[0] + '">3</label>' +
            '</div>' +
            '<div class="col-2 col-2-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion4-' + Pregunta[0] + '" name="satisfaccion-' + Pregunta[0] + '" value="4">' +
            '<label class="texto-12px"  for="opcion4-' + Pregunta[0] + '">4</label>' +
            '</div>' +
            '<div class="col-2 col-2-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion5-' + Pregunta[0] + '" name="satisfaccion-' + Pregunta[0] + '" value="5">' +
            '<label class="texto-12px"  for="opcion5-' + Pregunta[0] + '">5</label>' +
            '</div>' +
            '</div>';
    } else if (Pregunta[2] == 2) {
        Encuesta = Encuesta + ' <div class="row Opcion Titulopregunta center_element m-5"  data-tipo="2" data-contador="' + Pregunta[3] + '">' +
            '<div class="col-4 col-4-small">' +
            '<input class="pregunta2" data-contador="' + Pregunta[3] + '" data-tipo="2" data-pregunta="' + Pregunta[0] + '" type="radio" id="opcionA-' + Pregunta[0] + '" name="condicion-' + Pregunta[0] + '" value="1" >' +
            '<label class="texto-12px"  for="opcionA-' + Pregunta[0] + '">SI</label>' +
            '</div>' +
            '<div class="col-4 col-4-small">' +
            '<input class="pregunta2" data-contador="' + Pregunta[3] + '" data-tipo="2" data-pregunta="' + Pregunta[0] + '" type="radio" id="opcionB-' + Pregunta[0] + '" name="condicion-' + Pregunta[0] + '" value="2">' +
            '<label class="texto-12px"  for="opcionB-' + Pregunta[0] + '">NO</label> ' +
            '</div>' +
            '<div class="col-4 col-4-small">' +
            '<input class="pregunta2" data-contador="' + Pregunta[3] + '" data-tipo="2" data-pregunta="' + Pregunta[0] + '" type="radio" id="opcionC-' + Pregunta[0] + '" name="condicion-' + Pregunta[0] + '" value="3">' +
            '<label class="texto-12px" for="opcionC-' + Pregunta[0] + '">NO SABE/NO OPINA</label>' +
            '</div>' +
            '</div>';
    } else if (Pregunta[2] == 3) {
        Encuesta = Encuesta + '<div class="row Opcion Titulopregunta center_element m-5" data-tipo="3" data-contador="' + Pregunta[3] + '">' +
            '<div class="col-10 col-12-xsmall">' +
            ' <textarea class="pregunta3" data-tipo="3" data-pregunta="' + Pregunta[0] + '" placeholder="Ingrese Respuesta" rows="2" name="descripcion-' + Pregunta[0] + '"></textarea>' +
            '</div> ' +
            '</div>';
    } else if (Pregunta[2] == 4) {
        Encuesta = Encuesta + '<div class="row Opcion Titulopregunta center_element m-5" data-tipo="1" data-contador="' + Pregunta[3] + '">' +
            '<div class="col-3 col-3-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion1-' + Pregunta[0] + '" name="satisfaccion2-' + Pregunta[0] + '" value="1" >' +
            '<label class="texto-12px" for="opcion1-' + Pregunta[0] + '">MALO</label>' +
            '</div>' +
            '<div class="col-3 col-3-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion2-' + Pregunta[0] + '" name="satisfaccion2-' + Pregunta[0] + '" value="2">' +
            '<label class="texto-12px"  for="opcion2-' + Pregunta[0] + '">REGULAR</label>' +
            '</div>' +
            '<div class="col-3 col-3-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion3-' + Pregunta[0] + '" name="satisfaccion2-' + Pregunta[0] + '" value="3">' +
            '<label class="texto-12px"  for="opcion3-' + Pregunta[0] + '">BUENO</label>' +
            '</div>' +
            '<div class="col-3 col-3-small">' +
            '<input class="pregunta1" data-contador="' + Pregunta[3] + '" data-tipo="1" data-pregunta="' + Pregunta[0] + '"  type="radio" id="opcion4-' + Pregunta[0] + '" name="satisfaccion2-' + Pregunta[0] + '" value="4">' +
            '<label class="texto-12px"  for="opcion4-' + Pregunta[0] + '">MUY BUENO</label>' +
            '</div>' +

            '</div>';
    }
    return Encuesta;
}


function RegistroEncuesta(event) {
    //cargar(true);
    event.preventDefault(); 
     
    //No se activará la acción predeterminada del evento
    var error = "";
    var ArregloRespuesta = "";

    var idEnviado = $("#idEnviado").val();

    $(".Opcion").each(function () {
    
        var pregunta = $(this).data("contador");
        var hijos = $(this).children();
        var tipo = $(this).data("tipo");
        var Seleccion = false;

        hijos.each(function () {
            if (tipo == 3 || tipo == "3") {
                var textArea = $(this).children("textarea");
                if (textArea.val() != "") {
                    Seleccion = true;
                }
            } else {
                var input = $(this).children("input");
                if (input.prop('checked')) {
                    Seleccion = true;
                }
            }

        });
        if (!Seleccion) {
            error = error + "La Pregunta Nº " + pregunta + "<br>";
        }
    });


    console.log(error); 

    //Verifica si la variable error tiene informacion para poder continuar con el registro.
    if (error == "") {
        $("#botonEncuesta").attr("disabled",true);   

        $("input[class='pregunta1']:checked").each(function () {
            var tipo = $(this).data("tipo");
            var pregunta = $(this).data("pregunta");
            var valor = $(this).val();
            var pregunta = pregunta + "||" + valor + "-";
            ArregloRespuesta = ArregloRespuesta + pregunta;
        });

        $("input[class='pregunta2']:checked").each(function () {
            var tipo = $(this).data("tipo");
            var pregunta = $(this).data("pregunta");
            var valor = $(this).val();

            var pregunta = pregunta + "||" + valor + "-";
            ArregloRespuesta = ArregloRespuesta + pregunta;
        });

        $("textarea[class='pregunta3']").each(function () {
            var tipo = $(this).data("tipo");
            var pregunta = $(this).data("pregunta");
            var valor = $(this).val();

            var pregunta = pregunta + "|" + valor + "|0-";
            ArregloRespuesta = ArregloRespuesta + pregunta;

        });

        ArregloRespuesta = ArregloRespuesta.substring(0, ArregloRespuesta.length - 1);
        console.log(ArregloRespuesta);
        
        $.post("/Encuesta/RegistrarResultados", {
             "ArregloRespuesta": ArregloRespuesta,
             "CodigoEnvio": idEnviado
         }, function (data, status) {
             data = JSON.parse(data);
             console.log(data);

             var Error = data.Error;
             var Mensaje = data.Mensaje;

             if (!Error) {
                 $("#ModuloRespuesta").show();
                 $("#FormularioEncuesta").hide();
             } else {
                 $('#FormularioEncuesta')[0].reset();
                 notificar_danger(Mensaje);
             }

         });   
    } else {
        //Mostrar mensaje error
        notificar_warning("Complete Las siguientes Preguntas:<br>" + error);
    }





}

init();
