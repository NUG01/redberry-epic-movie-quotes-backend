# Movie Quotes

"Movie Quotes" helps people to share interesting movie quotes with each other, application can be split on four main parts: Landing, Profile, Newsfeed and Movie List pages.
##### Landing Page
Guest can register or login with custom authorization system or with help of Google OAuth API.
##### Profile     
There are two variations of profile, Google users can change their profile image or username, on other hand, regular users can change their profile image, username, password and even Email address.
##### Newsfeed
This page is where applications main functionality unifies with each other, where quotes can be seen or added as posts, users can interact via likes and comments, these can be transformed into notifications in real time, posts can be searched with their quotes or movie names.
##### Movie List
This page is a connecting bridge between users and application functionality, because only from here is possible to add movies and many other things too.

#### Application supports English and Georgian languages.

#
### Table of content

1. [Introduction](#Introduction)
2. [Prerequisites](#Prerequisites)
3. [Tech Stack](#Tech-Stack)
4. [Getting Started](#Getting-Started)
5. [Development](#Development)
6. [Deployment](#Deployment)
7. [Resources](#Resources)

#
### Prerequisites

* <img src="./public/assets/readme/php.png" width="35" style="position: relative; top: 4px" /> *PHP@8.0 and up*
* <img src="./public/assets/readme/mysql.png" width="35" style="position: relative; top: 4px" /> *MYSQL@8 and up*
* <img src="./public/assets/readme/npm.png" width="35" style="position: relative; top: 4px" /> *npm@6 and up*
* <img src="./public/assets/readme/composer.png" width="35" style="position: relative; top: 6px" /> *composer@2 and up*



#
### Tech Stack

* <img src="./public/assets/readme/laravel.png" height="18" style="position: relative; top: 4px" /> [Laravel@8.1.x](https://laravel.com/docs/8.1.x) - back-end framework
* <img src="./public/assets/readme/spatie.png" height="19" style="position: relative; top: 4px" /> [Spatie Translatable](https://github.com/spatie/laravel-translatable) - package for translation
* <img src="./public/assets/readme/swagger.png" height="19" style="position: relative; top: 4px" /> [Swagger](https://github.com/spatie/laravel-translatable) - package for displaying API's
* <img src="./public/assets/readme/jwt.png" height="19" style="position: relative; top: 4px" /> [Firebase/php-jwt](https://github.com/spatie/laravel-translatable) - package for authorization 
* <img src="./public/assets/readme/pusher.jpg" height="19" style="position: relative; top: 4px" /> [Pusher](https://github.com/spatie/laravel-translatable) - package for real time notifications



#
### Getting Started
1\. First of all you need to clone 'epic-movie-quotes-api-nugzar-skhiereli' repository from github:
```sh
git clone https://github.com/RedberryInternship/epic-movie-quotes-api-nugzar-skhiereli.git
```

2\. Next step requires you to run *composer install* in order to install all the dependencies.
```sh
composer install
```

3\. In order to build your tailwind styling and swagger.
```sh
npm run dev
```

5\. And don't forget to provide **.env** file all the necessary environment variables, manually or with help of :  
```sh
cp .env.example .env
```

6\. After setting up .env variables
```sh
php artisan config:cache
```


7\. Now execute in the root of you project following:
```sh
  php artisan key:generate
```
Which generates auth key.

8\. Also execute the following command:
```sh
  php artisan db:seed
```

##### Now, you should be good to go!



#
### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```

when working on Tailwindcss you may run:

```sh
  npm run dev
```
it builds your Tailwindcss styling into executable CSS.


#
### Deployment
<br/>

!["nginx / php fpm"](./public/assets/readme/ngnix-fpm.png)

<br />

- Projects first step is of course is development.
 After some time of development when you are ready to integrate and deploy your feature/fix/work you start doing these actions:
* Installing tools and programs on the server.
* Downloading the Laravel project to the server.
* PHP FPM configuration.
* NGINX configuration.
* Setting up SSL with LetsEncrypt.

After these steps everything should be OK :pray:


#
### Resources
!["drawsql"](./public/assets/readme/drawsql.png)