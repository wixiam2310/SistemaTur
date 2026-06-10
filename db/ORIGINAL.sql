--
-- PostgreSQL database dump
--

\restrict naljKWW5CRR5DPL5ZIsaz585kwnO4PctOGX0m3g93H5Ra0hgUklvwx9h7dYKux9

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-06-09 13:59:16

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 256 (class 1259 OID 16763)
-- Name: acceso_turistico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.acceso_turistico (
    id_acceso integer NOT NULL,
    id_usuario integer NOT NULL,
    id_zona integer NOT NULL,
    autorizado boolean NOT NULL,
    fecha_acceso timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.acceso_turistico OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 16762)
-- Name: acceso_turistico_id_acceso_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.acceso_turistico_id_acceso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.acceso_turistico_id_acceso_seq OWNER TO postgres;

--
-- TOC entry 5254 (class 0 OID 0)
-- Dependencies: 255
-- Name: acceso_turistico_id_acceso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.acceso_turistico_id_acceso_seq OWNED BY public.acceso_turistico.id_acceso;


--
-- TOC entry 230 (class 1259 OID 16476)
-- Name: actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.actividad (
    id_actividad integer NOT NULL,
    nombre character varying(150) NOT NULL,
    tipo character varying(30) NOT NULL,
    descripcion text,
    cupo_maximo integer NOT NULL,
    cupo_actual integer NOT NULL,
    horario time without time zone NOT NULL,
    duracion_minutos integer NOT NULL,
    precio numeric(10,2) NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT actividad_estado_check CHECK (((estado)::text = ANY ((ARRAY['Activa'::character varying, 'Cancelada'::character varying, 'Completada'::character varying])::text[]))),
    CONSTRAINT actividad_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['Deportiva'::character varying, 'Acuatica'::character varying, 'Guiada'::character varying, 'Premium'::character varying])::text[])))
);


ALTER TABLE public.actividad OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16475)
-- Name: actividad_id_actividad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.actividad_id_actividad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.actividad_id_actividad_seq OWNER TO postgres;

--
-- TOC entry 5255 (class 0 OID 0)
-- Dependencies: 229
-- Name: actividad_id_actividad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.actividad_id_actividad_seq OWNED BY public.actividad.id_actividad;


--
-- TOC entry 252 (class 1259 OID 16735)
-- Name: alberca; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.alberca (
    id_alberca integer NOT NULL,
    nombre character varying(100) NOT NULL,
    capacidad_maxima integer NOT NULL,
    ocupacion_actual integer DEFAULT 0 NOT NULL,
    funcion character varying(50),
    estado character varying(20) NOT NULL,
    CONSTRAINT alberca_estado_check CHECK (((estado)::text = ANY ((ARRAY['Abierta'::character varying, 'Cerrada'::character varying, 'Mantenimiento'::character varying])::text[])))
);


ALTER TABLE public.alberca OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 16734)
-- Name: alberca_id_alberca_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.alberca_id_alberca_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.alberca_id_alberca_seq OWNER TO postgres;

--
-- TOC entry 5256 (class 0 OID 0)
-- Dependencies: 251
-- Name: alberca_id_alberca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.alberca_id_alberca_seq OWNED BY public.alberca.id_alberca;


--
-- TOC entry 250 (class 1259 OID 16715)
-- Name: bitacora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bitacora (
    id_bitacora integer NOT NULL,
    id_usuario integer NOT NULL,
    accion character varying(255) NOT NULL,
    modulo character varying(100) NOT NULL,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    ip_origen character varying(50),
    detalle text
);


ALTER TABLE public.bitacora OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 16714)
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bitacora_id_bitacora_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.bitacora_id_bitacora_seq OWNER TO postgres;

--
-- TOC entry 5257 (class 0 OID 0)
-- Dependencies: 249
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bitacora_id_bitacora_seq OWNED BY public.bitacora.id_bitacora;


--
-- TOC entry 242 (class 1259 OID 16625)
-- Name: evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.evento (
    id_evento integer NOT NULL,
    nombre character varying(150) NOT NULL,
    tipo character varying(30) NOT NULL,
    fecha date NOT NULL,
    hora_inicio time without time zone NOT NULL,
    hora_fin time without time zone NOT NULL,
    capacidad_maxima integer NOT NULL,
    participantes_actuales integer DEFAULT 0 NOT NULL,
    espacio character varying(150) NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT evento_estado_check CHECK (((estado)::text = ANY ((ARRAY['Programado'::character varying, 'EnCurso'::character varying, 'Completado'::character varying, 'Cancelado'::character varying])::text[])))
);


ALTER TABLE public.evento OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 16624)
-- Name: evento_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.evento_id_evento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.evento_id_evento_seq OWNER TO postgres;

--
-- TOC entry 5258 (class 0 OID 0)
-- Dependencies: 241
-- Name: evento_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.evento_id_evento_seq OWNED BY public.evento.id_evento;


--
-- TOC entry 224 (class 1259 OID 16426)
-- Name: guia_turistico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.guia_turistico (
    id_guia integer NOT NULL,
    id_usuario integer NOT NULL,
    especialidad character varying(150),
    estado character varying(20) NOT NULL,
    CONSTRAINT guia_turistico_estado_check CHECK (((estado)::text = ANY ((ARRAY['Disponible'::character varying, 'Asignado'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.guia_turistico OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16425)
-- Name: guia_turistico_id_guia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.guia_turistico_id_guia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.guia_turistico_id_guia_seq OWNER TO postgres;

--
-- TOC entry 5259 (class 0 OID 0)
-- Dependencies: 223
-- Name: guia_turistico_id_guia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.guia_turistico_id_guia_seq OWNED BY public.guia_turistico.id_guia;


--
-- TOC entry 228 (class 1259 OID 16457)
-- Name: habitacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.habitacion (
    id_habitacion integer NOT NULL,
    numero character varying(20) NOT NULL,
    edificio character(1) NOT NULL,
    tipo character varying(30) NOT NULL,
    capacidad integer NOT NULL,
    precio_base numeric(10,2) NOT NULL,
    estado character varying(30) NOT NULL,
    CONSTRAINT habitacion_edificio_check CHECK ((edificio = ANY (ARRAY['A'::bpchar, 'B'::bpchar, 'C'::bpchar]))),
    CONSTRAINT habitacion_estado_check CHECK (((estado)::text = ANY ((ARRAY['Disponible'::character varying, 'Reservada'::character varying, 'Mantenimiento'::character varying, 'Limpieza'::character varying])::text[]))),
    CONSTRAINT habitacion_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['Estandar'::character varying, 'Doble'::character varying, 'VistaMar'::character varying, 'JuniorSuite'::character varying, 'Ejecutiva'::character varying, 'Presidencial'::character varying])::text[])))
);


ALTER TABLE public.habitacion OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16456)
-- Name: habitacion_id_habitacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.habitacion_id_habitacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.habitacion_id_habitacion_seq OWNER TO postgres;

--
-- TOC entry 5260 (class 0 OID 0)
-- Dependencies: 227
-- Name: habitacion_id_habitacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.habitacion_id_habitacion_seq OWNED BY public.habitacion.id_habitacion;


--
-- TOC entry 248 (class 1259 OID 16692)
-- Name: notificacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notificacion (
    id_notificacion integer NOT NULL,
    id_usuario integer NOT NULL,
    mensaje text NOT NULL,
    tipo character varying(30) NOT NULL,
    estado character varying(20) NOT NULL,
    fecha_envio timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT notificacion_estado_check CHECK (((estado)::text = ANY ((ARRAY['Pendiente'::character varying, 'Enviada'::character varying, 'Leida'::character varying])::text[]))),
    CONSTRAINT notificacion_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['Confirmacion'::character varying, 'Recordatorio'::character varying, 'Alerta'::character varying, 'Asignacion'::character varying])::text[])))
);


