# cronpdf

This is a boilerplate for building projects using [dompdf](https://github.com/dompdf/dompdf) to generate a pdf of sitemaps with plotted points. Built using the standard setup for data from our [feather](https://github.com/GunnJerkens/feather) CouchDB instance.

## usage & config

```
composer install
```

Setup your config utilizing these options and then edit buildBody() if the scope of the points or structure is different than supplied.

| Options       | Type        | Details                                         |
| ------        | -----       | -----                                           |
| remote_assets | bool        | allows assets from remote domains (img/css/etc) |
| filename      | string      | set the filename of the final pdf               |
| orientation   | string      | landscape or portrait                           |
| endpoint      | string      | the couchdb json endpoint                       |
| scale_x       | int (float) | percent to scale the points on the x axis       |
| scale_y       | int (float) | percent to scale the points on the y axis       |
| shift_x       | int         | pixels to move the points on the x axis         |
| shift_y       | int         | pixels to move the points on the y axis         |
| z_index       | int         | z-index to set the points                       |

To setup a cronjob use:

```
crontab -e
00 00,12 * * * php /path/to/cronpdf/cronpdf.php > /dev/null 2>&1
```

This will run the cronjob every 12 hours at midnight & noon. Any errors/output encountered are piped into the black hole of doom.

## caveats

This is meant to be a one time install, not a submodule or an ever updating app. As the buildBody() function in the app is meant to be edited. Possibly in the future it could be built differently to accept more versatile inputs.

## issues

[open issues](https://github.com/GunnJerkens/cronpdf/issues?q=is%3Aissue+is%3Aopen+)

## license

MIT