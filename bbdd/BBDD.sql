
DROP DATABASE IF EXISTS joyeriaBD;
CREATE DATABASE joyeriaBD;
USE joyeriaBD;

CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'usuario';
GRANT ALL PRIVILEGES ON joyeriaBD.* TO 'usuario'@'localhost';
FLUSH PRIVILEGES;

CREATE TABLE usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    telefono INT NOT NULL,
    admin BIT(1) DEFAULT 0
);

CREATE TABLE tiposProducto (
    idTipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    imagen VARCHAR(255)
);

CREATE TABLE productos (
    idProducto INT AUTO_INCREMENT PRIMARY KEY,
    idTipo INT,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
	FOREIGN KEY (idTipo) REFERENCES tiposProducto(idTipo)
);

CREATE TABLE catalogos (
    idCatalogo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    temporada VARCHAR(100),
    año INT,
    archivoPDF VARCHAR(255),
    activo BIT(1) DEFAULT 1
);

CREATE TABLE carritos (
    idCarrito INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT,
    importeTotal DECIMAL(10,2),
    envioGratuito BIT(1) DEFAULT 0, #envioGratuito 1si, 0no
    activo BIT(1) DEFAULT 1, # activo 1si, 0no
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
);

CREATE TABLE lineaCarrito (
    idLinea INT AUTO_INCREMENT PRIMARY KEY,
    idCarrito INT,
    idProducto INT,
    cantidad INT,
    FOREIGN KEY (idCarrito) REFERENCES carritos(idCarrito),
    FOREIGN KEY (idProducto) REFERENCES productos(idProducto)
);

CREATE TABLE pedidos (
    idPedido INT AUTO_INCREMENT PRIMARY KEY,
    idCarrito INT,
    fechaPedido DATETIME,
    estado INT,
    fechaEnvio DATETIME,
    fechaAnulacion DATETIME,
    FOREIGN KEY (idCarrito) REFERENCES carritos(idCarrito)
);


INSERT INTO usuarios (nombre, email, pwd, telefono, admin)
VALUES ("Administrador", "admin@admin.com", "$2y$10$7IlU4yEeKkEPD82Ih7NFCeTWTwGQQi8FZNP9TUo7K6c35Rf.1w1fW",123456789, 1),
	   ("Usuario", "usuario@usuario.com", "$2y$10$s1qykf33e6n1JXLMq5UV2e8mZsmeGw5MHtKuJKfko9XghC.9yaTGS",987654321, 0);

INSERT INTO tiposproducto (nombre, imagen)
VALUES ("ANILLOS", "tipoAnillo.png"),
	   ("COLLARES", "tipoCollar.png"),
       ("PULSERAS", "tipoPulsera.png"),
       ("PENDIENTES", "tipoPendiente.png"),
       ("RELOJES", "tipoReloj.png");
       
