/*************************************
 * Librería de Funciones JavaScript
 * @since  3/12/2016
 *************************************/
 
metaCollection = document.getElementsByTagName('meta'); 

for (i=0;i<metaCollection.length;i++) { 
    nameAttribute = metaCollection[i].name.search(/baseurl/);
    if (nameAttribute!= -1) { 
        var base_url = metaCollection[i].content;
    } 
}

$(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 400) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
    
    //********************************************************************************************
    //* 1) Establece el valor máximo de caracteres que pueden ir en una caja de texto.
    //********************************************************************************************
    $.fn.maxlength = function (expresion) {
        return this.keypress(function (event) {
            if ((event.which == 8) || (event.which == 0))
                return true;
            else if ($(this).val().length < expresion)
                return true;
            else
                return false;
        });
    };

    //*******************************************************************************************
    //* 2) Bloquea el ingreso de caracteres de texto en una caja de texto. Solo permite números
    //*******************************************************************************************
    $.fn.bloquearTexto = function () {
        return this.keypress(function (event) {
            if ((event.which == 8) || (event.which == 0) || (event.which == 45))
                return true;
            if ((event.which >= 48) && (event.which <= 57))
                return true;
            else
                return false;
        });
    };

    //******************************************************************************************
    //* 3) Bloquea el ingreso de caracteres numericos en una caja de texto. Solo permite letras
    //******************************************************************************************
    $.fn.bloquearNumeros = function () {
        return this.keypress(function (event) {
            if ((event.which < 48) || (event.which > 57))
                return true;
            else
                return false;
        });
    };

    //******************************************************************************************
    //* 4) Convierte el contenido de una caja de texto todo a mayusculas
    //******************************************************************************************
    $.fn.convertirMayuscula = function () {
        return this.blur(function (event) {
            $(this).val($(this).val().toUpperCase());
        });
    };

    //******************************************************************************************
    //* 5) Convierte el contenido de una caja de texto todo a minusculas
    //******************************************************************************************
    $.fn.convertirMinuscula = function () {
        return this.blur(function (event) {
            $(this).val($(this).val().toLowerCase());
        });
    };

    //******************************************************************************************
    //* 6) verificar que el contenido no sean solo espacios
    //******************************************************************************************
    $.fn.verificaEspacios = function () {
        return this.blur(function (event) {
            var ele = $(this).val();
            //alert ("aqui"+ele);
            var tama = ele.length;
            if ((vacio(ele) == false) && (tama > 0)) {
                alert("Introduzca un cadena de texto.")
                $(this).val("");
            }
        });
    };
    //******************************************************************************************
    //* 7) verificar que el contenido no sean solo espacios
    //******************************************************************************************
    $.fn.minlength = function (expresion) {
        return this.blur(function (event) {
            var ele = $(this).val();
            var tama = ele.length;
            if ((tama < expresion)) {
                alert("Debe ser m\u00ednimo de " + expresion + " digitos")
                $(this).val("");
            }
        });
    };

    //****************************************************************************
    //* Valida que el valor por defecto de un comboBox no se haya seleccionado 
    //****************************************************************************
    $.validator.addMethod("comboBox", function (value, element, param) {
        var idx = (param).toString();
        if ($(element).val() == idx)
            return false;
        else
            return true;
    }, "");


    //*****************************************************************************************
    //* Ejecuta una funcion ajax para actualizar un comboBox
    //*****************************************************************************************
    $.fn.cargarCombo = function (element, url) {
        return this.change(function (event) {

            $("#" + element + " option[value='-']").prop('selected', true)
            $.ajax({
                type: "POST",
                url: url,
                data: "id=" + $(this).val(),
                dataType: "html",
                cache: false,
                success: function (html) {
                    var target = "#" + element;
                    $(target).html("");
                    $(html).appendTo(target);
                },
                error: function (result) {
                    $("#" + element + " option[value='-']").attr("selected", "selected");
                }
            });
        });
    };

    //************************************************************************
    //* Valida que una fecha digitada sea valida, con formato dd/mm/yyy
    //************************************************************************
    jQuery.validator.addMethod("dateFormatValid", function (value, element) {
        var check = false;
        //var re = /^\d{1,2}\.\d{1,2}\.\d{4}$/;
        var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
        if (re.test(value)) {
            var adata = value.split('/');
            var dd = parseInt(adata[0], 10);
            var mm = parseInt(adata[1], 10);
            var yyyy = parseInt(adata[2], 10);
            var xdata = new Date(yyyy, mm - 1, dd);
            if ((xdata.getFullYear() === yyyy) && (xdata.getMonth() === mm - 1) && (xdata.getDate() === dd)) {
                check = true;
            } else {
                check = false;
            }
        } else {
            check = false;
        }
        return this.optional(element) || check;
    });

    //****************************************************************************************************************
    //** Compara y valida que el valor de una caja de texto contra una expresion completa escrita en jQuery
    //****************************************************************************************************************
    $.validator.addMethod("expresion", function (value, element, param) {
        var comp = convertirExpresion(param);
        if (comp) {
            return false;
        } else {
            return true;
        }
    }, "");

    $.validator.addMethod("expresion2", function (value, element, param) {
        var comp = convertirExpresion(param);
        if (comp) {
            return false;
        } else {
            return true;
        }
    }, "");
    
    $.fn.validarCorreo = function () {
        // Expresion regular para validar el correo
        var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        // Se utiliza la funcion test() nativa de JavaScript
        if (regex.test($(this).val().trim())) {
            return true;
        } else {
            return false;
        }
    };
    
    $.fn.soloTexto = function () {
        return this.keypress(function (event) {
            if ((event.which == 8) || (event.which == 0) || (event.which == 45) || (event.which == 189) || (event.which == 190))
                return true;
            if ((event.which >= 97) && (event.which <= 122))
                return true;
            if ((event.which >= 65) && (event.which <= 90))
                return true;
            else
                return false;
        });
    };
});