ALTER TABLE public.notificacion OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 16691)
-- Name: notificacion_id_notificacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notificacion_id_notificacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notificacion_id_notificacion_seq OWNER TO postgres;

--
-- TOC entry 5261 (class 0 OID 0)
-- Dependencies: 247
-- Name: notificacion_id_notificacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificacion_id_notificacion_seq OWNED BY public.notificacion.id_notificacion;


--
-- TOC entry 234 (class 1259 OID 16529)
-- Name: pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pago (
    id_pago integer NOT NULL,
    id_reservacion integer NOT NULL,
    monto numeric(12,2) NOT NULL,
    tipo character varying(20) NOT NULL,
    estado character varying(20) NOT NULL,
    fecha_pago timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    concepto character varying(255),
    CONSTRAINT pago_estado_check CHECK (((estado)::text = ANY ((ARRAY['Valido'::character varying, 'Revertido'::character varying])::text[]))),
    CONSTRAINT pago_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['Deposito'::character varying, 'Abono'::character varying, 'Total'::character varying])::text[])))
);


ALTER TABLE public.pago OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16528)
-- Name: pago_id_pago_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pago_id_pago_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pago_id_pago_seq OWNER TO postgres;

--
-- TOC entry 5262 (class 0 OID 0)
-- Dependencies: 233
-- Name: pago_id_pago_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pago_id_pago_seq OWNED BY public.pago.id_pago;


--
-- TOC entry 238 (class 1259 OID 16577)
-- Name: participacion_actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participacion_actividad (
    id_participacion integer NOT NULL,
    id_usuario integer NOT NULL,
    id_actividad integer NOT NULL,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT participacion_actividad_estado_check CHECK (((estado)::text = ANY ((ARRAY['Activa'::character varying, 'Cancelada'::character varying])::text[])))
);


ALTER TABLE public.participacion_actividad OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 16576)
-- Name: participacion_actividad_id_participacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.participacion_actividad_id_participacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.participacion_actividad_id_participacion_seq OWNER TO postgres;

--
-- TOC entry 5263 (class 0 OID 0)
-- Dependencies: 237
-- Name: participacion_actividad_id_participacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.participacion_actividad_id_participacion_seq OWNED BY public.participacion_actividad.id_participacion;


--
-- TOC entry 244 (class 1259 OID 16644)
-- Name: participacion_evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participacion_evento (
    id_participacion integer NOT NULL,
    id_usuario integer NOT NULL,
    id_evento integer NOT NULL,
    fecha_inscripcion timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT participacion_evento_estado_check CHECK (((estado)::text = ANY ((ARRAY['Inscrito'::character varying, 'Cancelado'::character varying])::text[])))
);


ALTER TABLE public.participacion_evento OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 16643)
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.participacion_evento_id_participacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.participacion_evento_id_participacion_seq OWNER TO postgres;

--
-- TOC entry 5264 (class 0 OID 0)
-- Dependencies: 243
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.participacion_evento_id_participacion_seq OWNED BY public.participacion_evento.id_participacion;


--
-- TOC entry 240 (class 1259 OID 16601)
-- Name: participacion_tour; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participacion_tour (
    id_participacion integer NOT NULL,
    id_usuario integer NOT NULL,
    id_tour integer NOT NULL,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT participacion_tour_estado_check CHECK (((estado)::text = ANY ((ARRAY['Activa'::character varying, 'Cancelada'::character varying])::text[])))
);


ALTER TABLE public.participacion_tour OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 16600)
-- Name: participacion_tour_id_participacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.participacion_tour_id_participacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.participacion_tour_id_participacion_seq OWNER TO postgres;

--
-- TOC entry 5265 (class 0 OID 0)
-- Dependencies: 239
-- Name: participacion_tour_id_participacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.participacion_tour_id_participacion_seq OWNED BY public.participacion_tour.id_participacion;


--
-- TOC entry 232 (class 1259 OID 16496)
-- Name: reservacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reservacion (
    id_reservacion integer NOT NULL,
    id_usuario integer NOT NULL,
    id_habitacion integer NOT NULL,
    id_temporada integer NOT NULL,
    fecha_check_in date NOT NULL,
    fecha_check_out date NOT NULL,
    estado character varying(20) NOT NULL,
    total numeric(12,2) NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT reservacion_estado_check CHECK (((estado)::text = ANY ((ARRAY['Pendiente'::character varying, 'Confirmada'::character varying, 'Cancelada'::character varying, 'NoShow'::character varying, 'Completada'::character varying])::text[])))
);


ALTER TABLE public.reservacion OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16495)
-- Name: reservacion_id_reservacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reservacion_id_reservacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservacion_id_reservacion_seq OWNER TO postgres;

--
-- TOC entry 5266 (class 0 OID 0)
-- Dependencies: 231
-- Name: reservacion_id_reservacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reservacion_id_reservacion_seq OWNED BY public.reservacion.id_reservacion;


--
-- TOC entry 220 (class 1259 OID 16387)
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rol (
    id_rol integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16386)
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_id_rol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rol_id_rol_seq OWNER TO postgres;

--
-- TOC entry 5267 (class 0 OID 0)
-- Dependencies: 219
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- TOC entry 226 (class 1259 OID 16444)
-- Name: temporada; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.temporada (
    id_temporada integer NOT NULL,
    nombre character varying(20) NOT NULL,
    incremento_porcentaje numeric(5,2) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    CONSTRAINT temporada_nombre_check CHECK (((nombre)::text = ANY ((ARRAY['Baja'::character varying, 'Media'::character varying, 'Alta'::character varying, 'Especial'::character varying])::text[])))
);


ALTER TABLE public.temporada OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16443)
-- Name: temporada_id_temporada_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.temporada_id_temporada_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.temporada_id_temporada_seq OWNER TO postgres;

--
-- TOC entry 5268 (class 0 OID 0)
-- Dependencies: 225
-- Name: temporada_id_temporada_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.temporada_id_temporada_seq OWNED BY public.temporada.id_temporada;


--
-- TOC entry 246 (class 1259 OID 16668)
-- Name: ticket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ticket (
    id_ticket integer NOT NULL,
    id_usuario integer NOT NULL,
    descripcion text NOT NULL,
    area_afectada character varying(150) NOT NULL,
    estado character varying(20) NOT NULL,
    prioridad character varying(20) NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    fecha_cierre timestamp without time zone,
    CONSTRAINT ticket_estado_check CHECK (((estado)::text = ANY ((ARRAY['Abierto'::character varying, 'EnProceso'::character varying, 'Cerrado'::character varying])::text[]))),
    CONSTRAINT ticket_prioridad_check CHECK (((prioridad)::text = ANY ((ARRAY['Alta'::character varying, 'Media'::character varying, 'Baja'::character varying])::text[])))
);


ALTER TABLE public.ticket OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 16667)
-- Name: ticket_id_ticket_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ticket_id_ticket_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_id_ticket_seq OWNER TO postgres;

--
-- TOC entry 5269 (class 0 OID 0)
-- Dependencies: 245
-- Name: ticket_id_ticket_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ticket_id_ticket_seq OWNED BY public.ticket.id_ticket;


