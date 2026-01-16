</div>
   <!-- Versión bundle de Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">
   </script>


<script>
   
   //Eliminar usuario
   const btnsBorrarUsuario = document.querySelectorAll(".btnBorrarUsuario");
   btnsBorrarUsuario.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idUsuario = b.target.id.split("idUsuario")[1];

         let fila = document.getElementById("fila" + idUsuario);

         datos.append('idUsuario', idUsuario);
         fila.parentNode.removeChild(fila);

         fetch("eliminarUsuario.php", {
            method: 'POST',
            body: datos
         })
        
      });
   });


   //Eliminar producto
   const btnsBorrarProducto = document.querySelectorAll(".btnBorrarProducto");
   btnsBorrarProducto.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idProducto = b.target.id.split("idProducto")[1];

         let fila = document.getElementById("fila" + idProducto);

         datos.append('idProducto', idProducto);
         fila.parentNode.removeChild(fila);

         fetch("eliminarProducto.php", {
            method: 'POST',
            body: datos
         })
        
      });
   });


   //Eliminar catálogo
   const btnsBorrarCatalogo = document.querySelectorAll(".btnBorrarCatalogo");
   btnsBorrarCatalogo.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idCatalogo = b.target.id.split("idCatalogo")[1];

         let fila = document.getElementById("fila" + idCatalogo);

         datos.append('idCatalogo', idCatalogo);
         fila.parentNode.removeChild(fila);

         fetch("eliminarCatalogo.php", {
            method: 'POST',
            body: datos
         })
        
      });
   });
   

   //Enviar pedido
   const btnsEnviar = document.querySelectorAll(".btnEnviar");
   btnsEnviar.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idPedido = b.target.id.split("btnEnviar")[1];
         let envio = document.getElementById("envio" + idPedido);
         let estado = document.getElementById("estado" + idPedido);
         let anulacion =document.getElementById("anulacion" + idPedido);
         let btnCancelar =document.getElementById("btnCancelar" + idPedido);
         let pPendiente =document.getElementById("pPendiente" + idPedido);

         datos.append('idPedido', idPedido);
         envio.removeChild(btn);
         estado.removeChild(pPendiente);
         anulacion.removeChild(btnCancelar);

         //Quita el estado de pendiente y añade el de finalizado
         let nuevoEstado = document.createElement('p');
         nuevoEstado.classList.add('estado', 'estado-1');
         nuevoEstado.textContent = 'Finalizado';
         estado.appendChild(nuevoEstado);

         //Quita el botón de cancelar y añade el de devolución
         let nuevaAnulacion = document.createElement('button');
         nuevaAnulacion.type = 'button';
         nuevaAnulacion.classList.add('btn', 'btn-outline-primary');
         nuevaAnulacion.style.width = '105px';
         nuevaAnulacion.id = 'btnDevuelto' + idPedido;
         nuevaAnulacion.setAttribute('data-bs-toggle', 'modal');
         nuevaAnulacion.setAttribute('data-bs-target', '#modalDevolucion' + idPedido);
         nuevaAnulacion.textContent = 'Devolución';
         anulacion.appendChild(nuevaAnulacion);

         fetch("enviar.php", {
            method: 'POST',
            body: datos
         });

         //En la columna de la fecha de envío, pone la actual
         fetch("include/obtenerFecha.php")
         .then(res=>res.text())
         .then(fecha => {
            envio.innerText=fecha;
         });

      });
   });


   //Cancelar pedido
   const btnsCancelar = document.querySelectorAll(".btnCancelar");
   btnsCancelar.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idPedido = b.target.id.split("idPedido")[1];
         let envio = document.getElementById("envio" + idPedido);
         let btnEnviar = document.getElementById("btnEnviar" + idPedido);
         let estado = document.getElementById("estado" + idPedido);
         let anulacion =document.getElementById("anulacion" + idPedido);
         let btnCancelar =document.getElementById("btnCancelar" + idPedido);
         let pPendiente =document.getElementById("pPendiente" + idPedido);

         datos.append('idPedido', idPedido);
         estado.removeChild(pPendiente);
         envio.removeChild(btnEnviar);
         anulacion.removeChild(btnCancelar);

         //Quita el estado de pendiente y añade el de cancelado
         let nuevoEstado = document.createElement('p');
         nuevoEstado.classList.add('estado', 'estado-2');
         nuevoEstado.textContent = 'Cancelado';
         estado.appendChild(nuevoEstado);

         //En la columna de la fecha de envío pone envío cancelado
         envio.style.color= '#ff7f7f';
         envio.innerText='Envío cancelado';

         fetch("cancelar.php", {
            method: 'POST',
            body: datos
         });

         //En la columna de anulación pone la fecha actual
         fetch("include/obtenerFecha.php")
         .then(res=>res.text())
         .then(fecha => {
            anulacion.innerText=fecha;
         });

      });
   });


   //Devolver pedido
   const btnsDevolver = document.querySelectorAll(".btnDevolver");
   btnsDevolver.forEach(function(btn) {
      btn.addEventListener("click", function(b) {
         let datos = new FormData();

         let idPedido = b.target.id.split("idPedido")[1];
         let estado = document.getElementById("estado" + idPedido);
         let anulacion =document.getElementById("anulacion" + idPedido);
         let btnDevolver =document.getElementById("btnDevolver" + idPedido);
         let pFinalizado =document.getElementById("pFinalizado" + idPedido);

         datos.append('idPedido', idPedido);
         estado.removeChild(pFinalizado);
         anulacion.removeChild(btnDevolver);

         //Quita el estado de finalizado y añade el de devuelto
         let nuevoEstado = document.createElement('p');
         nuevoEstado.classList.add('estado', 'estado-3');
         nuevoEstado.textContent = 'Devuelto';
         estado.appendChild(nuevoEstado);

         fetch("devolver.php", {
            method: 'POST',
            body: datos
         });

         //En la columna de anulación pone la fecha actual
         fetch("include/obtenerFecha.php")
         .then(res=>res.text())
         .then(fecha => {
            anulacion.innerText=fecha;
         });

      });
   });

</script>
