# Algeria's City Sniffer
On many projects, when we need the full official list of all wilayas (regions), dairas (departments) and communes (cities) of Algeria, we struggle finding such a basic information.

This simple project, made by php (Laravel framework), extract, import and convert the data from the [official source](http://www.interieur.gov.dz/index.php/fr/component/annuaire/?view=wilayas) to different file formats (csv, xml, excel, etc.)

## Installation
Clone the project on your local machine. And install the components with composer.
```
php composer.phar install
```

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Setup
First, make sure to create an empty database, and set the parameters in the `.env` file.

Then, execute the following command to run the migrations:
```
php artisan migrate --seed
```
This will create the necessary tables, with all wilayas preloaded.

## Import the data

1. run the server `php artisan serve`
2. browse to `http://localhost:8000`
3. click on `Import to your local machine`. This will create a Job, 
4. to execute the job: `php artisan queue:work`

## Export

I will update the export feature later.
