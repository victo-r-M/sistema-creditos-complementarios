-- ============================================================
-- Sistema de Créditos Complementarios — TecNM
-- ============================================================

IF DB_ID('sistema_creditos_complementarios') IS NULL
BEGIN
    CREATE DATABASE sistema_creditos_complementarios;
END
GO


USE sistema_creditos_complementarios;
GO

-- ============================================================
-- 1. AUTENTICACIÓN
-- ============================================================

CREATE TABLE usuarios (
    idUser INT IDENTITY(1,1) NOT NULL,

    correoInst VARCHAR(120) NOT NULL,
    password VARCHAR(255) NOT NULL,

    rol VARCHAR(25) NOT NULL CHECK (
        rol IN (
            'departamento',
            'comite',
            'director',
            'division_ep',
            'responsable',
            'servicios_escolares',
            'estudiante'
        )
    ),

    fechaCreacion DATE NOT NULL,

    activo CHAR(1) NOT NULL
        CONSTRAINT DF_usuarios_activo DEFAULT 'S'
        CHECK (activo IN ('S','N')),

    CONSTRAINT PK_usuarios PRIMARY KEY (idUser),

    CONSTRAINT UK_usuarios_correo UNIQUE (correoInst)
);
GO

-- ============================================================
-- 2. ACTORES INSTITUCIONALES
-- ============================================================

