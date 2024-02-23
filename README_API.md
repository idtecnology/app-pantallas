# API ADSUPP

## API Reference
### Collection: Auth 
#### End-point: Registro de cliente
```http
POST localhost:8000/api/v2/register
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `name` | `string` | Nombre de usuario |
| `last_name` | `string` | Apellido del usuario |
| `email` | `string` | Correo del usuario |
| `password` | `string` | Contreseña del usuario |

**Response: 200**
```json
{
    "data": {
        "name": "Test",
        "email": "email@example.com",
        "last_name": "User",
        "isUser": 0,
        "updated_at": "2024-02-23T19:55:14.000000Z",
        "created_at": "2024-02-23T19:55:14.000000Z",
        "id": 15
    },
    "access_token": "5|y5bvJNzLJ7B28gHVojOuF5RPfrLZQOjAH2eemhMqa9211b4b",
    "token_type": "Bearer"
}
```

#### End-point: login
```http
POST localhost:8000/api/v2/login
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `email` | `string` | Correo del usuario |
| `password` | `string` | Contreseña del usuario |

**Response: 200**
```json
{
    "user": {
        "id": 15,
        "name": "Test",
        "last_name": "User",
        "email": "email@example.com",
        "email_verified_at": null,
        "birth": null,
        "discounts": null,
        "phone": null,
        "isActive": 1,
        "isUser": 0,
        "verify_hash": null,
        "created_at": "2024-02-23T19:55:14.000000Z",
        "updated_at": "2024-02-23T19:55:14.000000Z"
    },
    "access_token": "6|lu9j6eS8wbGiCIiy8PYLQ4uJJWcnNNO79EFpOE5Df2b918bb",
    "token_type": "Bearer"
}
```


#### End-point: Cerrar sesion
```http
GET localhost:8000/api/v2/logout
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "message": "Successfully logged out"
}
```
---

### Collection: Tramos 


#### End-point: Fechas habiles
```http
 POST localhost:8000/api/v2/tramos/availability-dates
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `screen_id` | `integer` | Identficador de la pantalla |
| `duration` | `integer` | Tiempo seleccionado |
| `Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "data": [
        {
            "fecha": "2024-02-23"
        },
        {
            "fecha": "2024-02-24"
        },
        {
            "fecha": "2024-02-25"
        },
        {
            "fecha": "2024-02-26"
        },
        {
            "fecha": "2024-02-27"
        }
    ]
}
```


#### End-point: listar tramos
Obtener la lista de los tramos
```http
 POST localhost:8000/api/v2/tramos
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `fecha` | `string` | Fecha a consultar|
| `screen_id` | `integer` | Identficador de la pantalla |
| `duration` | `integer` | Tiempo seleccionado |
| `Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "data": [
        {
            "_id": 29803,
            "tramo_id": "202402240000",
            "screen_id": 2,
            "fecha": "2024-02-24",
            "duracion": 600,
            "tramos": "00:00:00",
            "isActive": 1,
            "created_at": null,
            "updated_at": null
        },
        {
            "_id": 29804,
            "tramo_id": "202402240010",
            "screen_id": 2,
            "fecha": "2024-02-24",
            "duracion": 600,
            "tramos": "00:10:00",
            "isActive": 1,
            "created_at": null,
            "updated_at": null
        },
        {
            "_id": 29805,
            "tramo_id": "202402240020",
            "screen_id": 2,
            "fecha": "2024-02-24",
            "duracion": 600,
            "tramos": "00:20:00",
            "isActive": 1,
            "created_at": null,
            "updated_at": null
        }
    ]
}
```
---
### Collection: Media 


#### End-point: listar media

Obtener toda la multimedia registrada.

```http
 GET localhost:8000/api/v2/media
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "data": {
        "current_page": 1,
        "data": [],
        "first_page_url": "http://localhost:8000/api/v2/media?page=1",
        "from": null,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/v2/media?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v2/media?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost:8000/api/v2/media",
        "per_page": 20,
        "prev_page_url": null,
        "to": null,
        "total": 0
    }
}
```


#### End-point: Aprobar multimedia

```http
 GET localhost:8000/api/v2/media/approved/10940
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `_id` | `integer` | Identificador de la multimedia a aprobar|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "message": "Aprobado con exito"
}
```

**Response: 400**
```json
{
    "message": "Media ya aporbada, no puede aprobar una media mas de una vez"
}
```