INSERT INTO productos (idProducto, idTipo, nombre, descripcion, precio, imagen)
VALUES (1, 1, "Anillo Destello Dorado", "Ilumina cada momento con el Anillo Destello Dorado, una pieza sofisticada que combina un acabado en tono oro con una deslumbrante circonita cúbica central. Su diseño de múltiples bandas aporta volumen y elegancia, mientras que los pequeños detalles brillantes realzan su carácter refinado. Perfecto para quienes buscan un anillo llamativo que aporte un toque de lujo a cualquier ocasión, desde el día a día hasta los momentos más especiales.", 79.00, "AnilloDestelloDorado.png"),
	   (2, 2, "Collar Cielo Sereno", "Evoca la calma y la belleza del cielo despejado con el Collar Cielo Sereno. Esta delicada pieza está compuesta por piedras en suaves tonos azul claro que aportan frescura y luminosidad al diseño. Perfecto para looks diarios o para añadir un toque sutil de color a estilismos más elegantes, este collar se convertirá en un básico imprescindible de tu joyero.", 59.00, "CollarCieloSereno.png"),
       (3, 5, "Reloj Premium Verde", "El Reloj Premium Verde combina un diseño deportivo con un toque de elegancia gracias a su acabado negro y detalles en verde intenso. Su esfera moderna y bien definida lo convierte en el complemento perfecto para quienes buscan un reloj versátil, ideal tanto para el día a día como para ocasiones más especiales.", 129.00, "RelojPremiumVerde.png"),
       (4, 4, "Pendientes Gota Plateada", "Los Pendientes Gota Plateada destacan por su diseño clásico en forma de gota, elaborado en plata de primera ley y realzado con un acabado brillante que refleja la luz con elegancia. Una pieza atemporal y versátil, perfecta para aportar sofisticación tanto a looks diarios como a ocasiones especiales.", 49.00, "PendientesGotaPlateada.png"),
       (5, 3, "Pulsera Oro Tradicional", "La Pulsera Oro Tradicional es una pieza elegante y atemporal que destaca por su diseño sencillo y refinado en acabado dorado. Perfecta para llevar sola o combinada con otras pulseras, aporta un toque de lujo discreto a cualquier look.", 79.00, "PulseraOroTradicional.png"),
	   (6, 1, "Anillo Doble Enlace Brillante", "El Anillo Doble Enlace Brillante simboliza la conexión y la armonía a través de su diseño de dos bandas plateadas entrelazadas, decoradas con una delicada línea de circonitas cúbicas. Su estilo minimalista y elegante lo convierte en una joya versátil, ideal para llevar sola o combinada con otros anillos. Una pieza atemporal que aporta un brillo sutil y sofisticado a cualquier look.", 65.00, "AnilloDobleEnlaceBrillante.png"),
       (7, 1, "Anillo Jardin Rosa", "Déjate seducir por el encanto del Anillo Jardin Rosa, una joya de inspiración floral que destaca por sus intensas piedras en tonos rosa engastadas sobre un elegante acabado dorado. Su diseño voluminoso y colorido aporta personalidad y feminidad, convirtiéndolo en el complemento perfecto para quienes buscan una pieza única y llena de carácter. Ideal para dar un toque de color y sofisticación a cualquier conjunto.", 65.00, "AnilloJardinRosa.png"),
       (8, 5, "Reloj Oro Tradicional", "El Reloj Oro Tradicional es una pieza atemporal que destaca por su diseño elegante y refinado en acabado dorado. Su estética sobria y sofisticada lo convierte en un reloj perfecto para quienes valoran la elegancia tradicional y desean un accesorio que nunca pase de moda.", 179.00, "RelojOroTradicional.png"),
       (9, 2, "Collar Estrellas del Firmamento", "Inspirado en la magia del cielo nocturno, el Collar Estrellas del Firmamento presenta un delicado diseño de pequeñas bolitas metálicas combinadas con encantadoras estrellas. Una pieza juvenil y versátil que aporta un toque celestial y romántico, perfecta para llevar sola o combinada con otros collares para un look en tendencia.", 55.00, "CollarEstrellasFirmamento.png"),
       (10, 4, "Pendientes Perla Dorada", "Clásicos y elegantes, los Pendientes Perla Dorada combinan un acabado dorado con delicadas perlas que aportan un toque de distinción atemporal. Una joya imprescindible que nunca pasa de moda, perfecta para completar looks sofisticados y femeninos.", 55.00, "PendientesPerlaDorada.png"),
       (11, 3, "Pulsera Trenza Negra", "La Pulsera Trenza Negra combina una resistente cuerda trenzada en color negro con elegantes bolas de acabado dorado, creando un contraste moderno y sofisticado. Una pieza con carácter, perfecta para un estilo contemporáneo y versátil.", 69.00, "PulseraTrenzaNegra.png"),
       (12, 5, "Reloj Blanco Elegante", "Minimalista y refinado, el Reloj Blanco Elegante presenta una esfera blanca de diseño limpio combinada con una correa de cuero negro. Su estilo elegante y discreto lo convierte en el complemento perfecto para looks formales y profesionales, aportando distinción y buen gusto.", 149.00, "RelojBlancoElegante.png"),
       (13, 2, "Collar Gota Fucsia", "El Collar Gota Fucsia presenta un elegante charm en forma de gota adornado con circonitas en tono fucsia que aportan un brillo intenso y sofisticado. Su diseño refinado y femenino lo convierte en una joya ideal para destacar y añadir un toque de color vibrante a cualquier look.", 68.00, "CollarGotaFucsia.png"),
       (14, 4, "Pendientes Copo Azul", "Inspirados en la delicadeza del invierno, los Pendientes Copo Azul presentan un diseño de copo de nieve adornado con circonitas en tonos azules que aportan un brillo fresco y luminoso. Una joya elegante y femenina que añade un toque mágico y refinado a cualquier conjunto.", 52.00, "PendientesCopoAzul.png"),
       (15, 5, "Reloj Oro & Cuero", "El Reloj Oro & Cuero combina una elegante caja dorada con una clásica correa de cuero negro, creando un equilibrio perfecto entre sofisticación y comodidad. Un reloj ideal para ocasiones formales o para aportar un toque distinguido a cualquier look de vestir.", 165.00, "RelojOroYCuero.png"),
       (16, 3, "Pulsera Oro Brillante", "La Pulsera Oro Brillante combina un sofisticado acabado dorado con delicados diamantes que aportan un brillo excepcional. Una joya elegante y lujosa, ideal para ocasiones especiales o para elevar cualquier conjunto con un toque de glamour.", 99.00, "PulseraOroBrillante.png"),
       (17, 3, "Pulsera Contraste", "La Pulsera Contraste destaca por su diseño moderno que combina diamantes negros y blancos sobre una base de plata de primera ley. El equilibrio entre tonos claros y oscuros crea una joya sofisticada y con personalidad, perfecta para quienes buscan un estilo elegante y diferente.", 89.00, "PulseraContraste.png"),
       (18, 2, "Collar Brillo Violeta", "El Collar Brillo Violeta destaca por sus piedras moradas de acabado brillante, que aportan intensidad y elegancia al diseño. Esta joya sofisticada es ideal para quienes buscan un collar llamativo que añada personalidad y un toque de color vibrante a cualquier conjunto.", 62.00, "CollarBrilloVioleta.png"),
       (19, 1, "Anillo Brillo Eterno", "El Anillo Brillo Eterno es una pieza clásica y sofisticada elaborada en plata de primera ley, protagonizada por una circonita central que captura la luz con cada movimiento. Su diseño limpio y atemporal lo convierte en el complemento perfecto para cualquier ocasión, aportando un toque de elegancia discreta y refinada. Ideal para llevar solo o combinado con otros anillos para crear un look personal y actual.", 69.00, "AnilloBrilloEterno.png"),
       (20, 3, "Pack Pulseras Trenzadas Bicolor", "Este pack de Pulseras Trenzadas Bicolor incluye tres pulseras con un original diseño de cuerdas entrelazadas en tonos plata y oro. Ideales para llevar juntas o por separado, aportan un estilo moderno y versátil que se adapta a cualquier ocasión.", 85.00, "PackPulserasTrenzadasBicolor.png"),
       (21, 5, "Reloj Rosa Azul", "El Reloj Rosa Azul combina un delicado acabado en oro rosa con una elegante esfera azul que crea un contraste sofisticado y moderno. Una pieza refinada y actual, ideal para quienes buscan un reloj con personalidad y un toque de distinción.", 169.00, "RelojRosaAzul.png"),
       (22, 4, "Pendientes Esmeralda Real", "Los Pendientes Esmeralda Real destacan por la intensidad de sus piedras en tono esmeralda, elegantemente engastadas en un acabado dorado. Una joya sofisticada y con carácter, ideal para quienes buscan un accesorio llamativo que aporte elegancia y color a cualquier ocasión especial.", 65.00, "PendientesEsmeraldaReal.png"),
       (23, 2, "Collar Rosa Serenidad", "El Collar Rosa Serenidad está elaborado con piedras de cuarzo rosa, símbolo de armonía y delicadeza. Su suave tonalidad aporta un aire romántico y femenino, convirtiéndolo en una pieza perfecta para el día a día o para ocasiones especiales en las que quieras transmitir calma y elegancia natural.", 60.00, "CollarRosaSerenidad.png"),
       (24, 1, "Anillo Equilibrio Bicolor", "El Anillo Equilibrio Bicolor combina dos tonos metálicos en un diseño moderno y elegante que destaca por sus filas de circonitas cúbicas cuidadosamente engastadas. Su estructura envolvente aporta un brillo equilibrado y sofisticado, ideal para quienes buscan una joya contemporánea que combine fácilmente con otros accesorios. Un anillo versátil que se adapta tanto a looks formales como informales.", 72.00, "AnilloEquilibrioBicolor.png"),
       (25, 5, "Reloj Urban Silver", "El Reloj Urban Silver combina tonos plateados y negros en un diseño moderno y urbano. Su estética equilibrada y contemporánea lo convierte en una opción versátil, perfecta tanto para el uso diario como para completar looks actuales y sofisticados.", 139.00, "RelojUrbanSilver.png"),
       (26, 3, "Pack Pulseras Doradas", "El Pack Pulseras Doradas incluye cinco pulseras en acabado oro con diseños complementarios que permiten combinarlas de múltiples formas. Una opción versátil y elegante para crear looks actuales y sofisticados, tanto de día como de noche.", 95.00, "PackPulserasDoradas.png"),
       (27, 2, "Collar Flor Fucsia", "Romántico y lleno de brillo, el Collar Flor Fucsia destaca por su charm floral decorado con circonitas fucsias que reflejan la luz de forma espectacular. Una joya delicada y elegante que simboliza la belleza y la feminidad, perfecta para regalar o para completar un look especial con un toque de color y sofisticación.", 70.00, "CollarFlorFucsia.png"),
       (28, 4, "Pendientes Cielo Claro", "Los Pendientes Cielo Claro combinan un diseño alargado y estilizado con delicadas piedras en tonos azul claro que aportan movimiento y luminosidad. Ideales para estilizar el rostro y añadir un toque sofisticado y fresco a looks tanto de día como de noche.", 58.00, "PendientesCieloClaro.png"),
       (29, 5, "Reloj Brillante Plateado", "El Reloj Brillante Plateado destaca por su elegante acabado en plata y su delicada decoración con brillantes que aportan un toque de lujo y sofisticación. Una pieza refinada, ideal para quienes buscan un reloj elegante que combine funcionalidad y glamour.", 189.00, "RelojBrillantePlateado.png"),
       (30, 1, "Anillos Mar Azul", "Este pack de 4 anillos está inspirado en la serenidad del mar, los Anillos Mar Azul destacan por su diseño de varias bandas adornadas con piedras en tonos azules que evocan la profundidad y el brillo del océano. Su acabado plateado y su estilo elegante lo convierten en unas piezas ideales para quienes buscan unos anillos originales y sofisticados, perfectos para añadir un toque de color y frescura a cualquier look.", 240.00, "AnillosMarAzul.png"),
       (31, 3, "Pulsera Encanto Rosa", "La Pulsera Encanto Rosa está elaborada en plata de primera ley y decorada con charms en tonos rosa y plata que aportan un aire femenino y delicado. Una joya perfecta para personalizar tu estilo y crear una historia única a través de cada charm.", 75.00, "PulseraEncantoRosa.png"),
       (32, 2, "Collar Noche Magica", "El Collar Noche Magica combina piedras en intensos tonos azul oscuro y morado, creando una joya llena de profundidad y misterio. Su diseño elegante y envolvente aporta carácter y sofisticación, convirtiéndolo en el complemento ideal para ocasiones especiales o para elevar cualquier look nocturno con un toque de color y brillo.", 65.00, "CollarNocheMagica.png");
       
INSERT INTO catalogos (nombre, temporada, año, archivoPDF, activo)
VALUES ('Colección Oro 2025', 'Primavera-Verano', 2025, 'oro2025.pdf', 1),
	   ('Colección Plata 2025', 'Otoño-Invierno', 2025, 'plata2025.pdf', 1);
