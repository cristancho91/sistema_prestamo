<aside class="main-sidebar">

	 <section class="sidebar">

		<ul class="sidebar-menu">

		<?php

		if($_SESSION["perfil"] == "Administrador"){

			echo '<li class="active">

				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>

			</li>

			<li>

				<a href="usuarios">

					<i class="fa fa-user"></i>
					<span>Usuarios</span>

				</a>

			</li>';

		}

		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Especial"){

			echo '<li>

				<a href="abonos">

					<i class="fa fa-th"></i>
					<span>Abonos</span>

				</a>

			</li>';

		}
		echo '<li class="treeview">

				<a href="#">

					<i class="fa fa-list-ul"></i>
					
					<span>Administrar Cuotas</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">

					

					<li>

					<a href="cobros">
						
						<i class="fa fa-circle-o"></i>
						<span>Todas las cuotas</span>

					</a>

					</li>
					
					<li>

						<a href="cuotas-activas">
							
							<i class="fa fa-circle-o"></i>
							<span>Cuotas Activas</span>

						</a>

					</li>
					<li>

						<a href="cuotas-pagadas">
							
							<i class="fa fa-circle-o"></i>
							<span>Cuotas Pagadas</span>

						</a>

					</li>
					<li>

						<a href="cuotas-pendientes">
							
							<i class="fa fa-circle-o"></i>
							<span>Cuotas Pendientes</span>

						</a>

					</li>
					</ul>

			</li>';

		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){

			echo '<li>

				<a href="clientes">

					<i class="fa fa-users"></i>
					<span>Clientes</span>

				</a>

			</li>
			<li>

				<a href="ganancias">

					<i class="fa fa-money"></i>
					<span>Ganancias</span>

				</a>

			</li>
			<li>

				<a href="codeudores">

					<i class="fa fa-address-card"></i>
					<span>Codeudores</span>

				</a>

			</li>';

		}

		if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){

			echo '<li class="treeview">

				<a href="#">

					<i class="fa fa-product-hunt"></i>
					
					<span>Prestamos</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">

					

					<li>

					<a href="crear-prestamo">
						
						<i class="fa fa-circle-o"></i>
						<span>Crear Prestamo</span>

					</a>

					</li>
					
					<li>

						<a href="prestamos">
							
							<i class="fa fa-circle-o"></i>
							<span>Todos los Prestamos</span>

						</a>

					</li>
					<li>

						<a href="prestamos-activos">
							
							<i class="fa fa-circle-o"></i>
							<span>Prestamos Activos</span>

						</a>

					</li>
					<li>

						<a href="prestamos-pagados">
							
							<i class="fa fa-circle-o"></i>
							<span>Prestamos Pagados</span>

						</a>

					</li>';

					if($_SESSION["perfil"] == "Administrador"){

					echo '<li>

						<a href="reportes">
							
							<i class="fa fa-circle-o"></i>
							<span>Reporte de Prestamos</span>

						</a>

					</li>';

					}

				

				echo '</ul>

			</li>';

		}

		?>

		</ul>

	 </section>

</aside>