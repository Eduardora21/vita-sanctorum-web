# vita-sanctorum-web
# ✝ Vita Sanctorum

> Plataforma hagiográfica interactiva dedicada a la vida, virtudes y devoción de los santos cristianos, enriquecida con reflexiones espirituales asistidas por Inteligencia Artificial.

---

## 🌟 Características Principales

- **Catálogo Hagiográfico:** Biografías detalladas, oraciones tradicionales y patronazgos de santos a lo largo de la historia.
- **Buscador Dinámico:** Búsqueda en tiempo real por nombre, título o atributos sin recargar la página.
- **Calendario Litúrgico:** Organización mensual de festividades y leccionario litúrgico diario.
- **Reflexión Espiritual con IA (Google Gemini):** Módulo flotante no invasivo que genera meditaciones y aplicaciones morales contemporáneas basadas en la vida de cada santo.
- **Panel Administrativo:** Módulo de gestión para administrar y actualizar el santoral.
- **Diseño Códice / Medieval Moderno:** Interfaz sobria, elegante y totalmente adaptada a dispositivos móviles.

---

## 🛠 Tecnologías Utilizadas

- **Backend:** PHP 8+ (Programación Orientada a Objetos, PDO, cURL nativo).
- **Base de Datos:** MySQL / MariaDB (UTF-8 mb4).
- **Frontend:** HTML5 semántico, CSS3 moderno (Variables CSS, Flexbox, Grid) y JavaScript Vanilla (Asíncrono con Fetch API).
- **Inteligencia Artificial:** Google Gemini API (`gemini-3.5-flash-lite` y `gemini-3.8-flash` con sistema de respaldo automático).
- **Contenerización:** Docker & Docker Compose (para entorno de desarrollo local).

---

## 📁 Estructura del Proyecto

```text
vita-sanctorum/
│
├── admin/               # Panel administrativo y autenticación
├── api/                 # Endpoints REST (Reflexiones IA y leccionario)
├── config/              # Conexión a BD y configuración de IA
├── controllers/         # Controladores de lógica de negocio
├── data/                # Archivos de datos auxiliares (JSON litúrgico)
├── database/            # Scripts SQL (Esquema y datos iniciales)
├── models/              # Modelos de datos (PDO)
├── public/              # Archivos públicos estáticos
│   ├── css/             # Estilos de la aplicación
│   ├── img/             # Retratos y recursos visuales
│   └── js/              # Scripts interactivos del frontend
├── views/               # Vistas y plantillas de la aplicación
├── index.php            # Portada principal
└── README.md            # Documentación del proyecto

🛡 Seguridad
Las credenciales privadas (config/ai_config.php) y variables de entorno sensibles están excluidas del control de versiones mediante .gitignore.

Consultas parametrizadas mediante sentencias preparadas de PDO para prevenir inyecciones SQL.

Escape de salidas HTML (htmlspecialchars) para mitigar vulnerabilidades XSS.

📜 Licencia
Distribuido bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.