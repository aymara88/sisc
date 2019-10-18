//AQUI AGREGAREMOS ESTE ARCHIVO QUE FALTA;
function CargarDatosProyecto(){
	$("#datos_sub_obras").html("<div id=\"spinner\"><i class=\"fa fa-spinner fa-w-16 fa-spin fa-lg\"></i> Cargando Datos...</div>");
	$("#lista_sub_obras").remove();
	id_proyecto = $("#id_proyecto").val();
    $.post({
        method: "post",
        url: "includes/LoadProyecto.php?id="+id_proyecto,
        data: $(this).serialize(),
        success: function(data)
        {
			$("#subobra_resultado").html(data.resultado);
			$("#subobra_duracion").val("");
			$("#duracion").val(data.duracion);
			$("#subobras").html(data.subobras);
			$("#costo").val(data.costo);
			$("#spinner").remove();
        }
	});	
}
function GuardarDatosProyecto(){
	id_proyecto = $("#id_proyecto").val();
	descripcion = $("#descripcion").val();
	cliente = $("#cliente").val();
    $.post({
        method: "post",
        url: "includes/GuardarProyecto.php?id="+id_proyecto+"&descripcion="+descripcion+"&cliente="+cliente,
        data: $(this).serialize(),
        success: function(data)
        {
			var template = "<div class=\"alert\">"+data+"</div>";
			$("#proyecto_resultado").html(template);
        }
	});					
}

function EliminarProyecto(){
	id_proyecto = $("#id_proyecto").val();
	if (confirm('Estás seguro que deseas eliminar éste proyecto?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarProyecto.php?id="+id_proyecto,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#nuevo_proyecto_select option[value='"+id_proyecto+"']").remove();
				$("#costo").val("");
				$("#duracion").val("");
				$("#descripcion").val("");
				$("#cliente").val(0);
				$("#proyecto_resultado").html(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function EliminarSubobra(id_sub_obra){
	if (confirm('Estás seguro que deseas eliminar esta Sub Obra?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarSubObra.php?id="+id_sub_obra,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#subobras option[value='"+id_sub_obra+"']").remove();
				$("#tres"+id_sub_obra).remove();
				$("#subobra_duracion").val("");
				$("#subobra_resultado").append(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function EliminarHerramienta(id){
	if (confirm('Estás seguro que deseas eliminar esta Herramienta?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarHerramienta.php?id="+id,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#treh"+id).remove();
				$("#herramienta_resultado").append(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function EliminarManoObra(id){
	if (confirm('Estás seguro que deseas eliminar esta Mano de Obra?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarManoObra.php?id="+id,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#trmo"+id).remove();
				$("#mano_obra_resultado").append(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function EliminarMaquinaria(id){
	if (confirm('Estás seguro que deseas eliminar esta Maquinaria?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarMaquinaria.php?id="+id,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#emaq"+id).remove();
				$("#maquinaria_resultado").append(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function EliminarMaterial(id){
	if (confirm('Estás seguro que deseas eliminar este Material?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post({
			method: "post",
			url: "includes/EliminarMaterial.php?id="+id,
			data: $(this).serialize(),
			success: function(data)
			{
				$("#emat"+id).remove();
				$("#material_resultado").append(data);
			}
		});
	} 
	else {
		return false;
	}	
}

function AsignarProyecto(){
	id_proyecto = $("#id_proyecto").val();
	if (confirm('Estás seguro que deseas asignar los datos de todas las subobras creadas al proyecto actual?')) {
		if(id_proyecto <= 0)
		{
			return false;
		}
		$.post("includes/AsignarProyecto.php", { id_proyecto: id_proyecto}, function(data){
				$("#proyecto_resultado").html(data.message);
				$("#costo").val(data.costo);
				$("#duracion").val(data.duracion);
		});
	} 
	else {
		return false;
	}	
}

function LimpiarSubobras(){
	$("#nueva_subobra").val("");
	$("#subobra_duracion").val("");
	$("#descripcion_subobra_txt").val("");
	$("#clonar_subobra_nombre").val("");
	$("#subobra_resultado").html("");
	$("#maquinaria_resultado").html("");
	$("#material_resultado").html("");
	$("#mano_obra_resultado").html("");
	$("#herramienta_resultado").html("");
	$("#clonar_subobra_checkbox").removeProp("checked");
}

function CargarSubobras(){
	$("#nueva_subobra").val("");
	$("#subobra_duracion").val("");
	$("#clonar_subobra_nombre").val("");
	$("#maquinaria_resultado").html("");
	$("#material_resultado").html("");
	$("#mano_obra_resultado").html("");
	$("#herramienta_resultado").html("");
	$("#subobras_div").show();
	$("#nueva_subobra").hide();
	$("#descripcion_subobra").hide();
	$("#subobra_datos").hide();
	$("#clonar_subobra_checkbox").removeProp("checked");
	id_proyecto = $("#id_proyecto").val();
	$.post("includes/getSubObras.php", { id_proyecto: id_proyecto }, function(data){
		$("#subobras").html(data);
		$("#clonar_subobra").show();
	}); 	
}