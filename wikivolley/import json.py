import json
import os
import mysql.connector

def actualizar_noticias_json():
    #lista de noticias que se van a mostrar
    noticias_frescas = [
        {
            "titulo": "Copa del Rey y de la Reina de Vóley Playa",
            "contenido": "Las mejores parejas del circuito nacional se citan en la arena para disputar el título más prestigioso de la temporada en un torneo lleno de intensidad.",
            "imagen": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ1tSfXvFrzfxF1FYooHBroatauw_AcJgZMw&s",
            "enlace": "https://www.rfevb.com"
        },
        {
            "titulo": "VNL 2026: Arranca la Volleyball Nations League",
            "contenido": "Las grandes potencias del voleibol mundial miden sus fuerzas en la edición 2026 de la VNL, buscando el pase a la gran fase final.",
            "imagen": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTaQaNG9i9ocJ7PscBf3HQ5zHggHNERXCswyA&s",
            "enlace": "https://en.volleyballworld.com"
        },
        {
            "titulo": "Wilfredo León lidera el mercado de fichajes",
            "contenido": "El receptor cubano-polaco, considerado uno de los jugadores más determinantes del planeta, vuelve a acaparar todas las miradas con sus últimas actuaciones estelares.",
            "imagen": "https://encrypted-tbn3.gstatic.com/licensed-image?q=tbn:ANd9GcTTKp-0fmFuk3yetMnUsog85DyyBbvd0zYM94111a4ZMkBSYBWRzYebjOLucW-8J01Rpoq52TiCo-DRNLM",
            "enlace": "https://en.volleyballworld.com"
        },
        {
            "titulo": "España se prepara para el Mundial de Vóley Playa",
            "contenido": "La selección española de vóley playa intensifica su preparación de cara al próximo Mundial, con la mirada puesta en superar su mejor resultado histórico.",
            "imagen": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQA8uxGlAO1nWplF7C-aLyAycc3xpzLX5gNxg&s",
            "enlace": "https://www.marca.com/voleibol/2024/09/15/5f8b9c1e268e3e7a2b8b456.html"      
        },
        {
            "titulo": "La Liga Iberdrola de Vóley Femenino arranca con emoción",
            "contenido": "El campeonato nacional femenino da el pistoletazo de salida con un cartel de lujo, prometiendo una temporada llena de rivalidades y talento emergente.",
            "imagen": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSMhs2r8FYIJ4uIfSI4DYaXzyy6hxR09lko1A&sg",
            "enlace": "https://www.esvoley.es"
        }
        
    ]
    
    directorio_actual = os.path.dirname(os.path.abspath(__file__))
    ruta_json = os.path.join(directorio_actual, 'noticias.json')

    try:
        with open(ruta_json, 'w', encoding='utf-8') as archivo:
            json.dump(noticias_frescas, archivo, ensure_ascii=False, indent=4)
        print(f"Script de Python dice: ¡Las {len(noticias_frescas)} noticias se han guardado con éxito en: {ruta_json}!")
        
    except Exception as e:
        print(f"Script de Python dice: Error crítico al escribir el archivo JSON: {e}")


def actualizar_tablas_mysql():
    conexion = None
    try:
        conexion = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="4736484_volley"  
        )
        cursor = conexion.cursor()

        # 1. actualizar lista de empleo
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0")  # desactivamos temporalmente las restricciones de clave foránea

        cursor.execute("TRUNCATE TABLE bolsa_empleo")  # vaciamos la tabla de empleo
        sql_empleo = "INSERT INTO bolsa_empleo (PUESTO, SALARIO, DESCRIPCION, CONTACTO) VALUES (%s, %s, %s, %s)"
        datos_empleo = [
            ("Entrenador Principal Vóley Playa", 1800, "Club de Málaga busca entrenador para categorías inferiores.", "contacto@clubmalaga.com"),
            ("Estadístico / Scoutman VNL", 2200, "Selección nacional busca analista de datos para el torneo.", "vnl@volleyballworld.com"),
            ("Preparador Físico Vóley Playa", 1600, "Centro de alto rendimiento busca preparador físico especializado.", "preparador@centroaltorendimiento.com"),
            ("Coordinador de Eventos", 1400, "Federación busca coordinador para eventos nacionales e internacionales.", "coordinador@federacionvoley.com"),
            ("Técnico de Desarrollo Juvenil", 1300, "Club de Valencia busca técnico para desarrollo de jóvenes talentos.", "tecnico@clubvalencia.com"),
            ("Operador de datavolley", 1200, "Empresa de análisis de voleibol busca operador para eventos en vivo.", "operador@datavolley.com"),
            ("Fisioterapeuta Deportivo", 1700, "Equipo profesional busca fisioterapeuta con experiencia en voleibol.", "fisioterapeuta@equipo.com")
        ]
        cursor.executemany(sql_empleo, datos_empleo)

        # 2. actualizar lista de cursos
        cursor.execute("TRUNCATE TABLE cursos")
        cursor.execute("TRUNCATE TABLE cursos")

       #   volvemos a activar las claves foraneas
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1")
        sql_cursos = "INSERT INTO cursos (NOMBRE, DESCRIPCION, PRECIO, DURACION, IMAGEN, ENLACE) VALUES (%s, %s, %s, %s, %s, %s)"
        datos_cursos = [
            ("Curso de Colocación Avanzada", "Especialización para colocadores de alto rendimiento.", 150, "20 horas", "LOGOCEV.jpg", "inscripciones@voley.com"),
            ("Curso de Arbitros Nacional", "Formación para árbitros que quieran ascender a nivel nacional.", 100, "15 horas", "logoesvoley.jpg", "inscripciones@voley.com"),
            ("Curso de Preparación Física Específica", "Entrenamiento físico adaptado al voleibol.", 120, "25 horas", "LOGOFIVB.jpg", "inscripciones@voley.com"),
            ("Curso de Análisis de Video para Entrenadores", "Aprende a usar herramientas de análisis de video para mejorar el rendimiento del equipo.", 130, "18 horas", "LOGOFIVB.jpg", "inscripciones@voley.com"),
            ("Curso de VAR para Árbitros", "Formación en el uso del sistema de videoarbitraje en voleibol.", 110, "12 horas", "LOGOFIVB.jpg", "inscripciones@voley.com"),
            ("Curso de Psicología Deportiva para Voleibolistas", "Mejora el rendimiento mental de los jugadores con técnicas de psicología deportiva.", 140, "22 horas", "logoesvoley.jpg", "inscripciones@voley.com")

 
        ]
        cursor.executemany(sql_cursos, datos_cursos)

        conexion.commit()
        print("Script de Python dice: ¡Tablas de Empleo y Cursos actualizadas con éxito en MySQL!")

    except mysql.connector.Error as error:
        print(f"\n--- ERROR DE CONEXIÓN A MYSQL ---")
        print(f"Detalle del fallo: {error}")
        print(f"---------------------------------\n")
    finally:
        if conexion is not None and conexion.is_connected():
            cursor.close()
            conexion.close()


# arranque general del script
if __name__ == "__main__":
    actualizar_noticias_json()
    actualizar_tablas_mysql()