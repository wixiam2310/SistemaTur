-- ==========================================
-- TABLA ROL
-- ==========================================

CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- ==========================================
-- TABLA USUARIO
-- ==========================================

CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,

    id_rol INTEGER NOT NULL,

    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    telefono VARCHAR(20),

    estado VARCHAR(20) NOT NULL
        CHECK (estado IN ('Activo','Inactivo')),

    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuario_rol
        FOREIGN KEY (id_rol)
        REFERENCES rol(id_rol)
);

-- ==========================================
-- TABLA GUIA TURISTICO
-- ==========================================

CREATE TABLE guia_turistico (

    id_guia SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL UNIQUE,

    especialidad VARCHAR(150),

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN
            ('Disponible','Asignado','Inactivo')
        ),

    CONSTRAINT fk_guia_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

-- ==========================================
-- TABLA TEMPORADA
-- ==========================================

CREATE TABLE temporada (

    id_temporada SERIAL PRIMARY KEY,

    nombre VARCHAR(20) NOT NULL
        CHECK (
            nombre IN
            ('Baja','Media','Alta','Especial')
        ),

    incremento_porcentaje NUMERIC(5,2) NOT NULL,

    fecha_inicio DATE NOT NULL,

    fecha_fin DATE NOT NULL
);

-- ==========================================
-- TABLA HABITACION
-- ==========================================

CREATE TABLE habitacion (

    id_habitacion SERIAL PRIMARY KEY,

    numero VARCHAR(20) NOT NULL UNIQUE,

    edificio CHAR(1) NOT NULL
        CHECK (
            edificio IN ('A','B','C')
        ),

    tipo VARCHAR(30) NOT NULL
        CHECK (
            tipo IN (
                'Estandar',
                'Doble',
                'VistaMar',
                'JuniorSuite',
                'Ejecutiva',
                'Presidencial'
            )
        ),

    capacidad INTEGER NOT NULL,

    precio_base NUMERIC(10,2) NOT NULL,

    estado VARCHAR(30) NOT NULL
        CHECK (
            estado IN (
                'Disponible',
                'Reservada',
                'Mantenimiento',
                'Limpieza'
            )
        )
);

-- ==========================================
-- TABLA ACTIVIDAD
-- ==========================================

CREATE TABLE actividad (

    id_actividad SERIAL PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,

    tipo VARCHAR(30) NOT NULL
        CHECK (
            tipo IN (
                'Deportiva',
                'Acuatica',
                'Guiada',
                'Premium'
            )
        ),

    descripcion TEXT,

    cupo_maximo INTEGER NOT NULL,

    cupo_actual INTEGER NOT NULL,

    horario TIME NOT NULL,

    duracion_minutos INTEGER NOT NULL,

    precio NUMERIC(10,2) NOT NULL,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Activa',
                'Cancelada',
                'Completada'
            )
        )
);

-- ==========================================
-- TABLA RESERVACION
-- ==========================================

CREATE TABLE reservacion (

    id_reservacion SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    id_habitacion INTEGER NOT NULL,

    id_temporada INTEGER NOT NULL,

    fecha_check_in DATE NOT NULL,

    fecha_check_out DATE NOT NULL,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Pendiente',
                'Confirmada',
                'Cancelada',
                'NoShow',
                'Completada'
            )
        ),

    total NUMERIC(12,2) NOT NULL,

    fecha_creacion TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reservacion_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),

    CONSTRAINT fk_reservacion_habitacion
        FOREIGN KEY (id_habitacion)
        REFERENCES habitacion(id_habitacion),

    CONSTRAINT fk_reservacion_temporada
        FOREIGN KEY (id_temporada)
        REFERENCES temporada(id_temporada)
);

-- ==========================================
-- TABLA PAGO
-- ==========================================

CREATE TABLE pago (

    id_pago SERIAL PRIMARY KEY,

    id_reservacion INTEGER NOT NULL,

    monto NUMERIC(12,2) NOT NULL,

    tipo VARCHAR(20) NOT NULL
        CHECK (
            tipo IN (
                'Deposito',
                'Abono',
                'Total'
            )
        ),

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Valido',
                'Revertido'
            )
        ),

    fecha_pago TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    concepto VARCHAR(255),

    CONSTRAINT fk_pago_reservacion
        FOREIGN KEY (id_reservacion)
        REFERENCES reservacion(id_reservacion)
);

-- ==========================================
-- TABLA TOUR
-- ==========================================

