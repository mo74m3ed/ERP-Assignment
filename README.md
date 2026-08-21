## How to run this app?
 1. if you have mac: 

 
    install laravel herd https://herd.laravel.com/docs
    
    cd ERP-Assignment
    
    copy .env.example to .env 

    configure DB_DATABASE in .env with the full bath to the database 
    
    ex: /Users/macbookpro/Documents/Repos/ERP-Assignment/database/database.sqlite


    composer install

    php artisan key:generate

    php artisan migrate --seed 

    php artisan test 

2. if you have another operating system, you need to
    

    install composer
    
    install apache or nginx 

    install php 8.2

then

    cd ERP-Assignment

    copy .env.example to .env 

    configure DB_DATABASE in .env with the full bath to the database 
    
    ex: /Users/macbookpro/Documents/Repos/ERP-Assignment/database/database.sqlite

    composer install

    php artisan key:generate

    php artisan migrate --seed 

    php artisan test 


    
    