#### End-point: Desaprobar multimedia
```http
 GET localhost:8000/api/v2/media/not-approved/10941
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `_id` | `integer` | Identificador de la multimedia a aprobar|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "message": "Media desaprobada con exito, se envio un email, notificando al cliente"
}
```

**Response: 400**
```json
{
    "message": "Media desaporbada, no puede desaprobar una media mas de una vez"
}
```


#### End-point: Deshabilitar multimedia
```http
 GET localhost:8000/api/v2/media/disabled/10933
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `_id` | `integer` | Identificador de la multimedia a deshabilitar|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "message": "Desactivado con exito"
}
```

**Response: 400**
```json
{
    "message": "Media desactivada, no puede inactivar una media mas de una vez"
}
```


#### End-point: Marcar como reproducido
```http
 GET localhost:8000/api/v2/media/reproducido/10892
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `_id` | `integer` | Identificador de la multimedia a marcar como reproducido|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 400**
```json
{
    "message": "La media ya se encuentra actualizada"
}
```

**Response: 200**
```json
{
    "message": "Actualizacion exitosa"
}
```


#### End-point: Mostrar la multimedia
```http
 GET localhost:8000/api/v2/media/show/50
```
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `_id` | `integer` | Identificador de la multimedia|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "data": {
        "_id": 50,
        "campania_id": 1,
        "client_id": 1,
        "tramo_id": "202401120810",
        "screen_id": 1,
        "pago_id": null,
        "preference_id": null,
        "time": "08:10:00",
        "date": "2024-01-12",
        "duration": 15,
        "files_name": [
            {
                "file_name": "https://reproductor-pantallas-test.s3.amazonaws.com/1/20240112/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120821Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=66129d7c67158ddf66c6659bc1afcac475df969d592c83b8c9f0b36dbf5bfcc9",
                "duration": "15"
            }
        ],
        "approved": 1,
        "isPaid": 1,
        "reproducido": 0,
        "isActive": 0,
        "created_at": null,
        "updated_at": "2024-02-20T20:59:18.000000Z"
    },
    "extension": [
        "jpg"
    ]
}
```


#### End-point: Mostrar la programacion
Debe seleccionar la pantalla y el dia a consultar 
```http
 POST localhost:8000/api/v2/media/search-programation
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
| `fecha` | `string` | Fecha a consultar|
| `screen_id` | `integer` | Identificador de la pantalla|
| `Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "data": {
        "current_page": 1,
        "data": [
            {
                "media_id": 2,
                "media_client_id": 1,
                "media_reproducido": 0,
                "media_time": "08:10:00",
                "media_date": "2024-01-03",
                "media_duration": 15,
                "media_files_name": "[{\"file_name\":\"https:\\/\\/reproductor-pantallas-test.s3.amazonaws.com\\/1\\/20240103\\/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120815Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=7e3f76d188f55dc43dedaf9f14b0b382d9b2193d57b31667731adce85f10b3dc\",\"duration\":\"15\"}]",
                "media_isActive": 1,
                "email": "admin@1.com",
                "campania_name": "prueba",
                "screen_name": "Bunge Centro"
            },
            {
                "media_id": 3,
                "media_client_id": 1,
                "media_reproducido": 0,
                "media_time": "08:10:00",
                "media_date": "2024-01-03",
                "media_duration": 15,
                "media_files_name": "[{\"file_name\":\"https:\\/\\/reproductor-pantallas-test.s3.amazonaws.com\\/1\\/20240103\\/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120815Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=7e3f76d188f55dc43dedaf9f14b0b382d9b2193d57b31667731adce85f10b3dc\",\"duration\":\"15\"}]",
                "media_isActive": 1,
                "email": "admin@1.com",
                "campania_name": "prueba",
                "screen_name": "Bunge Centro"
            }
        ],
        "first_page_url": "http://localhost:8000/api/v2/media/search-programation?page=1",
        "from": 1,
        "last_page": 24,
        "last_page_url": "http://localhost:8000/api/v2/media/search-programation?page=24",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v2/media/search-programation?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://localhost:8000/api/v2/media/search-programation?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v2/media/search-programation?page=3",
                "label": "3",
                "active": false
            }
        ],
        "next_page_url": "http://localhost:8000/api/v2/media/search-programation?page=2",
        "path": "http://localhost:8000/api/v2/media/search-programation",
        "per_page": 15,
        "prev_page_url": null,
        "to": 15,
        "total": 360
    },
    "tramo": [
        "08:10:00",
        "08:20:00",
        "08:30:00"
    ],
    "data_tramo": {
        "08:10:00": [
            {
                "media_id": 2,
                "media_client_id": 1,
                "media_reproducido": 0,
                "media_time": "08:10:00",
                "media_date": "2024-01-03",
                "media_duration": 15,
                "media_files_name": "[{\"file_name\":\"https:\\/\\/reproductor-pantallas-test.s3.amazonaws.com\\/1\\/20240103\\/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120815Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=7e3f76d188f55dc43dedaf9f14b0b382d9b2193d57b31667731adce85f10b3dc\",\"duration\":\"15\"}]",
                "media_isActive": 1,
                "email": "admin@1.com",
                "campania_name": "prueba",
                "screen_name": "Bunge Centro"
            }
        ],
        "08:20:00": [
            {
                "media_id": 147,
                "media_client_id": 1,
                "media_reproducido": 0,
                "media_time": "08:20:00",
                "media_date": "2024-01-03",
                "media_duration": 15,
                "media_files_name": "[{\"file_name\":\"https:\\/\\/reproductor-pantallas-test.s3.amazonaws.com\\/1\\/20240103\\/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120815Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=7e3f76d188f55dc43dedaf9f14b0b382d9b2193d57b31667731adce85f10b3dc\",\"duration\":\"15\"}]",
                "media_isActive": 1,
                "email": "admin@1.com",
                "campania_name": "prueba",
                "screen_name": "Bunge Centro"
            }
        ],
        "08:30:00": [
            {
                "media_id": 292,
                "media_client_id": 1,
                "media_reproducido": 0,
                "media_time": "08:30:00",
                "media_date": "2024-01-03",
                "media_duration": 15,
                "media_files_name": "[{\"file_name\":\"https:\\/\\/reproductor-pantallas-test.s3.amazonaws.com\\/1\\/20240103\\/65954e1605b6f.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6BPHNGUBKQ7DEERO%2F20240103%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240103T120815Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Signature=7e3f76d188f55dc43dedaf9f14b0b382d9b2193d57b31667731adce85f10b3dc\",\"duration\":\"15\"}]",
                "media_isActive": 1,
                "email": "admin@1.com",
                "campania_name": "prueba",
                "screen_name": "Bunge Centro"
            }
        ]
    }
}
```


#### End-point: Guardar Campaña
```http
 POST localhost:8000/api/v2/media/store-camping
