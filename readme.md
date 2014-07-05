#Subcomic


Subcomic is a intelligent comic storage and web comic viewer.


## Setup

```sh
$ touch app/config/subcomic.php
$ vi app/config/subcomic.php
```

```
<?php

return [
    'data_dir' => '/you/dir/to/put/comics',
];
```

```sh
$ composer install
$ php artisan migrate
$ php artisan command:user_add your-username your-password
```

Then open Subcomic with web and push "Sync" button.