//var ruta="/SisGG/web/";
var ruta="/SisGG/web/app_dev.php/";
function isNumberKey(id,evt){
    var charCode = (evt.which) ? evt.which : event.keyCode

    if (charCode==46){
        for (var i=0;i< id.value.length ;i++){
            if (id.value.charAt(i)=='.')
                return false;
        }
    }
    if (charCode > 31 && (charCode < 45|| charCode < 46 || charCode > 57))
        return false;
    return true;
}
function isOnlyNumberKey(evt){
        
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
        
function pasarDecimal (n, cifras) {
    var  num=n.toFixed(cifras);
    return num;
       
}
function msjWC(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'warning',
        layout: 'bottomRight',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click'],
                });
            }
        }
    });

}
function msjIC(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'alert',
        layout: 'bottomRight',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click']
                });
            }
        }
    });
    return n;

}
function msjAC(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'information',
        layout: 'bottomRight',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click']
                });
            }
        }
    });
    return n;

}
function msjACentro(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'information',
        layout: 'center',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click']
                });
            }
        }
    });
    return n;

}

function msjEC(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'error',
        layout: 'bottomRight',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click']
                });
            }
        }
    });
    return n;

}

function msjSC(txt){
    var n = noty({
        text: '<h4 class="alert-heading">Atención!</h4> '+txt,
        type: 'success',
        layout: 'bottomRight',
        timeout: 2500, 
        closeWith: ['hover'],
        callback: {
            afterClose: function() {
                noty({
                    text: '<strong>Hehe!</strong> <br /> Sorry, you can catch me now.',
                    type: 'alert',
                    layout: 'topRight',
                    closeWith: ['click']
                });
            }
        }
    });
    return n;

}

function msjSINO(txt){

var n = noty({
  text: '<h4 class="alert-heading">Atención!</h4> <h5>'+txt+'</h5>',
    layout: 'center',
    buttons: [
    {addClass: 'btn btn-primary', text: 'Aceptar', onClick: function($noty) {

        // this = button element
        // $noty = $noty element
        $noty.close();
        noty({text: 'You clicked "Ok" button', type: 'success'});
      }
    },
    {addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
        $noty.close();
        noty({text: 'You clicked "Cancel" button', type: 'error'});
      }
    }
  ]
});
    return n;
}
function validarCuit(cuit) {
 
        if(cuit.length != 11) {
            return false;
        }
 
        var acumulado   = 0;
        var digitos     = cuit.split("");
        var digito      = digitos.pop();
 
        for(var i = 0; i < digitos.length; i++) {
            acumulado += digitos[9 - i] * (2 + (i % 6));
        }
 
        var verif = 11 - (acumulado % 11);
        if(verif == 11) {
            verif = 0;
        } else if(verif == 10) {
            verif = 9;
        }
 
        return digito == verif;
}
function mostrarPop(id){
    $(id).popover('toggle');
}
function cerrarPop(id){
    $(id).popover('hide')
}
function eliminarFila(e,id){
    e.preventDefault();
    $('#'+id).remove();
}

function nuevaFila(e,id) {
    e.preventDefault();
    // Get the data-prototype explained earlier
    var collectionHolder = $("#"+id);
    var prototype = collectionHolder.data('prototype');
    // get the new index
    var index = collectionHolder.data('index');
    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);
    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = collectionHolder.append(newForm);
    $('[data-rel="chosen"],[rel="chosen"]').chosen();
    inicializarFila(index);
}  
//Conversor de tasas
function cargarUMCVR(){
    $('#umCVR').load(ruta+"cargarUM", function() {             
        }); 
}
function cargarTasaCVR(combo){
    
    var name=combo.options[combo.selectedIndex].value;
    $('#tasa1CVR').load(ruta+"cargarTasa",{
        'name':name
    }, function() {             
        }); 
    $('#tasa2CVR').load(ruta+"cargarTasa",{
        'name':name
    }, function() {             
        }); 
}
    
function seleccionarUMCVR(tasa){       
    $.getJSON(ruta+"buscarUMdeTasa",{
        name:tasa
    },function (json){
        var combo=document.getElementById('um');
        seleccionarTasa(tasa);
        for (var i=0;i<combo.length;i++){
            if (combo.options[i].text==json.name){
                combo.options[i].selected="selected";                   
            }
        }
    });       
}
    