CREATE TABLE tour (

    id_tour SERIAL PRIMARY KEY,

    id_actividad INTEGER NOT NULL,

    id_guia INTEGER,

    nombre VARCHAR(150) NOT NULL,

    fecha DATE NOT NULL,

    hora_inicio TIME NOT NULL,

    hora_fin TIME NOT NULL,

    cupo_maximo INTEGER NOT NULL,

    cupo_actual INTEGER NOT NULL,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Programado',
                'EnCurso',
                'Completado',
                'Cancelado'
            )
        ),

    CONSTRAINT fk_tour_actividad
        FOREIGN KEY (id_actividad)
        REFERENCES actividad(id_actividad),

    CONSTRAINT fk_tour_guia
        FOREIGN KEY (id_guia)
        REFERENCES guia_turistico(id_guia)
);

-- ==========================================
-- TABLA PARTICIPACION ACTIVIDAD
-- ==========================================

CREATE TABLE participacion_actividad (

    id_participacion SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    id_actividad INTEGER NOT NULL,

    fecha_registro TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Activa',
                'Cancelada'
            )
        ),

    CONSTRAINT fk_pa_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),

    CONSTRAINT fk_pa_actividad
        FOREIGN KEY (id_actividad)
        REFERENCES actividad(id_actividad)
);

-- ==========================================
-- TABLA PARTICIPACION TOUR
-- ==========================================

CREATE TABLE participacion_tour (

    id_participacion SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    id_tour INTEGER NOT NULL,

    fecha_registro TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Activa',
                'Cancelada'
            )
        ),

    CONSTRAINT fk_pt_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),

    CONSTRAINT fk_pt_tour
        FOREIGN KEY (id_tour)
        REFERENCES tour(id_tour)
);

-- ==========================================
-- TABLA EVENTO
-- ==========================================

CREATE TABLE evento (

    id_evento SERIAL PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,

    tipo VARCHAR(30) NOT NULL,

    fecha DATE NOT NULL,

    hora_inicio TIME NOT NULL,

    hora_fin TIME NOT NULL,

    capacidad_maxima INTEGER NOT NULL,

    participantes_actuales INTEGER NOT NULL DEFAULT 0,

    espacio VARCHAR(150) NOT NULL,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Programado',
                'EnCurso',
                'Completado',
                'Cancelado'
            )
        )
);

-- ==========================================
-- TABLA PARTICIPACION EVENTO
-- ==========================================

CREATE TABLE participacion_evento (

    id_participacion SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    id_evento INTEGER NOT NULL,

    fecha_inscripcion TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Inscrito',
                'Cancelado'
            )
        ),

    CONSTRAINT fk_pe_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),

    CONSTRAINT fk_pe_evento
        FOREIGN KEY (id_evento)
        REFERENCES evento(id_evento)
);

-- ==========================================
-- TABLA TICKET
-- ==========================================

CREATE TABLE ticket (

    id_ticket SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    descripcion TEXT NOT NULL,

    area_afectada VARCHAR(150) NOT NULL,

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Abierto',
                'EnProceso',
                'Cerrado'
            )
        ),

    prioridad VARCHAR(20) NOT NULL
        CHECK (
            prioridad IN (
                'Alta',
                'Media',
                'Baja'
            )
        ),

    fecha_creacion TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    fecha_cierre TIMESTAMP,

    CONSTRAINT fk_ticket_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

-- ==========================================
-- TABLA NOTIFICACION
-- ==========================================

CREATE TABLE notificacion (

    id_notificacion SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    mensaje TEXT NOT NULL,

    tipo VARCHAR(30) NOT NULL
        CHECK (
            tipo IN (
                'Confirmacion',
                'Recordatorio',
                'Alerta',
                'Asignacion'
            )
        ),

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Pendiente',
                'Enviada',
                'Leida'
            )
        ),

    fecha_envio TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notificacion_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

-- ==========================================
-- TABLA BITACORA
-- ==========================================

CREATE TABLE bitacora (

    id_bitacora SERIAL PRIMARY KEY,

    id_usuario INTEGER NOT NULL,

    accion VARCHAR(255) NOT NULL,

    modulo VARCHAR(100) NOT NULL,

    fecha_hora TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    ip_origen VARCHAR(50),

    detalle TEXT,

    CONSTRAINT fk_bitacora_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
);

-- ==========================================
-- TABLA ALBERCA
-- ==========================================

CREATE TABLE alberca (

    id_alberca SERIAL PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,

    capacidad_maxima INTEGER NOT NULL,

    ocupacion_actual INTEGER NOT NULL DEFAULT 0,

    funcion VARCHAR(50),

    estado VARCHAR(20) NOT NULL
        CHECK (
            estado IN (
                'Abierta',
                'Cerrada',
                'Mantenimiento'
            )
        )
);