--
-- TOC entry 236 (class 1259 OID 16550)
-- Name: tour; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tour (
    id_tour integer NOT NULL,
    id_actividad integer NOT NULL,
    id_guia integer,
    nombre character varying(150) NOT NULL,
    fecha date NOT NULL,
    hora_inicio time without time zone NOT NULL,
    hora_fin time without time zone NOT NULL,
    cupo_maximo integer NOT NULL,
    cupo_actual integer NOT NULL,
    estado character varying(20) NOT NULL,
    CONSTRAINT tour_estado_check CHECK (((estado)::text = ANY ((ARRAY['Programado'::character varying, 'EnCurso'::character varying, 'Completado'::character varying, 'Cancelado'::character varying])::text[])))
);


ALTER TABLE public.tour OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16549)
-- Name: tour_id_tour_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tour_id_tour_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tour_id_tour_seq OWNER TO postgres;

--
-- TOC entry 5270 (class 0 OID 0)
-- Dependencies: 235
-- Name: tour_id_tour_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tour_id_tour_seq OWNED BY public.tour.id_tour;


--
-- TOC entry 222 (class 1259 OID 16400)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    id_rol integer NOT NULL,
    nombre character varying(100) NOT NULL,
    apellido character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    password_hash character varying(255) NOT NULL,
    telefono character varying(20),
    estado character varying(20) NOT NULL,
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT usuario_estado_check CHECK (((estado)::text = ANY ((ARRAY['Activo'::character varying, 'Inactivo'::character varying])::text[])))
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16399)
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuario_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 5271 (class 0 OID 0)
-- Dependencies: 221
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- TOC entry 254 (class 1259 OID 16750)
-- Name: zona_turistica; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.zona_turistica (
    id_zona integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    estado character varying(20) NOT NULL,
    CONSTRAINT zona_turistica_estado_check CHECK (((estado)::text = ANY ((ARRAY['Activa'::character varying, 'Inactiva'::character varying])::text[])))
);


ALTER TABLE public.zona_turistica OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 16749)
-- Name: zona_turistica_id_zona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.zona_turistica_id_zona_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.zona_turistica_id_zona_seq OWNER TO postgres;

--
-- TOC entry 5272 (class 0 OID 0)
-- Dependencies: 253
-- Name: zona_turistica_id_zona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.zona_turistica_id_zona_seq OWNED BY public.zona_turistica.id_zona;


--
-- TOC entry 4975 (class 2604 OID 16766)
-- Name: acceso_turistico id_acceso; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acceso_turistico ALTER COLUMN id_acceso SET DEFAULT nextval('public.acceso_turistico_id_acceso_seq'::regclass);


--
-- TOC entry 4952 (class 2604 OID 16479)
-- Name: actividad id_actividad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad ALTER COLUMN id_actividad SET DEFAULT nextval('public.actividad_id_actividad_seq'::regclass);


--
-- TOC entry 4972 (class 2604 OID 16738)
-- Name: alberca id_alberca; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alberca ALTER COLUMN id_alberca SET DEFAULT nextval('public.alberca_id_alberca_seq'::regclass);


--
-- TOC entry 4970 (class 2604 OID 16718)
-- Name: bitacora id_bitacora; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora ALTER COLUMN id_bitacora SET DEFAULT nextval('public.bitacora_id_bitacora_seq'::regclass);


--
-- TOC entry 4962 (class 2604 OID 16628)
-- Name: evento id_evento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evento ALTER COLUMN id_evento SET DEFAULT nextval('public.evento_id_evento_seq'::regclass);


--
-- TOC entry 4949 (class 2604 OID 16429)
-- Name: guia_turistico id_guia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_turistico ALTER COLUMN id_guia SET DEFAULT nextval('public.guia_turistico_id_guia_seq'::regclass);


--
-- TOC entry 4951 (class 2604 OID 16460)
-- Name: habitacion id_habitacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitacion ALTER COLUMN id_habitacion SET DEFAULT nextval('public.habitacion_id_habitacion_seq'::regclass);


--
-- TOC entry 4968 (class 2604 OID 16695)
-- Name: notificacion id_notificacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificacion ALTER COLUMN id_notificacion SET DEFAULT nextval('public.notificacion_id_notificacion_seq'::regclass);


--
-- TOC entry 4955 (class 2604 OID 16532)
-- Name: pago id_pago; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago ALTER COLUMN id_pago SET DEFAULT nextval('public.pago_id_pago_seq'::regclass);


--
-- TOC entry 4958 (class 2604 OID 16580)
-- Name: participacion_actividad id_participacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_actividad ALTER COLUMN id_participacion SET DEFAULT nextval('public.participacion_actividad_id_participacion_seq'::regclass);


--
-- TOC entry 4964 (class 2604 OID 16647)
-- Name: participacion_evento id_participacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento ALTER COLUMN id_participacion SET DEFAULT nextval('public.participacion_evento_id_participacion_seq'::regclass);


--
-- TOC entry 4960 (class 2604 OID 16604)
-- Name: participacion_tour id_participacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_tour ALTER COLUMN id_participacion SET DEFAULT nextval('public.participacion_tour_id_participacion_seq'::regclass);


--
-- TOC entry 4953 (class 2604 OID 16499)
-- Name: reservacion id_reservacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservacion ALTER COLUMN id_reservacion SET DEFAULT nextval('public.reservacion_id_reservacion_seq'::regclass);


--
-- TOC entry 4946 (class 2604 OID 16390)
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- TOC entry 4950 (class 2604 OID 16447)
-- Name: temporada id_temporada; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temporada ALTER COLUMN id_temporada SET DEFAULT nextval('public.temporada_id_temporada_seq'::regclass);


--
-- TOC entry 4966 (class 2604 OID 16671)
-- Name: ticket id_ticket; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket ALTER COLUMN id_ticket SET DEFAULT nextval('public.ticket_id_ticket_seq'::regclass);


--
-- TOC entry 4957 (class 2604 OID 16553)
-- Name: tour id_tour; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tour ALTER COLUMN id_tour SET DEFAULT nextval('public.tour_id_tour_seq'::regclass);


--
-- TOC entry 4947 (class 2604 OID 16403)
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- TOC entry 4974 (class 2604 OID 16753)
-- Name: zona_turistica id_zona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zona_turistica ALTER COLUMN id_zona SET DEFAULT nextval('public.zona_turistica_id_zona_seq'::regclass);


--
-- TOC entry 5248 (class 0 OID 16763)
-- Dependencies: 256
-- Data for Name: acceso_turistico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.acceso_turistico (id_acceso, id_usuario, id_zona, autorizado, fecha_acceso) FROM stdin;
3	9	1	t	2026-06-05 21:50:01.27877
6	9	3	f	2026-06-07 20:10:53.131274
7	10	3	t	2026-06-09 13:34:33.957198
\.


