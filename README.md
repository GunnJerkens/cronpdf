# cronpdf

This is a boilerplate for building projects using [dompdf](https://github.com/dompdf/dompdf) to generate a pdf of sitemaps with plotted points. Built using the standard setup for data from our [feather](https://github.com/GunnJerkens/feather) CouchDB instance.

## usage & config

```
composer install
```



| Options      | Type        | Details                                       |
| ------       | -----       | -----                                         |
| filename     | string      | set the filename of the final pdf             |
| orientation  | string      | landscape or portrait                         |
| endpoint     | string      | the couchdb json endpoint                     |
| scale_x      | int (float) | percent to scale the points on the x axis     |
| scale_y      | int (float) | percent to scale the points on the y axis     |
| shift_x      | int         | pixels to move the points on the x axis       |
| shift_y      | int         | pixels to move the points on the y axis       |
| z_index      | int         | z-index to set the points                     |

## caveats

This is meant to be a one time install, not a submodule or an ever updating app. As the buildBody() function in the app is meant to be edited. Possibly in the future it could be built differently to accept more versatile inputs.

## issues

[open issues](https://github.com/GunnJerkens/cronpdf/issues?q=is%3Aissue+is%3Aopen+)

## license

MIT