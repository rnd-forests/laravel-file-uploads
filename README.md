# Laravel File Uploads

## Installation

We use Docker and Docker Compose for constructing the development environment of the application. Therefore, we first need to install these two softwares:

- Install Docker: https://docs.docker.com/install
- Install Docker Compose: https://docs.docker.com/compose/install

Make sure `docker` and `docker-compose` commands are available in current shell.

In order to run `docker` command without **sudo** privilege:
- Create **docker** group if it hasn't existed yet: `sudo groupadd docker`
- Add current user to **docker** group: `sudo gpasswd -a ${whoami} docker`
- You may need to logout in order to these changes to take effect.

Change current directory to application code folder and run the following commands:
- Copy file `.env.example` to `.env`, `docker-compose.yml.example` to `docker-compose.yml`
- Start up docker containers: `docker-compose up -d`. To stop docker containers, using `docker-compose stop` command
- Change to workspace environment: `docker exec -it chatty_workspace bash`

Inside workspace container, run the following commands:
- Install composer packages: `composer install --no-suggest`
- Install the application: `php artisan app:install`
- Change permission for some directories: `chmod -R 777 storage/ bootstrap/`
- Seed the database: `php artisan db:seed`

The default database credentials for different environments (database, username, password):
- Local environment: **homestead**, **homestead**, **secret**

By default, port 80 of NGINX container is mapped to port 8000 of the host machine. If this port is currently used by another application, you can change that port by editing `docker-compose.yml`.

We can access the application at address `0.0.0.0:8000`

### File Uploads
We use different storage disks for storing different types of data such as **avatars**, **audio** and **graphs**. Currently,
all of these disks extend the `local` disk provided default by Laravel.

To change the configuration of these disks, edit `config/filesystems.php` file.

In order for files in these disks be publicly accessible, run the following command:
```
php artisan uploader:create-links
```
During uploading action, two events will be fired:
- `App\Components\Upload\UploadProcessing`: this event is fired **before** the uploading process. It receives the currently authenticated user instance, the `Illuminate\Http\UploadedFile` instance, and any additional data.
- `App\Components\Upload\UploadProcessed`: this event is fired **after** the uploading process is completed. It receives the currently authenticated user instance, the `App\Models\Upload` instance, and any additional data.

`App\Components\Upload\UploadManager` class will be used to hook into these above events, specifically:
- `before` method defines a listener for `App\Components\Upload\UploadProcessing` event.
- `after` method defines a listener for `App\Components\Upload\UploadProcessed` event.
- `cycle` method defines a listener for both of these events.

You can define custom upload handlers in `app/Components/Upload/Handlers` folder. Each handler must extend the base handler `App\Components\Upload\Uploader` and should implement the following methods:
- `getCustomDiskName`: return the disk name that uploaded files will be stored. You can refer to `config/filesystems.php` for more information. If there is no custom disk provided, we'll use the default disk.
- `getFilePath`: return the path of the sub-directory (not including the file name) relative to the root directory defined in the configuration of the disk.
- `extraEventContext`: return an associative array of additional data for the uploading events.

Each handler may have one or more associated listeners defined in `App\Providers\UploadServiceProvider`. Each listener can implement the following methods:
- `preprocess`: define actions taken before uploading process
- `postprocess`: define actions taken after uploading process

You can also refer to a upload handler as a string by adding custom driver to the upload manager class. For more information you can refer to `App\Providers\UploadServiceProvider` class.

Steps:
- Add new disk in `config/filesystems.php` (if necessary).
- Create new handlers and their associated listeners.
- Register handlers and listeners in `App\Providers\UploadServiceProvider`.
- Create shortcuts to access handlers via upload manager.

To get an instance of a upload handler with shortcut name *avatar*

```
resolve('upload')->handler('avatar');
```

