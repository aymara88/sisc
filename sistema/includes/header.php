	<header>
		<div class="header"> 
           <div id="logotipo">
               <a href="index.php"><img src="img/logo_ocamcas2.png" alt="OCAMCAS" width="100%"></a>
           </div>
           <div class="textos">
              <h1>Sistema Web para el Control y Gestión de Proyectos de Construcción</h1>
           </div>
			<div class="optionsBar">
			<?php 
	            date_default_timezone_set('America/Mexico_City'); 
                $mes = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            ?>
				<p><?php echo date('d')." de ". $mes[date('n')] . " de " . date('Y');?> </p>
				<span>|</span>
				<span class="user"> <?php echo $_SESSION['nombre_usuario'].' - '.$_SESSION['rol']; ?>  
				</span>
				<i class="fas fa-user-circle fa-2x" alt="Foto del Usuario" title="Foto del usuario">&nbsp;</i>
				<a href="salir.php"><i class="fas fa-power-off fa-2x" alt="Salir del sistema" title="Salir"></i>&nbsp;</a>
			</div>
		</div>
		<?php include "nav.php"; ?>
	</header>