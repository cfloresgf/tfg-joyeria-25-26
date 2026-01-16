<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/tipoProducto.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Productos";
$menu = "productos";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}


if (isset($_GET['pag'])) {
    $pag = $_GET['pag'];
} else {
    $pag = 1;
}


if(isset($_GET['idTipo'])) {
    $idTipo = $_GET['idTipo'];
    $_SESSION['idTipo'] = $idTipo;
    if (isset($_GET['nombreProducto'])) {
        $nombreProducto = $_GET['nombreProducto'];
        $_SESSION['nombreProducto'] = $nombreProducto;
        if(isset($_GET['orden'])) {
            $orden = $_GET['orden'];
            if($idTipo == 0) {
                $productos = Producto::listadoBusquedaOrdenado($pag, $nombreProducto, $orden);
            }
            else {
                $productos = Producto::listadoFiltradoBusquedaOrdenado($pag, $idTipo, $nombreProducto, $orden);
            }
        }
        else {
            if($idTipo == 0) {
                $productos = Producto::listadoBusqueda($pag, $nombreProducto);
            }
            else {
                $productos = Producto::listadoFiltradoBusqueda($pag, $idTipo, $nombreProducto);
            }
        }
        if($idTipo == 0) {
            $numProductos = Producto::cuentaBusqueda($nombreProducto);
        }
        else {
            $numProductos = Producto::cuentaFiltradoBusqueda($idTipo, $nombreProducto);
        }
    }
    else {
        $nombreProducto="";
        if(isset($_GET['orden'])) {
            $orden = $_GET['orden'];
            if($idTipo == 0) {
                $productos = Producto::listadoOrdenado($pag, $orden);
            }
            else {
                $productos = Producto::listadoFiltradoOrdenado($idTipo, $pag, $orden);
            }
        }
        else {
            if($idTipo == 0) {
                $productos = Producto::listado($pag);
            }
            else {
                $productos = Producto::listadoFiltrado($idTipo, $pag);
            }
        }
        if($idTipo == 0) {
            $numProductos = Producto::cuenta();
        }
        else {
            $numProductos = Producto::cuentaFiltrado($idTipo);
        }
    }
}
else {
    $idTipo=0;
    if (isset($_GET['nombreProducto'])) {
        $nombreProducto = $_GET['nombreProducto'];
        $_SESSION['nombreProducto'] = $nombreProducto;
        if(isset($_GET['orden'])) {
            $orden = $_GET['orden'];
            $productos = Producto::listadoBusquedaOrdenado($pag, $nombreProducto, $orden);
        }
        else {
            $productos = Producto::listadoBusqueda($pag, $nombreProducto);
        }
        $numProductos = Producto::cuentaBusqueda($nombreProducto);
    }
    else {
        $nombreProducto="";
        if(isset($_GET['orden'])) {
            $orden = $_GET['orden'];
            $productos = Producto::listadoOrdenado($pag, $orden);
        }
        else {
            $productos = Producto::listado($pag);
        }
        $numProductos = Producto::cuenta();
    } 
}


$numPaginas = ceil($numProductos / TAM_PAGINA);


include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<div class="row filtros g-2">
    <!--Desplegable para seleccionar un tipo determinado de productos y filtrar por dicho tipo-->
    <div class="col-12 col-md-4">
        <select class="form-select" id="tipos"></select>
    </div>

    <!--Filtrar por nombre de producto-->
    <div class="col-12 col-md-4">
        <form action="productos.php" method="GET" class="d-flex">
            <input type="hidden" name="idTipo" id="inputIdTipo" value="<?= $idTipo ?>">
            <input type="text" name="nombreProducto" class="search-input" placeholder="Buscar por nombre...">
            <button type="submit" class="search-button"><i class="bi bi-search"></i> Buscar</button>
        </form>
    </div>

    <!--Ordenar listado por precio-->
    <div class="col-12 col-md-4 d-flex">
        <select name="orden" id="orden" class="orden" class="form-select me-2">
            <option value="ASC">Precio: Menor a Mayor</option>
            <option value="DESC">Precio: Mayor a Menor</option>
        </select>
        <button id="btnOrdenar" class="btn-ordenar">Ordenar</button>
    </div>

    <input type="hidden" name="nombreProducto" id="nombreProducto" value="<?= $nombreProducto ?>">