CREATE TABLE departamentos (
    idDepartamento INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_departamentos PRIMARY KEY (idDepartamento),

    CONSTRAINT FK_departamentos_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE comiteAcademico (
    idComiteAcademico INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_comite PRIMARY KEY (idComiteAcademico),

    CONSTRAINT FK_comite_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE director (
    idDirector INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_director PRIMARY KEY (idDirector),

    CONSTRAINT FK_director_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE divisionEstudiosProfesionales (
    idDivisionEstudiosProfesionales INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_division PRIMARY KEY (idDivisionEstudiosProfesionales),

    CONSTRAINT FK_division_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE responsableActividadComplementaria (
    idResponsableActividadComplementaria INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombreResponsable VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_responsable PRIMARY KEY (idResponsableActividadComplementaria),

    CONSTRAINT FK_responsable_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE serviciosEscolares (
    idServiciosEscolares INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    CONSTRAINT PK_servicios PRIMARY KEY (idServiciosEscolares),

    CONSTRAINT FK_servicios_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

-- ============================================================
-- 3. ESTUDIANTE
-- ============================================================

CREATE TABLE carrera (
    idCarrera INT IDENTITY(1,1) NOT NULL,

    nombreCarrera VARCHAR(100) NOT NULL,

    CONSTRAINT PK_carrera PRIMARY KEY (idCarrera)
);
GO

CREATE TABLE estudiante (
    idEstudiante INT IDENTITY(1,1) NOT NULL,

    idCarrera INT NOT NULL,
    idUser INT NOT NULL,

    nombre VARCHAR(60) NOT NULL,
    primerApellido VARCHAR(40) NOT NULL,
    segundoApellido VARCHAR(40) NULL,

    noControl CHAR(8) NOT NULL,

    cargaAcademicaCreditos TINYINT NOT NULL
        CHECK (cargaAcademicaCreditos >=20),

    CONSTRAINT PK_estudiante PRIMARY KEY (idEstudiante),

    CONSTRAINT UK_estudiante_noControl UNIQUE (noControl),

    CONSTRAINT FK_estudiante_carrera
        FOREIGN KEY (idCarrera)
        REFERENCES carrera(idCarrera),

    CONSTRAINT FK_estudiante_user
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE expedienteEstudiante (
    idExpedienteEstudiante INT IDENTITY(1,1) NOT NULL,

    idEstudiante INT NOT NULL,

    fechaCreacionExpediente DATE NOT NULL,

    estadoExpediente CHAR(1) NOT NULL
        CONSTRAINT DF_expediente_estado DEFAULT 'A'
        CHECK (estadoExpediente IN ('A','I')),

    creditosAcumulados DECIMAL(2,1) NOT NULL
        CONSTRAINT DF_expediente_creditos DEFAULT 0.0,

    semestreActual TINYINT NOT NULL
        CHECK (semestreActual BETWEEN 1 AND 12),

    CONSTRAINT PK_expediente PRIMARY KEY (idExpedienteEstudiante),

    CONSTRAINT FK_expediente_estudiante
        FOREIGN KEY (idEstudiante)
        REFERENCES estudiante(idEstudiante)
);
GO

-- ============================================================
-- 4. CATÁLOGO DE ACTIVIDADES
-- ============================================================

CREATE TABLE tipoActividad (
    idTipoActividadComplementario INT IDENTITY(1,1) NOT NULL,

    nombreTipoActComplementaria VARCHAR(100) NOT NULL,

    CONSTRAINT PK_tipoActividad PRIMARY KEY (idTipoActividadComplementario),

    CONSTRAINT UK_tipoActividad_nombre
        UNIQUE (nombreTipoActComplementaria)
);
GO

CREATE TABLE actividadComplementaria (
    idActividadComplementarias INT IDENTITY(1,1) NOT NULL,

    idDepartamento INT NOT NULL,
    idTipoActividadComplementaria INT NOT NULL,

    nombreActividadComplementaria VARCHAR(120) NOT NULL,

    descripcionActividadComplementaria VARCHAR(300) NOT NULL,

    creditosAsignados DECIMAL(2,1) NOT NULL
        CONSTRAINT DF_actividad_creditos DEFAULT 0.0,


    estadoActividad CHAR(1) NOT NULL
        CONSTRAINT DF_actividad_estado DEFAULT 'P'
        CHECK (estadoActividad IN ('D','R','A','X')),

		
    cupoMax SMALLINT NOT NULL
        CONSTRAINT DF_catalogo_cupo CHECK (cupoMax BETWEEN 1 AND 500),

    CONSTRAINT PK_actividad PRIMARY KEY (idActividadComplementarias),

    CONSTRAINT FK_actividad_departamento
        FOREIGN KEY (idDepartamento)
        REFERENCES departamentos(idDepartamento),

    CONSTRAINT FK_actividad_tipo
        FOREIGN KEY (idTipoActividadComplementaria)
        REFERENCES tipoActividad(idTipoActividadComplementario),


    CONSTRAINT CK_creditos_actividad
        CHECK (creditosAsignados BETWEEN 0 AND 2)
);
GO
/*
-- 1. Primero eliminamos las restricciones anteriores para que no choquen
-- (Si te da error porque no encuentra alguna, puedes saltar al paso 2)
ALTER TABLE actividadComplementaria DROP CONSTRAINT IF EXISTS CK__actividad__estad__619B8048;
GO

-- 2. Aplicamos el valor por defecto 'P'
ALTER TABLE actividadComplementaria 
ADD CONSTRAINT DF_actividad_estado DEFAULT 'P' FOR estadoActividad;
GO

-- 3. Aplicamos la regla de validación para las letras ('D','R','A','X')
ALTER TABLE actividadComplementaria 
ADD CONSTRAINT CK_actividad_estado CHECK  (estadoActividad IN ('D','R','A','X'));
GO*/

CREATE TABLE ActividadComplementariaRevisada (
    idRevisionActividadComplementaria INT IDENTITY(1,1) NOT NULL,

    --idDirector INT NOT NULL,
    idActividadComplementarias INT NOT NULL,
	idComiteAcademico INT NOT NULL,


    fechaRevision DATE NOT NULL,

    observacion VARCHAR(300) NULL,

    estadoRevision CHAR(1) NOT NULL
        CHECK (estadoRevision IN ('D','A','X')),

    CONSTRAINT PK_revision PRIMARY KEY (idRevisionActividadComplementaria),

 /*   CONSTRAINT FK_revision_director
        FOREIGN KEY (idDirector)
        REFERENCES director(idDirector),*/

    CONSTRAINT FK_revision_actividad
        FOREIGN KEY (idActividadComplementarias)
        REFERENCES actividadComplementaria(idActividadComplementarias),

	CONSTRAINT FK_actividad_comite
        FOREIGN KEY (idComiteAcademico)
        REFERENCES comiteAcademico(idComiteAcademico)
);
GO
/*-- 1. Modificamos la columna por si necesitas asegurar el tipo de dato
ALTER TABLE ActividadComplementariaRevisada
ALTER COLUMN estadoRevision CHAR(1) NOT NULL;
GO

-- 2. Agregamos el nuevo CHECK con un nombre propio para que no se duplique
-- (Cambia las letras 'D', 'A', 'X' por las nuevas que necesites si es el caso)
ALTER TABLE ActividadComplementariaRevisada
ADD CONSTRAINT CK_actividad_revision 
CHECK (estadoRevision IN ('D', 'A', 'X'));
GO*/

CREATE TABLE dictamenActividad (
    idDictamenActividadComplementaria INT IDENTITY(1,1) NOT NULL,

    idDirector INT NOT NULL,
    idRevisionActividadComplementaria INT NOT NULL,


    fechaDictamen DATE NOT NULL,

    observacion VARCHAR(300) NULL,

    estadoDictamen CHAR(1) NOT NULL
        CHECK (estadoDictamen IN ('D','A','X')),

    CONSTRAINT PK_dictamen PRIMARY KEY (idDictamenActividadComplementaria),

    CONSTRAINT FK_dictamen_director
        FOREIGN KEY (idDirector)
        REFERENCES director(idDirector),

    CONSTRAINT FK_dictamen_actividad
        FOREIGN KEY (idRevisionActividadComplementaria)
        REFERENCES ActividadComplementariaRevisada(idRevisionActividadComplementaria)

);
GO

CREATE TABLE catalogoActividadComplementaria (
    idCatalogoActividadComplementaria INT IDENTITY(1,1) NOT NULL,

    idActividadComplementarias INT NOT NULL,

    fechaPublicacion DATE NOT NULL,

    activo CHAR(1) NOT NULL
        CONSTRAINT DF_catalogo_activo DEFAULT 'S'
        CHECK (activo IN ('S','N')),


    CONSTRAINT PK_catalogo PRIMARY KEY (idCatalogoActividadComplementaria),

    CONSTRAINT FK_catalogo_actividad
        FOREIGN KEY (idActividadComplementarias)
        REFERENCES actividadComplementaria(idActividadComplementarias)
);
GO

CREATE TABLE asignaResponsableActividadComplementaria (
    idCatalogoActividadComplementaria INT NOT NULL,

    idResponsableActividadComplementaria INT NOT NULL,

    fechaAsignacion DATE NOT NULL,

    CONSTRAINT PK_asignacion PRIMARY KEY (
        idCatalogoActividadComplementaria,
        idResponsableActividadComplementaria
    ),

    CONSTRAINT FK_asignacion_catalogo
        FOREIGN KEY (idCatalogoActividadComplementaria)
        REFERENCES catalogoActividadComplementaria(idCatalogoActividadComplementaria),

    CONSTRAINT FK_asignacion_responsable
        FOREIGN KEY (idResponsableActividadComplementaria)
        REFERENCES responsableActividadComplementaria(idResponsableActividadComplementaria)
);
GO

-- ============================================================
-- 5. INSCRIPCIÓN
-- ============================================================

CREATE TABLE inscripcionActividadComplementaria (
    idInscripcionActividadComplementaria INT IDENTITY(1,1) NOT NULL,

    idEstudiante INT NOT NULL,
    idDivisionEstudiosProfesionales INT NULL,
    idCatalogoActividadComplementaria INT NOT NULL,

    fechaInscripcion DATE NOT NULL,

    estadoAutorizacion CHAR(1) NOT NULL
        CONSTRAINT DF_inscripcion_estado DEFAULT 'P'
        CHECK (estadoAutorizacion IN ('A','R','P')),

    semestreInscripcion TINYINT NOT NULL
        CHECK (semestreInscripcion BETWEEN 1 AND 6),

    numeroIntento TINYINT NOT NULL
        CONSTRAINT DF_inscripcion_intento DEFAULT 1
        CHECK (numeroIntento BETWEEN 1 AND 3),

    CONSTRAINT PK_inscripcion PRIMARY KEY (idInscripcionActividadComplementaria),

    CONSTRAINT FK_inscripcion_estudiante
        FOREIGN KEY (idEstudiante)
        REFERENCES estudiante(idEstudiante),

    CONSTRAINT FK_inscripcion_division
        FOREIGN KEY (idDivisionEstudiosProfesionales)
        REFERENCES divisionEstudiosProfesionales(idDivisionEstudiosProfesionales),

    CONSTRAINT FK_inscripcion_catalogo
        FOREIGN KEY (idCatalogoActividadComplementaria)
        REFERENCES catalogoActividadComplementaria(idCatalogoActividadComplementaria)
);
GO

-- ============================================================
-- 6. EVIDENCIAS Y EVALUACIÓN
-- ============================================================

CREATE TABLE evidenciaEstudiante (
    idEvidencia INT IDENTITY(1,1) NOT NULL,

    idInscripcionActividadComplementaria INT NOT NULL,

    nombreEvidencia VARCHAR(120) NOT NULL,

    evidenciaAdjunta VARCHAR(255) NOT NULL,

    fechaEvidencia DATE NOT NULL,

    descripcionEvidencia VARCHAR(300) NULL,

    CONSTRAINT PK_evidencia PRIMARY KEY (idEvidencia),

    CONSTRAINT FK_evidencia_inscripcion
        FOREIGN KEY (idInscripcionActividadComplementaria)
        REFERENCES inscripcionActividadComplementaria(idInscripcionActividadComplementaria)
);
GO

CREATE TABLE criterioRubrica (
    idNumCriterio TINYINT IDENTITY(1,1) NOT NULL,

    descripcionCriterio VARCHAR(300) NOT NULL,

    CONSTRAINT PK_criterio PRIMARY KEY (idNumCriterio)
);
GO

CREATE TABLE evaluacionEstudiante (
    idEvaluacion INT IDENTITY(1,1) NOT NULL,

    idResponsableActividadComplementaria INT NOT NULL,
    idInscripcionActividadComplementaria INT NOT NULL,
    idEvidencia INT NOT NULL,

    observaciones VARCHAR(300) NULL,

    nivelDesempeñoAlcanzado VARCHAR(20) NOT NULL
        CHECK (
            nivelDesempeñoAlcanzado IN (
                'Excelente',
                'Notable',
                'Bueno',
                'Suficiente',
                'Insuficiente'
            )
        ),

    fechaEvaluacion DATE NOT NULL,

    valorNumPromedio DECIMAL(4,2) NOT NULL
        CHECK (valorNumPromedio BETWEEN 0 AND 4),

    CONSTRAINT PK_evaluacion PRIMARY KEY (idEvaluacion),

    CONSTRAINT FK_evaluacion_responsable
        FOREIGN KEY (idResponsableActividadComplementaria)
        REFERENCES responsableActividadComplementaria(idResponsableActividadComplementaria),

    CONSTRAINT FK_evaluacion_inscripcion
        FOREIGN KEY (idInscripcionActividadComplementaria)
        REFERENCES inscripcionActividadComplementaria(idInscripcionActividadComplementaria),

    CONSTRAINT FK_evaluacion_evidencia
        FOREIGN KEY (idEvidencia)
        REFERENCES evidenciaEstudiante(idEvidencia)
);
GO

CREATE TABLE evaluacionCriterio (
    idEvaluacionCriterio INT IDENTITY(1,1) NOT NULL,

    idNumCriterio TINYINT NOT NULL,
    idEvaluacion INT NOT NULL,

    nivelDesempeño VARCHAR(15) NOT NULL
        CHECK (
            nivelDesempeño IN (
                'Excelente',
                'Notable',
                'Bueno',
                'Suficiente',
                'Insuficiente'
            )
        ),

    CONSTRAINT PK_evaluacionCriterio PRIMARY KEY (idEvaluacionCriterio),

    CONSTRAINT FK_evalCriterio_criterio
        FOREIGN KEY (idNumCriterio)
        REFERENCES criterioRubrica(idNumCriterio),

    CONSTRAINT FK_evalCriterio_eval
        FOREIGN KEY (idEvaluacion)
        REFERENCES evaluacionEstudiante(idEvaluacion)
);
GO

-- ============================================================
-- 7. CONSTANCIAS
-- ============================================================

CREATE TABLE constanciaCumplimiento (
    idConstanciaCumplimiento INT IDENTITY(1,1) NOT NULL,

    idEvaluacion INT NOT NULL,

    fechaEmision DATE NOT NULL,

    selloConstancia VARCHAR(255) NULL,

    firmaResponsable VARCHAR(255) NULL,

    firmaDepartamento VARCHAR(255) NULL,

    CONSTRAINT PK_constanciaCumplimiento
        PRIMARY KEY (idConstanciaCumplimiento),

    CONSTRAINT FK_constancia_evaluacion
        FOREIGN KEY (idEvaluacion)
        REFERENCES evaluacionEstudiante(idEvaluacion)
);
GO

CREATE TABLE constanciaLiberacionCreditos (
    idConstanciaLiberacionCreditos INT IDENTITY(1,1) NOT NULL,

    idServiciosEscolares INT NOT NULL,

    idExpedienteEstudiante INT NOT NULL,

    promedioFinal DECIMAL(2,1) NOT NULL
        CHECK (promedioFinal BETWEEN 0 AND 4),

    nivelDesempenoFinal VARCHAR(15) NOT NULL
        CHECK (
            nivelDesempenoFinal IN (
                'Excelente',
                'Notable',
                'Bueno',
                'Suficiente',
                'Insuficiente'
            )
        ),

    CONSTRAINT PK_constanciaLiberacion
        PRIMARY KEY (idConstanciaLiberacionCreditos),

    CONSTRAINT FK_liberacion_servicios
        FOREIGN KEY (idServiciosEscolares)
        REFERENCES serviciosEscolares(idServiciosEscolares),

    CONSTRAINT FK_liberacion_expediente
        FOREIGN KEY (idExpedienteEstudiante)
        REFERENCES expedienteEstudiante(idExpedienteEstudiante)
);
GO

CREATE TABLE constanciaLiberacionDetalle (
    idConstanciaLiberacionDetalle INT IDENTITY(1,1) NOT NULL,

    idConstanciaLiberacionCreditos INT NOT NULL,

    idConstanciaCumplimiento INT NOT NULL,

    CONSTRAINT PK_constanciaLiberacionDetalle
        PRIMARY KEY (idConstanciaLiberacionDetalle),

    CONSTRAINT FK_detalle_liberacion
        FOREIGN KEY (idConstanciaLiberacionCreditos)
        REFERENCES constanciaLiberacionCreditos(idConstanciaLiberacionCreditos),

    CONSTRAINT FK_detalle_cumplimiento
        FOREIGN KEY (idConstanciaCumplimiento)
        REFERENCES constanciaCumplimiento(idConstanciaCumplimiento)
);
GO

-- ============================================================
-- 8. NOTIFICACIONES Y SOLICITUDES
-- ============================================================

CREATE TABLE notificacion (
    idNotificacion INT IDENTITY(1,1) NOT NULL,

    idUser INT NOT NULL,

    mensaje VARCHAR(500) NOT NULL,



    leida CHAR(1) NOT NULL
        CONSTRAINT DF_notificacion_leida DEFAULT 'N'
        CHECK (leida IN ('S','N')),

    fechaCreacion DATE NOT NULL,

    CONSTRAINT PK_notificacion
        PRIMARY KEY (idNotificacion),

    CONSTRAINT FK_notificacion_usuario
        FOREIGN KEY (idUser)
        REFERENCES usuarios(idUser)
);
GO

CREATE TABLE solicitudLiberacion (
    idSolicitudLiberacion INT IDENTITY(1,1) NOT NULL,

    idEstudiante INT NOT NULL,

    fechaSolicitud DATE NOT NULL,

    estadoSolicitud VARCHAR(15) NOT NULL
        CHECK (
            estadoSolicitud IN (
                'Pendiente',
                'Aprobada',
                'Rechazada'
            )
        ),

    observaciones VARCHAR(300) NULL,

    CONSTRAINT PK_solicitudLiberacion
        PRIMARY KEY (idSolicitudLiberacion),

    CONSTRAINT FK_solicitud_estudiante
        FOREIGN KEY (idEstudiante)
        REFERENCES estudiante(idEstudiante)
);
GO

-- ============================================================
-- REGISTROS INICIALES
-- ============================================================
-- ============================================================
-- ============================================================
--CARRERAS
INSERT INTO carrera (nombreCarrera)
VALUES
('Ingeniería en Administración'),

('Licenciatura en Administración'),

('Arquitectura'),

('Licenciatura en Biología'),

('Licenciatura en Turismo'),

('Ingeniería Civil'),

('Contador Público'),

('Ingeniería Eléctrica'),

('Ingeniería Electromecánica'),

('Ingeniería en Gestión Empresarial'),

('Ingeniería en Desarrollo de Aplicaciones'),

('Ingeniería en Sistemas Computacionales'),

('Ingeniería en Tecnologías de la Información y Comunicaciones');

--TIPOS DE ACTIVIDADES
INSERT INTO tipoActividad (nombreTipoActComplementaria)
VALUES
('Monitor Estudiante'),

('Asesorías Académicas en función de un programa implementado por asignatura'),

('Cursos de Superación Académica');

USE sistema_creditos_complementarios;
GO

/* 
   USUARIOS ADMINISTRATIVOS
 */

INSERT INTO usuarios
(
    correoInst,
    password,
    rol,
    fechaCreacion,
    activo
)
VALUES
(
    'departamento@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'departamento',
    GETDATE(),
    'S'
),

(
    'comite@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'comite',
    GETDATE(),
    'S'
),

(
    'director@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'director',
    GETDATE(),
    'S'
),

(
    'division@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'division_ep',
    GETDATE(),
    'S'
),

(
    'responsable@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'responsable',
    GETDATE(),
    'S'
),

(
    'servicios@chetumal.tecnm.mx',
    '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru',
    'servicios_escolares',
    GETDATE(),
    'S'
);
GO
/* =========================================================
   DEPARTAMENTOS
========================================================= */

INSERT INTO departamentos
(
    idUser,
    nombre,
    primerApellido,
    segundoApellido
)
VALUES
(
    1, -- Se cambió de 7 a 1 para corresponder al ID autogenerado real de usuarios
    'Carlos',
    'Ramirez',
    'Lopez'
);

/* =========================================================
   COMITÉ ACADÉMICO
========================================================= */

INSERT INTO comiteAcademico
(
    idUser,
    nombre,
    primerApellido,
    segundoApellido
)
VALUES
(
    2, -- Se cambió de 8 a 2 para corresponder al ID autogenerado real de usuarios
    'María',
    'Gonzalez',
    'Hernandez'
);

/* =========================================================
   DIRECTOR
========================================================= */

INSERT INTO director
(
    idUser,
    nombre,
    primerApellido,
    segundoApellido
)
VALUES
(
    3, -- Se cambió de 9 a 3 para corresponder al ID autogenerado real de usuarios
    'José',
    'Martinez',
    'Castillo'
);

/* =========================================================
   DIVISIÓN DE ESTUDIOS PROFESIONALES
========================================================= */

INSERT INTO divisionEstudiosProfesionales
(
    idUser,
    nombre,
    primerApellido,
    segundoApellido
)
VALUES
(
    4, -- Se cambió de 10 a 4 para corresponder al ID autogenerado real de usuarios
    'Ana',
    'Pérez',
    'Vargas'
);

/* =========================================================
   RESPONSABLE ACTIVIDAD COMPLEMENTARIA
========================================================= */

INSERT INTO responsableActividadComplementaria
(
    idUser,
    nombreResponsable,
    primerApellido,
    segundoApellido
)
VALUES
(
    5, -- Se cambió de 11 a 5 para corresponder al ID autogenerado real de usuarios
    'Luis',
    'Torres',
    'Mendoza'
);

/* =========================================================
   SERVICIOS ESCOLARES
========================================================= */

INSERT INTO serviciosEscolares
(
    idUser,
    nombre,
    primerApellido,
    segundoApellido
)
VALUES
(
    6, -- Se cambió de 12 a 6 para corresponder al ID autogenerado real de usuarios
    'Fernanda',
    'Ruiz',
    'Morales'
);
UPDATE usuarios
SET password = '$2y$10$FiAIS6kwuPJO3OFdqnS7FeKqe6wuS/WkQwVl0afYxErC.XxwuuRru'
WHERE idUser IN (1,2,3,4,5,6);*/