```

**Body**
            
| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`cant`|`integer`|Cantidad por hora|
|`name`|`string`|Nombre de la campaña|
|`screen_id`|`integer`|Identificador de la pantalla|
|`fecha_inicio`|`string`|Fecha de inicio de la campaña|
|`fecha_fin`|`string`|Fecha de fin de la campaña|
|`hora_inicio`|`string`|Hora de inicio de la campaña|
|`hora_fin`|`string`|Hora de fin de la campaña|
|`files[]`|`file`|Archivos multimedia|
| `Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "status": "success",
    "code": 200,
    "message": "Masividad cargada con exito"
}
```


#### End-point: Preguardado y validacion de formatos de la multimedia
El siguiente end-point cumple 2 funciones: 

-  Caso 1: Al momento de seleccionar la pantalla y la multimedia, con el fin de verificar y almacenar la multimedia en el servidor para su posterior proceso, la primera vez solo se envia, el tiempo, los archivos, el cliente y la pantalla
-  Caso2: Para editar la multimedia ya precargada en el servidor. En este caso se envian los mismos datos, pero añadiendo el media_id que ya te genero el primer caso y el momentun en true

##### Caso uno
```http
 POST localhost:8000/api/v2/media/presave-media
```

**Body**

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`tiempo`|`integer`|Cantidad por hora|
|`client_id`|`string`|Nombre de la campaña|
|`screen_id`|`integer`|Identificador de la pantalla|
|`archivos[]`|`file`|Archivos multimedia|
|`Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "mensaje": "Archivos guardados con éxito",
    "media_id": 11002,
    "preference_id": "92765599-99986911-a6aa-4cf4-b598-19d4893c7c51"
}
```

##### Caso 2: 

```http
 POST localhost:8000/api/v2/media/presave-media
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`tiempo`|`integer`|Cantidad por hora|
|`client_id`|`string`|Nombre de la campaña|
|`screen_id`|`integer`|Identificador de la pantalla|
|`archivos[]`|`file`|Archivos multimedia|
|`media_id`|`integer`|Identificador de la multimedia pregrabada|
|`momentun`|`boolean`|Es un indicativo de que solo es actualizacion|
|`Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "mensaje": "Archivos guardados con éxito",
    "media_id": 11002,
    "preference_id": "92765599-99986911-a6aa-4cf4-b598-19d4893c7c51"
}
```

