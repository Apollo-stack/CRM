<?php

use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// CORES
$green = "\033[32m";
$red = "\033[31m";
$reset = "\033[0m";

echo "--------------------------------------------------\n";
echo "  👑 PROMOVER USUÁRIO A GERENTE  \n";
echo "--------------------------------------------------\n";

if (php_sapi_name() !== 'cli') {
    die("Apenas via terminal!");
}

echo "Digite o Email do usuário para promover: ";
$email = trim(fgets(STDIN));

if (empty($email)) {
    echo $red . "ERRO: Email obrigatório.\n" . $reset;
    exit;
}

$user = User::where('email', $email)->first();

if (!$user) {
    echo $red . "ERRO: Usuário não encontrado.\n" . $reset;
    exit;
}

if ($user->role === 'manager' || $user->role === 'admin') {
    echo $green . "AVISO: O usuário {$user->name} JÁ É um Gerente/Admin.\n" . $reset;
} else {
    $user->role = 'manager';
    $user->save();
    echo $green . "SUCESSO! {$user->name} agora é um GERENTE.\n" . $reset;
}