--
-- TOC entry 5222 (class 0 OID 16476)
-- Dependencies: 230
-- Data for Name: actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.actividad (id_actividad, nombre, tipo, descripcion, cupo_maximo, cupo_actual, horario, duracion_minutos, precio, estado) FROM stdin;
1	Snorkel en Arrecifes	Acuatica	Recorrido guiado de snorkel en arrecifes protegidos.	20	0	09:00:00	120	850.00	Activa
2	Paseo en Kayak	Acuatica	Recorrido recreativo en kayak por la costa.	15	0	10:00:00	90	650.00	Activa
3	Paseo en Banana	Acuatica	Actividad acuatica recreativa en banana inflable.	12	0	11:00:00	45	450.00	Activa
4	Introduccion al Buceo	Acuatica	Experiencia basica de buceo con instructor certificado.	10	0	08:00:00	180	1200.00	Activa
5	Tour Privado en Yate	Premium	Recorrido exclusivo en embarcacion privada.	8	0	16:00:00	240	3500.00	Activa
6	Cena Gourmet Frente al Mar	Premium	Experiencia gastronomica premium.	20	0	20:00:00	120	1800.00	Activa
7	Spa Premium	Premium	Tratamiento integral de relajacion y bienestar.	15	0	14:00:00	120	2200.00	Activa
9	Tour Cultural Local	Guiada	Recorrido por sitios historicos y culturales.	30	0	15:00:00	150	500.00	Activa
10	Senderismo Interpretativo	Guiada	Recorrido guiado por senderos naturales.	20	0	07:00:00	210	600.00	Activa
8	Recorrido Ecologico	Guiada	Visita guiada a ecosistemas protegidos.	25	1	09:30:00	180	550.00	Activa
\.


--
-- TOC entry 5244 (class 0 OID 16735)
-- Dependencies: 252
-- Data for Name: alberca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.alberca (id_alberca, nombre, capacidad_maxima, ocupacion_actual, funcion, estado) FROM stdin;
\.


--
-- TOC entry 5242 (class 0 OID 16715)
-- Dependencies: 250
-- Data for Name: bitacora; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bitacora (id_bitacora, id_usuario, accion, modulo, fecha_hora, ip_origen, detalle) FROM stdin;
\.


--
-- TOC entry 5234 (class 0 OID 16625)
-- Dependencies: 242
-- Data for Name: evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.evento (id_evento, nombre, tipo, fecha, hora_inicio, hora_fin, capacidad_maxima, participantes_actuales, espacio, estado) FROM stdin;
1	Congreso Internacional de Turismo Sustentable	Congreso	2026-07-05	09:00:00	17:00:00	300	120	Zona de Eventos	Programado
2	Festival Cultural del Pacifico	Cultural	2026-06-20	18:00:00	22:00:00	200	75	Zona de Eventos	Programado
3	Feria Artesanal Regional	Cultural	2026-07-12	10:00:00	18:00:00	180	60	Zona de Eventos	Programado
4	Conferencia de Innovacion Hotelera	Congreso	2026-07-18	09:00:00	15:00:00	150	40	Zona de Eventos	Programado
5	Encuentro de Guias Turisticos	Congreso	2026-07-25	09:00:00	13:00:00	120	35	Zona de Eventos	Programado
6	Torneo Recreativo de Voleibol de Playa	Social	2026-06-28	08:00:00	14:00:00	80	32	Playa Norte	Programado
7	Festival Nocturno del Mar	Cultural	2026-07-02	19:00:00	23:00:00	120	50	Playa Sur	Programado
8	Competencia de Castillos de Arena	Social	2026-07-09	09:00:00	13:00:00	60	20	Playa Norte	Programado
9	Noche de Cine Bajo las Estrellas	Social	2026-08-01	20:00:00	23:00:00	100	45	Playa Sur	Programado
10	Cena de Gala para Huespedes VIP	Social	2026-08-08	20:00:00	23:30:00	100	25	Hotel Principal	Programado
\.


--
-- TOC entry 5216 (class 0 OID 16426)
-- Dependencies: 224
-- Data for Name: guia_turistico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.guia_turistico (id_guia, id_usuario, especialidad, estado) FROM stdin;
1	7	Actividades Acuaticas	Disponible
2	14	Premium	Disponible
3	15	Guiada	Disponible
\.


