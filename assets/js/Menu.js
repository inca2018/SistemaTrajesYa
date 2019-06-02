function init(){

}

init();

function RecuperarArea(idArea) {
    //solicitud de recuperar Proveedor
    $.post("/Mantenimiento/Area/ObtenerArea", {
        "idArea": idArea
    }, function (data, status) {
        data = JSON.parse(data);
        console.log(data);
        $("#id Area").val(data.idArea);
        $("#AreaTitulo").val(data.Descripcion);
  
    }); 
}