</div>

<br/>

<!--Listado de productos-->
<div class="row productos">
    <?php if (!empty($productos)) : ?>
        <?php foreach($productos as $producto) : ?>
            <div class="col-md-4 col-sm-12">
                <div class="card mb-4 btn btnDetalles" data-producto-id="<?=$producto['idProducto']?>">
                    <img src="fotos/<?=e($producto['idProducto']) . '_' . e($producto['imagen'])?>" class="card-img-top mx-auto mt-3"/>
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <h6><?=e($producto['nombre'])?></h6>
                        </div>
                        <div class="col-md-12 text-center">
                            <p><?=e($producto['precio'])?> €</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="margin-top: 50px;"><i class="bi bi-bag-x"></i> Lo sentimos, no se han encontrado productos que coincidan con tu búsqueda.</p>
    <?php endif; ?>
</div>


<!--Paginación-->
<?php if (($numPaginas)>1) : ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                <a class="page-link" href="productos.php?pag=<?= $pag - 1 ?>&idTipo=<?= $idTipo ?>">
                    &lt;&lt;
                </a>
            </li>
            <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                    <a class="page-link" href="productos.php?pag=<?= $i ?>&idTipo=<?= $idTipo ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                <a class="page-link" href="productos.php?pag=<?= $pag + 1 ?>&idTipo=<?= $idTipo ?>">
                    &gt;&gt;
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    const select = document.getElementById("tipos");
    let id = <?=$idTipo?>;
    let pag = <?=$pag?>;
    let nombre = document.getElementById("nombreProducto").value;
    let btnOrdenar = document.getElementById("btnOrdenar");
    let orden = document.getElementById("orden");

   //Recarga la página mostrando solo los productos del tipo seleccionado
   select.addEventListener("change", function() {
      if(nombre=='') {
        window.location.href="productos.php?idTipo=" + select.value;
      }
      else {
        window.location.href="productos.php?idTipo=" + select.value + "&nombreProducto=" + nombre;
      }
   });

   //Carga el select de tipos de contenido
   function cargarTipos() {
      select.innerHTML = "";
      fetch("listadoTipos.php")
      .then(res => res.json())
      .then(tipos => {
         let optionVacia = document.createElement("option");
         optionVacia.value = 0;
         optionVacia.text = "Mostrar todos los productos";
         select.appendChild(optionVacia);
         tipos.forEach(function(t) {
            let option = document.createElement("option");
            option.value = t.idTipo;
            option.text = t.nombre;
            if(option.value==id) {
                select.appendChild(option).selected=true;
            }
            select.appendChild(option);
         });
      })
   }

   cargarTipos();

   //Accede a la página de detalles con el idProducto del que se seleccione
    const btnsDetalles = document.querySelectorAll(".btnDetalles");
    btnsDetalles.forEach(function(btn) {
        btn.addEventListener("click", function(b) {
            let idProducto = this.getAttribute("data-producto-id");
 
            window.location.href = "detallesProducto.php?idProducto=" + idProducto + "&pag=" + pag + "&idTipo=" + id;
        });
    });

    //Ordena el listado
    btnOrdenar.addEventListener("click", function(b) {
        if(nombre=='') {
            window.location.href = "productos.php?orden=" + orden.value + "&idTipo=" + id;
        }
        else {
            window.location.href = "productos.php?orden=" + orden.value + "&idTipo=" + id + "&nombreProducto=" + nombre;
        }
    });

</script>

<?php include __DIR__.'/include/pie.php';?>