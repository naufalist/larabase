<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;

class FirebaseController extends Controller
{
    protected $auth, $database;

    public function __construct()
    {
        $factory = (new Factory)
        ->withServiceAccount(__DIR__.'/YOUR-FIREBASE-PROJECT-adminsdk.json')
        ->withDatabaseUri('https://YOUR-FIREBASE-PROJECT.firebaseio.com/');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    public function signUp()
    {
        $email = "angelicdemon@gmail.com";
        $pass = "anya123";

        try {
            $newUser = $this->auth->createUserWithEmailAndPassword($email, $pass);
            dd($newUser);
        } catch (\Throwable $e) {
            if ($e->getMessage() == "The email address is already in use by another account.") {
                dd("email has been used");
            } else {
                dd($e->getMessage());
            }
        }
    }

    public function signIn()
    {
        $email = "angelicdemon@gmail.com";
        $pass = "anya123";

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $pass);
            Session::put('uid', $signInResult->data()["localId"]);
            Session::put('idTokenString', $signInResult->data()["idToken"]);
            Session::save();
            dump(Session::get('uid'));
            dump(Session::get('idTokenString'));
            dump($signInResult);
        } catch (\Throwable $e) {
            if ($e->getMessage() == "INVALID_PASSWORD") {
                dd("Invalid password");
            } elseif ($e->getMessage() == "EMAIL_NOT_FOUND") {
                dd("Email not found");
            }
        }
    }

    public function signOut()
    {
        $this->auth->revokeRefreshTokens(Session::get('uid'));

        if ($this->userCheck() == "revoked") {
            Session::forget('uid');
            Session::forget('idTokenString');
            Session::save();
            dd("Successfully signed out");
        } else {
            dd("Sign out failed");
        }
    }

    public function userCheck()
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken(Session::get('idTokenString'), $checkIfRevoked = true);
            $response = "valid";
            // dd("Valid");
            // $uid = $verifiedIdToken->getClaim('sub');
            // $user = $auth->getUser($uid);
            // dump($uid);
            // dump($user);
        } catch (\InvalidArgumentException $e) {
            // dd('The token could not be parsed: '.$e->getMessage());
            $response = "The token could not be parsed: ".$e->getMessage();
        } catch (InvalidToken $e) {
            // dd('The token is invalid: '.$e->getMessage());
            $response = "The token is invalid: ".$e->getMessage();
        } catch (RevokedIdToken $e) {
            $response = "revoked";
        } catch (\Throwable $e) {
            if (substr($e->getMessage(), 0, 21) == "This token is expired") {
                $response = "expired";
            } else {
                $response = "something_wrong";
            }
        }

        return $response;
    }

    public function read()
    {
        $ref = $this->database->getReference('hewan/herbivora/domba')->getSnapshot();
        dump($ref);
        $ref = $this->database->getReference('hewan/herbivora')->getValue();
        dump($ref);
        $ref = $this->database->getReference('hewan/karnivora')->getValue();
        dump($ref);
        $ref = $this->database->getReference('hewan/omnivora')->getSnapshot()->exists();
        dump($ref);
    }

    public function update()
    {
        // before
        $ref = $this->database->getReference('tumbuhan/dikotil')->getValue();
        dump($ref);

        // update data
        $ref = $this->database->getReference('tumbuhan')
        ->update(["dikotil" => "mangga"]);

        // after
        $ref = $this->database->getReference('tumbuhan/dikotil')->getValue();
        dump($ref);
    }

    public function set()
    {
        // before
        $ref = $this->database->getReference('hewan')->getValue();
        dump($ref);

        // set data
        $ref = $this->database->getReference('hewan/karnivora')
        ->set([
            "harimau" => [
                "benggala" => "galak",
                "sumatera" => "jinak"
            ]
        ]);

        // after
        $ref = $this->database->getReference('hewan')->getValue();
        dump($ref);
    }
}
