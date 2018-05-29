var parallaxInstance, contador;
/* NUMERO TOTAL DE ITEMS EN LA GALERIA */
var numero_Items=$('#totalc').val();
var numero_Items2=$('#totalcr').val();
function iniciarParallax(){
	if(DesactivarParallax=="SI"){
		$("#capa2").hide();
		$("#capa1").hide();
		$("#capa3").show();
	}else{
		scene = document.getElementById('habitacion');
		parallaxInstance = new Parallax(scene);
		parallaxInstance.disable();
	}
}
function animacionSPRITE(){
	if(DesactivarSprite=="SI"){
		TweenMax.to($('#login'), 0.3, {opacity:0,delay:1});
		TweenMax.to($('#entrada'), 1.5, {x:-1080, delay:1.3, ease:Power3.easeInOut});
		TweenMax.to($('#capa3'), 1.5, {x:-1080, delay:1.3, ease:Power3.easeInOut});
		setTimeout(function(){
				$('#entrada').hide();
		},2800);
	}else{
		numero=contador*(100);
		TweenMax.to($('#login'), 0.3, {opacity:0});
		setTimeout(function(){
				$('#login').hide();
		},300);
		setTimeout(function(){
			$('#entrada').css("background-position-x","-"+numero+"%");
			if(contador<3){
				contador++;
				animacionSPRITE();
			}else{
				contador=3;
				TweenMax.to($('#habitacion'), 1.5, {scale:1.2, delay:0.3, ease:Power3.easeInOut});
				TweenMax.to($('#entrada'), 1.5, {scale:3.5, delay:0.3, ease:Power3.easeInOut});
				setTimeout(function(){
					$('#entrada').hide();
						parallaxInstance.enable();
					},1800);
				}
		},100);
	}
}
function Redactar(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
	}
	$('#redactar').removeClass('oculto').addClass('mostrar');
}
function CerrarRedactar(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.enable();
	}
	$('#redactar').removeClass('mostrar').addClass('oculto');
}
function verGracias(){
	$('#formulario').hide();$('#gracias').show();
}
function vermas(){
	CerrarRedactar();
	setTimeout(function(){
			VerVotos();
	},1000);
}
function VerVideo(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
	}
	$('#contenedorvideo').html('<div class="cerrar" onClick="CerrarVideo();">X</div><iframe width="560" height="315" src="https://www.youtube.com/embed/U_Gw23UBYy0?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');
	$('#videoplayer').removeClass('oculto').addClass('mostrar');
}
function CerrarVideo(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.enable();
	}
	$('#contenedorvideo').html('');
	$('#videoplayer').removeClass('mostrar').addClass('oculto');
}
function VerMiContrato(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
	}
	$('#micontrato').removeClass('oculto').addClass('mostrar');
}
function CerrarMiContrato(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.enable();
	}
	$('#micontrato').removeClass('mostrar').addClass('oculto');
}
function VerVotos(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
		ancho=25*numero_Items;
		$('#carrusel').css("width",ancho+"vw");
		$('#carrusel').css("left","0vw");
	}else{
		ancho=54*numero_Items;
		$('#carrusel').css("height",ancho+"vw");
		$('#carrusel').css("top","0vw");
	}
	$('#votaciones').removeClass('oculto').addClass('mostrar');
}
function VerRankig(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
		ancho=25*numero_Items2;
		$('#carrusel2').css("width",ancho+"vw");
		$('#carrusel2').css("left","0vw");
	}else{
		ancho=74*numero_Items2;
		$('#carrusel2').css("height",ancho+"vw");
		$('#carrusel2').css("top","0vw");
	}
	$('#votaciones').removeClass('oculto').addClass('mostrar');
}
function CerrarVotos(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.enable();
	}
	$('#votaciones').removeClass('mostrar').addClass('oculto');
}
posicion=0;
totalPosicion=numero_Items/4;
controlPosicion=1;
function mover(direccion){
	if(direccion=='izq'){
		if(controlPosicion>1){
			posicion=posicion+100;
			$('#carrusel').css("left",posicion+"vw");
			controlPosicion--;
		}
	}else if(direccion=='dch'){
		if(controlPosicion<totalPosicion){
			posicion=posicion-100;
			$('#carrusel').css("left",posicion+"vw");
			controlPosicion++;
		}
	}
}
posicion2=0;
totalPosicion2=numero_Items2/4;
controlPosicion2=1;
function mover2(direccion){
	if(direccion=='izq'){
		if(controlPosicion2>1){
			posicion2=posicion2+100;
			$('#carrusel2').css("left",posicion2+"vw");
			controlPosicion2--;
		}
	}else if(direccion=='dch'){
		if(controlPosicion2<totalPosicion2){
			posicion2=posicion2-100;
			$('#carrusel2').css("left",posicion2+"vw");
			controlPosicion2++;
		}
	}
}
function mostrarSugerencias(){
	$("#dch").css("display","block");
}
function CerrarEjemplos(){
	$("#dch").css("display","none");
}
function validateForm(){
	var val = true;
	if($("#clausula1").val() == '') {
    $('.errorm').show();
		 val = false;
	}
	if ($('#bases:checked').length  === 0) {
		 alert('Debes aceptar las bases legales para continuar');
      val = false;
		}
	if(val) {
		$.post("save-clausulas.php", { clausula1: $('#clausula1').val(), clausula2: $('#clausula2').val(), clausula3: $('#clausula3').val(), clausula4: $('#clausula4').val(), clausula5: $('#clausula5').val(), })
						.done(function( data ) {
							verGracias();
						});
		} else {
       return false;
		}
}
function votar(iditem){
	$.get( "votar.php", { cid: iditem } )
  .done(function( data ) {
  });
		$('#'+iditem+' span').addClass('yavotado');
		$('#'+iditem+' span').html('VOTADO');
		$('#'+iditem+' span').attr('onclick','').unbind('click');
		$('#r'+iditem+' span').addClass('yavotado');
		$('#r'+iditem+' span').html('VOTADO');
		$('#r'+iditem+' span').attr('onclick','').unbind('click');
}
function votarGrande(){
	let iditem = $('.clausulaGrande .votar').attr('rel');
	$.get( "votar.php", { cid: iditem } )
  .done(function( data ) {
  });
		$('.clausulaGrande .votar').addClass('yavotado');$('.clausulaGrande .votar').html('VOTADO');
		$('#'+iditem+' span').addClass('yavotado');
		$('#'+iditem+' span').html('VOTADO');
		$('#'+iditem+' span').attr('onclick','').unbind('click');
		$('#r'+iditem+' span').addClass('yavotado');
		$('#r'+iditem+' span').html('VOTADO');
		$('#r'+iditem+' span').attr('onclick','').unbind('click');
}
function AbrirClausulaGrande(idclausula){
	if(DesactivarParallax!="SI"){
		parallaxInstance.disable();
	}
	$('.clausulaGrande .eltexto').html($('#'+idclausula+' .texto').attr('rel'));
	if($('#'+idclausula+' span').hasClass('yavotado') || $('#r'+idclausula+' span').hasClass('yavotado')) {
		$('.clausulaGrande .votar').addClass('yavotado');$('.clausulaGrande .votar').html('VOTADO');
	}
	$('.clausulaGrande .votar').attr('rel', idclausula);
	$('#contenedorClausula').removeClass('oculto').addClass('mostrar');
}
function CerrarClausula(){
	if(DesactivarParallax!="SI"){
		parallaxInstance.enable();
	}
	$('.clausulaGrande .eltexto').html('');	$('.clausulaGrande .votar').removeClass('yavotado');$('.clausulaGrande .votar').html('VOTAR');
	$('#contenedorClausula').removeClass('mostrar').addClass('oculto');
}
function ranking() {
	$('#votosa').fadeOut(300);
	setTimeout(function(){
		$('#rankinga').fadeIn(300);
		VerRankig();
		$('.votosl').show();
		$('.ranking').hide();
	},400);
}
function votos() {
	$('#rankinga').fadeOut(300);
		setTimeout(function(){
			$('#votosa').fadeIn(300);
			$('.votosl').hide();
			$('.ranking').show();
	},400);
}
function putpx() {
var axel = Math.random() + "";
var a = axel * 10000000000000;
document.write('<iframe src="https://8499384.fls.doubleclick.net/activityi;src=8499384;type=mc_count;cat=mcdon00-;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
}
/*--------------------- MUSICA --------------------------------*/
var nota=$("#nota");
var selector=$("#selector");
var conmutador=$("#conmutador");
var controlmusica=0;
function PararReproducirMusica(){
	if(controlmusica==0){
		var opcion=document.getElementById("musica");
		fadeSound(opcion,'up');
		controlmusica=1;
		nota.removeClass("desactivado");
		selector.removeClass("desactivado");
		conmutador.removeClass("desactivado");
	}else{
		var opcion=document.getElementById("musica");
		fadeSound(opcion,'down');
		controlmusica=0;
		nota.addClass("desactivado");
		selector.addClass("desactivado");
		conmutador.addClass("desactivado");
	}
}
function fadeSound(element,level){
	if(level == 'up'){
			i = 0;
			max = 100;
			element.volume = i;
			element.play();
			var interval = setInterval(function(){
					if(i >= max){
						clearInterval(interval);
					}else{
						element.volume = i/100;
						i++;
						//console.log(i+"#"+i/100);
					}
				},
				10);
		}else if(level == 'down'){
			j = 100;
			max = 0;
			var interval = setInterval(function(){
				if(j <= max){
					element.pause();
					clearInterval(interval);
				}else{
				element.volume = j/100;
				j--;
				//console.log(j+"#"+j/100);
				}
				},
				10);
		}
}
function reproducirSonido(misonido){
	if(controlmusica==1){
	var EfectoSonido=document.getElementById("audio_"+misonido);
	EfectoSonido.play();
	document.getElementById("audio_"+misonido).prop("currentTime",0);
	}
}
function pararSonido(misonido){
	if(controlmusica==1){
	var EfectoSonido=document.getElementById("audio_"+misonido);
	EfectoSonido.pause();
	document.getElementById("audio_"+misonido).prop("currentTime",0);
	}
}

$(document).ready(function(e) {
	var visit=GetCookie("cookies_mycombo");
		if (visit==1){
				$('#politica_cookies').toggle();
		} else {
				var expire=new Date();
				expire=new Date(expire.getTime()+7776000000);
				document.cookie="cookies_mycombo=aceptada; expires="+expire;
		}
});

function GetCookie(name) {
    var arg=name+"=";
    var alen=arg.length;
    var clen=document.cookie.length;
    var i=0;
    while (i<clen) {
        var j=i+alen;
        if (document.cookie.substring(i,j)==arg)
            return "1";
        i=document.cookie.indexOf(" ",i)+1;
        if (i==0)
             break;
     }
    return null;
}
function aceptar_cookies(){
    var expire=new Date();
    expire=new Date(expire.getTime()+7776000000);
    document.cookie="cookies_mycombo=aceptada; expires="+expire;
    var visit=GetCookie("cookies_mycombo");
    if (visit==1){
        $('#politica_cookies').toggle();
    }
}
function cerrar_cookies() {
	   $('#politica_cookies').toggle();
}