--
-- TOC entry 5220 (class 0 OID 16457)
-- Dependencies: 228
-- Data for Name: habitacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.habitacion (id_habitacion, numero, edificio, tipo, capacidad, precio_base, estado) FROM stdin;
6	A106	A	Estandar	2	1800.00	Disponible
7	A107	A	Estandar	2	1800.00	Disponible
8	A108	A	Estandar	2	1800.00	Disponible
9	A109	A	Estandar	2	1800.00	Disponible
10	A110	A	Estandar	2	1800.00	Disponible
11	A111	A	Estandar	2	1800.00	Disponible
12	A112	A	Estandar	2	1800.00	Disponible
13	A113	A	Estandar	2	1800.00	Disponible
14	A114	A	Estandar	2	1800.00	Disponible
15	A115	A	Estandar	2	1800.00	Disponible
16	A116	A	Estandar	2	1800.00	Disponible
17	A117	A	Estandar	2	1800.00	Disponible
18	A118	A	Estandar	2	1800.00	Disponible
19	A119	A	Estandar	2	1800.00	Disponible
20	A120	A	Estandar	2	1800.00	Disponible
21	A121	A	Estandar	2	1800.00	Disponible
22	A122	A	Estandar	2	1800.00	Disponible
23	A123	A	Estandar	2	1800.00	Disponible
24	A124	A	Estandar	2	1800.00	Disponible
25	A125	A	Estandar	2	1800.00	Disponible
26	A126	A	Estandar	2	1800.00	Disponible
27	A127	A	Estandar	2	1800.00	Disponible
28	A128	A	Estandar	2	1800.00	Disponible
29	A129	A	Estandar	2	1800.00	Disponible
30	A130	A	Estandar	2	1800.00	Disponible
31	A131	A	Estandar	2	1800.00	Disponible
32	B201	B	Estandar	2	1800.00	Disponible
33	B202	B	Estandar	2	1800.00	Disponible
34	B203	B	Estandar	2	1800.00	Disponible
35	B204	B	Estandar	2	1800.00	Disponible
36	B205	B	Estandar	2	1800.00	Disponible
37	B206	B	Estandar	2	1800.00	Disponible
38	B207	B	Estandar	2	1800.00	Disponible
39	B208	B	Estandar	2	1800.00	Disponible
40	B209	B	Estandar	2	1800.00	Disponible
41	B210	B	Estandar	2	1800.00	Disponible
42	B211	B	Estandar	2	1800.00	Disponible
43	B212	B	Estandar	2	1800.00	Disponible
44	B213	B	Estandar	2	1800.00	Disponible
45	B214	B	Estandar	2	1800.00	Disponible
46	B215	B	Estandar	2	1800.00	Disponible
47	B216	B	Estandar	2	1800.00	Disponible
48	B217	B	Estandar	2	1800.00	Disponible
49	B218	B	Estandar	2	1800.00	Disponible
50	B219	B	Estandar	2	1800.00	Disponible
51	B220	B	Estandar	2	1800.00	Disponible
52	B221	B	Estandar	2	1800.00	Disponible
53	B222	B	Estandar	2	1800.00	Disponible
54	B223	B	Estandar	2	1800.00	Disponible
55	B224	B	Estandar	2	1800.00	Disponible
56	B225	B	Estandar	2	1800.00	Disponible
57	B226	B	Estandar	2	1800.00	Disponible
58	B227	B	Estandar	2	1800.00	Disponible
59	B228	B	Estandar	2	1800.00	Disponible
60	B229	B	Estandar	2	1800.00	Disponible
61	C301	C	Estandar	2	1800.00	Disponible
62	C302	C	Estandar	2	1800.00	Disponible
63	C303	C	Estandar	2	1800.00	Disponible
64	C304	C	Estandar	2	1800.00	Disponible
65	C305	C	Estandar	2	1800.00	Disponible
66	C306	C	Estandar	2	1800.00	Disponible
67	C307	C	Estandar	2	1800.00	Disponible
68	C308	C	Estandar	2	1800.00	Disponible
69	C309	C	Estandar	2	1800.00	Disponible
70	C310	C	Estandar	2	1800.00	Disponible
71	C311	C	Estandar	2	1800.00	Disponible
72	C312	C	Estandar	2	1800.00	Disponible
73	C313	C	Estandar	2	1800.00	Disponible
74	C314	C	Estandar	2	1800.00	Disponible
75	C315	C	Estandar	2	1800.00	Disponible
76	C316	C	Estandar	2	1800.00	Disponible
77	C317	C	Estandar	2	1800.00	Disponible
78	C318	C	Estandar	2	1800.00	Disponible
79	C319	C	Estandar	2	1800.00	Disponible
80	D001	A	Doble	4	2500.00	Disponible
81	D002	A	Doble	4	2500.00	Disponible
83	D004	A	Doble	4	2500.00	Disponible
84	D005	A	Doble	4	2500.00	Disponible
85	D006	A	Doble	4	2500.00	Disponible
86	D007	A	Doble	4	2500.00	Disponible
87	D008	A	Doble	4	2500.00	Disponible
88	D009	A	Doble	4	2500.00	Disponible
89	D010	A	Doble	4	2500.00	Disponible
90	D011	A	Doble	4	2500.00	Disponible
91	D012	A	Doble	4	2500.00	Disponible
92	D013	A	Doble	4	2500.00	Disponible
93	D014	A	Doble	4	2500.00	Disponible
94	D015	A	Doble	4	2500.00	Disponible
95	D016	B	Doble	4	2500.00	Disponible
96	D017	B	Doble	4	2500.00	Disponible
97	D018	B	Doble	4	2500.00	Disponible
98	D019	B	Doble	4	2500.00	Disponible
99	D020	B	Doble	4	2500.00	Disponible
100	D021	B	Doble	4	2500.00	Disponible
101	D022	B	Doble	4	2500.00	Disponible
102	D023	B	Doble	4	2500.00	Disponible
103	D024	B	Doble	4	2500.00	Disponible
104	D025	B	Doble	4	2500.00	Disponible
105	D026	B	Doble	4	2500.00	Disponible
106	D027	B	Doble	4	2500.00	Disponible
107	D028	C	Doble	4	2500.00	Disponible
108	D029	C	Doble	4	2500.00	Disponible
109	D030	C	Doble	4	2500.00	Disponible
110	D031	C	Doble	4	2500.00	Disponible
111	D032	C	Doble	4	2500.00	Disponible
112	D033	C	Doble	4	2500.00	Disponible
113	D034	C	Doble	4	2500.00	Disponible
114	D035	C	Doble	4	2500.00	Disponible
115	VM001	A	VistaMar	2	3200.00	Disponible
116	VM002	A	VistaMar	2	3200.00	Disponible
117	VM003	A	VistaMar	2	3200.00	Disponible
118	VM004	A	VistaMar	2	3200.00	Disponible
119	VM005	A	VistaMar	2	3200.00	Disponible
2	A102	A	Estandar	2	1800.00	Reservada
3	A103	A	Estandar	2	1800.00	Reservada
82	D003	A	Doble	4	2500.00	Reservada
4	A104	A	Estandar	2	1800.00	Reservada
5	A105	A	Estandar	2	1800.00	Reservada
120	VM006	A	VistaMar	2	3200.00	Disponible
121	VM007	A	VistaMar	2	3200.00	Disponible
122	VM008	A	VistaMar	2	3200.00	Disponible
123	VM009	A	VistaMar	2	3200.00	Disponible
124	VM010	A	VistaMar	2	3200.00	Disponible
125	VM011	B	VistaMar	2	3200.00	Disponible
126	VM012	B	VistaMar	2	3200.00	Disponible
127	VM013	B	VistaMar	2	3200.00	Disponible
128	VM014	B	VistaMar	2	3200.00	Disponible
129	VM015	B	VistaMar	2	3200.00	Disponible
130	VM016	B	VistaMar	2	3200.00	Disponible
131	VM017	B	VistaMar	2	3200.00	Disponible
132	VM018	B	VistaMar	2	3200.00	Disponible
133	VM019	C	VistaMar	2	3200.00	Disponible
134	VM020	C	VistaMar	2	3200.00	Disponible
135	VM021	C	VistaMar	2	3200.00	Disponible
136	VM022	C	VistaMar	2	3200.00	Disponible
137	VM023	C	VistaMar	2	3200.00	Disponible
140	JS001	A	JuniorSuite	3	4200.00	Disponible
141	JS002	A	JuniorSuite	3	4200.00	Disponible
142	JS003	A	JuniorSuite	3	4200.00	Disponible
143	JS004	A	JuniorSuite	3	4200.00	Disponible
144	JS005	A	JuniorSuite	3	4200.00	Disponible
145	JS006	A	JuniorSuite	3	4200.00	Disponible
146	JS007	A	JuniorSuite	3	4200.00	Disponible
147	JS008	A	JuniorSuite	3	4200.00	Disponible
148	JS009	B	JuniorSuite	3	4200.00	Disponible
149	JS010	B	JuniorSuite	3	4200.00	Disponible
150	JS011	B	JuniorSuite	3	4200.00	Disponible
151	JS012	B	JuniorSuite	3	4200.00	Disponible
152	JS013	B	JuniorSuite	3	4200.00	Disponible
153	JS014	B	JuniorSuite	3	4200.00	Disponible
154	JS015	C	JuniorSuite	3	4200.00	Disponible
155	JS016	C	JuniorSuite	3	4200.00	Disponible
156	JS017	C	JuniorSuite	3	4200.00	Disponible
157	JS018	C	JuniorSuite	3	4200.00	Disponible
158	JS019	C	JuniorSuite	3	4200.00	Disponible
159	JS020	C	JuniorSuite	3	4200.00	Disponible
160	EJ001	A	Ejecutiva	4	5500.00	Disponible
161	EJ002	A	Ejecutiva	4	5500.00	Disponible
162	EJ003	A	Ejecutiva	4	5500.00	Disponible
163	EJ004	A	Ejecutiva	4	5500.00	Disponible
164	EJ005	A	Ejecutiva	4	5500.00	Disponible
165	EJ006	A	Ejecutiva	4	5500.00	Disponible
166	EJ007	B	Ejecutiva	4	5500.00	Disponible
167	EJ008	B	Ejecutiva	4	5500.00	Disponible
168	EJ009	B	Ejecutiva	4	5500.00	Disponible
169	EJ010	B	Ejecutiva	4	5500.00	Disponible
170	EJ011	B	Ejecutiva	4	5500.00	Disponible
171	EJ012	C	Ejecutiva	4	5500.00	Disponible
172	EJ013	C	Ejecutiva	4	5500.00	Disponible
173	EJ014	C	Ejecutiva	4	5500.00	Disponible
174	EJ015	C	Ejecutiva	4	5500.00	Disponible
176	PR002	A	Presidencial	8	9000.00	Disponible
177	PR003	B	Presidencial	8	9000.00	Disponible
178	PR004	B	Presidencial	8	9000.00	Disponible
180	PR006	C	Presidencial	8	9000.00	Disponible
1	A101	A	Estandar	2	1800.00	Reservada
139	VM025	C	VistaMar	2	3200.00	Reservada
175	PR001	A	Presidencial	8	9000.00	Reservada
179	PR005	C	Presidencial	8	9000.00	Disponible
138	VM024	C	VistaMar	2	3200.00	Reservada
\.


