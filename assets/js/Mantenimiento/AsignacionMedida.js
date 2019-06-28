var Accion=false;

function init(){
    IniciarComponentes();
    var idProducto=$("#idProducto").val();
    RecuperarProducto(idProducto);
    ListarMedidas(idProducto);

}

function IniciarComponentes(){

     $('#SeleccionarTodo').on('click',function() {
        $('#selectMedidas').multiSelect('select_all');
        return false;
    });
    $('#QuitarTodo').on('click',function() {
        $('#selectMedidas').multiSelect('deselect_all');
        return false;
    });

}
function ListarMedidas(idProducto){
     $.post("/Mantenimiento/AsignacionMedida/ListarMedidas",function (ts) {
      $("#selectMedidas").empty();
      $("#selectMedidas").append(ts);
        FuncionSelecMultiple();
        ObtenerAsignaciones(idProducto);
   });
}

function FuncionSelecMultiple(){
     $('.buscadorMedida').multiSelect({
        selectableHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Buscar Medida'>",
        selectionHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='Buscar Medida'>",
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
                AgregarMedida(values);
               }

        },
        afterDeselect: function(values) {
            this.qs1.cache();
            this.qs2.cache();
            if(!Accion){
                QuitarMedida(values);
               }

        }
    });
}
function ObtenerAsignaciones(idProducto){
     $.post("/Mantenimiento/AsignacionMedida/ObtenerAsignaciones",{idProducto:idProducto},function (data,status) {
        data = JSON.parse(data);
         console.log(data);
         Accion=true;
         for (var x=0;x<data.length;x++){
          $('#selectMedidas').multiSelect('select',data[x]);
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


function AgregarMedida(ArregloId){
    var idProducto=$("#idProducto").val();
    for (var x=0;x<ArregloId.length;x++){
         AjaxAgregarMedida(idProducto,ArregloId[x]);
    }
}

function AjaxAgregarMedida(idProducto,idMedida){
    $.post("/Mantenimiento/AsignacionMedida/AgregarMedida", {
        "idProducto": idProducto,
        "idMedida":idMedida
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
    });
}

function QuitarMedida(ArregloId){
     var idProducto=$("#idProducto").val();
     for (var x=0;x<ArregloId.length;x++){
       AjaxQuitarMedida(idProducto,ArregloId[x]);
    }
}

function AjaxQuitarMedida(idProducto,idMedida){
    $.post("/Mantenimiento/AsignacionMedida/QuitarMedida", {
        "idProducto": idProducto,
        "idMedida":idMedida
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);

    });
}

init();
