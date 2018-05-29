function mostarVentana(){
	$("#ventana").css("display","block");
}
function cerrarVentana(){
	$("#ventana").css("display","none");
}

function Siguiente(){
	$("#pagina1").css("display","none");
	$("#pagina2").css("display","block");
}

function gotoParticipar(){
	$("#intro").css("display","none");
	$("#participar").css("display","block");
}

function EscribirTexto(){
	$(".texto_intro").css("display","none");
	$(".mitexto").css("display","block");
	$(".mitexto").focus();
}

function mostrarFinal(){
	$("#formulario").css("display","none");
	$("#botones_formulario").css("display","none");

	$("#form_completado").css("display","block");
}

function votar(iditem){

	$.get( "votar.php", { cid: iditem } )
  .done(function( data ) {
    alert( "Gracias por tu voto");
  });
		$('#'+iditem).addClass('votado');
}

/*---------- Menu web --------------- */
controlNavegacion=0;


function navegacion(){
	if(controlNavegacion==0){
		/*abrimos*/
		$("#menu_btn").removeClass("abrir").addClass("cerrar");
		$("#menunav").addClass("abierto");

		controlNavegacion=1;
	}else{
		/*cerramos*/
		$("#menu_btn").removeClass("cerrar").addClass("abrir");
		$("#menunav").removeClass("abierto");

		controlNavegacion=0;
	}
}

function generateImagen() {

}
