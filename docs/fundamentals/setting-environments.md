# Setting environments

| Key                                                                                          | Value                                                                                                                                                                            | Default         |
| -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------- |
| `APP_PORT`                                                                                   | The port on which the application will be                                                                                                                                        | 80              |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`           | These keys are used to configure the database connection. They specify the database driver, host, port, name, username, and password, respectively.                              | sqlite database |
| `CACHE_DRIVER`, `SESSION_DRIVER`, `QUEUE_DRIVER`                                             | These keys define the drivers to be used for caching, session management, and queuing. Common options for these keys include `file`, `database`, `redis`, `memcached`, and more. |                 |
| `MAIL_DRIVER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION` | These keys are used to configure the mail service. They specify the mail driver, host, port, username, password, and encryption method, respectively.                            |                 |
| `LOG_CHANNEL`                                                                                | This key determines the logging channel to be used. Laravel provides several options, including `stack`, `single`, `daily`, `slack`, `syslog`, and more.                         |                 |
| `APP_DEBUG`                                                                                  |                                                                                                                                                                                  | false           |

## Permission levels

There are 4 types of permission levels in the product.

| Role          | Capabilities                            |
| ------------- | --------------------------------------- |
| Administrator | Has all admin privileges                |
| Editor        | Can edit posts                          |
| Viewer        | Can only view posts                     |
| Guest         | Can only view posts they are inivted to |
