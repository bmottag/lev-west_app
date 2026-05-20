<?php
namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['password'];

    public function validateLogin($arrData)
    {
        $user = ["valid" => false];

        $login  = str_replace(["<",">","[","]","*","^","-","'","="],"",$arrData["login"]);
        $passwd = str_replace(["<",">","[","]","*","^","-","'","="],"",$arrData["passwd"]);

        $row = $this->where('log_user', $login)->first();

        if ($row) {
            $valid = false;

            // Password nuevo (hash seguro)
            if (password_verify($passwd, $row['password'])) {
                $valid = true;
            } 
            // Password viejo (MD5)
            elseif ($row['password'] === md5($passwd)) {
                $valid = true;

                // Migración automática a hash seguro
                $newHash = password_hash($passwd, PASSWORD_DEFAULT);
                $this->update($row['id_user'], ['password' => $newHash]);
            }

            if ($valid) {
                $user["valid"]     = true;
                $user["id"]        = $row['id_user'];
                $user["firstname"] = $row['first_name'];
                $user["lastname"]  = $row['last_name'];
                $user["logUser"]   = $row['log_user'];
                $user["movil"]     = $row['movil'];
                $user["state"]     = $row['state'];
                $user["rol"]       = $row['perfil'];
                $user["bankTime"]  = $row['bank_time'];
                $user["photo"]     = $row['photo'];
            }
        }

        return $user;
    }
}