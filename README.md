# deaventura


### archivos de configuracion

- aplication/inc.config.php
- .htaccess                         # configuración de url friendly
- aplication/utilities/Libs.php     # file configuration UTF-8
- validateUser.php                  # config app facebook (id & password) | return_url
- aplication/webroot/js/js.js       # config app facebook (id)
- index.php                         # config app facebook (id)



## Configuración en local host de preferencia (MAC  o LINUXS)

## Configuracion Técnica

### Paso 1:
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

### Paso 2:
configurar /etc/hosts
    sudo vim /etc/hosts

    127.0.0.1       deaventura.local


## Configuración de API (Facebook)

API FACEBOOK : https://developers.facebook.com
Generar una cuenta (apps facebook) para obtener:
AppId : 111111111111111
AppSecret : !##############!

Luego de esto ir Getting Started o a : Dashboard >> Settings >> Website y ingrear tu URL
O dominio (funciona tambien para localhost)
en este caso yo en Site URL escribir mi dominio local:
    
    https://deaventura.local/





    http://deaventura.local/cuenta.php?cuenta=misdatos2#