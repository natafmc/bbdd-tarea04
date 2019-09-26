KUDOS - NODEJS - MONGODB
=========================
** BASE URL: http://localhost:7001
* LISTAR KUDOS
GET
/list

* CREAR KUDOS 
POST
/kudos
{
	"de":"1",
	"nombreDe":"Romina",
	"para":"2",
	"nombrePara":"Viviana",
	"tema":"GREAT JOB",
	"lugar":"Sta. Cruz",
	"texto": "Kudos Prueba"
}

* ELIMINAR KUDOS
DELETE
/kudos/:_idKudos

* DETALLE DE UN KUDOS
/kudos/?id=5d82c7e2ede5866eb132fa99


USER - PHP - MySQL
==================
** BASE URL : http://localhost/Kudos/PHPrest/
* CREAR USUARIO
POST
/add
id:
nombre:Romina
apellido:Medrano Cambara
email:romi@gmail.com
nickname:romi
password:kitty
telefono:00000



* ELIMINAR USUARIO
GET
/delete/$idUsuario

* LISTADO DE USUARIOS
GET
/all

* BUSQUEDA DE USUARIOS
POST
/search
nombre: Nata


* DETALLE DE USUARIO
GET
/perfil/$idUsuario



