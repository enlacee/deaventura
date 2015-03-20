# deaventura


## Archivos importantes de configuración

- aplication/inc.config.php
- .htaccess                         # configuración de url friendly
- aplication/utilities/Libs.php     # file configuration UTF-8
- validateUser.php                  # config app facebook (id & password) | return_url
- aplication/webroot/js/js.js       # config app facebook (id)
- index.php                         # config app facebook (id)



## Configuración en local host de preferencia (MAC  o LINUXS)

### Configuracion Técnica

### Paso 01:
configurar el virtual host en local,

    ### config deaventura.local
    <VirtualHost *:80>
      ServerName deaventura.local
      DocumentRoot "/Users/Bitalik/Documents/acopitan/develoweb/deaventura/src"
      <Directory /Users/Bitalik/Documents/acopitan/develoweb/deaventura/src>
            Options +Indexes
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
       </Directory>
       SetEnv APPLICATION_ENV "development"
    </VirtualHost>

### Paso 02:
configurar /etc/hosts
    sudo vim /etc/hosts

    127.0.0.1       deaventura.local


### Paso 03:
configurar servidor permisos: aplication/utilities/timthumb.php

    sudo chmod 777 src/aplication/utilities

configurar permisos para cache:

    sudo chmod 777 src/aplication/utilities/cache

configurar permisos para imagenes:

    sudo chmod 777 src/aplication/webroot/imgs


## Configuración de Servicios

### API (Facebook) 'configuracion del servicio'
API FACEBOOK : https://developers.facebook.com
Generar una cuenta (apps facebook) para obtener:

    AppId : 111111111111111
    AppSecret : !##############!

Luego de esto ir Getting Started o a : Dashboard >> Settings >> Website y ingrear tu URL
O dominio (funciona tambien para localhost)
en este caso yo en Site URL escribir mi dominio local:
    
    https://deaventura.local/

### comnfiguracion en el proyecto

Archivo de configuración app.js : (aqui configurar el ID de facebook)
esta configuración aun se esta integrando actualmente en :

- cuenta.php
- aventurero.php

    src/aplication/webroot/js/app.js

Nota: la cuenta facebook actualmente se configuro para este virtual host https://deaventura.local/
es importante configurar tu virtual host con este dominio local.


## API (GoogleMap)
sin documentación