#### End-point: Almacenar la multimedia y Pasar al proceso de compra ML
En este endpoint ya finalizamos el proceso y procedemos al pago, el valor que retorna es la preferencia que se le envia a mercado pago
```http
 POST localhost:8000/api/v2/media/store
```


| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`tramo_select`|`string`|Es el indicativo del tramo seleccionado en formato `HH:mm:ss`|
|`fecha`|`string`|Fecha Seleccionada|
|`screen_id`|`integer`|Identificador de la pantalla|
|`preference`|`string`|Identificador de la preferencia para enviar a ML|
|`media_id`|`integer`|Identificador de la multimedia pregrabada|
|`amount`|`boolean`|monto|
|`Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "message": "Media registrada correctamente",
    "preference_id": "92765599-99986911-a6aa-4cf4-b598-19d4893c7c51"
}
```

#### Collection: Screens 


#### End-point: Listar una pantalla
```http
 GET localhost:8000/api/v2/screen/1
 
```

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`_id`|`integer`|Identificador de la pantalla|
|`Authentication: bearer` | `string` | Token de autenticacion |

**Response: 200**
```json
{
    "data": {
        "_id": 1,
        "nombre": "Bunge Centro",
        "direccion": "Av. Arq Bunge 547, esq. Av. del Libertador",
        "imagen": "../images/screen/bungecentro.png",
        "hora_encendido": "06:00:00",
        "horario_apagado": "02:00:00",
        "url_google_maps": "todavia no disponible",
        "proximo_horario_disponible": "20 minutos",
        "ultimo_dia_compra": "30/03/2024 - 23:59hs GTM-3",
        "aspect_ratio": "4:3",
        "dimension_px": "{\"width\":\"1030px\",\"height\":\"800px\"}",
        "dimension_mts_marco": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
        "dimension_mts_pantalla": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
        "isActive": 1,
        "created_at": "2024-01-02T20:01:59.000000Z",
        "updated_at": "2024-01-02T20:01:59.000000Z"
    }
}
```


#### End-point: Listar Pantallas
```http
 GET localhost:8000/api/v2/screen
```
- Authentication: bearer

| Parametro | Tipo     | Descripción            |
| :-------- | :------- | :------------------------- |
|`Authentication: bearer` | `string` | Token de autenticacion |


**Response: 200**
```json
{
    "data": {
        "current_page": 1,
        "data": [
            {
                "_id": 1,
                "nombre": "Bunge Centro",
                "direccion": "Av. Arq Bunge 547, esq. Av. del Libertador",
                "imagen": "../images/screen/bungecentro.png",
                "hora_encendido": "06:00:00",
                "horario_apagado": "02:00:00",
                "url_google_maps": "todavia no disponible",
                "proximo_horario_disponible": "20 minutos",
                "ultimo_dia_compra": "30/03/2024 - 23:59hs GTM-3",
                "aspect_ratio": "4:3",
                "dimension_px": "{\"width\":\"1030px\",\"height\":\"800px\"}",
                "dimension_mts_marco": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
                "dimension_mts_pantalla": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
                "isActive": 1,
                "created_at": "2024-01-02T20:01:59.000000Z",
                "updated_at": "2024-01-02T20:01:59.000000Z"
            },
            {
                "_id": 2,
                "nombre": "Bunge al Mar",
                "direccion": "Av. Arq Bunge 488, esq. Av. del Libertador",
                "imagen": "../images/screen/bungealmar.png",
                "hora_encendido": "06:00:00",
                "horario_apagado": "02:00:00",
                "url_google_maps": "todavia no disponible",
                "proximo_horario_disponible": "20 minutos",
                "ultimo_dia_compra": "30/03/2024 - 23:59hs GTM-3",
                "aspect_ratio": "4:3",
                "dimension_px": "{\"width\":\"1030px\",\"height\":\"800px\"}",
                "dimension_mts_marco": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
                "dimension_mts_pantalla": "{ \"ancho\": \"4 MTS\", \"alto\": \"3 MTS\" }",
                "isActive": 1,
                "created_at": "2024-01-02T20:01:59.000000Z",
                "updated_at": "2024-01-02T20:01:59.000000Z"
            }
        ],
        "first_page_url": "http://localhost:8000/api/v2/screen?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/v2/screen?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v2/screen?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost:8000/api/v2/screen",
        "per_page": 20,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}
```