--
-- TOC entry 5240 (class 0 OID 16692)
-- Dependencies: 248
-- Data for Name: notificacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notificacion (id_notificacion, id_usuario, mensaje, tipo, estado, fecha_envio) FROM stdin;
2	6	Su reservacion fue confirmada	Confirmacion	Enviada	2026-06-05 16:20:54.631417
3	6	Recordatorio de Tour Playa Norte	Recordatorio	Pendiente	2026-06-05 16:20:54.631417
4	6	Evento Noche Mexicana proximo	Alerta	Pendiente	2026-06-05 16:20:54.631417
5	6	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-05 17:30:17.560748
6	6	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-05 17:30:53.376518
7	6	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-05 17:35:03.976119
8	9	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-05 19:45:23.808794
9	9	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-05 19:45:40.412284
10	6	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-05 22:27:19.867228
11	10	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-05 22:32:12.519634
12	11	Inscripción exitosa a tour	Confirmacion	Enviada	2026-06-05 23:18:20.924422
13	6	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-06 19:24:36.927106
14	10	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 11:49:44.3631
15	6	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 16:35:32.851283
16	10	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 16:46:56.189984
17	10	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 16:47:10.531634
18	10	Inscripción exitosa a tour	Confirmacion	Enviada	2026-06-07 16:47:28.945556
19	10	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-07 17:53:46.897066
20	11	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-07 17:54:03.416099
21	8	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-07 18:04:18.647403
22	10	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-07 19:07:48.510499
23	10	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 22:11:07.868894
24	10	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 22:11:39.588324
25	10	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-07 22:12:40.528117
26	6	Reservación realizada correctamente	Confirmacion	Enviada	2026-06-07 22:27:52.616253
27	6	Reservación realizada correctamente. Temporada: Media. Total: $14490 MXN	Confirmacion	Enviada	2026-06-08 02:32:31.03938
28	6	Reservación realizada correctamente. Temporada: Media. Total: $14490 MXN	Confirmacion	Enviada	2026-06-08 02:33:07.335084
29	6	Reservación realizada correctamente. Temporada: Media. Total: $2070 MXN	Confirmacion	Enviada	2026-06-08 02:44:25.785953
30	6	Reservación realizada correctamente. Temporada: Baja. Total: $2500 MXN	Confirmacion	Enviada	2026-06-08 03:04:27.666418
31	6	Reservación realizada correctamente. Temporada: Baja. Total: $3200 MXN	Confirmacion	Enviada	2026-06-08 03:05:11.930658
32	6	Reservación realizada correctamente. Temporada: Media. Total: $14490 MXN	Confirmacion	Enviada	2026-06-08 03:08:35.200959
33	10	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-08 10:49:36.082642
34	6	Inscripción exitosa a tour	Confirmacion	Enviada	2026-06-08 10:53:19.948965
35	6	Inscripción exitosa a tour	Confirmacion	Enviada	2026-06-08 11:08:15.980928
36	6	Reservación realizada correctamente. Temporada: Media. Total: $10350 MXN	Confirmacion	Enviada	2026-06-08 15:37:04.656697
37	10	Inscripción exitosa a tour	Confirmacion	Enviada	2026-06-08 21:43:18.36714
38	6	Reservación realizada correctamente. Temporada: Media. Total: $2070 MXN	Confirmacion	Enviada	2026-06-09 11:07:24.393811
39	10	Reservación realizada correctamente. Temporada: Media. Total: $128.8 MXN	Confirmacion	Enviada	2026-06-09 11:10:02.089817
40	11	Reservación realizada correctamente. Temporada: Media. Total: $10350 MXN	Confirmacion	Enviada	2026-06-09 11:18:57.225891
41	10	Inscripción exitosa a actividad	Confirmacion	Enviada	2026-06-09 11:21:29.604003
42	10	Inscripción exitosa a evento	Confirmacion	Enviada	2026-06-09 11:59:54.143629
43	10	Reservación realizada correctamente. Temporada: Media. Total: $114080 MXN	Confirmacion	Enviada	2026-06-09 13:31:36.731044
\.


--
-- TOC entry 5226 (class 0 OID 16529)
-- Dependencies: 234
-- Data for Name: pago; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pago (id_pago, id_reservacion, monto, tipo, estado, fecha_pago, concepto) FROM stdin;
1	3	400.00	Abono	Valido	2026-06-08 15:09:48.83098	T
2	4	3105.00	Deposito	Valido	2026-06-08 20:44:25.003727	DEPOSITO
3	4	7245.00	Deposito	Valido	2026-06-08 21:40:29.271163	DEPOSITO
\.


--
-- TOC entry 5230 (class 0 OID 16577)
-- Dependencies: 238
-- Data for Name: participacion_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participacion_actividad (id_participacion, id_usuario, id_actividad, fecha_registro, estado) FROM stdin;
1	10	8	2026-06-09 11:21:29.596514	Activa
\.


--
-- TOC entry 5236 (class 0 OID 16644)
-- Dependencies: 244
-- Data for Name: participacion_evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participacion_evento (id_participacion, id_usuario, id_evento, fecha_inscripcion, estado) FROM stdin;
\.


--
-- TOC entry 5232 (class 0 OID 16601)
-- Dependencies: 240
-- Data for Name: participacion_tour; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participacion_tour (id_participacion, id_usuario, id_tour, fecha_registro, estado) FROM stdin;
1	10	1	2026-06-08 21:43:18.354235	Activa
\.


--
-- TOC entry 5224 (class 0 OID 16496)
-- Dependencies: 232
-- Data for Name: reservacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reservacion (id_reservacion, id_usuario, id_habitacion, id_temporada, fecha_check_in, fecha_check_out, estado, total, fecha_creacion) FROM stdin;
2	6	139	4	2026-09-28	2026-09-29	Pendiente	3200.00	2026-06-08 03:05:11.92786
3	6	4	2	2026-06-23	2026-06-30	Pendiente	14490.00	2026-06-08 03:08:35.198356
4	6	175	2	2026-06-08	2026-06-09	Confirmada	10350.00	2026-06-08 15:37:04.624493
5	6	5	2	2026-06-09	2026-06-10	Pendiente	2070.00	2026-06-09 11:07:24.365855
7	11	179	2	2026-06-09	2026-06-10	Cancelada	10350.00	2026-06-09 11:18:57.223543
8	10	138	2	2026-06-29	2026-07-30	Pendiente	114080.00	2026-06-09 13:31:36.722394
\.


--
-- TOC entry 5212 (class 0 OID 16387)
-- Dependencies: 220
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rol (id_rol, nombre, descripcion) FROM stdin;
5	Turista	Cliente del complejo
7	Guia Turistico	Guia asignado a tours
8	Encargado Zona Turistica	Responsable de operacion turistica
9	Encargado Playa	Responsable de actividades acuaticas
\.


--
-- TOC entry 5218 (class 0 OID 16444)
-- Dependencies: 226
-- Data for Name: temporada; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.temporada (id_temporada, nombre, incremento_porcentaje, fecha_inicio, fecha_fin) FROM stdin;
1	Baja	0.00	2026-01-11	2026-03-31
2	Media	15.00	2026-04-01	2026-06-30
3	Alta	35.00	2026-07-01	2026-08-31
4	Baja	0.00	2026-09-01	2026-12-14
5	Especial	50.00	2026-12-15	2027-01-10
\.


