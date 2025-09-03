create database tulipan;
use tulipan;


-- Tabla de empleados (para login)
CREATE TABLE empleados (
    id_empleado INT IDENTITY(1,1) PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    nombre_completo VARCHAR(100),
    email VARCHAR(100), -- agregado directamente
    contrasena_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL CHECK (rol IN ('admin', 'vendedor')) DEFAULT 'vendedor',
    creado_en DATETIME DEFAULT GETDATE()
);
select * from empleados;

-- Tabla de clientes
CREATE TABLE clientes (
    id_cliente INT IDENTITY(1,1) PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(30),
    ciudad VARCHAR(50),
    creado_en DATETIME DEFAULT GETDATE()
);

select * from clientes;

-- Tabla de eventos (ocasiones)
CREATE TABLE eventos (
    id_evento INT IDENTITY(1,1) PRIMARY KEY,
    tipo_ocasion VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);
select * from eventos;

-- Tabla de productos
CREATE TABLE productos (
    id_producto INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50),
    stock INT DEFAULT 0,
    disponible BIT DEFAULT 1,--boolean default true,
    imagen_url VARCHAR(255),
    creado_en DATETIME DEFAULT GETDATE()--creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
select * from productos;


-- Tabla de pedidos
CREATE TABLE pedidos (
    id_pedido INT IDENTITY(1,1) PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_evento INT,
    fecha_pedido DATETIME DEFAULT GETDATE(),--fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_evento DATE,
    mensaje_adicional TEXT,
    recibe_boletines BIT DEFAULT 0,--BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_evento) REFERENCES eventos(id_evento)
);
select * from pedidos;

-- Detalle del pedido
CREATE TABLE detalle_pedido (
    id_detalle INT IDENTITY(1,1) PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);
select * from detalle_pedido;

-- Datos De Los Empleados.
INSERT INTO empleados (nombre_usuario, nombre_completo, contrasena_hash, rol, email) VALUES-- puedes agregar la cantidad que desees.
('admin01', 'Luis Delgado', 'hash_admin01', 'admin', 'luis.d@tulipan.com'),
('admin02', 'Andr�s Montoya', 'hash_admin02', 'admin', 'andres.m@tulipan.com'),
('admin03', 'Mauricio Mu�oz pava', 'hash_admin03', 'admin', 'mauricio.mp@tulipan.com'),
('vendedor02', 'Mar�a Garc�a', 'hash_vend02', 'vendedor', 'maria.g@tulipan.com'),
('vendedor03', 'Diana L�pez', 'hash_vend03', 'vendedor', 'diana.l@tulipan.com'),
('vendedor04', 'Jorge Herrera', 'hash_vend04', 'vendedor', 'jorge.h@tulipan.com'),
('vendedor05', 'Elena Ram�rez', 'hash_vend05', 'vendedor', 'elena.r@tulipan.com'),
('vendedor06', 'Luis G�mez', 'hash_vend06', 'vendedor', 'luis.g@tulipan.com'),
('vendedor07', 'Natalia D�az', 'hash_vend07', 'vendedor', 'natalia.d@tulipan.com'),
('vendedor08', 'Miguel Torres', 'hash_vend08', 'vendedor', 'miguel.t@tulipan.com')

;
select * from empleados;

--Datos De Los Clientes.
INSERT INTO clientes (nombre_completo, email, telefono, ciudad) VALUES
('Andr�s Felipe', 'andres.felipe@gmail.com', '3011234567', 'Bogot�'),
('Laura Torres', 'laura.torres@hotmail.com', '3109876543', 'Medell�n'),
('Daniela P�rez', 'daniela.perez@gmail.com', '3004567890', 'Cali'),
('Esteban Salazar', 'esteban.salazar@yahoo.com', '3021122334', 'Medell�n'),
('Carolina Ruiz', 'carolina.ruiz@hotmail.com', '3113344556', 'Barranquilla'),
('Diana Mu�oz', 'diana.munoz@gmail.com', '3154784251', 'Bucaramanga'),
('Gabriel isaza', 'gabriza@gmail.com', '3114587452', 'Monteria'),
('Juliana Soto', 'j.soto@hotmail.com', '3176521452', 'Medell�n'),
('Amparo Toro', 'amparito@gmail.com', '3003254652', 'Bogot�'),
('Daniela Londo�o', 'dannylondo@hotmail.com', '3012252212', 'Medell�n'),
('Andr�s Sanchez', 'andresito.100@gmail.com', '3174521415', 'Manizales'),
('Alejandro Rodriguez', 'alejor@yahoo.com', '3054521451', 'Envigado'),
('Julian Guillem', 'julian.g@gmail.com', '3148254563', '}Medell�n'),
('Bernardo Mej�a', 'berme@fenix.com', '3132584521', 'Barranquilla'),
('Claudia Perez', 'claudia1218@gmail.com', '3153335231', 'Monter�a'),
('Lilyam Aguilar', 'Aguilucha@hotmail.com', '3147152445', 'cali'),
('Fabiola Mosquera', 'faby.mosque@gmail.com', '3147854369', 'Barranquilla'),
('William Pava', 'W.pava@cloud.com', '3119636696', 'Medell�n'),
('Carlos Villa', 'C.villa@gmail.com', '3187496210', 'Bogot�'),
('Diana Isaza', 'diana.ramirez@eassyty.com', '3163254857', 'Medell�n');
select * from clientes;

