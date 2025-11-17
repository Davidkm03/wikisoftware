# Configuración de Notificaciones por Email

El sistema Wiki incluye notificaciones automáticas por email cuando se publican nuevos documentos.

## Características

- **Notificaciones automáticas**: Se envían emails a administradores y editores cuando se publica un documento nuevo
- **Emails en cola**: Las notificaciones se procesan en segundo plano para no afectar el rendimiento
- **Plantilla profesional**: Emails con formato HTML responsive

## Configuración

### 1. Configurar el Driver de Email

Edita tu archivo `.env` con la configuración de tu servidor de email:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="wiki@example.com"
MAIL_FROM_NAME="Software Wiki"
```

### 2. Opciones de Servicio de Email

#### Opción 1: Gmail (Para desarrollo)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
```

**Nota**: Necesitas crear una "Contraseña de aplicación" en tu cuenta de Google.

#### Opción 2: Mailtrap (Para pruebas)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
```

Registrate gratis en [Mailtrap.io](https://mailtrap.io) para obtener las credenciales.

#### Opción 3: SendGrid (Para producción)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-de-sendgrid
MAIL_ENCRYPTION=tls
```

#### Opción 4: Servidor SMTP de cPanel
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.tudominio.com
MAIL_PORT=587
MAIL_USERNAME=wiki@tudominio.com
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
```

### 3. Configurar Cola de Trabajos (Queue)

Las notificaciones se envían usando colas para mejorar el rendimiento.

#### Para desarrollo (base de datos):
```env
QUEUE_CONNECTION=database
```

Luego ejecuta:
```bash
php artisan queue:table
php artisan migrate
```

Para procesar la cola:
```bash
php artisan queue:work
```

#### Para producción (Redis recomendado):
```env
QUEUE_CONNECTION=redis
```

### 4. Probar las Notificaciones

1. Inicia sesión como admin o editor
2. Crea un nuevo documento
3. Marca el estado como "Published"
4. Los demás admins y editores recibirán un email

### 5. Desactivar Notificaciones (Opcional)

Si no quieres usar notificaciones por email, puedes comentar el listener en el método `handle` de `SendDocumentPublishedNotification.php`:

```php
public function handle(DocumentPublished $event): void
{
    // Comentar para desactivar notificaciones
    // return;

    // ... resto del código
}
```

O cambiar el driver a `log` para solo registrar los emails:
```env
MAIL_MAILER=log
```

## Personalización

### Cambiar el template del email

El template se encuentra en:
```
resources/views/emails/document-published.blade.php
```

### Cambiar quién recibe notificaciones

Edita el archivo:
```
app/Listeners/SendDocumentPublishedNotification.php
```

Por defecto notifica a:
- Administradores
- Editores
- Excluyendo al autor del documento

## Troubleshooting

### Los emails no se envían

1. Verifica que la configuración SMTP sea correcta
2. Revisa los logs: `storage/logs/laravel.log`
3. Asegúrate de que la cola esté corriendo: `php artisan queue:work`
4. Prueba con `php artisan tinker`:
   ```php
   Mail::raw('Test', function($msg) {
       $msg->to('test@example.com')->subject('Test');
   });
   ```

### Error de autenticación

- Verifica usuario y contraseña
- Para Gmail, usa una "Contraseña de aplicación"
- Verifica que el puerto y encryption sean correctos

### Los emails van a spam

- Configura SPF y DKIM en tu dominio
- Usa un servicio de email reputado (SendGrid, Mailgun)
- Incluye un enlace de "unsubscribe"

## Producción en Shared Hosting

Para cPanel:

1. Crea una cuenta de email en cPanel (ej: wiki@tudominio.com)
2. Usa las credenciales SMTP del servidor
3. Configura un cronjob para procesar la cola:

```cron
* * * * * cd /home/usuario/public_html && php artisan schedule:run >> /dev/null 2>&1
```

4. Agrega en `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('queue:work --stop-when-empty')->everyMinute();
}
```

## Recursos Adicionales

- [Documentación de Laravel Mail](https://laravel.com/docs/11.x/mail)
- [Documentación de Laravel Queues](https://laravel.com/docs/11.x/queues)
- [Mailtrap](https://mailtrap.io) - Testing
- [SendGrid](https://sendgrid.com) - Producción