function seleccionarTasaCVR(tasa){
    var comboTasa=document.getElementById('sisgg_finalbundle_mercaderiatype_tasa');
    $(comboTasa).load(ruta+"seleccionarTasa",{
        'name':tasa
    }, function() {             
        });
}
function mostrarConversor(){
    cargarUMCVR();
    var combo = document.getElementById('umCVR');   
    cargarTasaCVR(combo);
    $('#conversorTasa').modal('toggle');
    
}
function tasaCVR (){
    $.getJSON(ruta+"tasaCVR",{
        'tasa1':$('#tasa1CVR').val(), 
        'tasa2':$('#tasa2CVR').val(),
        'cantidad':$('#cantidad1CVR').val()
    },function (json){      
        //alert(json.valor);
        $('#cantidad2CVR').val(json.valor);
            
    });  
}

//FIN COnversor

//adecuar de un json pasado por twig
function convertirJSON(n){
             var data = n.replace(/&quot;/g,"");
             data = data.replace(/@/g,"'");
             data='(' + data + ')';
             return eval(data);
}


var flag;
function mostrarPopProducto(id,txt, pop, t){      
    
    /*var table=pop.parentNode.parentNode;
    var rows = table.getElementsByTagName('tr');*/
    
   /* $(pop).popover('toggle');*/
    var abierto = $(pop).attr('abierto');
    if (abierto=="0"){
        
        var div = document.createElement('div');
        $(div).load(ruta+"popPE",{
            'id':id
        }, function() {             
            });
        $(pop).popover({
            html:true,           
            title:txt,
            content:div
        });
        $(pop).attr('abierto', "1");
        if (t)
            $(pop).popover('show');
    }else{
       //$(pop).attr('abierto', "0");
    }

   /* if (rows.length =='1'){
    }else{
        if (flag){
            $(flag).popover('hide');
            flag=false;
        }
        flag=pop;
    }   */  
}

function cerrarPopProducto(){

    $(flag).popover('hide');
}
function isDate(txtDate)
{
    var currVal = txtDate;
    if (currVal == '')
        return false;

    //Declare Regex  
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    var dtArray = currVal.match(rxDatePattern); // is format OK?

    if (dtArray == null)
        return false;

   //Checks for dd/mm/yyyy format.
    dtDay = dtArray[1];
    dtMonth= dtArray[3];
    dtYear = dtArray[5];  

    if (dtMonth < 1 || dtMonth > 12)
        return false;
    else if (dtDay < 1 || dtDay > 31)
        return false;
    else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31)
        return false;
    else if (dtMonth == 2)
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay > 29 || (dtDay == 29 && !isleap))
            return false;
    }
    return true;
}    
   function mostrar(p){


 $.fancybox.open([{
						href :'http://localhost/SisGG/web/uploads/imagenes/d1ca845bf042d151c0d6a134b58cf6aa99064f38.jpeg',
						title : 'My title'
					}], {
					padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,
  
				closeClick : true,
				width: 100,
				helpers : {
					overlay : null
				}
				});
}
 
//pads left
String.prototype.lpad = function(padString, length) {
	var str = this;
    while (str.length < length)
        str = padString + str;
    return str;
};
 
//pads right
String.prototype.rpad = function(padString, length) {
	var str = this;
        
    while (str.length < length)
        str = str + padString;
    return str;
};
//EXAMPLE
//var str = "5";
//alert(str.lpad("0", 5)); //result "00005"
//alert(str.rpad("0", 5)); //result "50000"



//Devuelve la fecha en string con formato dd/mm/yyyy
function getFechaFormat(date){
    var dia= date.getDate().toString();
    dia = dia.lpad('0',2);
    var mes = parseFloat(date.getMonth()) + 1;
    mes = mes.toString().lpad('0',2);
    var año=parseFloat(date.getYear())+ 1900;
    var f = dia+'/'+mes+'/'+año;
    return  f;
}
function lostFoc(ele){
    if (!($.isNumeric(ele.value) ))
        ele.value='0';
}

function entreDosNros(menor, mayor, num){
    if (num>=menor && mayor>=num)
        return true;
    return false;
}

 function selTodosImp(ele){
            var estado = $(ele).attr('estado');
            if (estado=='0'){
                $(ele).attr('style', false);
                $(ele).attr('style', 'color:white');
                $(ele).attr('estado', '1');
                $('.imprimirDatos').each(function (){
                    $(this).attr('checked', true);});
            }else{
                $(ele).attr('style', false);
                $(ele).attr('style', 'color:black');
                $(ele).attr('estado', '0');
                $('.imprimirDatos').each(function (){
                    $(this).attr('checked', false);});
            }
        }