-- Datos base para eventos.
INSERT INTO eventos (tipo_ocasion, descripcion) VALUES
('cumplea�os', 'Celebraci�n de cumplea�os personal o infantil'),
('boda', 'Eventos matrimoniales'),
('aniversario', 'Conmemoraciones especiales'),
('bautizo', 'Fiestas de bautizo y ceremonias religiosas'),
('graduaci�n', 'Actos acad�micos de grado'),
('corporativo', 'Eventos empresariales o de oficina'),
('otro', 'Otro tipo de evento personalizado');
select * from eventos;

--Datos Para Los Productos.
INSERT INTO productos (nombre, descripcion, precio, categoria, stock, disponible, imagen_url) VALUES
('Tulip�n de Arequipe', 'Pastel dulce con suave bizcochuelo de vainilla y centro de arequipe montado con crema batida artesanal.', $5.000, 'pasteles', 12, 4, 'tulipan_arequipe.jpg'),
('Suspiro de Caf�', 'Brownie cremoso con infusi�n de caf� colombiano y nueces tostadas, envuelto en papel reciclado con sello Tulip�n.', $8.000, 'brownies', 20, 5, 'suspiro_cafe.jpg'),
('Cupcake Encanto', 'Cupcake de mora con glaseado de queso campesino, decorado con flor comestible hecha a mano.', $9.000, 'cupcakes', 15, 3, 'encanto_mora.jpg'),
('Galletas Origen', 'Galletas horneadas con guayaba natural y trazos de chocolate 70%, inspiradas en la tradici�n antioque�a.', $4.000, 'galletas', 45, 10, 'galleta_origen.jpg'),
('Pastel de la Abuela', 'Receta secreta con sabor a historia: bizcocho criollo con canela y uvas pasas, envuelto en tela Tulip�n.', $7.500, 'pasteles', 8, 3, 'pastel_abuelita.jpg');
select * from productos;

--Datos De Los Pedidos.
INSERT INTO pedidos (id_cliente, id_evento, fecha_evento, mensaje_adicional, recibe_boletines) VALUES
(1, 1, '2025-09-20', 'Gracias por permitirnos endulzar este momento. Tu pastel fue hecho con cari�o desde nuestro horno Tulip�n.', 1),
(2, 3, '2025-08-12', 'Un dulce abrazo en forma de cupcake. �Feliz aniversario, con sabor criollo!', 0),
(3, 2, '2025-08-25', 'De nuestra cocina a tu coraz�n. Esperamos que el �Pastel de la Abuela� te transporte a casa.', 1),
(4, 4, '2025-10-05', 'Celebra este bautizo con dulzura artesanal Tulip�n.', 1),
(5, 5, '2025-11-01', '�Felicitaciones en tu graduaci�n! El �xito sabe a chocolate.', 0),
(6, 1, '2025-09-10', 'Tu cumplea�os merece una explosi�n de sabor criollo.', 1),
(7, 2, '2025-08-30', 'Que el amor se acompa�e de nuestros suspiros dulces.', 1),
(8, 3, '2025-09-15', 'Un pastel para celebrar a�os de dulzura juntos.', 0),
(9, 6, '2025-08-28', 'Reuni�n de equipo con sabor Tulip�n incluido.', 1),
(10, 7, '2025-10-20', 'Detalle personalizado para un evento especial y �nico.', 0),
(11, 3, '2025-09-25', 'Celebra el amor con pastel y tradici�n.', 1),
(12, 4, '2025-09-02', 'Momentos especiales requieren sabores �nicos.', 0),
(13, 5, '2025-09-30', 'Un dulce honor por tu logro acad�mico.', 1),
(14, 2, '2025-11-11', 'Pastel para compartir alegr�a matrimonial.', 0),
(15, 1, '2025-08-09', '�Feliz vuelta al sol! Que la dulzura te acompa�e.', 1),
(16, 6, '2025-08-26', 'Regalo corporativo con estilo y sabor.', 0),
(17, 7, '2025-10-07', 'Evento personalizado con toque artesanal.', 1),
(18, 2, '2025-10-12', 'El amor se celebra con chocolate y mora.', 0),
(19, 4, '2025-09-21', 'Bienvenido al mundo con un cupcake encantado.', 1),
(20, 5, '2025-11-19', 'Graduaci�n con pastel y orgullo Tulip�n.', 0),
(1, 1, '2025-10-01', 'Un nuevo a�o, una nueva historia con sabor.', 1),
(3, 6, '2025-09-18', 'Reuni�n de oficina con dulces memorias.', 0),
(7, 7, '2025-09-22', 'Evento personalizado: �creado con amor artesanal!', 1);
select * from pedidos;

-- Datos De Detalle Pedido.
INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
VALUES
(4, 1, 2, 5000),
(5, 2, 1, 8000),
(6, 3, 4, 9000),
(7, 5, 2, 7500),
(8, 4, 6, 4000),
(9, 2, 3, 8000),
(10, 1, 2, 5000),
(11, 3, 1, 9000),
(12, 4, 5, 4000),
(13, 5, 3, 7500),
(14, 2, 2, 8000),
(15, 3, 4, 9000),
(16, 1, 6, 5000),
(17, 4, 2, 4000),
(18, 5, 1, 7500),
(19, 3, 2, 9000),
(20, 4, 3, 4000),
(21, 2, 4, 8000),
(22, 1, 1, 5000),
(23, 5, 2, 7500);
select * from detalle_pedido;