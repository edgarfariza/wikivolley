# 🏐 WIKIVOLLEY

Portal web dinámico orientado al entorno del vóley playa profesional, desarrollado como proyecto intermodular de 1º de DAM en la Universidad Alfonso X el Sabio (UAX).

## Tecnologías utilizadas

- **PHP** — backend, sesiones, control de acceso por roles
- **MySQL** — base de datos relacional (XAMPP en local, AwardSpace en producción)
- **Python** — automatización de datos (noticias, empleo, cursos)
- **HTML/CSS** — diseño responsivo en modo oscuro
- **JavaScript** — confirmaciones y validaciones en frontend

## Funcionalidades principales

- Noticias cargadas dinámicamente desde un archivo JSON
- Bolsa de empleo y catálogo de cursos desde base de datos
- Registro e inicio de sesión con contraseñas cifradas (bcrypt)
- Control de acceso: los botones de "Aplicar" están bloqueados para usuarios anónimos
- Panel de administración exclusivo para el rol Admin:
  - Automatización híbrida PHP → Python que actualiza noticias y tablas en MySQL
  - Auditoría de postulaciones en tiempo real con INNER JOIN triple
  - Gestión de usuarios: ascender, degradar o eliminar cuentas

## Estructura del proyecto
wikivolley/
├── index.php
├── login.php
├── registro.php
├── logout.php
├── conexion.php
├── ejecutar_script.php
├── modificar_usuario.php
├── postular.php
├── import json.py
├── noticias.json
├── styles.css
└── img/
