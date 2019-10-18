		<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-house-damage"></i> Inicio</a></li>
				<?php
                    if($_SESSION['id_rol'] == 1){
                ?>
				<li class="principal">
					<a href="#"><i class="fas fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-address-book"></i> Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-user-tie"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php"><i class="fas fa-plus-circle"></i> Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="far fa-clipboard-list"></i> Lista de Clientes</a></li>
					</ul>
				</li>				
				<li class="principal">
					<a href="#"><i class="fas fa-truck-container"></i> Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-plus-circle"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="far fa-clipboard-list"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php
                    if($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 2){
                ?>				
				<li class="principal">
					<a href="#"><i class="fas fa-dolly-flatbed"></i> Inventarios</a>
					<ul>
						<li><a href="familias.php"><i class="fas fa-toolbox"></i> Familias</a></li>
						<li><a href="unidades.php"><i class="fas fa-ruler"></i> Unidades</a></li>
						<li><a href="materiales.php"><i class="fas fa-paint-roller"></i> Materiales</a></li>
						<li><a href="mano_obra.php"><i class="fas fa-user-hard-hat"></i> Mano de Obra</a></li>
						<li><a href="maquinaria.php"><i class="fas fa-truck-monster"></i> Maquinaria</a></li>
						<li><a href="herramientas.php"><i class="fas fa-tools"></i> Herramienta</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-newspaper"></i> Proyectos</a>
					<ul>
						<li><a href="crear_proyecto.php"><i class="fas fa-chalkboard-teacher"></i> Nuevo Proyecto</a></li>
					</ul>
				</li>
				<?php
					}
                ?>				
				<li class="principal">
					<a href="#"><i class="fal fa-ballot-check"></i> Reportes</a>
					<ul>
						<li><a href="reportes.php"><i class="fas fa-file-chart-line"></i> Generar Reportes</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		