$(document).ready(function(){
	$("#subobras_control1").click(function(){
		$("#descripcion_subobra").show();
		$("#subobras_div").hide();
		$("#clonar_subobra").hide();
		$("#nueva_subobra").show();
	});
	$("#subobras_control2").click(function(){
		$("#subobras_div").show();
		$("#nueva_subobra").hide();
		$("#descripcion_subobra").hide();
		id_proyecto = $("#id_proyecto").val();
		$.post("includes/getSubObras.php", { id_proyecto: id_proyecto }, function(data){
			$("#subobras").html(data);
			$("#clonar_subobra").show();
		}); 				
	});
	$("#nuevo_proyecto").change(function(){
		np_value = $(this).val();
		if($("#nuevo_proyecto").prop("checked")){
			$("#nuevo_proyecto_text").text("Nuevo Proyecto");
			$("#nuevo_proyecto_input").show();
			$("#nombre").show();
			$("#nuevo_proyecto_select").hide();
			$("#costo").val("");
			$("#duracion").val("");
			$("#descripcion").val("");
			$("#cliente").val("");
			$("#btn-signup").show();
			$("#btn-update").hide();
			$("#btn-delete").hide();
			$("#id_proyecto").val(0);
			$("#nuevo_proyecto_select").val(0);
			$("#proyecto_parte_2").hide();
			$("#btn-load").hide();
		}
		else{
			$("#nuevo_proyecto_text").text("Proyecto Existente");
			$("#nuevo_proyecto_input").hide();
			$("#proyecto_parte_2").show();
			$("#nombre").hide();
			$("#btn-signup").hide();
			$("#btn-update").show();
			$("#nuevo_proyecto_select").show();
		}
	});
	$("#clonar_subobra_checkbox").change(function(){
		np_value = $(this).val();
		if($("#clonar_subobra_checkbox").prop("checked")){
			$("#descripcion_subobra").show();
			$("#subobra_datos").show();
		}
		else{
			$("#descripcion_subobra").hide();
			$("#subobra_datos").hide();			
		}
	});
	$("#nuevo_proyecto_select").change(function () {
		$("#nuevo_proyecto_select option:selected").each(function () {
			id_proyecto = $(this).val();
            $.post({
                method: "post",
                url: "includes/getProyecto.php?id="+id_proyecto,
                data: $(this).serialize(),
                success: function(data)
                {
					$("#costo").val(data.costo);
					$("#duracion").val(data.duracion);
					$("#descripcion").val(data.descripcion);
					$("#cliente").val(data.cliente);
					$("#id_proyecto").val(data.id);
					$("#id_proyecto_subobras").val(data.id);
					$(".id_proyecto").val(data.id);
					$("#btn-load").show();
					$("#btn-delete").show();
					LimpiarSubobras();
                }
            });           
		});
	});
	$("#subobras").change(function () {
		$("#subobras option:selected").each(function () {
			id_proyecto = $("#id_proyecto").val();
			id_subobra = $(this).val();
            $.post({
                method: "post",
                url: "includes/LoadSubObra.php?id="+id_proyecto+"&subobra="+id_subobra,
                data: $(this).serialize(),
                success: function(data)
                {
					$(".id_sub_proyecto").val(id_subobra);
					$("#subobra_duracion").val(data.duracion);
					$("#subobra_descripcion").val(data.descripcion);
					$("#subobra_resultado").html(data.subobra);
					$("#herramienta_resultado").html(data.herramienta);
					$("#mano_obra_resultado").html(data.mano_obra);
					$("#material_resultado").html(data.material);
					$("#maquinaria_resultado").html(data.maquinaria);
                }
            });           
		});
	});	
	$("#material_select1").change(function(){
		$("#material_select1 option:selected").each(function () {
			id_familia = $(this).val();
			template = '<option value="0">Seleccionar un material</option>';
			$.post("includes/getUnidades.php", { id_familia: id_familia }, function(data){
				$("#material_select3").html(data);
				$("#material_select2").html(template);
			});            
		});		
	});
	$("#material_select2").change(function () {
		$("#material_select2 option:selected").each(function () {
			id_elemento = $(this).val();
			$.post("includes/getCostoMaterial.php", { id_elemento: id_elemento}, function(data){
				$("#material_precio").val(data.precio);
				$("#material_unidad").val(data.unidad);
			});
		});
	});	
	$("#material_select3").change(function () {
		$("#material_select3 option:selected").each(function () {
			id_familia = $("#material_select1").val();
			id_unidad = $(this).val();
			$.post("includes/getMateriales.php", { id_familia: id_familia, id_unidad: id_unidad }, function(data){
				$("#material_select2").html(data);
				$("#material_precio_total").val("");
			});
		});
	});
	$("#material_cantidad").blur(function(){
		duracion = $(this).val();
		precio = $("#material_precio").val();
		if(duracion > 0){
			var Total = parseFloat(duracion * precio).toFixed(2);
			$("#material_precio_total").val(Total);
		}
	});	
	$("#maquinaria_select").change(function () {
		$("#maquinaria_select option:selected").each(function () {
			id_elemento = $(this).val();
			$.post("includes/getCostoMaquinaria.php", { id_elemento: id_elemento }, function(data){
				$("#maquinaria_precio").val(data);
				$("#maquinaria_precio_total").val("");
			});            
		});
	});
	$("#maquinaria_duracion").blur(function(){
		duracion = $(this).val();
		precio = $("#maquinaria_precio").val();
		if(duracion > 0){
			var Total = parseFloat(duracion * precio).toFixed(2);
			$("#maquinaria_precio_total").val(Total);
		}
	});
	$("#mano_obra_select").change(function () {
		$("#mano_obra_select option:selected").each(function () {
			id_elemento = $(this).val();
			$.post("includes/getCostoManoObra.php", { id_elemento: id_elemento }, function(data){
				$("#mano_obra_dia").val(data);
				$("#mano_obra_precio_total").val("");
			});            
		});
	});
	$("#mano_obra_cantidad").blur(function(){
		duracion = $(this).val();
		precio = $("#mano_obra_dia").val();
		if(duracion > 0){
			var Total = parseFloat(duracion * precio).toFixed(2);
			$("#mano_obra_precio_total").val(Total);
		}
	});
	$("#herramienta_select").change(function () {
		$("#herramienta_select option:selected").each(function () {
			id_elemento = $(this).val();
			$.post("includes/getCostoHerramienta.php", { id_elemento: id_elemento }, function(data){
				$("#herramienta_precio").val(data);
				$("#herramienta_precio_total").val("");
			});            
		});
	});	
	$("#herramienta_cantidad").blur(function(){
		duracion = $(this).val();
		precio = $("#herramienta_precio").val();
		if(duracion > 0){
			var Total = parseFloat(duracion * precio).toFixed(2);
			$("#herramienta_precio_total").val(Total);
		}
	});
	$("#FormSubobras").submit(function(e){
		e.preventDefault();
		id_proyecto = $("#id_proyecto").val();
        $.post({
            method: "post",
            url: "includes/addSubObra.php?id="+id_proyecto,
            data: $(this).serialize(),
            success: function(data)
            {
				$("#nueva_subobra").val("");
				$("#subobra_duracion").val("");
				$("#descripcion_subobra_txt").val("");
				$("#subobra_resultado").html(data);
				CargarSubobras();
            }
        }); 		
	});
	$("#FormEstimacionMaquinaria").submit(function(e){
		e.preventDefault();
		id_proyecto = $("#id_proyecto").val();
		id_subobra = $("#subobras").val();
        $.post({
            method: "post",
            url: "includes/addMaquinaria.php?id="+id_proyecto+"&subobra="+id_subobra,
            data: $(this).serialize(),
            success: function(data)
            {
				$("#maquinaria_select").val(0);
				$("#maquinaria_precio").val("");
				$("#maquinaria_duracion").val("");
				$("#maquinaria_precio_total").val("");
				$("#maquinaria_resultado").html(data);
            }
        });
	});
	$("#FormEstimacionMateriales").submit(function(e){
		e.preventDefault();
		id_proyecto = $("#id_proyecto").val();
		id_subobra = $("#subobras").val();
        $.post({
            method: "post",
            url: "includes/addMaterial.php?id="+id_proyecto+"&subobra="+id_subobra,
            data: $(this).serialize(),
            success: function(data)
            {
				$("#material_select1").val(0);
				$("#material_select2").val(0);
				$("#material_select3").val(0);
				$("#material_unidad").val("");
				$("#material_precio").val("");
				$("#material_cantidad").val("");
				$("#material_precio_total").val("");
				$("#material_resultado").html(data);
            }
        });
	});
	$("#FormEstimacionManoObra").submit(function(e){
		e.preventDefault();
		id_proyecto = $("#id_proyecto").val();
		id_subobra = $("#subobras").val();
        $.post({
            method: "post",
            url: "includes/addManoObra.php?id="+id_proyecto+"&subobra="+id_subobra,
            data: $(this).serialize(),
            success: function(data)
            {
				$("#mano_obra_select").val(0);
				$("#mano_obra_dia").val("");
				$("#mano_obra_cantidad").val("");
				$("#mano_obra_precio_total").val("");
				$("#mano_obra_resultado").html(data);
            }
        });
	});
	$("#FormEstimacionHerramienta").submit(function(e){
		e.preventDefault();
		id_proyecto = $("#id_proyecto").val();
		id_subobra = $("#subobras").val();
        $.post({
            method: "post",
            url: "includes/addHerramienta.php?id="+id_proyecto+"&subobra="+id_subobra,
            data: $(this).serialize(),
            success: function(data)
            {
				$("#herramienta_select").val(0);
				$("#herramienta_precio").val("");
				$("#herramienta_precio_total").val("");
				$("#herramienta_cantidad").val("");				
				$("#herramienta_resultado").html(data);
            }
        });
	});
});