--
-- TOC entry 5238 (class 0 OID 16668)
-- Dependencies: 246
-- Data for Name: ticket; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ticket (id_ticket, id_usuario, descripcion, area_afectada, estado, prioridad, fecha_creacion, fecha_cierre) FROM stdin;
1	8	fuga de gas	Habitación 101	Abierto	Alta	2026-06-09 13:26:17.744789	\N
\.


--
-- TOC entry 5228 (class 0 OID 16550)
-- Dependencies: 236
-- Data for Name: tour; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tour (id_tour, id_actividad, id_guia, nombre, fecha, hora_inicio, hora_fin, cupo_maximo, cupo_actual, estado) FROM stdin;
2	7	2	Descanso carlos	2026-06-18	13:42:00	14:42:00	1	0	Programado
1	8	3	Recorrido William	2026-06-10	22:41:00	23:59:00	1	1	Completado
3	5	2	WILLIAM PRUEBA DE REGISTRO	2026-06-10	01:24:00	05:28:00	1	0	Programado
\.


--
-- TOC entry 5214 (class 0 OID 16400)
-- Dependencies: 222
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (id_usuario, id_rol, nombre, apellido, email, password_hash, telefono, estado, fecha_registro) FROM stdin;
6	5	Juan	Perez	juan@turistico.com	123456	5551234567	Activo	2026-06-04 23:19:18.301933
7	7	Carlos	Lopez	guia@turistico.com	123456	5551111111	Activo	2026-06-04 23:27:52.607012
8	8	Roberto	Martinez	zona@turistico.com	123456	5558888888	Activo	2026-06-05 18:12:56.776795
9	9	Miguel	Ramirez	playa@turistico.com	123456	5557777777	Activo	2026-06-05 18:12:56.776795
10	5	Ana	Gonzalez	ana@turistico.com	123456	5551111111	Activo	2026-06-05 22:30:25.61626
11	5	Luis	Fernandez	luis@turistico.com	123456	5551111112	Activo	2026-06-05 22:30:25.61626
12	5	Maria	Lopez	maria@turistico.com	123456	5551111113	Activo	2026-06-05 22:30:25.61626
13	5	Pedro	Sanchez	pedro@turistico.com	123456	5551111114	Activo	2026-06-05 22:30:25.61626
14	7	Javier	Morales	javier.guia@turistico.com	123456	5552222221	Activo	2026-06-05 22:30:25.61626
15	7	Sofia	Castillo	sofia.guia@turistico.com	123456	5552222222	Activo	2026-06-05 22:30:25.61626
16	8	Ricardo	Herrera	ricardo.zona@turistico.com	123456	5553333331	Activo	2026-06-05 22:30:25.61626
17	8	Patricia	Navarro	patricia.zona@turistico.com	123456	5553333332	Activo	2026-06-05 22:30:25.61626
18	9	Fernando	Rojas	fernando.playa@turistico.com	123456	5554444441	Activo	2026-06-05 22:30:25.61626
19	9	Daniela	Cruz	daniela.playa@turistico.com	123456	5554444442	Activo	2026-06-05 22:30:25.61626
21	5	An	LLL	anaMMM@turistico.com	123456	5559076543	Activo	2026-06-07 20:50:34.630885
\.


--
-- TOC entry 5246 (class 0 OID 16750)
-- Dependencies: 254
-- Data for Name: zona_turistica; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.zona_turistica (id_zona, nombre, descripcion, estado) FROM stdin;
1	Playa Norte	Zona principal de playa	Activa
2	Playa Sur	Zona secundaria de playa	Activa
3	Hotel Principal	Área de hospedaje	Activa
4	Zona de Eventos	Área para eventos turísticos	Activa
\.


--
-- TOC entry 5273 (class 0 OID 0)
-- Dependencies: 255
-- Name: acceso_turistico_id_acceso_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.acceso_turistico_id_acceso_seq', 7, true);


--
-- TOC entry 5274 (class 0 OID 0)
-- Dependencies: 229
-- Name: actividad_id_actividad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.actividad_id_actividad_seq', 10, true);


--
-- TOC entry 5275 (class 0 OID 0)
-- Dependencies: 251
-- Name: alberca_id_alberca_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.alberca_id_alberca_seq', 1, false);


--
-- TOC entry 5276 (class 0 OID 0)
-- Dependencies: 249
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bitacora_id_bitacora_seq', 1, false);


--
-- TOC entry 5277 (class 0 OID 0)
-- Dependencies: 241
-- Name: evento_id_evento_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.evento_id_evento_seq', 12, true);


--
-- TOC entry 5278 (class 0 OID 0)
-- Dependencies: 223
-- Name: guia_turistico_id_guia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.guia_turistico_id_guia_seq', 3, true);


--
-- TOC entry 5279 (class 0 OID 0)
-- Dependencies: 227
-- Name: habitacion_id_habitacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.habitacion_id_habitacion_seq', 181, true);


--
-- TOC entry 5280 (class 0 OID 0)
-- Dependencies: 247
-- Name: notificacion_id_notificacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificacion_id_notificacion_seq', 43, true);


--
-- TOC entry 5281 (class 0 OID 0)
-- Dependencies: 233
-- Name: pago_id_pago_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pago_id_pago_seq', 5, true);


--
-- TOC entry 5282 (class 0 OID 0)
-- Dependencies: 237
-- Name: participacion_actividad_id_participacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.participacion_actividad_id_participacion_seq', 1, true);


--
-- TOC entry 5283 (class 0 OID 0)
-- Dependencies: 243
-- Name: participacion_evento_id_participacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.participacion_evento_id_participacion_seq', 8, true);


--
-- TOC entry 5284 (class 0 OID 0)
-- Dependencies: 239
-- Name: participacion_tour_id_participacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.participacion_tour_id_participacion_seq', 1, true);


--
-- TOC entry 5285 (class 0 OID 0)
-- Dependencies: 231
-- Name: reservacion_id_reservacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservacion_id_reservacion_seq', 8, true);


--
-- TOC entry 5286 (class 0 OID 0)
-- Dependencies: 219
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 9, true);


--
-- TOC entry 5287 (class 0 OID 0)
-- Dependencies: 225
-- Name: temporada_id_temporada_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.temporada_id_temporada_seq', 5, true);


--
-- TOC entry 5288 (class 0 OID 0)
-- Dependencies: 245
-- Name: ticket_id_ticket_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ticket_id_ticket_seq', 1, true);


--
-- TOC entry 5289 (class 0 OID 0)
-- Dependencies: 235
-- Name: tour_id_tour_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tour_id_tour_seq', 3, true);


--
-- TOC entry 5290 (class 0 OID 0)
-- Dependencies: 221
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 21, true);


--
-- TOC entry 5291 (class 0 OID 0)
-- Dependencies: 253
-- Name: zona_turistica_id_zona_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.zona_turistica_id_zona_seq', 4, true);


--
-- TOC entry 5044 (class 2606 OID 16774)
-- Name: acceso_turistico acceso_turistico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acceso_turistico
    ADD CONSTRAINT acceso_turistico_pkey PRIMARY KEY (id_acceso);


--
-- TOC entry 5018 (class 2606 OID 16494)
-- Name: actividad actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad
    ADD CONSTRAINT actividad_pkey PRIMARY KEY (id_actividad);


--
-- TOC entry 5040 (class 2606 OID 16747)
-- Name: alberca alberca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.alberca
    ADD CONSTRAINT alberca_pkey PRIMARY KEY (id_alberca);


--
-- TOC entry 5038 (class 2606 OID 16728)
-- Name: bitacora bitacora_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora
    ADD CONSTRAINT bitacora_pkey PRIMARY KEY (id_bitacora);


