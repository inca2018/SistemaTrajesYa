var Accion=false;

function init(){
    IniciarComponentes();
    var idProducto=$("#idProducto").val();
    RecuperarProducto(idProducto);
    ListarGeneros(idProducto);

}

function IniciarComponentes(){

     $('#SeleccionarTodo').on('click',function() {
        $('#selectGeneros').multiSelect('select_all');
        return false;
    });
    $('#QuitarTodo').on('click',function() {
        $('#selectGeneros').multiSelect('deselect_all');
        return false;
    });

}
function ListarGeneros(idProducto){
     $.post("/Mantenimiento/AsignacionGenero/ListarGeneros",function (ts) {
      $("#selectGeneros").empty();
      $("#selectGeneros").append(ts);
        FuncionSelecMultiple();
        ObtenerAsignaciones(idProducto);
   });
}

function FuncionSelecMultiple(){
     $('.buscadorGenero').multiSelect({
        selectableHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Buscar Genero'>",
        selectionHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Buscar Genero'>",
        afterInit: function(ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(values) {
            this.qs1.cache();
            this.qs2.cache();
            if(!Accion){
                AgregarGenero(values);
               }

        },
        afterDeselect: function(values) {
            this.qs1.cache();
            this.qs2.cache();
            if(!Accion){
                QuitarGenero(values);
               }

        }
    });
}
function ObtenerAsignaciones(idProducto){
     $.post("/Mantenimiento/AsignacionGenero/ObtenerAsignaciones",{idProducto:idProducto},function (data,status) {
        data = JSON.parse(data);
         console.log(data);
         Accion=true;
         for (var x=0;x<data.length;x++){
          $('#selectGeneros').multiSelect('select',data[x]);
        }
         Accion=false;
     });
}

function RecuperarProducto(idProducto) {
    $.post("/Mantenimiento/Producto/ObtenerProducto", {
        "idProducto": idProducto
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
          $("#DetalleTitulo").empty();
          $("#DetalleTitulo").html(data.NombreProducto);
    });
}


function AgregarGenero(ArregloId){
    var idProducto=$("#idProducto").val();
    for (var x=0;x<ArregloId.length;x++){
         AjaxAgregarGenero(idProducto,ArregloId[x]);
    }
}

function AjaxAgregarGenero(idProducto,idGenero){
    $.post("/Mantenimiento/AsignacionGenero/AgregarGenero", {
        "idProducto": idProducto,
        "idGenero":idGenero
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
    });
}

function QuitarGenero(ArregloId){
     var idProducto=$("#idProducto").val();
     for (var x=0;x<ArregloId.length;x++){
       AjaxQuitarGenero(idProducto,ArregloId[x]);
    }
}

function AjaxQuitarGenero(idProducto,idGenero){
    $.post("/Mantenimiento/AsignacionGenero/QuitarGenero", {
        "idProducto": idProducto,
        "idGenero":idGenero
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

    });
}

init();
