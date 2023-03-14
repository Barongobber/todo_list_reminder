# Ron's Todo List
This is a todo-list project which includes email notification reminder

## Requirements
To install this Laravel project. Kindly refer [here](https://laravel.com/docs/4.2/quick#installation) to perform installation of Laravel successfully

## Installation
1. Clone the code in this repository
    
    ```bash
    git clone https://baron_gobi@bitbucket.org/baron_gobi/infinity.git
    ```

2. On the project root, run `composer install` to install all dependencies.

3. Create database named as `infinity` in your MySQL Database. You may modify it on the `.env.example` file on `DATABASE_NAME` variable.

4. Copy the .env.example and rename it as .env by executing this cmd

    ```cmd
    cp .env.example .env
    ```

5. Edit your .env based on db, [timezone](https://www.php.net/manual/en/timezones.php), and email settings.

    - Change this line according to your db setting
    ```readme
    # Changing your DB according to your setup here
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=infinity
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    - Change this line according to your timezone. [List of Timezone](https://www.php.net/manual/en/timezones.php)
    ```readme
    # Changing the timezone according to your area here
    APP_TIMEZONE=<your timezone here, i.e Asia/Jakarta>
    ```

    - Change these lines according to your email credential. However, please [allow less secure apps in gmail account](https://support.google.com/accounts/answer/6010255?hl=en) first before using your email on the project. 

        > It is highly recommended to use dump gmail or unused gmail for this project. Just make one if you don't have


        ```readme
        # Changing your email env according to your email setup
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.googlemail.com
        MAIL_PORT=465
        MAIL_USERNAME=<your_email>@gmail.com
        MAIL_PASSWORD=<your_password>
        MAIL_ENCRYPTION=ssl
        MAIL_FROM_ADDRESS=<your_email>@gmail.com
        MAIL_FROM_NAME="${APP_NAME}"
        ```
    
6. run `php artisan migrate` to run migration database in laravel.

7. run `php artisan key:generate` to generate the key in laravel.

8. run `php artisan config:cache` to quickly restart config cache.

9. Navigate to project root -> app -> Mail -> open `sendEmail.php` and change the line code at line 35 (kindly refer on the comment section line below it). Uncomment line 35 if it has been finished to put your email on it and save it.

10. run `php artisan serve` to turn on local server. The local website would be accessible on the given link showed in the terminal/bash

11. Open new terminal and run `php artisan schedule:work` on the project root to run the kernel performing the schedule to send a notif email.

12. Play around with the project. 

## Flow of using the system
> Register -> Login -> Add Todo List along with Task(s) -> set reminder time (5 minutes ahead from now is highly recommended) -> turn the notification toogle on! 

**Finish!!** The setup should be done. Anything wrong? Hit me up at barongobirn@gmail.com. 