--
-- TOC entry 5030 (class 2606 OID 16642)
-- Name: evento evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.evento
    ADD CONSTRAINT evento_pkey PRIMARY KEY (id_evento);


--
-- TOC entry 5008 (class 2606 OID 16437)
-- Name: guia_turistico guia_turistico_id_usuario_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_turistico
    ADD CONSTRAINT guia_turistico_id_usuario_key UNIQUE (id_usuario);


--
-- TOC entry 5010 (class 2606 OID 16435)
-- Name: guia_turistico guia_turistico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_turistico
    ADD CONSTRAINT guia_turistico_pkey PRIMARY KEY (id_guia);


--
-- TOC entry 5014 (class 2606 OID 16474)
-- Name: habitacion habitacion_numero_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitacion
    ADD CONSTRAINT habitacion_numero_key UNIQUE (numero);


--
-- TOC entry 5016 (class 2606 OID 16472)
-- Name: habitacion habitacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitacion
    ADD CONSTRAINT habitacion_pkey PRIMARY KEY (id_habitacion);


--
-- TOC entry 5036 (class 2606 OID 16708)
-- Name: notificacion notificacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificacion
    ADD CONSTRAINT notificacion_pkey PRIMARY KEY (id_notificacion);


--
-- TOC entry 5022 (class 2606 OID 16543)
-- Name: pago pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT pago_pkey PRIMARY KEY (id_pago);


--
-- TOC entry 5026 (class 2606 OID 16589)
-- Name: participacion_actividad participacion_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_actividad
    ADD CONSTRAINT participacion_actividad_pkey PRIMARY KEY (id_participacion);


--
-- TOC entry 5032 (class 2606 OID 16656)
-- Name: participacion_evento participacion_evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT participacion_evento_pkey PRIMARY KEY (id_participacion);


--
-- TOC entry 5028 (class 2606 OID 16613)
-- Name: participacion_tour participacion_tour_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_tour
    ADD CONSTRAINT participacion_tour_pkey PRIMARY KEY (id_participacion);


--
-- TOC entry 5020 (class 2606 OID 16512)
-- Name: reservacion reservacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservacion
    ADD CONSTRAINT reservacion_pkey PRIMARY KEY (id_reservacion);


--
-- TOC entry 5000 (class 2606 OID 16398)
-- Name: rol rol_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_nombre_key UNIQUE (nombre);


--
-- TOC entry 5002 (class 2606 OID 16396)
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- TOC entry 5012 (class 2606 OID 16455)
-- Name: temporada temporada_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.temporada
    ADD CONSTRAINT temporada_pkey PRIMARY KEY (id_temporada);


--
-- TOC entry 5034 (class 2606 OID 16685)
-- Name: ticket ticket_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket
    ADD CONSTRAINT ticket_pkey PRIMARY KEY (id_ticket);


--
-- TOC entry 5024 (class 2606 OID 16565)
-- Name: tour tour_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tour
    ADD CONSTRAINT tour_pkey PRIMARY KEY (id_tour);


--
-- TOC entry 5004 (class 2606 OID 16419)
-- Name: usuario usuario_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_email_key UNIQUE (email);


--
-- TOC entry 5006 (class 2606 OID 16417)
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 5042 (class 2606 OID 16761)
-- Name: zona_turistica zona_turistica_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zona_turistica
    ADD CONSTRAINT zona_turistica_pkey PRIMARY KEY (id_zona);


--
-- TOC entry 5062 (class 2606 OID 16775)
-- Name: acceso_turistico fk_acceso_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acceso_turistico
    ADD CONSTRAINT fk_acceso_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5063 (class 2606 OID 16780)
-- Name: acceso_turistico fk_acceso_zona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.acceso_turistico
    ADD CONSTRAINT fk_acceso_zona FOREIGN KEY (id_zona) REFERENCES public.zona_turistica(id_zona);


--
-- TOC entry 5061 (class 2606 OID 16729)
-- Name: bitacora fk_bitacora_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora
    ADD CONSTRAINT fk_bitacora_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5046 (class 2606 OID 16438)
-- Name: guia_turistico fk_guia_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.guia_turistico
    ADD CONSTRAINT fk_guia_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5060 (class 2606 OID 16709)
-- Name: notificacion fk_notificacion_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificacion
    ADD CONSTRAINT fk_notificacion_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5053 (class 2606 OID 16595)
-- Name: participacion_actividad fk_pa_actividad; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_actividad
    ADD CONSTRAINT fk_pa_actividad FOREIGN KEY (id_actividad) REFERENCES public.actividad(id_actividad);


--
-- TOC entry 5054 (class 2606 OID 16590)
-- Name: participacion_actividad fk_pa_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_actividad
    ADD CONSTRAINT fk_pa_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5050 (class 2606 OID 16544)
-- Name: pago fk_pago_reservacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pago
    ADD CONSTRAINT fk_pago_reservacion FOREIGN KEY (id_reservacion) REFERENCES public.reservacion(id_reservacion);


--
-- TOC entry 5057 (class 2606 OID 16662)
-- Name: participacion_evento fk_pe_evento; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT fk_pe_evento FOREIGN KEY (id_evento) REFERENCES public.evento(id_evento);


--
-- TOC entry 5058 (class 2606 OID 16657)
-- Name: participacion_evento fk_pe_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_evento
    ADD CONSTRAINT fk_pe_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5055 (class 2606 OID 16619)
-- Name: participacion_tour fk_pt_tour; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_tour
    ADD CONSTRAINT fk_pt_tour FOREIGN KEY (id_tour) REFERENCES public.tour(id_tour);


--
-- TOC entry 5056 (class 2606 OID 16614)
-- Name: participacion_tour fk_pt_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participacion_tour
    ADD CONSTRAINT fk_pt_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5047 (class 2606 OID 16518)
-- Name: reservacion fk_reservacion_habitacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservacion
    ADD CONSTRAINT fk_reservacion_habitacion FOREIGN KEY (id_habitacion) REFERENCES public.habitacion(id_habitacion);


--
-- TOC entry 5048 (class 2606 OID 16523)
-- Name: reservacion fk_reservacion_temporada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservacion
    ADD CONSTRAINT fk_reservacion_temporada FOREIGN KEY (id_temporada) REFERENCES public.temporada(id_temporada);


--
-- TOC entry 5049 (class 2606 OID 16513)
-- Name: reservacion fk_reservacion_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservacion
    ADD CONSTRAINT fk_reservacion_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5059 (class 2606 OID 16686)
-- Name: ticket fk_ticket_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket
    ADD CONSTRAINT fk_ticket_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 5051 (class 2606 OID 16566)
-- Name: tour fk_tour_actividad; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tour
    ADD CONSTRAINT fk_tour_actividad FOREIGN KEY (id_actividad) REFERENCES public.actividad(id_actividad);


--
-- TOC entry 5052 (class 2606 OID 16571)
-- Name: tour fk_tour_guia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tour
    ADD CONSTRAINT fk_tour_guia FOREIGN KEY (id_guia) REFERENCES public.guia_turistico(id_guia);


--
-- TOC entry 5045 (class 2606 OID 16420)
-- Name: usuario fk_usuario_rol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol);


-- Completed on 2026-06-09 13:59:17

--
-- PostgreSQL database dump complete
--

\unrestrict naljKWW5CRR5DPL5ZIsaz585kwnO4PctOGX0m3g93H5Ra0hgUklvwx9h7dYKux9

