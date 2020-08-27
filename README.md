# Laravel x Firebase
Simple CRUD and Authentication (using Email and Password)

## Installation & Usage

1. Install `kreait` using composer. Example:
```bash
composer require kreait/firebase-php ^5.0
```
2. Place the script into controller directory `App\Http\Controllers`
3. Change this line with your Project's Service Account (private key) and move it to controller directory
```php
->withServiceAccount(__DIR__.'/YOUR-FIREBASE-PROJECT-adminsdk.json')
->withDatabaseUri('https://YOUR-FIREBASE-PROJECT.firebaseio.com/');
```
4. Add some route (using GET) to access each method. Example:
```php
Route::get('/signin', 'FirebaseController@signIn');
```
4. Access route on your web browser. Example: [http://localhost:8000/signin](http://localhost:8000/signin)

## Firebase Realtime Database
```json
{
  "hewan" : {
    "herbivora" : {
      "domba" : "kecil",
      "sapi" : "besar"
    },
    "karnivora" : {
      "harimau" : {
        "benggala" : "galak",
        "sumatera" : "jinak"
      }
    },
    "omnivora" : {
      "serigala" : "galakbanget"
    }
  },
  "tumbuhan" : {
    "dikotil" : "mangga",
    "monokotil" : "bambu"
  }
}
```

## Notes
- `PHP`: **>= 7.2**
- `Laravel`: **5.8.x** *(min requirement | tested on 7.25.0)*
- `kreait/clock`: **1.0.1**
- `kreait/firebase-php`: **5.7.0**
- `kreait/firebase-tokens`: **1.10.0**

## References

* Laravel (**7.x**) - [laravel.com/docs](https://laravel.com/docs/7.x/)
* Firebase Admin SDK for PHP  (**5.7.0**) - [firebase-php.readthedocs.io](http://firebase-php.readthedocs.io/en/5.7.0/)
