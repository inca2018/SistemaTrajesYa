// cargador
function Formato_Moneda(amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0)
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

    return amount_parts.join('.');
}

function SoloNumerosModificado(e, num, id) {
    var path = "#" + id;
    var da = $(path).val().length;
    if (da == num) {
        return false;
    } else {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46)) {
            return true;
        } else {
            return /\d/.test(String.fromCharCode(keynum));
        }
    }
}

function SoloLetras(e, num, id) {
    var path = "#" + id;
    var da = $(path).val().length;
    if (da == num) {
        return false;
    } else {
        var tecla = document.all ? tecla = e.keyCode : tecla = e.which;
        return !((tecla > 47 && tecla < 58) || tecla == 46);
    }
}

function Cargar(selector, Estado) {
    if (Estado) {
        $(selector).addClass('whirl double-up');
    } else {
        $(selector).removeClass('whirl double-up');
    }
}

/*$.fn.datepicker.dates['es'] = {
    days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    daysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    today: "Hoy",
    clear: "Clear",
    format: "mm/dd/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 0
};*/

function MenuActivo() {

    var path = location.pathname;
    var Arrpath = location.pathname.split('/');
    var menu = Arrpath[1];
    console.log(path);

    $(".pcoded-hasmenu").each(function () {

        if ($(this).attr("id") == menu) {
            $(this).addClass("active");
            $(this).addClass("pcoded-trigger");

        } else {
            $(this).removeClass("active");
            $(this).removeClass("pcoded-trigger");
        }

    });

    $(".opcionMenu").each(function () {

        var hijo = $(this).children('a');
        var valor = hijo.attr("href");
        if (valor == path) {
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });




}

function CerrarSession() {
    swal({
            title: "Finalizar Sesión?",
            text: "Esta seguro de finalizar la sesión",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Finalizar!",
            closeOnConfirm: false
        },
        function () {
            $.redirect("/Menu/CerrarSession/");
        });
}

function notify(message, from, align, icon, type, animIn, animOut) {

    $.growl({
        icon: icon,
        title: "Sistema: ",
        message: message,
        url: ''
    }, {
        element: 'body',
        type: type,
        allow_dismiss: true,
        placement: {
            from: from,
            align: align
        },
        offset: {
            x: 30,
            y: 30
        },
        spacing: 10,
        z_index: 999999,
        delay: 2500,
        timer: 1000,
        url_target: '_blank',
        mouse_over: false,
        animate: {
            enter: animIn,
            exit: animOut
        },
        icon_type: 'class',
        template: '<div data-growl="container" class="alert" role="alert">' +
            '<button type="button" class="close" data-growl="dismiss">' +
            '<span aria-hidden="true">&times;</span>' +
            '<span class="sr-only">Close</span>' +
            '</button>' +
            '<span data-growl="icon"></span>' +
            '<span data-growl="title"></span>' +
            '<span data-growl="message"></span>' +
            '<a href="#" data-growl="url"></a>' +
            '</div>'
    });
};

function mensaje_warning(mensaje) {
    notify(mensaje, "bottom", "right", false, "warning", false, false);
}

function mensaje_success(mensaje) {
    notify(mensaje, "bottom", "right", false, "success", false, false);
}

function RecuperarFechaActualLimit() {
    var Fecha = "";
    let date = new Date()

    let day = date.getDate() - 1
    let month = date.getMonth() + 1
    let year = date.getFullYear()

    if (month < 10) {
        if (day < 10) {
            return Fecha = (`${year}/0${month}/0${day}`);
        } else {
            return Fecha = (`${year}/0${month}/${day}`);
        }

    } else {
        if (day < 10) {
            return Fecha = (`${year}/${month}/0${day}`);
        } else {
            return Fecha = (`${year}/${month}/${day}`);
        }
    }
}

function RecuperarFechaAyerLimit() {
    var Fecha = "";
    let date = new Date()

    let day = date.getDate() - 2
    let month = date.getMonth() + 1
    let year = date.getFullYear()

    if (month < 10) {
        if (day < 10) {
            return Fecha = (`${year}/0${month}/0${day}`);
        } else {
            return Fecha = (`${year}/0${month}/${day}`);
        }

    } else {
        if (day < 10) {
            return Fecha = (`${year}/${month}/0${day}`);
        } else {
            return Fecha = (`${year}/${month}/${day}`);
        }
    } 
}
function mostrarFecha(days){
    var Fecha="";
    milisegundos=parseInt(35*24*60*60*1000);
 
    fecha=new Date();
    day=fecha.getDate();
    // el mes es devuelto entre 0 y 11
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
 
    
    //Obtenemos los milisegundos desde media noche del 1/1/1970
    tiempo=fecha.getTime();
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos=parseInt(days*24*60*60*1000);
    //Modificamos la fecha actual
    total=fecha.setTime(tiempo+milisegundos);
    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();

     if (month < 10) {
        if (day < 10) {
            return Fecha = (`${year}/0${month}/0${day}`);
        } else {
            return Fecha = (`${year}/0${month}/${day}`);
        }

    } else {
        if (day < 10) {
            return Fecha = (`${year}/${month}/0${day}`);
        } else {
            return Fecha = (`${year}/${month}/${day}`);
        }
    } 
          
}

window.onload = function () {
    MenuActivo();





};
